<?php

namespace App\Http\Controllers;

use App\Services\UnifiedPmsService;
use App\Services\IntegrationDispatchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UnifiedPmsWebController extends Controller
{
    public function __construct(
        private UnifiedPmsService $service,
        private IntegrationDispatchService $integrationDispatchService
    )
    {
    }

    public function dashboard(): View
    {
        return view('phase.dashboard');
    }

    public function land(): View
    {
        return view('phase.land', [
            'branches' => DB::table('branches')->where('parent_id', $this->parentId())->orderByDesc('id')->get(),
            'projects' => DB::table('projects')->where('parent_id', $this->parentId())->orderByDesc('id')->get(),
            'blocks' => DB::table('blocks')->where('parent_id', $this->parentId())->orderByDesc('id')->get(),
            'plots' => DB::table('plots')->where('parent_id', $this->parentId())->orderByDesc('id')->get(),
            'properties' => DB::table('properties')->where('parent_id', $this->parentId())->orderByDesc('id')->get(),
            'landRatePayments' => DB::table('land_rate_payments as lrp')
                ->leftJoin('properties as p', 'p.id', '=', 'lrp.property_id')
                ->select('lrp.*', 'p.name as property_name')
                ->where('lrp.parent_id', $this->parentId())
                ->orderByDesc('lrp.id')
                ->get(),
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

    public function updateBranch(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $branch = $this->tableByParent('branches')->where('id', $id)->first();
        if (!$branch) {
            return back()->with('error', __('Branch not found.'));
        }

        $this->tableByParent('branches')->where('id', $id)->update([
            'name' => $request->name ?? $branch->name,
            'code' => $request->code ?? $branch->code,
            'address' => $request->address ?? $branch->address,
            'is_active' => (bool) ($request->is_active ?? $branch->is_active),
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Branch updated.'));
    }

    public function destroyBranch(int $id): RedirectResponse
    {
        $this->tableByParent('branches')->where('id', $id)->delete();
        return back()->with('success', __('Branch deleted.'));
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

    public function updateProject(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $project = $this->tableByParent('projects')->where('id', $id)->first();
        if (!$project) {
            return back()->with('error', __('Project not found.'));
        }

        $this->tableByParent('projects')->where('id', $id)->update([
            'branch_id' => $request->branch_id ?? $project->branch_id,
            'name' => $request->name ?? $project->name,
            'description' => $request->description ?? $project->description,
            'status' => $request->status ?? $project->status,
            'start_date' => $request->start_date ?? $project->start_date,
            'end_date' => $request->end_date ?? $project->end_date,
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Project updated.'));
    }

    public function destroyProject(int $id): RedirectResponse
    {
        $this->tableByParent('projects')->where('id', $id)->delete();
        return back()->with('success', __('Project deleted.'));
    }

    public function storeBlock(Request $request): RedirectResponse
    {
        $request->validate([
            'project_id' => 'required|integer|exists:projects,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
        ]);

        DB::table('blocks')->insert([
            'parent_id' => $this->parentId(),
            'project_id' => $request->project_id,
            'name' => $request->name,
            'code' => $request->code,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Block created.'));
    }

    public function updateBlock(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $block = DB::table('blocks')->where('id', $id)->where('parent_id', $this->parentId())->first();
        if (!$block) {
            return back()->with('error', __('Block not found.'));
        }

        DB::table('blocks')->where('id', $id)->where('parent_id', $this->parentId())->update([
            'project_id' => $request->project_id ?? $block->project_id,
            'name' => $request->name ?? $block->name,
            'code' => $request->code ?? $block->code,
            'status' => $request->status ?? $block->status,
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Block updated.'));
    }

    public function destroyBlock(int $id): RedirectResponse
    {
        DB::table('blocks')->where('id', $id)->where('parent_id', $this->parentId())->delete();
        return back()->with('success', __('Block deleted.'));
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

    public function updatePlot(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'plot_number' => 'required|string|max:255',
            'sale_price' => 'required|numeric|min:0',
        ]);

        $plot = DB::table('plots')->where('id', $id)->where('parent_id', $this->parentId())->first();
        if (!$plot) {
            return back()->with('error', __('Plot not found.'));
        }

        DB::table('plots')->where('id', $id)->where('parent_id', $this->parentId())->update([
            'block_id' => $request->block_id ?? $plot->block_id,
            'property_id' => $request->property_id ?? $plot->property_id,
            'plot_number' => $request->plot_number ?? $plot->plot_number,
            'title_deed_no' => $request->title_deed_no ?? $plot->title_deed_no,
            'size_sqm' => $request->size_sqm ?? $plot->size_sqm,
            'sale_price' => $request->sale_price ?? $plot->sale_price,
            'rental_price' => $request->rental_price ?? $plot->rental_price,
            'status' => $request->status ?? 'available',
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Plot updated.'));
    }

    public function destroyPlot(int $id): RedirectResponse
    {
        DB::table('plots')->where('id', $id)->where('parent_id', $this->parentId())->delete();
        return back()->with('success', __('Plot deleted.'));
    }

    public function storeLandRatePayment(Request $request): RedirectResponse
    {
        $request->validate([
            'property_id' => 'required|integer|exists:properties,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
        ]);

        DB::table('land_rate_payments')->insert([
            'parent_id' => $this->parentId(),
            'property_id' => $request->property_id,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'reference' => $request->reference,
            'notes' => $request->notes,
            'created_by' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('properties')->where('id', $request->property_id)->where('parent_id', $this->parentId())->increment('land_rates_paid', $request->amount);

        return back()->with('success', __('Land rate payment recorded.'));
    }

    public function updateLandRatePayment(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
        ]);

        $payment = DB::table('land_rate_payments')->where('id', $id)->where('parent_id', $this->parentId())->first();
        if (!$payment) {
            return back()->with('error', __('Land rate payment not found.'));
        }

        DB::table('land_rate_payments')->where('id', $id)->where('parent_id', $this->parentId())->update([
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'reference' => $request->reference,
            'notes' => $request->notes,
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Land rate payment updated.'));
    }

    public function destroyLandRatePayment(int $id): RedirectResponse
    {
        DB::table('land_rate_payments')->where('id', $id)->where('parent_id', $this->parentId())->delete();
        return back()->with('success', __('Land rate payment deleted.'));
    }

    public function parties(): View
    {
        return view('phase.parties', [
            'customers' => DB::table('customers')->where('parent_id', $this->parentId())->orderByDesc('id')->get(),
            'sellers' => DB::table('sellers')->where('parent_id', $this->parentId())->orderByDesc('id')->get(),
            'agents' => DB::table('agents')->where('parent_id', $this->parentId())->orderByDesc('id')->get(),
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

    public function updateCustomer(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:buyer,tenant,investor',
        ]);

        $customer = $this->tableByParent('customers')->where('id', $id)->first();
        if (!$customer) {
            return back()->with('error', __('Customer not found.'));
        }

        $this->tableByParent('customers')->where('id', $id)->update([
            'type' => $request->type ?? $customer->type,
            'name' => $request->name ?? $customer->name,
            'title' => $request->title ?? $customer->title,
            'business_name' => $request->business_name ?? $customer->business_name,
            'business_registration_number' => $request->business_registration_number ?? $customer->business_registration_number,
            'tin' => $request->tin ?? $customer->tin,
            'taxpayer_identification_number' => $request->taxpayer_identification_number ?? $customer->taxpayer_identification_number,
            'id_number' => $request->id_number ?? $customer->id_number,
            'phone' => $request->phone ?? $customer->phone,
            'email' => $request->email ?? $customer->email,
            'address' => $request->address ?? $customer->address,
            'is_active' => (bool) ($request->is_active ?? $customer->is_active),
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Customer updated.'));
    }

    public function destroyCustomer(int $id): RedirectResponse
    {
        $this->tableByParent('customers')->where('id', $id)->delete();
        return back()->with('success', __('Customer deleted.'));
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

    public function updateSeller(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $seller = $this->tableByParent('sellers')->where('id', $id)->first();
        if (!$seller) {
            return back()->with('error', __('Seller not found.'));
        }

        $this->tableByParent('sellers')->where('id', $id)->update([
            'name' => $request->name ?? $seller->name,
            'phone' => $request->phone ?? $seller->phone,
            'email' => $request->email ?? $seller->email,
            'address' => $request->address ?? $seller->address,
            'ownership_reference' => $request->ownership_reference ?? $seller->ownership_reference,
            'agreed_amount' => $request->agreed_amount ?? $seller->agreed_amount,
            'amount_paid' => $request->amount_paid ?? $seller->amount_paid,
            'amount_remaining' => $request->amount_remaining ?? $seller->amount_remaining,
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Seller updated.'));
    }

    public function destroySeller(int $id): RedirectResponse
    {
        $this->tableByParent('sellers')->where('id', $id)->delete();
        return back()->with('success', __('Seller deleted.'));
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

    public function updateAgent(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'commission_type' => 'required|in:percentage,fixed',
            'commission_value' => 'required|numeric|min:0',
            'commission_trigger' => 'required|in:sale_confirmed,full_payment,collected_amount',
        ]);

        $agent = $this->tableByParent('agents')->where('id', $id)->first();
        if (!$agent) {
            return back()->with('error', __('Agent not found.'));
        }

        $this->tableByParent('agents')->where('id', $id)->update([
            'name' => $request->name ?? $agent->name,
            'phone' => $request->phone ?? $agent->phone,
            'email' => $request->email ?? $agent->email,
            'commission_type' => $request->commission_type ?? $agent->commission_type,
            'commission_value' => $request->commission_value ?? $agent->commission_value,
            'commission_trigger' => $request->commission_trigger ?? $agent->commission_trigger,
            'is_active' => (bool) ($request->is_active ?? $agent->is_active),
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Agent updated.'));
    }

    public function destroyAgent(int $id): RedirectResponse
    {
        $this->tableByParent('agents')->where('id', $id)->delete();
        return back()->with('success', __('Agent deleted.'));
    }

    public function sales(): View
    {
        return view('phase.sales', [
            'sales' => DB::table('sales as s')
                ->leftJoin('customers as c', 'c.id', '=', 's.customer_id')
                ->leftJoin('plots as p', 'p.id', '=', 's.plot_id')
                ->leftJoin('agents as a', 'a.id', '=', 's.agent_id')
                ->where('s.parent_id', $this->parentId())
                ->select('s.*', 'c.name as customer_name', 'p.plot_number', 'a.name as agent_name')
                ->orderByDesc('s.id')
                ->get(),
            'projects' => DB::table('projects')->where('parent_id', $this->parentId())->orderBy('name')->get(),
            'blocks' => DB::table('blocks')->where('parent_id', $this->parentId())->orderBy('name')->get(),
            'plots' => DB::table('plots')->where('parent_id', $this->parentId())->where('status', 'available')->orderBy('plot_number')->get(),
            'customers' => DB::table('customers')->where('parent_id', $this->parentId())->orderBy('name')->get(),
            'sellers' => DB::table('sellers')->where('parent_id', $this->parentId())->orderBy('name')->get(),
            'agents' => DB::table('agents')->where('parent_id', $this->parentId())->orderBy('name')->get(),
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

    public function destroySale(int $saleId): RedirectResponse
    {
        $sale = DB::table('sales')->where('id', $saleId)->where('parent_id', $this->parentId())->first();
        if ($sale) {
            $this->service->updateSaleStatus($saleId, 'cancelled');
        }
        return back()->with('success', __('Sale cancelled.'));
    }

    public function finance(): View
    {
        $saleIds = DB::table('sales')->where('parent_id', $this->parentId())->pluck('id');

        return view('phase.finance', [
            'receivables' => DB::table('receivables as r')
                ->leftJoin('customers as c', 'c.id', '=', 'r.customer_id')
                ->whereIn('r.sale_id', $saleIds)
                ->select('r.*', 'c.name as customer_name')
                ->orderByDesc('r.id')
                ->get(),
            'commissions' => DB::table('commissions as c')
                ->leftJoin('agents as a', 'a.id', '=', 'c.agent_id')
                ->leftJoin('sales as s', 's.id', '=', 'c.sale_id')
                ->whereIn('c.sale_id', $saleIds)
                ->select('c.*', 'a.name as agent_name', 's.agreement_no')
                ->orderByDesc('c.id')
                ->get(),
            'credits' => DB::table('customer_credits as cc')
                ->leftJoin('customers as c', 'c.id', '=', 'cc.customer_id')
                ->whereIn('cc.sale_id', $saleIds)
                ->select('cc.*', 'c.name as customer_name')
                ->orderByDesc('cc.id')
                ->get(),
            'controlNumbers' => DB::table('bank_control_numbers')->where('parent_id', $this->parentId())->orderByDesc('id')->get(),
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

    public function destroyControlNumber(int $id): RedirectResponse
    {
        $this->tableByParent('bank_control_numbers')->where('id', $id)->delete();
        return back()->with('success', __('Control number deleted.'));
    }

    public function payCommission(Request $request, int $commissionId): RedirectResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
        ]);

        DB::transaction(function () use ($commissionId, $request) {
            $commission = DB::table('commissions as c')
                ->leftJoin('sales as s', 's.id', '=', 'c.sale_id')
                ->where('c.id', $commissionId)
                ->where('s.parent_id', $this->parentId())
                ->select('c.*')
                ->first();
            if (!$commission) {
                abort(404, 'Commission not found.');
            }

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

    public function updateCurrencyRate(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'base_currency' => 'required|string|size:3',
            'quote_currency' => 'required|string|size:3',
            'rate' => 'required|numeric|min:0.000001',
            'effective_date' => 'required|date',
        ]);

        DB::table('currency_rates')->where('id', $id)->update([
            'base_currency' => strtoupper($request->base_currency),
            'quote_currency' => strtoupper($request->quote_currency),
            'rate' => $request->rate,
            'effective_date' => $request->effective_date,
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Currency rate updated.'));
    }

    public function destroyCurrencyRate(int $id): RedirectResponse
    {
        DB::table('currency_rates')->where('id', $id)->delete();
        return back()->with('success', __('Currency rate deleted.'));
    }

    public function operations(): View
    {
        $propertyIds = DB::table('properties')->where('parent_id', $this->parentId())->pluck('id');

        return view('phase.operations', [
            'utilityBills' => DB::table('utility_bills')->where('parent_id', $this->parentId())->orderByDesc('id')->get(),
            'maintenanceSchedules' => DB::table('maintenance_schedules')->where('parent_id', $this->parentId())->orderByDesc('id')->get(),
            'assets' => DB::table('asset_registers')->where('parent_id', $this->parentId())->orderByDesc('id')->get(),
            'properties' => DB::table('properties')->where('parent_id', $this->parentId())->orderBy('name')->get(),
            'units' => DB::table('property_units')->whereIn('property_id', $propertyIds)->orderBy('name')->get(),
            'branches' => DB::table('branches')->where('parent_id', $this->parentId())->orderBy('name')->get(),
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

    public function updateUtilityBill(Request $request, int $billId): RedirectResponse
    {
        $request->validate([
            'bill_type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $bill = $this->tableByParent('utility_bills')->where('id', $billId)->first();
        if (!$bill) {
            return back()->with('error', __('Utility bill not found.'));
        }

        $this->tableByParent('utility_bills')->where('id', $billId)->update([
            'property_id' => $request->property_id ?? $bill->property_id,
            'property_unit_id' => $request->property_unit_id ?? $bill->property_unit_id,
            'bill_type' => $request->bill_type ?? $bill->bill_type,
            'provider' => $request->provider ?? $bill->provider,
            'period' => $request->period ?? $bill->period,
            'amount' => $request->amount ?? $bill->amount,
            'currency_code' => strtoupper($request->currency_code ?? $bill->currency_code ?? 'TZS'),
            'due_date' => $request->due_date ?? $bill->due_date,
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Utility bill updated.'));
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

    public function destroyUtilityBill(int $billId): RedirectResponse
    {
        $this->tableByParent('utility_bills')->where('id', $billId)->delete();
        return back()->with('success', __('Utility bill deleted.'));
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

    public function updateMaintenanceSchedule(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'service_type' => 'required|string|max:255',
            'next_maintenance_date' => 'required|date',
        ]);

        $maintenance = $this->tableByParent('maintenance_schedules')->where('id', $id)->first();
        if (!$maintenance) {
            return back()->with('error', __('Maintenance schedule not found.'));
        }

        $this->tableByParent('maintenance_schedules')->where('id', $id)->update([
            'property_id' => $request->property_id ?? $maintenance->property_id,
            'property_unit_id' => $request->property_unit_id ?? $maintenance->property_unit_id,
            'service_type' => $request->service_type ?? $maintenance->service_type,
            'last_maintenance_date' => $request->last_maintenance_date ?? $maintenance->last_maintenance_date,
            'next_maintenance_date' => $request->next_maintenance_date ?? $maintenance->next_maintenance_date,
            'cost_estimate' => $request->cost_estimate ?? $maintenance->cost_estimate,
            'currency_code' => strtoupper($request->currency_code ?? $maintenance->currency_code ?? 'TZS'),
            'status' => $request->status ?? $maintenance->status ?? 'scheduled',
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Maintenance schedule updated.'));
    }

    public function destroyMaintenanceSchedule(int $id): RedirectResponse
    {
        $this->tableByParent('maintenance_schedules')->where('id', $id)->delete();
        return back()->with('success', __('Maintenance schedule deleted.'));
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

    public function updateAsset(Request $request, int $assetId): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0',
            'useful_life_years' => 'required|integer|min:1',
        ]);

        $asset = $this->tableByParent('asset_registers')->where('id', $assetId)->first();
        if (!$asset) {
            return back()->with('error', __('Asset not found.'));
        }

        $this->tableByParent('asset_registers')->where('id', $assetId)->update([
            'branch_id' => $request->branch_id ?? $asset->branch_id,
            'name' => $request->name ?? $asset->name,
            'category' => $request->category ?? $asset->category,
            'acquisition_date' => $request->acquisition_date ?? $asset->acquisition_date,
            'cost' => $request->cost ?? $asset->cost,
            'salvage_value' => $request->salvage_value ?? $asset->salvage_value,
            'useful_life_years' => $request->useful_life_years ?? $asset->useful_life_years,
            'method' => $request->method ?? $asset->method ?? 'straight_line',
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Asset updated.'));
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

    public function destroyAsset(int $assetId): RedirectResponse
    {
        $this->tableByParent('asset_registers')->where('id', $assetId)->delete();
        return back()->with('success', __('Asset deleted.'));
    }

    public function communications(): View
    {
        return view('phase.communications', [
            'threads' => DB::table('email_threads')->where('parent_id', $this->parentId())->orderByDesc('id')->get(),
            'notifications' => DB::table('notification_logs')->where('parent_id', $this->parentId())->orderByDesc('id')->limit(50)->get(),
            'feedback' => DB::table('property_feedback')->where('parent_id', $this->parentId())->orderByDesc('id')->limit(50)->get(),
            'plots' => DB::table('plots')->where('parent_id', $this->parentId())->where('status', 'available')->orderByDesc('id')->limit(50)->get(),
            'properties' => DB::table('properties')->where('parent_id', $this->parentId())->where('is_active', 1)->orderByDesc('id')->limit(50)->get(),
        ]);
    }

    public function showThread(int $threadId): View
    {
        return view('phase.thread', [
            'thread' => DB::table('email_threads')->where('parent_id', $this->parentId())->where('id', $threadId)->firstOrFail(),
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

    public function updateThread(Request $request, int $threadId): RedirectResponse
    {
        $request->validate([
            'subject' => 'required|string|max:255',
        ]);

        $thread = $this->tableByParent('email_threads')->where('id', $threadId)->first();
        if (!$thread) {
            return back()->with('error', __('Thread not found.'));
        }

        $this->tableByParent('email_threads')->where('id', $threadId)->update([
            'subject' => $request->subject ?? $thread->subject,
            'linked_type' => $request->linked_type ?? $thread->linked_type,
            'linked_id' => $request->linked_id ?? $thread->linked_id,
            'status' => $request->status ?? $thread->status ?? 'open',
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Thread updated.'));
    }

    public function destroyThread(int $threadId): RedirectResponse
    {
        $this->tableByParent('email_threads')->where('id', $threadId)->delete();
        DB::table('email_messages')->where('email_thread_id', $threadId)->delete();
        return back()->with('success', __('Thread deleted.'));
    }

    public function storeThreadMessage(Request $request, int $threadId): RedirectResponse
    {
        $request->validate([
            'direction' => 'required|in:inbound,outbound',
            'body' => 'required|string',
        ]);

        $threadExists = DB::table('email_threads')->where('id', $threadId)->where('parent_id', $this->parentId())->exists();
        if (!$threadExists) {
            return back()->with('error', __('Thread not found.'));
        }

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

        if ($request->direction === 'outbound' && !empty($request->to_address)) {
            $this->integrationDispatchService->dispatch(
                'email',
                $request->to_address,
                $request->subject ?? 'Reply from PMS',
                $request->body,
                $this->parentId(),
                ['reference_type' => 'email_thread', 'reference_id' => $threadId]
            );
        }

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

    public function updateFeedback(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'status' => 'required|string|max:50',
        ]);

        $this->tableByParent('property_feedback')->where('id', $id)->update([
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Feedback updated.'));
    }

    public function destroyFeedback(int $id): RedirectResponse
    {
        $this->tableByParent('property_feedback')->where('id', $id)->delete();
        return back()->with('success', __('Feedback deleted.'));
    }

    public function storeNotification(Request $request): RedirectResponse
    {
        $request->validate([
            'channel' => 'required|in:email,sms,whatsapp,in_app',
            'recipient' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        $notificationId = DB::table('notification_logs')->insertGetId([
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

        $dispatchResult = $this->integrationDispatchService->dispatch(
            $request->channel,
            $request->recipient,
            $request->subject,
            $request->message,
            $this->parentId(),
            ['reference_type' => 'notification', 'reference_id' => $notificationId]
        );

        DB::table('notification_logs')->where('id', $notificationId)->where('parent_id', $this->parentId())->update([
            'status' => $dispatchResult['status'],
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Notification queued.'));
    }

    public function updateNotification(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'status' => 'required|string|max:50',
        ]);

        $this->tableByParent('notification_logs')->where('id', $id)->update([
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        return back()->with('success', __('Notification updated.'));
    }

    public function destroyNotification(int $id): RedirectResponse
    {
        $this->tableByParent('notification_logs')->where('id', $id)->delete();
        return back()->with('success', __('Notification deleted.'));
    }

    public function generateReminders(): RedirectResponse
    {
        $this->service->generateReminders(7);

        return back()->with('success', __('Reminders generated.'));
    }

    public function reports(): View
    {
        $summary = $this->service->reportSummary();
        $rentRoll = $this->service->rentRollReport();
        $aging = $this->service->receivablesAgingReport();
        $excessStatements = $this->service->excessPaymentStatements();
        $sellerSettlement = $this->service->sellerSettlementReport();
        $baseCurrency = 'TZS';
        $balances = DB::table('sales')
            ->where('parent_id', $this->parentId())
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
            'rentRoll' => $rentRoll,
            'receivableAging' => $aging,
            'excessStatements' => $excessStatements,
            'sellerSettlement' => $sellerSettlement,
        ]);
    }

    private function parentId(): int
    {
        if (function_exists('parentId') && auth()->check()) {
            return (int) parentId();
        }

        return 1;
    }

    private function tableByParent(string $table)
    {
        return DB::table($table)->where('parent_id', $this->parentId());
    }
}
