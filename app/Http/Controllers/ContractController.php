<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Tenant;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('manage contract')) {
            $contracts = Contract::where('user_id', parentId())->orderByDesc('id')->get();
            return view('contract.index', compact('contracts'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('create contract')) {
            $tenants = Tenant::where('parent_id', parentId())->get();
            return view('contract.create', compact('tenants'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function store(Request $request)
    {
        if (!\Auth::user()->can('create contract')) {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }

        $request->validate([
            'tenant_id' => 'required|integer|exists:tenants,id',
            'lease_start_date' => 'required|date',
            'lease_end_date' => 'required|date|after_or_equal:lease_start_date',
            'lease_tenure' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'lease_terms' => 'nullable|string|max:1000',
            'lease_rate' => 'required|numeric|min:0',
            'increments' => 'nullable|numeric|min:0',
            'payment_cycle' => 'required|string|max:100',
            'penalty' => 'nullable|string|max:100',
            'discount' => 'nullable|string|max:100',
            'contract_status' => 'required|string|max:50',
            'status' => 'required|string|max:50',
            'payment_date' => 'nullable|date',
        ]);

        $tenant = Tenant::where('id', $request->tenant_id)->where('parent_id', parentId())->first();
        if (!$tenant) {
            return redirect()->back()->with('error', __('Selected tenant is invalid.'));
        }

        $contract = new Contract();
        $contract->user_id = parentId();
        $contract->tenant_id = $request->tenant_id;
        $contract->lease_start_date = $request->lease_start_date;
        $contract->lease_end_date = $request->lease_end_date;
        $contract->lease_tenure = $request->lease_tenure;
        $contract->amount = $request->amount;
        $contract->amount_paid = $request->amount_paid ?? 0;
        $contract->amount_remained = max(0, (float) $request->amount - (float) ($request->amount_paid ?? 0));
        $contract->lease_terms = $request->lease_terms;
        $contract->lease_rate = $request->lease_rate;
        $contract->increments = $request->increments;
        $contract->payment_cycle = $request->payment_cycle;
        $contract->penalty = $request->penalty;
        $contract->discount = $request->discount;
        $contract->contract_status = $request->contract_status;
        $contract->status = $request->status;
        $contract->payment_date = $request->payment_date;
        $contract->save();

        return redirect()->route('contract.index')->with('success', __('Contract successfully created.'));
    }

    public function show(Contract $contract)
    {
        if (\Auth::user()->can('show contract')) {
            if ((int) $contract->user_id !== (int) parentId()) {
                return redirect()->back()->with('error', __('Permission Denied!'));
            }
            return view('contract.show', compact('contract'));
        }
        return redirect()->back()->with('error', __('Permission Denied!'));
    }

    public function edit(Contract $contract)
    {
        if (\Auth::user()->can('edit contract')) {
            if ((int) $contract->user_id !== (int) parentId()) {
                return redirect()->back()->with('error', __('Permission Denied!'));
            }
            $tenants = Tenant::where('parent_id', parentId())->get();
            return view('contract.edit', compact('contract', 'tenants'));
        }
        return redirect()->back()->with('error', __('Permission Denied!'));
    }

    public function update(Request $request, Contract $contract)
    {
        if (!\Auth::user()->can('edit contract')) {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
        if ((int) $contract->user_id !== (int) parentId()) {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }

        $request->validate([
            'tenant_id' => 'required|integer|exists:tenants,id',
            'lease_start_date' => 'required|date',
            'lease_end_date' => 'required|date|after_or_equal:lease_start_date',
            'lease_tenure' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'lease_terms' => 'nullable|string|max:1000',
            'lease_rate' => 'required|numeric|min:0',
            'increments' => 'nullable|numeric|min:0',
            'payment_cycle' => 'required|string|max:100',
            'penalty' => 'nullable|string|max:100',
            'discount' => 'nullable|string|max:100',
            'contract_status' => 'required|string|max:50',
            'status' => 'required|string|max:50',
            'payment_date' => 'nullable|date',
        ]);

        $tenant = Tenant::where('id', $request->tenant_id)->where('parent_id', parentId())->first();
        if (!$tenant) {
            return redirect()->back()->with('error', __('Selected tenant is invalid.'));
        }

        $contract->tenant_id = $request->tenant_id;
        $contract->lease_start_date = $request->lease_start_date;
        $contract->lease_end_date = $request->lease_end_date;
        $contract->lease_tenure = $request->lease_tenure;
        $contract->amount = $request->amount;
        $contract->amount_paid = $request->amount_paid ?? 0;
        $contract->amount_remained = max(0, (float) $request->amount - (float) ($request->amount_paid ?? 0));
        $contract->lease_terms = $request->lease_terms;
        $contract->lease_rate = $request->lease_rate;
        $contract->increments = $request->increments;
        $contract->payment_cycle = $request->payment_cycle;
        $contract->penalty = $request->penalty;
        $contract->discount = $request->discount;
        $contract->contract_status = $request->contract_status;
        $contract->status = $request->status;
        $contract->payment_date = $request->payment_date;
        $contract->save();

        return redirect()->route('contract.index')->with('success', __('Contract successfully updated.'));
    }

    public function destroy(Contract $contract)
    {
        if (\Auth::user()->can('delete contract')) {
            if ((int) $contract->user_id !== (int) parentId()) {
                return redirect()->back()->with('error', __('Permission Denied!'));
            }
            $contract->delete();
            return redirect()->route('contract.index')->with('success', __('Contract successfully deleted.'));
        }
        return redirect()->back()->with('error', __('Permission Denied!'));
    }
}
