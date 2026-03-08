<?php

namespace App\Http\Controllers;

use App\Services\UnifiedPmsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UnifiedPmsWebController extends Controller
{
    public function __construct(private UnifiedPmsService $service)
    {
    }

    public function dashboard(): View
    {
        return view('phase.dashboard');
    }

    public function land(): View
    {
        return view('phase.land', [
            'branches' => DB::table('branches')->orderByDesc('id')->get(),
            'projects' => DB::table('projects')->orderByDesc('id')->get(),
            'blocks' => DB::table('blocks')->orderByDesc('id')->get(),
            'plots' => DB::table('plots')->orderByDesc('id')->get(),
            'properties' => DB::table('properties')->orderByDesc('id')->get(),
        ]);
    }

    public function storeBranch(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        DB::table('branches')->insert([
            'parent_id' => $this->parentId(),
            'name' => $request->name,
            'code' => $request->code,
            'address' => $request->address,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Branch created.'));
    }

    public function storeProject(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'branch_id' => 'nullable|integer|exists:branches,id',
            'description' => 'nullable|string|max:1000',
        ]);

        DB::table('projects')->insert([
            'parent_id' => $this->parentId(),
            'branch_id' => $request->branch_id,
            'name' => $request->name,
            'description' => $request->description,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Project created.'));
    }

    public function storeBlock(Request $request): RedirectResponse
    {
        $request->validate([
            'project_id' => 'required|integer|exists:projects,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
        ]);

        DB::table('blocks')->insert([
            'project_id' => $request->project_id,
            'name' => $request->name,
            'code' => $request->code,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Block created.'));
    }

    public function storePlot(Request $request): RedirectResponse
    {
        $request->validate([
            'block_id' => 'required|integer|exists:blocks,id',
            'property_id' => 'nullable|integer|exists:properties,id',
            'plot_number' => 'required|string|max:255',
            'title_deed_no' => 'nullable|string|max:255',
            'size_sqm' => 'nullable|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'rental_price' => 'nullable|numeric|min:0',
        ]);

        DB::table('plots')->insert([
            'parent_id' => $this->parentId(),
            'block_id' => $request->block_id,
            'property_id' => $request->property_id,
            'plot_number' => $request->plot_number,
            'title_deed_no' => $request->title_deed_no,
            'size_sqm' => $request->size_sqm,
            'sale_price' => $request->sale_price,
            'rental_price' => $request->rental_price ?? 0,
            'status' => 'available',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Plot created.'));
    }

    public function parties(): View
    {
        return view('phase.parties', [
            'customers' => DB::table('customers')->orderByDesc('id')->get(),
            'sellers' => DB::table('sellers')->orderByDesc('id')->get(),
            'agents' => DB::table('agents')->orderByDesc('id')->get(),
        ]);
    }

    public function storeCustomer(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:buyer,tenant,investor',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        DB::table('customers')->insert([
            'parent_id' => $this->parentId(),
            'type' => $request->type,
            'name' => $request->name,
            'title' => $request->title,
            'business_name' => $request->business_name,
            'business_registration_number' => $request->business_registration_number,
            'tin' => $request->tin,
            'taxpayer_identification_number' => $request->taxpayer_identification_number,
            'id_number' => $request->id_number,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'credit_balance' => 0,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Customer created.'));
    }

    public function storeSeller(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        DB::table('sellers')->insert([
            'parent_id' => $this->parentId(),
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'ownership_reference' => $request->ownership_reference,
            'agreed_amount' => 0,
            'amount_paid' => 0,
            'amount_remaining' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Seller created.'));
    }

    public function storeAgent(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'commission_type' => 'required|in:percentage,fixed',
            'commission_value' => 'required|numeric|min:0',
            'commission_trigger' => 'required|in:sale_confirmed,full_payment,collected_amount',
        ]);

        DB::table('agents')->insert([
            'parent_id' => $this->parentId(),
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'commission_type' => $request->commission_type,
            'commission_value' => $request->commission_value,
            'commission_trigger' => $request->commission_trigger,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Agent created.'));
    }

    public function sales(): View
    {
        return view('phase.sales', [
            'sales' => DB::table('sales as s')
                ->leftJoin('customers as c', 'c.id', '=', 's.customer_id')
                ->leftJoin('plots as p', 'p.id', '=', 's.plot_id')
                ->leftJoin('agents as a', 'a.id', '=', 's.agent_id')
                ->select('s.*', 'c.name as customer_name', 'p.plot_number', 'a.name as agent_name')
                ->orderByDesc('s.id')
                ->get(),
            'projects' => DB::table('projects')->orderBy('name')->get(),
            'blocks' => DB::table('blocks')->orderBy('name')->get(),
            'plots' => DB::table('plots')->where('status', 'available')->orderBy('plot_number')->get(),
            'customers' => DB::table('customers')->orderBy('name')->get(),
            'sellers' => DB::table('sellers')->orderBy('name')->get(),
            'agents' => DB::table('agents')->orderBy('name')->get(),
        ]);
    }

    public function storeSale(Request $request): RedirectResponse
    {
        $request->validate([
            'plot_id' => 'required|integer|exists:plots,id',
            'customer_id' => 'required|integer|exists:customers,id',
            'sale_price' => 'required|numeric|min:0',
            'commission_trigger' => 'nullable|in:sale_confirmed,full_payment,collected_amount',
        ]);

        $payload = $request->only([
            'project_id',
            'block_id',
            'plot_id',
            'customer_id',
            'seller_id',
            'agent_id',
            'sale_price',
            'discount',
            'currency_code',
            'exchange_rate',
            'commission_rule_type',
            'commission_rule_value',
            'commission_trigger',
            'sale_date',
            'notes',
        ]);

        $payload['status'] = 'draft';
        $this->service->createSale($payload);

        return back()->with('success', __('Sale created.'));
    }

    public function addSaleCharge(Request $request, int $saleId): RedirectResponse
    {
        $request->validate([
            'charge_type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        DB::table('sale_extra_charges')->insert([
            'sale_id' => $saleId,
            'charge_type' => $request->charge_type,
            'description' => $request->description,
            'amount' => $request->amount,
            'added_by' => auth()->id(),
            'reason' => $request->reason,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->service->recalculateSale($saleId);

        return back()->with('success', __('Sale charge added.'));
    }

    public function addSalePayment(Request $request, int $saleId): RedirectResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'nullable|string|max:255',
        ]);

        $this->service->recordSalePayment($saleId, $request->only([
            'amount',
            'payment_method',
            'payment_reference',
            'bank_reference',
            'payment_date',
            'currency_code',
            'exchange_rate',
            'control_number_id',
            'excess_action',
            'excess_notes',
        ]));

        return back()->with('success', __('Sale payment posted.'));
    }

    public function updateSaleStatus(Request $request, int $saleId): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:draft,confirmed,cancelled,completed',
        ]);

        $this->service->updateSaleStatus($saleId, $request->status);

        return back()->with('success', __('Sale status updated.'));
    }

    public function finance(): View
    {
        return view('phase.finance', [
            'receivables' => DB::table('receivables as r')
                ->leftJoin('customers as c', 'c.id', '=', 'r.customer_id')
                ->select('r.*', 'c.name as customer_name')
                ->orderByDesc('r.id')
                ->get(),
            'commissions' => DB::table('commissions as c')
                ->leftJoin('agents as a', 'a.id', '=', 'c.agent_id')
                ->leftJoin('sales as s', 's.id', '=', 'c.sale_id')
                ->select('c.*', 'a.name as agent_name', 's.agreement_no')
                ->orderByDesc('c.id')
                ->get(),
            'credits' => DB::table('customer_credits as cc')
                ->leftJoin('customers as c', 'c.id', '=', 'cc.customer_id')
                ->select('cc.*', 'c.name as customer_name')
                ->orderByDesc('cc.id')
                ->get(),
            'controlNumbers' => DB::table('bank_control_numbers')->orderByDesc('id')->get(),
            'currencyRates' => DB::table('currency_rates')->orderByDesc('effective_date')->get(),
        ]);
    }

    public function storeControlNumber(Request $request): RedirectResponse
    {
        $request->validate([
            'reference_type' => 'required|string|max:255',
            'reference_id' => 'required|integer',
            'amount' => 'required|numeric|min:0',
            'currency_code' => 'required|string|size:3',
        ]);

        $this->service->createControlNumber($request->only([
            'reference_type',
            'reference_id',
            'amount',
            'currency_code',
            'bank_name',
            'expires_at',
        ]));

        return back()->with('success', __('Control number generated.'));
    }

    public function payCommission(Request $request, int $commissionId): RedirectResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
        ]);

        DB::transaction(function () use ($commissionId, $request) {
            DB::table('commission_payments')->insert([
                'commission_id' => $commissionId,
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'method' => $request->method,
                'reference' => $request->reference,
                'notes' => $request->notes,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $commission = DB::table('commissions')->where('id', $commissionId)->first();
            $paidTotal = DB::table('commission_payments')->where('commission_id', $commissionId)->sum('amount');
            DB::table('commissions')->where('id', $commissionId)->update([
                'paid_amount' => $paidTotal,
                'status' => $paidTotal >= (float) $commission->commission_amount ? 'paid' : 'approved',
                'updated_at' => now(),
            ]);
        });

        return back()->with('success', __('Commission payment recorded.'));
    }

    public function storeCurrencyRate(Request $request): RedirectResponse
    {
        $request->validate([
            'base_currency' => 'required|string|size:3',
            'quote_currency' => 'required|string|size:3',
            'rate' => 'required|numeric|min:0.000001',
            'effective_date' => 'required|date',
        ]);

        DB::table('currency_rates')->insert([
            'base_currency' => strtoupper($request->base_currency),
            'quote_currency' => strtoupper($request->quote_currency),
            'rate' => $request->rate,
            'effective_date' => $request->effective_date,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Currency rate saved.'));
    }

    public function operations(): View
    {
        return view('phase.operations', [
            'utilityBills' => DB::table('utility_bills')->orderByDesc('id')->get(),
            'maintenanceSchedules' => DB::table('maintenance_schedules')->orderByDesc('id')->get(),
            'assets' => DB::table('asset_registers')->orderByDesc('id')->get(),
            'properties' => DB::table('properties')->orderBy('name')->get(),
            'units' => DB::table('property_units')->orderBy('name')->get(),
            'branches' => DB::table('branches')->orderBy('name')->get(),
        ]);
    }

    public function storeUtilityBill(Request $request): RedirectResponse
    {
        $request->validate([
            'bill_type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'currency_code' => 'required|string|size:3',
        ]);

        DB::table('utility_bills')->insert([
            'parent_id' => $this->parentId(),
            'property_id' => $request->property_id,
            'property_unit_id' => $request->property_unit_id,
            'bill_type' => $request->bill_type,
            'provider' => $request->provider,
            'period' => $request->period,
            'amount' => $request->amount,
            'currency_code' => strtoupper($request->currency_code),
            'status' => 'pending',
            'due_date' => $request->due_date,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Utility bill created.'));
    }

    public function markUtilityBillPaid(int $billId): RedirectResponse
    {
        DB::table('utility_bills')->where('id', $billId)->update([
            'status' => 'paid',
            'paid_date' => now()->toDateString(),
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Utility bill marked paid.'));
    }

    public function storeMaintenanceSchedule(Request $request): RedirectResponse
    {
        $request->validate([
            'property_id' => 'required|integer|exists:properties,id',
            'service_type' => 'required|string|max:255',
            'next_maintenance_date' => 'required|date',
        ]);

        DB::table('maintenance_schedules')->insert([
            'parent_id' => $this->parentId(),
            'property_id' => $request->property_id,
            'property_unit_id' => $request->property_unit_id,
            'service_type' => $request->service_type,
            'last_maintenance_date' => $request->last_maintenance_date,
            'next_maintenance_date' => $request->next_maintenance_date,
            'cost_estimate' => $request->cost_estimate ?? 0,
            'currency_code' => strtoupper($request->currency_code ?? 'TZS'),
            'status' => 'scheduled',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Maintenance schedule created.'));
    }

    public function storeAsset(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'acquisition_date' => 'required|date',
            'cost' => 'required|numeric|min:0',
            'useful_life_years' => 'required|integer|min:1',
        ]);

        DB::table('asset_registers')->insert([
            'parent_id' => $this->parentId(),
            'branch_id' => $request->branch_id,
            'name' => $request->name,
            'category' => $request->category,
            'acquisition_date' => $request->acquisition_date,
            'cost' => $request->cost,
            'salvage_value' => $request->salvage_value ?? 0,
            'useful_life_years' => $request->useful_life_years,
            'method' => $request->method ?? 'straight_line',
            'accumulated_depreciation' => 0,
            'book_value' => $request->cost,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Asset added.'));
    }

    public function depreciateAsset(Request $request, int $assetId): RedirectResponse
    {
        $request->validate([
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
        ]);

        $this->service->depreciateAsset($assetId, $request->period_start, $request->period_end);

        return back()->with('success', __('Depreciation posted.'));
    }

    public function communications(): View
    {
        return view('phase.communications', [
            'threads' => DB::table('email_threads')->orderByDesc('id')->get(),
            'notifications' => DB::table('notification_logs')->orderByDesc('id')->limit(50)->get(),
            'feedback' => DB::table('property_feedback')->orderByDesc('id')->limit(50)->get(),
            'plots' => DB::table('plots')->where('status', 'available')->orderByDesc('id')->limit(50)->get(),
            'properties' => DB::table('properties')->where('is_active', 1)->orderByDesc('id')->limit(50)->get(),
        ]);
    }

    public function showThread(int $threadId): View
    {
        return view('phase.thread', [
            'thread' => DB::table('email_threads')->where('id', $threadId)->firstOrFail(),
            'messages' => DB::table('email_messages')->where('email_thread_id', $threadId)->orderBy('id')->get(),
        ]);
    }

    public function storeThread(Request $request): RedirectResponse
    {
        $request->validate([
            'subject' => 'required|string|max:255',
        ]);

        DB::table('email_threads')->insert([
            'parent_id' => $this->parentId(),
            'subject' => $request->subject,
            'channel' => 'email',
            'linked_type' => $request->linked_type,
            'linked_id' => $request->linked_id,
            'last_message_at' => now(),
            'status' => 'open',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Email thread created.'));
    }

    public function storeThreadMessage(Request $request, int $threadId): RedirectResponse
    {
        $request->validate([
            'direction' => 'required|in:inbound,outbound',
            'body' => 'required|string',
        ]);

        DB::table('email_messages')->insert([
            'email_thread_id' => $threadId,
            'direction' => $request->direction,
            'from_address' => $request->from_address,
            'to_address' => $request->to_address,
            'cc_addresses' => $request->cc_addresses,
            'bcc_addresses' => $request->bcc_addresses,
            'message_id' => $request->message_id,
            'body' => $request->body,
            'attachments' => null,
            'received_at' => $request->direction === 'inbound' ? now() : null,
            'sent_at' => $request->direction === 'outbound' ? now() : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('email_threads')->where('id', $threadId)->update([
            'last_message_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Message saved.'));
    }

    public function storeFeedback(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        DB::table('property_feedback')->insert([
            'parent_id' => $this->parentId(),
            'property_id' => $request->property_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'status' => 'new',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Feedback captured.'));
    }

    public function storeNotification(Request $request): RedirectResponse
    {
        $request->validate([
            'channel' => 'required|in:email,sms,whatsapp,in_app',
            'recipient' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        DB::table('notification_logs')->insert([
            'parent_id' => $this->parentId(),
            'channel' => $request->channel,
            'recipient' => $request->recipient,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
            'context' => null,
            'scheduled_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Notification queued.'));
    }

    public function generateReminders(): RedirectResponse
    {
        $this->service->generateReminders(7);

        return back()->with('success', __('Reminders generated.'));
    }

    public function reports(): View
    {
        $summary = $this->service->reportSummary();
        $baseCurrency = 'TZS';
        $balances = DB::table('sales')
            ->select('currency_code', DB::raw('SUM(outstanding_balance) as outstanding'))
            ->groupBy('currency_code')
            ->get();

        $valuation = [];
        foreach ($balances as $row) {
            $rate = 1.0;
            if ($row->currency_code !== $baseCurrency) {
                $lookup = DB::table('currency_rates')
                    ->where('base_currency', $baseCurrency)
                    ->where('quote_currency', $row->currency_code)
                    ->orderByDesc('effective_date')
                    ->value('rate');
                $rate = $lookup ?: 1.0;
            }
            $valuation[] = [
                'currency' => $row->currency_code,
                'outstanding' => (float) $row->outstanding,
                'rate' => (float) $rate,
                'base_amount' => round((float) $row->outstanding / (float) $rate, 2),
            ];
        }

        return view('phase.reports', [
            'summary' => $summary,
            'valuation' => $valuation,
            'projectProfit' => $summary['project_profit'] ?? collect(),
        ]);
    }

    private function parentId(): int
    {
        if (function_exists('parentId') && auth()->check()) {
            return (int) parentId();
        }

        return 1;
    }
}
