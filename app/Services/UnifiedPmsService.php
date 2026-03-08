<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UnifiedPmsService
{
    public function createSale(array $payload): array
    {
        return DB::transaction(function () use ($payload) {
            $parentId = $this->parentId();
            $agreementNo = $payload['agreement_no'] ?? ('SAL-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4)));

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
        $sale = DB::table('sales')->where('id', $saleId)->first();
        if (!$sale) {
            abort(404, 'Sale not found.');
        }

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
            $sale = DB::table('sales')->where('id', $saleId)->first();
            if (!$sale) {
                abort(404, 'Sale not found.');
            }

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
            $sale = DB::table('sales')->where('id', $saleId)->first();
            if (!$sale) {
                abort(404, 'Sale not found.');
            }

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
        $controlNumber = 'CN-' . now()->format('YmdHis') . '-' . random_int(1000, 9999);
        $id = DB::table('bank_control_numbers')->insertGetId([
            'parent_id' => $this->parentId(),
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
        $totalUnits = (int) DB::table('property_units')->count();
        $occupiedUnits = (int) DB::table('property_units')->where('status', 'occupied')->count();
        $occupancyRate = $totalUnits > 0 ? round(($occupiedUnits / $totalUnits) * 100, 2) : 0;

        $availableArea = (float) DB::table('property_units')->whereIn('status', ['vacant', 'on-hold'])->sum('size_sqm');
        $occupiedArea = (float) DB::table('property_units')->where('status', 'occupied')->sum('size_sqm');
        $averageLeaseRate = (float) DB::table('property_units')->avg('rent');

        $salesTotal = (float) DB::table('sales')->sum('total_contract_value');
        $cashIn = (float) DB::table('sale_payments')->sum('amount') + (float) DB::table('invoice_payments')->sum('amount');
        $outstanding = (float) DB::table('receivables')->sum('balance');
        $excessPayments = (float) DB::table('customer_credits')->sum('balance');
        $pendingCommissions = (float) DB::table('commissions')->where('status', 'pending')->sum('commission_amount');
        $paidCommissions = (float) DB::table('commission_payments')->sum('amount');
        $expenseTotal = (float) DB::table('expenses')->sum('amount');
        $deferredRevenue = (float) DB::table('sales')->sum('deferred_revenue');
        $profitEstimate = round($salesTotal - ($expenseTotal + $paidCommissions), 2);

        $projectProfit = DB::table('sales as s')
            ->leftJoin('projects as p', 'p.id', '=', 's.project_id')
            ->leftJoin('commissions as c', 'c.sale_id', '=', 's.id')
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

    private function evaluateCommissionTrigger(int $saleId, string $event): void
    {
        $sale = DB::table('sales')->where('id', $saleId)->first();
        if (!$sale || empty($sale->agent_id)) {
            return;
        }

        $agent = DB::table('agents')->where('id', $sale->agent_id)->first();
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
}
