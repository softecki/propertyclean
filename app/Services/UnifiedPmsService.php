<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UnifiedPmsService
{
    private IntegrationDispatchService $integrationDispatchService;

    public function __construct(IntegrationDispatchService $integrationDispatchService)
    {
        $this->integrationDispatchService = $integrationDispatchService;
    }

    public function createSale(array $payload): array
    {
        return DB::transaction(function () use ($payload) {
            $parentId = $this->parentId();
            $agreementNo = $payload['agreement_no'] ?? ('SAL-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4)));

            $plot = DB::table('plots')->where('id', $payload['plot_id'])->where('parent_id', $parentId)->first();
            if (!$plot) {
                abort(422, 'Plot not found in your workspace.');
            }
            if ($plot->status !== 'available') {
                abort(422, 'Selected plot is not available.');
            }

            $customer = DB::table('customers')->where('id', $payload['customer_id'])->where('parent_id', $parentId)->first();
            if (!$customer) {
                abort(422, 'Customer not found in your workspace.');
            }

            if (!empty($payload['seller_id'])) {
                $seller = DB::table('sellers')->where('id', $payload['seller_id'])->where('parent_id', $parentId)->first();
                if (!$seller) {
                    abort(422, 'Seller not found in your workspace.');
                }
            }

            if (!empty($payload['agent_id'])) {
                $agent = DB::table('agents')->where('id', $payload['agent_id'])->where('parent_id', $parentId)->first();
                if (!$agent) {
                    abort(422, 'Agent not found in your workspace.');
                }
            }

            $saleId = DB::table('sales')->insertGetId([
                'parent_id' => $parentId,
                'project_id' => $payload['project_id'] ?? null,
                'block_id' => $payload['block_id'] ?? null,
                'plot_id' => $payload['plot_id'],
                'customer_id' => $payload['customer_id'],
                'seller_id' => $payload['seller_id'] ?? null,
                'agent_id' => $payload['agent_id'] ?? null,
                'agreement_no' => $agreementNo,
                'status' => $payload['status'] ?? 'draft',
                'currency_code' => $payload['currency_code'] ?? 'TZS',
                'exchange_rate' => $payload['exchange_rate'] ?? 1,
                'sale_price' => $payload['sale_price'] ?? 0,
                'discount' => $payload['discount'] ?? 0,
                'penalty_interest_rate' => $payload['penalty_interest_rate'] ?? 0,
                'commission_rule_type' => $payload['commission_rule_type'] ?? null,
                'commission_rule_value' => $payload['commission_rule_value'] ?? null,
                'commission_trigger' => $payload['commission_trigger'] ?? 'sale_confirmed',
                'sale_date' => $payload['sale_date'] ?? now()->toDateString(),
                'notes' => $payload['notes'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach (($payload['installments'] ?? []) as $installment) {
                DB::table('sale_installments')->insert([
                    'sale_id' => $saleId,
                    'due_date' => $installment['due_date'],
                    'amount' => $installment['amount'],
                    'paid_amount' => 0,
                    'status' => 'due',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            foreach (($payload['extra_charges'] ?? []) as $charge) {
                DB::table('sale_extra_charges')->insert([
                    'sale_id' => $saleId,
                    'charge_type' => $charge['charge_type'],
                    'description' => $charge['description'] ?? null,
                    'amount' => $charge['amount'],
                    'added_by' => Auth::id(),
                    'reason' => $charge['reason'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('plots')->where('id', $payload['plot_id'])->update([
                'status' => 'reserved',
                'updated_at' => now(),
            ]);

            $sale = $this->recalculateSale($saleId);

            return [
                'sale_id' => $saleId,
                'agreement_no' => $agreementNo,
                'sale' => $sale,
            ];
        });
    }

    public function recalculateSale(int $saleId): object
    {
        $sale = $this->getSaleForParentOrFail($saleId);

        $extraChargeTotal = (float) DB::table('sale_extra_charges')->where('sale_id', $saleId)->sum('amount');
        $totalPaid = (float) DB::table('sale_payments')->where('sale_id', $saleId)->sum('amount');

        $contractValue = max(0, (float) $sale->sale_price - (float) $sale->discount + $extraChargeTotal);
        $outstandingBalance = round($contractValue - $totalPaid, 2);
        $recognizedRevenue = in_array($sale->status, ['confirmed', 'completed'], true) ? $contractValue : 0;
        $deferredRevenue = max(0, $contractValue - $recognizedRevenue);

        DB::table('sales')->where('id', $saleId)->update([
            'extra_charge_total' => $extraChargeTotal,
            'total_contract_value' => $contractValue,
            'total_paid' => $totalPaid,
            'outstanding_balance' => $outstandingBalance,
            'recognized_revenue' => $recognizedRevenue,
            'deferred_revenue' => $deferredRevenue,
            'updated_at' => now(),
        ]);

        $receivable = DB::table('receivables')->where('sale_id', $saleId)->first();
        $receivablePayload = [
            'customer_id' => $sale->customer_id,
            'expected_amount' => $contractValue,
            'received_amount' => min($totalPaid, $contractValue),
            'balance' => max(0, $outstandingBalance),
            'due_date' => DB::table('sale_installments')->where('sale_id', $saleId)->max('due_date'),
            'status' => $this->receivableStatus($outstandingBalance),
            'updated_at' => now(),
        ];

        if ($receivable) {
            DB::table('receivables')->where('id', $receivable->id)->update($receivablePayload);
        } else {
            $receivablePayload['sale_id'] = $saleId;
            $receivablePayload['created_at'] = now();
            DB::table('receivables')->insert($receivablePayload);
        }

        if ($sale->status === 'completed' || $sale->status === 'confirmed') {
            DB::table('plots')->where('id', $sale->plot_id)->update([
                'status' => 'sold',
                'updated_at' => now(),
            ]);
        }

        return DB::table('sales')->where('id', $saleId)->first();
    }

    public function recordSalePayment(int $saleId, array $payload): array
    {
        return DB::transaction(function () use ($saleId, $payload) {
            $sale = $this->getSaleForParentOrFail($saleId);

            $paymentId = DB::table('sale_payments')->insertGetId([
                'sale_id' => $saleId,
                'control_number_id' => $payload['control_number_id'] ?? null,
                'payment_reference' => $payload['payment_reference'] ?? ('PAY-' . strtoupper(Str::random(10))),
                'bank_reference' => $payload['bank_reference'] ?? null,
                'payment_method' => $payload['payment_method'] ?? 'bank',
                'amount' => $payload['amount'],
                'currency_code' => $payload['currency_code'] ?? $sale->currency_code,
                'exchange_rate' => $payload['exchange_rate'] ?? $sale->exchange_rate,
                'payment_date' => $payload['payment_date'] ?? now()->toDateString(),
                'notes' => $payload['notes'] ?? null,
                'received_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (!empty($payload['control_number_id'])) {
                DB::table('bank_control_numbers')->where('id', $payload['control_number_id'])->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $sale = $this->recalculateSale($saleId);
            $excessAmount = 0;

            if ((float) $sale->outstanding_balance < 0) {
                $excessAmount = abs((float) $sale->outstanding_balance);
                DB::table('customer_credits')->insert([
                    'customer_id' => $sale->customer_id,
                    'sale_id' => $saleId,
                    'source_payment_id' => $paymentId,
                    'amount' => $excessAmount,
                    'balance' => $excessAmount,
                    'action' => $payload['excess_action'] ?? 'credit',
                    'status' => 'open',
                    'notes' => $payload['excess_notes'] ?? 'Auto-created from overpayment.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('customers')->where('id', $sale->customer_id)->increment('credit_balance', $excessAmount);
                DB::table('sales')->where('id', $saleId)->update([
                    'outstanding_balance' => 0,
                    'status' => 'completed',
                    'completed_at' => now(),
                    'updated_at' => now(),
                ]);
            } elseif ((float) $sale->outstanding_balance === 0.0) {
                DB::table('sales')->where('id', $saleId)->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $sale = $this->recalculateSale($saleId);

            $this->createAccrualEntry('sale', $saleId, 'CASH-100', 'debit', (float) $payload['amount'], $sale->currency_code, 'Sale payment receipt');
            $this->createAccrualEntry('sale', $saleId, 'AR-110', 'credit', (float) $payload['amount'], $sale->currency_code, 'Receivable settlement');

            $this->evaluateCommissionTrigger($saleId, 'payment_collected');

            return [
                'payment_id' => $paymentId,
                'sale' => DB::table('sales')->where('id', $saleId)->first(),
                'excess_amount' => $excessAmount,
            ];
        });
    }

    public function updateSaleStatus(int $saleId, string $status): object
    {
        return DB::transaction(function () use ($saleId, $status) {
            $sale = $this->getSaleForParentOrFail($saleId);

            $update = [
                'status' => $status,
                'updated_at' => now(),
            ];
            if ($status === 'confirmed') {
                $update['confirmed_at'] = now();
            }
            if ($status === 'completed') {
                $update['completed_at'] = now();
            }

            DB::table('sales')->where('id', $saleId)->update($update);
            $sale = $this->recalculateSale($saleId);

            if ($status === 'confirmed') {
                $this->createAccrualEntry('sale', $saleId, 'AR-110', 'debit', (float) $sale->total_contract_value, $sale->currency_code, 'Sale confirmed receivable');
                $this->createAccrualEntry('sale', $saleId, 'REV-400', 'credit', (float) $sale->total_contract_value, $sale->currency_code, 'Revenue recognition');
                $this->evaluateCommissionTrigger($saleId, 'sale_confirmed');
            }

            if ($status === 'cancelled') {
                DB::table('plots')->where('id', $sale->plot_id)->update([
                    'status' => 'available',
                    'updated_at' => now(),
                ]);
                DB::table('commissions')->where('sale_id', $saleId)->update([
                    'status' => 'reversed',
                    'updated_at' => now(),
                ]);
            }

            return DB::table('sales')->where('id', $saleId)->first();
        });
    }

    public function createControlNumber(array $payload): array
    {
        $parentId = $this->parentId();
        $controlNumber = 'CN-' . now()->format('YmdHis') . '-' . random_int(1000, 9999);
        $id = DB::table('bank_control_numbers')->insertGetId([
            'parent_id' => $parentId,
            'reference_type' => $payload['reference_type'],
            'reference_id' => $payload['reference_id'],
            'bank_name' => $payload['bank_name'] ?? null,
            'control_number' => $controlNumber,
            'amount' => $payload['amount'] ?? 0,
            'currency_code' => $payload['currency_code'] ?? 'TZS',
            'status' => 'generated',
            'expires_at' => $payload['expires_at'] ?? Carbon::now()->addDays(7),
            'response_payload' => isset($payload['response_payload']) ? json_encode($payload['response_payload']) : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->integrationDispatchService->logBankEvent($parentId, (string) $payload['reference_type'], (int) $payload['reference_id'], [
            'bank_name' => $payload['bank_name'] ?? null,
            'control_number' => $controlNumber,
            'amount' => $payload['amount'] ?? 0,
            'currency_code' => $payload['currency_code'] ?? 'TZS',
            'control_number_id' => $id,
        ]);

        return [
            'id' => $id,
            'control_number' => $controlNumber,
        ];
    }

    public function depreciateAsset(int $assetId, string $periodStart, string $periodEnd): array
    {
        return DB::transaction(function () use ($assetId, $periodStart, $periodEnd) {
            $asset = DB::table('asset_registers')->where('id', $assetId)->first();
            if (!$asset) {
                abort(404, 'Asset not found.');
            }

            $periodDays = max(1, Carbon::parse($periodStart)->diffInDays(Carbon::parse($periodEnd)) + 1);
            $annualDepreciable = max(0, (float) $asset->cost - (float) $asset->salvage_value);
            $dailyDepreciation = $annualDepreciable / max(1, ((int) $asset->useful_life_years * 365));
            $depreciationAmount = round($dailyDepreciation * $periodDays, 2);
            $newAccumulated = min($annualDepreciable, round((float) $asset->accumulated_depreciation + $depreciationAmount, 2));
            $bookValue = round(max((float) $asset->salvage_value, (float) $asset->cost - $newAccumulated), 2);

            $entryId = DB::table('depreciation_entries')->insertGetId([
                'asset_register_id' => $assetId,
                'period_start' => $periodStart,
                'period_end' => $periodEnd,
                'depreciation_amount' => $depreciationAmount,
                'accumulated_depreciation' => $newAccumulated,
                'book_value' => $bookValue,
                'posted_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('asset_registers')->where('id', $assetId)->update([
                'accumulated_depreciation' => $newAccumulated,
                'book_value' => $bookValue,
                'updated_at' => now(),
            ]);

            return [
                'entry_id' => $entryId,
                'depreciation_amount' => $depreciationAmount,
                'book_value' => $bookValue,
            ];
        });
    }

    public function generateReminders(int $daysAhead = 7): array
    {
        $parentId = $this->parentId();
        $dueDate = Carbon::now()->addDays($daysAhead)->toDateString();
        $count = 0;

        $dueInvoices = DB::table('invoices')
            ->where('parent_id', $parentId)
            ->whereIn('status', ['open', 'partial_paid'])
            ->whereDate('end_date', '<=', $dueDate)
            ->get();

        foreach ($dueInvoices as $invoice) {
            DB::table('notification_logs')->insert([
                'parent_id' => $parentId,
                'channel' => 'email',
                'recipient' => 'invoice-' . $invoice->id,
                'subject' => 'Invoice due reminder',
                'message' => 'Invoice ' . $invoice->invoice_id . ' is due by ' . $invoice->end_date,
                'status' => 'pending',
                'context' => json_encode(['invoice_id' => $invoice->id]),
                'scheduled_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $count++;
        }

        $expiringContracts = DB::table('contracts')
            ->where('user_id', $parentId)
            ->whereNotNull('lease_end_date')
            ->whereDate('lease_end_date', '<=', $dueDate)
            ->get();

        foreach ($expiringContracts as $contract) {
            DB::table('notification_logs')->insert([
                'parent_id' => $parentId,
                'channel' => 'email',
                'recipient' => 'contract-' . $contract->id,
                'subject' => 'Lease expiry reminder',
                'message' => 'Contract #' . $contract->id . ' expires on ' . $contract->lease_end_date,
                'status' => 'pending',
                'context' => json_encode(['contract_id' => $contract->id]),
                'scheduled_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $count++;
        }

        $dueMaintenance = DB::table('maintenance_schedules')
            ->where('parent_id', $parentId)
            ->where('status', 'scheduled')
            ->whereDate('next_maintenance_date', '<=', $dueDate)
            ->get();

        foreach ($dueMaintenance as $schedule) {
            DB::table('notification_logs')->insert([
                'parent_id' => $parentId,
                'channel' => 'email',
                'recipient' => 'maintenance-' . $schedule->id,
                'subject' => 'Maintenance due reminder',
                'message' => 'Scheduled maintenance is due on ' . $schedule->next_maintenance_date,
                'status' => 'pending',
                'context' => json_encode(['maintenance_schedule_id' => $schedule->id]),
                'scheduled_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $count++;
        }

        return ['notifications_created' => $count];
    }

    public function reportSummary(): array
    {
        $parentId = $this->parentId();
        $propertyIds = DB::table('properties')->where('parent_id', $parentId)->pluck('id');

        $totalUnits = (int) DB::table('property_units')->whereIn('property_id', $propertyIds)->count();
        $occupiedUnits = (int) DB::table('property_units')->whereIn('property_id', $propertyIds)->where('status', 'occupied')->count();
        $occupancyRate = $totalUnits > 0 ? round(($occupiedUnits / $totalUnits) * 100, 2) : 0;

        $availableArea = (float) DB::table('property_units')->whereIn('property_id', $propertyIds)->whereIn('status', ['vacant', 'on-hold'])->sum('size_sqm');
        $occupiedArea = (float) DB::table('property_units')->whereIn('property_id', $propertyIds)->where('status', 'occupied')->sum('size_sqm');
        $averageLeaseRate = (float) DB::table('property_units')->whereIn('property_id', $propertyIds)->avg('rent');

        $saleIds = DB::table('sales')->where('parent_id', $parentId)->pluck('id');
        $commissionIds = DB::table('commissions')->whereIn('sale_id', $saleIds)->pluck('id');

        $salesTotal = (float) DB::table('sales')->where('parent_id', $parentId)->sum('total_contract_value');
        $cashIn = (float) DB::table('sale_payments')->whereIn('sale_id', $saleIds)->sum('amount')
            + (float) DB::table('invoice_payments')->where('parent_id', $parentId)->sum('amount');
        $outstanding = (float) DB::table('receivables')->whereIn('sale_id', $saleIds)->sum('balance');
        $excessPayments = (float) DB::table('customer_credits')->whereIn('sale_id', $saleIds)->sum('balance');
        $pendingCommissions = (float) DB::table('commissions')->whereIn('sale_id', $saleIds)->where('status', 'pending')->sum('commission_amount');
        $paidCommissions = (float) DB::table('commission_payments')->whereIn('commission_id', $commissionIds)->sum('amount');
        $expenseTotal = (float) DB::table('expenses')->where('parent_id', $parentId)->sum('amount');
        $deferredRevenue = (float) DB::table('sales')->where('parent_id', $parentId)->sum('deferred_revenue');
        $profitEstimate = round($salesTotal - ($expenseTotal + $paidCommissions), 2);

        $projectProfit = DB::table('sales as s')
            ->leftJoin('projects as p', 'p.id', '=', 's.project_id')
            ->leftJoin('commissions as c', 'c.sale_id', '=', 's.id')
            ->where('s.parent_id', $parentId)
            ->selectRaw('p.name as project_name, SUM(s.total_contract_value) as revenue, SUM(COALESCE(c.commission_amount, 0)) as commission, SUM(s.total_contract_value) - SUM(COALESCE(c.commission_amount, 0)) as gross_profit')
            ->groupBy('p.name')
            ->get()
            ->map(function ($row) {
                $row->project_name = $row->project_name ?: 'Unassigned';
                return $row;
            });

        return [
            'occupancy_rate_percent' => $occupancyRate,
            'available_area_m2' => $availableArea,
            'occupied_area_m2' => $occupiedArea,
            'average_lease_rate' => round($averageLeaseRate, 2),
            'sales_total' => $salesTotal,
            'outstanding_balances' => $outstanding,
            'excess_payments' => $excessPayments,
            'pending_commissions' => $pendingCommissions,
            'paid_commissions' => $paidCommissions,
            'cash_flow_in' => $cashIn,
            'deferred_revenue' => $deferredRevenue,
            'profit_estimate' => $profitEstimate,
            'project_profit' => $projectProfit,
        ];
    }

    public function rentRollReport(): array
    {
        $parentId = $this->parentId();

        return DB::table('invoices')
            ->where('parent_id', $parentId)
            ->select(
                'id',
                'invoice_id',
                'tenant_id',
                'property_id',
                'unit_id',
                'amount',
                'status',
                'start_date',
                'end_date',
                DB::raw('CASE WHEN status IN ("paid","cancelled") THEN 0 WHEN CURDATE() > end_date THEN DATEDIFF(CURDATE(), end_date) ELSE 0 END as days_past_due')
            )
            ->orderByDesc('days_past_due')
            ->get()
            ->toArray();
    }

    public function receivablesAgingReport(): array
    {
        $saleIds = DB::table('sales')->where('parent_id', $this->parentId())->pluck('id');

        $rows = DB::table('receivables')->whereIn('sale_id', $saleIds)->get();
        $buckets = [
            'current' => 0.0,
            '1_30' => 0.0,
            '31_60' => 0.0,
            '61_90' => 0.0,
            '90_plus' => 0.0,
        ];

        foreach ($rows as $row) {
            $balance = (float) $row->balance;
            $days = Carbon::parse($row->due_date)->diffInDays(now(), false);
            if ($days <= 0) {
                $buckets['current'] += $balance;
            } elseif ($days <= 30) {
                $buckets['1_30'] += $balance;
            } elseif ($days <= 60) {
                $buckets['31_60'] += $balance;
            } elseif ($days <= 90) {
                $buckets['61_90'] += $balance;
            } else {
                $buckets['90_plus'] += $balance;
            }
        }

        return [
            'buckets' => array_map(fn ($v) => round($v, 2), $buckets),
            'rows' => $rows->toArray(),
        ];
    }

    public function excessPaymentStatements(): array
    {
        $saleIds = DB::table('sales')->where('parent_id', $this->parentId())->pluck('id');

        return DB::table('customer_credits as cc')
            ->leftJoin('customers as c', 'c.id', '=', 'cc.customer_id')
            ->leftJoin('sales as s', 's.id', '=', 'cc.sale_id')
            ->whereIn('cc.sale_id', $saleIds)
            ->select(
                'cc.id',
                'cc.customer_id',
                'c.name as customer_name',
                'cc.sale_id',
                's.agreement_no',
                'cc.amount',
                'cc.balance',
                'cc.action',
                'cc.status',
                'cc.notes',
                'cc.created_at'
            )
            ->orderByDesc('cc.id')
            ->get()
            ->toArray();
    }

    public function sellerSettlementReport(): array
    {
        return DB::table('sellers as s')
            ->where('s.parent_id', $this->parentId())
            ->leftJoin('sales as sale', 'sale.seller_id', '=', 's.id')
            ->selectRaw('s.id, s.name, s.agreed_amount, s.amount_paid, s.amount_remaining, COUNT(sale.id) as linked_sales, SUM(COALESCE(sale.total_contract_value,0)) as linked_sales_value')
            ->groupBy('s.id', 's.name', 's.agreed_amount', 's.amount_paid', 's.amount_remaining')
            ->orderByDesc('s.id')
            ->get()
            ->toArray();
    }

    private function evaluateCommissionTrigger(int $saleId, string $event): void
    {
        $sale = $this->getSaleForParentOrFail($saleId);
        if (!$sale || empty($sale->agent_id)) {
            return;
        }

        $agent = DB::table('agents')->where('id', $sale->agent_id)->where('parent_id', $this->parentId())->first();
        if (!$agent) {
            return;
        }

        $trigger = $sale->commission_trigger ?: $agent->commission_trigger;
        $allow =
            ($trigger === 'sale_confirmed' && $event === 'sale_confirmed') ||
            ($trigger === 'full_payment' && (float) $sale->outstanding_balance <= 0) ||
            ($trigger === 'collected_amount' && $event === 'payment_collected');

        if (!$allow) {
            return;
        }

        $ruleType = $sale->commission_rule_type ?: $agent->commission_type;
        $ruleValue = $sale->commission_rule_value ?? $agent->commission_value;
        $baseAmount = $trigger === 'collected_amount' ? (float) $sale->total_paid : (float) $sale->total_contract_value;

        $commissionAmount = $ruleType === 'fixed'
            ? (float) $ruleValue
            : round($baseAmount * ((float) $ruleValue / 100), 2);

        $existing = DB::table('commissions')->where('sale_id', $saleId)->where('agent_id', $agent->id)->first();
        if ($existing) {
            DB::table('commissions')->where('id', $existing->id)->update([
                'rule_type' => $ruleType,
                'rule_value' => $ruleValue,
                'base_amount' => $baseAmount,
                'commission_amount' => $commissionAmount,
                'status' => 'approved',
                'recognized_at' => now(),
                'payable_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            DB::table('commissions')->insert([
                'sale_id' => $saleId,
                'agent_id' => $agent->id,
                'rule_type' => $ruleType,
                'rule_value' => $ruleValue,
                'base_amount' => $baseAmount,
                'commission_amount' => $commissionAmount,
                'status' => 'approved',
                'recognized_at' => now(),
                'payable_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->createAccrualEntry('commission', $saleId, 'EXP-COMM-510', 'debit', $commissionAmount, $sale->currency_code, 'Commission expense recognition');
        $this->createAccrualEntry('commission', $saleId, 'LIAB-COMM-210', 'credit', $commissionAmount, $sale->currency_code, 'Commission payable recognition');
    }

    private function createAccrualEntry(string $sourceType, int $sourceId, string $accountCode, string $entryType, float $amount, string $currencyCode, string $description): void
    {
        DB::table('accrual_entries')->insert([
            'parent_id' => $this->parentId(),
            'entry_date' => now()->toDateString(),
            'source_type' => $sourceType,
            'source_id' => $sourceId,
            'account_code' => $accountCode,
            'entry_type' => $entryType,
            'amount' => $amount,
            'currency_code' => $currencyCode,
            'description' => $description,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function receivableStatus(float $outstandingBalance): string
    {
        if ($outstandingBalance <= 0) {
            return 'closed';
        }
        return 'open';
    }

    private function parentId(): int
    {
        if (Auth::check() && function_exists('parentId')) {
            return (int) parentId();
        }
        return 1;
    }

    private function getSaleForParentOrFail(int $saleId): object
    {
        $sale = DB::table('sales')->where('id', $saleId)->where('parent_id', $this->parentId())->first();
        if (!$sale) {
            abort(404, 'Sale not found.');
        }

        return $sale;
    }
}
