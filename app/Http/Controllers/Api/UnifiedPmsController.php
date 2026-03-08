<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\IntegrationDispatchService;
use App\Services\UnifiedPmsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UnifiedPmsController extends Controller
{
    protected $service;
    protected $integrationDispatchService;

    public function __construct(UnifiedPmsService $service, IntegrationDispatchService $integrationDispatchService)
    {
        $this->service = $service;
        $this->integrationDispatchService = $integrationDispatchService;
    }

    public function createBranch(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        $id = DB::table('branches')->insertGetId([
            'parent_id' => $this->parentId(),
            'name' => $request->name,
            'code' => $request->code,
            'address' => $request->address,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'id' => $id], 201);
    }

    public function createProject(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'branch_id' => 'nullable|integer|exists:branches,id',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        $id = DB::table('projects')->insertGetId([
            'parent_id' => $this->parentId(),
            'branch_id' => $request->branch_id,
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status ?? 'active',
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'id' => $id], 201);
    }

    public function createBlock(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|integer|exists:projects,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        $id = DB::table('blocks')->insertGetId([
            'project_id' => $request->project_id,
            'name' => $request->name,
            'code' => $request->code,
            'status' => $request->status ?? 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'id' => $id], 201);
    }

    public function createPlot(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'block_id' => 'required|integer|exists:blocks,id',
            'plot_number' => 'required|string|max:255',
            'title_deed_no' => 'nullable|string|max:255',
            'size_sqm' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'rental_price' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:available,reserved,sold,cancelled',
            'property_id' => 'nullable|integer|exists:properties,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        $id = DB::table('plots')->insertGetId([
            'parent_id' => $this->parentId(),
            'block_id' => $request->block_id,
            'property_id' => $request->property_id,
            'plot_number' => $request->plot_number,
            'title_deed_no' => $request->title_deed_no,
            'size_sqm' => $request->size_sqm,
            'sale_price' => $request->sale_price ?? 0,
            'rental_price' => $request->rental_price ?? 0,
            'status' => $request->status ?? 'available',
            'meta' => $request->meta ? json_encode($request->meta) : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'id' => $id], 201);
    }

    public function createCustomer(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'nullable|in:buyer,tenant,investor',
            'title' => 'nullable|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'business_registration_number' => 'nullable|string|max:255',
            'tin' => 'nullable|string|max:255',
            'taxpayer_identification_number' => 'nullable|string|max:255',
            'id_number' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        $id = DB::table('customers')->insertGetId([
            'parent_id' => $this->parentId(),
            'type' => $request->type ?? 'buyer',
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

        return response()->json(['status' => 'success', 'id' => $id], 201);
    }

    public function createSeller(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'ownership_reference' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        $id = DB::table('sellers')->insertGetId([
            'parent_id' => $this->parentId(),
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'ownership_reference' => $request->ownership_reference,
            'agreed_amount' => $request->agreed_amount ?? 0,
            'amount_paid' => $request->amount_paid ?? 0,
            'amount_remaining' => $request->amount_remaining ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'id' => $id], 201);
    }

    public function createAgent(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'commission_type' => 'required|in:percentage,fixed',
            'commission_value' => 'required|numeric|min:0',
            'commission_trigger' => 'required|in:sale_confirmed,full_payment,collected_amount',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        $id = DB::table('agents')->insertGetId([
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

        return response()->json(['status' => 'success', 'id' => $id], 201);
    }

    public function createSale(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'nullable|integer|exists:projects,id',
            'block_id' => 'nullable|integer|exists:blocks,id',
            'plot_id' => 'required|integer|exists:plots,id',
            'customer_id' => 'required|integer|exists:customers,id',
            'seller_id' => 'nullable|integer|exists:sellers,id',
            'agent_id' => 'nullable|integer|exists:agents,id',
            'sale_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'commission_rule_type' => 'nullable|in:percentage,fixed',
            'commission_rule_value' => 'nullable|numeric|min:0',
            'commission_trigger' => 'nullable|in:sale_confirmed,full_payment,collected_amount',
            'currency_code' => 'nullable|string|max:3',
            'exchange_rate' => 'nullable|numeric|min:0.000001',
            'installments' => 'nullable|array',
            'installments.*.due_date' => 'required_with:installments|date',
            'installments.*.amount' => 'required_with:installments|numeric|min:0',
            'extra_charges' => 'nullable|array',
            'extra_charges.*.charge_type' => 'required_with:extra_charges|string|max:255',
            'extra_charges.*.amount' => 'required_with:extra_charges|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        $result = $this->service->createSale($request->all());

        return response()->json(['status' => 'success', 'data' => $result], 201);
    }

    public function addSaleCharge(Request $request, int $saleId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'charge_type' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'reason' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        DB::table('sale_extra_charges')->insert([
            'sale_id' => $saleId,
            'charge_type' => $request->charge_type,
            'description' => $request->description,
            'amount' => $request->amount,
            'added_by' => Auth::id(),
            'reason' => $request->reason,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $sale = $this->service->recalculateSale($saleId);

        return response()->json(['status' => 'success', 'sale' => $sale]);
    }

    public function recordSalePayment(Request $request, int $saleId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'nullable|string|max:100',
            'payment_reference' => 'nullable|string|max:255',
            'bank_reference' => 'nullable|string|max:255',
            'payment_date' => 'nullable|date',
            'currency_code' => 'nullable|string|max:3',
            'exchange_rate' => 'nullable|numeric|min:0.000001',
            'control_number_id' => 'nullable|integer|exists:bank_control_numbers,id',
            'excess_action' => 'nullable|in:credit,refund,carry_forward',
            'excess_notes' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        $result = $this->service->recordSalePayment($saleId, $request->all());

        return response()->json(['status' => 'success', 'data' => $result]);
    }

    public function updateSaleStatus(Request $request, int $saleId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:draft,confirmed,cancelled,completed',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        $sale = $this->service->updateSaleStatus($saleId, $request->status);

        return response()->json(['status' => 'success', 'sale' => $sale]);
    }

    public function createControlNumber(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'reference_type' => 'required|string|max:255',
            'reference_id' => 'required|integer',
            'amount' => 'required|numeric|min:0',
            'currency_code' => 'nullable|string|max:3',
            'bank_name' => 'nullable|string|max:255',
            'expires_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        $result = $this->service->createControlNumber($request->all());

        return response()->json(['status' => 'success', 'data' => $result], 201);
    }

    public function saleStatement(int $saleId): JsonResponse
    {
        $sale = DB::table('sales')->where('id', $saleId)->where('parent_id', $this->parentId())->first();
        if (!$sale) {
            return response()->json(['status' => 'error', 'message' => 'Sale not found.'], 404);
        }

        $payments = DB::table('sale_payments')->where('sale_id', $saleId)->orderByDesc('id')->get();
        $charges = DB::table('sale_extra_charges')->where('sale_id', $saleId)->orderByDesc('id')->get();
        $credits = DB::table('customer_credits')->where('sale_id', $saleId)->orderByDesc('id')->get();
        $commission = DB::table('commissions')->where('sale_id', $saleId)->first();

        return response()->json([
            'status' => 'success',
            'sale' => $sale,
            'payments' => $payments,
            'charges' => $charges,
            'credits' => $credits,
            'commission' => $commission,
        ]);
    }

    public function availableListings(): JsonResponse
    {
        $plots = DB::table('plots')
            ->leftJoin('blocks', 'blocks.id', '=', 'plots.block_id')
            ->leftJoin('projects', 'projects.id', '=', 'blocks.project_id')
            ->where('plots.status', 'available')
            ->select('plots.*', 'blocks.name as block_name', 'projects.name as project_name')
            ->orderBy('plots.id', 'desc')
            ->get();

        $properties = DB::table('properties')
            ->where('is_active', 1)
            ->whereIn('occupancy_status', ['available', 'vacant'])
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'status' => 'success',
            'plots' => $plots,
            'properties' => $properties,
        ]);
    }

    public function submitFeedback(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'message' => 'required|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'property_id' => 'nullable|integer|exists:properties,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        $id = DB::table('property_feedback')->insertGetId([
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

        return response()->json(['status' => 'success', 'id' => $id], 201);
    }

    public function createUtilityBill(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'nullable|integer|exists:properties,id',
            'property_unit_id' => 'nullable|integer|exists:property_units,id',
            'bill_type' => 'required|string|max:255',
            'provider' => 'nullable|string|max:255',
            'period' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'currency_code' => 'nullable|string|max:3',
            'due_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        $id = DB::table('utility_bills')->insertGetId([
            'parent_id' => $this->parentId(),
            'property_id' => $request->property_id,
            'property_unit_id' => $request->property_unit_id,
            'bill_type' => $request->bill_type,
            'provider' => $request->provider,
            'period' => $request->period,
            'amount' => $request->amount,
            'currency_code' => $request->currency_code ?? 'TZS',
            'status' => 'pending',
            'due_date' => $request->due_date,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'id' => $id], 201);
    }

    public function markUtilityBillPaid(int $billId): JsonResponse
    {
        $updated = DB::table('utility_bills')->where('id', $billId)->where('parent_id', $this->parentId())->update([
            'status' => 'paid',
            'paid_date' => now()->toDateString(),
            'updated_at' => now(),
        ]);

        if (!$updated) {
            return response()->json(['status' => 'error', 'message' => 'Bill not found.'], 404);
        }

        return response()->json(['status' => 'success']);
    }

    public function createMaintenanceSchedule(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required|integer|exists:properties,id',
            'property_unit_id' => 'nullable|integer|exists:property_units,id',
            'service_type' => 'required|string|max:255',
            'last_maintenance_date' => 'nullable|date',
            'next_maintenance_date' => 'required|date',
            'cost_estimate' => 'nullable|numeric|min:0',
            'currency_code' => 'nullable|string|max:3',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        $id = DB::table('maintenance_schedules')->insertGetId([
            'parent_id' => $this->parentId(),
            'property_id' => $request->property_id,
            'property_unit_id' => $request->property_unit_id,
            'service_type' => $request->service_type,
            'last_maintenance_date' => $request->last_maintenance_date,
            'next_maintenance_date' => $request->next_maintenance_date,
            'cost_estimate' => $request->cost_estimate ?? 0,
            'currency_code' => $request->currency_code ?? 'TZS',
            'status' => 'scheduled',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'id' => $id], 201);
    }

    public function createAsset(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'branch_id' => 'nullable|integer|exists:branches,id',
            'acquisition_date' => 'required|date',
            'cost' => 'required|numeric|min:0',
            'salvage_value' => 'nullable|numeric|min:0',
            'useful_life_years' => 'required|integer|min:1',
            'method' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        $cost = (float) $request->cost;
        $salvage = (float) ($request->salvage_value ?? 0);
        $id = DB::table('asset_registers')->insertGetId([
            'parent_id' => $this->parentId(),
            'branch_id' => $request->branch_id,
            'name' => $request->name,
            'category' => $request->category,
            'acquisition_date' => $request->acquisition_date,
            'cost' => $cost,
            'salvage_value' => $salvage,
            'useful_life_years' => $request->useful_life_years,
            'method' => $request->method ?? 'straight_line',
            'accumulated_depreciation' => 0,
            'book_value' => $cost,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'id' => $id], 201);
    }

    public function depreciateAsset(Request $request, int $assetId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        $result = $this->service->depreciateAsset($assetId, $request->period_start, $request->period_end);

        return response()->json(['status' => 'success', 'data' => $result]);
    }

    public function createEmailThread(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'nullable|string|max:255',
            'linked_type' => 'nullable|string|max:255',
            'linked_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        $id = DB::table('email_threads')->insertGetId([
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

        return response()->json(['status' => 'success', 'id' => $id], 201);
    }

    public function addEmailMessage(Request $request, int $threadId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'direction' => 'required|in:inbound,outbound',
            'from_address' => 'nullable|email|max:255',
            'to_address' => 'nullable|string|max:255',
            'body' => 'required|string',
            'attachments' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        if (!DB::table('email_threads')->where('id', $threadId)->where('parent_id', $this->parentId())->exists()) {
            return response()->json(['status' => 'error', 'message' => 'Thread not found.'], 404);
        }

        $id = DB::table('email_messages')->insertGetId([
            'email_thread_id' => $threadId,
            'direction' => $request->direction,
            'from_address' => $request->from_address,
            'to_address' => $request->to_address,
            'cc_addresses' => $request->cc_addresses ?? null,
            'bcc_addresses' => $request->bcc_addresses ?? null,
            'message_id' => $request->message_id ?? null,
            'body' => $request->body,
            'attachments' => $request->attachments ? json_encode($request->attachments) : null,
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

        return response()->json(['status' => 'success', 'id' => $id], 201);
    }

    public function showEmailThread(int $threadId): JsonResponse
    {
        $thread = DB::table('email_threads')->where('id', $threadId)->where('parent_id', $this->parentId())->first();
        if (!$thread) {
            return response()->json(['status' => 'error', 'message' => 'Thread not found.'], 404);
        }

        $messages = DB::table('email_messages')->where('email_thread_id', $threadId)->orderBy('id')->get();

        return response()->json(['status' => 'success', 'thread' => $thread, 'messages' => $messages]);
    }

    public function addCurrencyRate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'base_currency' => 'required|string|size:3',
            'quote_currency' => 'required|string|size:3|different:base_currency',
            'rate' => 'required|numeric|min:0.000001',
            'effective_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        $id = DB::table('currency_rates')->insertGetId([
            'base_currency' => strtoupper($request->base_currency),
            'quote_currency' => strtoupper($request->quote_currency),
            'rate' => $request->rate,
            'effective_date' => $request->effective_date,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'id' => $id], 201);
    }

    public function currencyValuationReport(Request $request): JsonResponse
    {
        $baseCurrency = strtoupper($request->get('base_currency', 'TZS'));

        $balances = DB::table('sales')
            ->where('parent_id', $this->parentId())
            ->select('currency_code', DB::raw('SUM(outstanding_balance) as outstanding'))
            ->groupBy('currency_code')
            ->get();

        $valuation = [];
        $grandTotal = 0.0;

        foreach ($balances as $row) {
            $currency = strtoupper($row->currency_code);
            $amount = (float) $row->outstanding;
            if ($currency === $baseCurrency) {
                $converted = $amount;
                $rate = 1.0;
            } else {
                $rate = (float) DB::table('currency_rates')
                    ->where('base_currency', $baseCurrency)
                    ->where('quote_currency', $currency)
                    ->orderByDesc('effective_date')
                    ->value('rate');
                $rate = $rate > 0 ? $rate : 1.0;
                $converted = $amount / $rate;
            }

            $grandTotal += $converted;
            $valuation[] = [
                'currency' => $currency,
                'outstanding' => $amount,
                'rate_to_' . $baseCurrency => $rate,
                'base_amount' => round($converted, 2),
            ];
        }

        return response()->json([
            'status' => 'success',
            'base_currency' => $baseCurrency,
            'valuation' => $valuation,
            'total_base_amount' => round($grandTotal, 2),
        ]);
    }

    public function generateReminders(Request $request): JsonResponse
    {
        $daysAhead = (int) $request->get('days_ahead', 7);
        $result = $this->service->generateReminders($daysAhead);

        return response()->json(['status' => 'success', 'data' => $result]);
    }

    public function createNotification(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'channel' => 'required|in:email,sms,whatsapp,in_app',
            'recipient' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'scheduled_at' => 'nullable|date',
            'context' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        $id = DB::table('notification_logs')->insertGetId([
            'parent_id' => $this->parentId(),
            'channel' => $request->channel,
            'recipient' => $request->recipient,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
            'context' => $request->context ? json_encode($request->context) : null,
            'scheduled_at' => $request->scheduled_at ? $request->scheduled_at : now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $dispatchResult = $this->integrationDispatchService->dispatch(
            $request->channel,
            $request->recipient,
            $request->subject,
            $request->message,
            $this->parentId(),
            ['reference_type' => 'notification', 'reference_id' => $id]
        );

        DB::table('notification_logs')->where('id', $id)->where('parent_id', $this->parentId())->update([
            'status' => $dispatchResult['status'],
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'id' => $id], 201);
    }

    public function reportSummary(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $this->service->reportSummary(),
        ]);
    }

    public function rentRollReport(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $this->service->rentRollReport(),
        ]);
    }

    public function receivablesAgingReport(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $this->service->receivablesAgingReport(),
        ]);
    }

    public function excessPaymentReport(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $this->service->excessPaymentStatements(),
        ]);
    }

    public function sellerSettlementReport(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $this->service->sellerSettlementReport(),
        ]);
    }

    private function parentId(): int
    {
        if (Auth::check() && function_exists('parentId')) {
            return (int) parentId();
        }
        return 1;
    }
}
