<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContractRequest;
use App\Http\Requests\UpdateContractRequest;
use App\Models\Contract;
use App\Models\Property;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\TenantDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (\Auth::user()->can('manage tenant')) {
            $contracts = Contract::all();
            return view('contract.index', compact('contracts'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        if (\Auth::user()->can('create tenant')) {
            $property = Property::where('parent_id',parentId())->get()->pluck('name', 'id');
            $property->prepend(__('Select Property'), 0);
            return view('contract.create', compact('property'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreContractRequest  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {

            $validator = \Validator::make(
                $request->all(), [
                'tenant_name' => 'required',
                'tenure' => 'required',
                'amount' => 'required',
                'lease_rate' => 'required',
                'increments' => 'required',
                'payment_cycle' => 'required',
                'penalty' => 'required',
                'discount' => 'required',
                'contract_status' => 'required',
            ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return response()->json([
                    'status' => 'error',
                    'msg' => $messages->first(),
                ]);

            }
            $ids = parentId();
            $authUser = \App\Models\User::find($ids);


            $userRole = Role::where('parent_id',parentId())->where('name','tenant')->first();

            $contract=new Contract();
            $contract->tenant_id = "1";
            $contract->lease_tenure = $request->tenure;
            $contract->amount = $request->amount;
            $contract->lease_rate = $request->lease_rate;
            $contract->increments = $request->increments;
            $contract->payment_cycle = $request->payment_cycle;
            $contract->penalty = $request->penalty;
            $contract->discount = $request->discount;
            $contract->contract_status = $request->contract_status;
            $contract->user_id =parentId();
            $contract->save();


            return response()->json([
                'status' => 'success',
                'msg' => __('Contract successfully created.'),

            ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function show(Contract $contract)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function edit(Contract $contract)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateContractRequest  $request
     * @param  \App\Models\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateContractRequest $request, Contract $contract)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contract $contract)
    {
        //
    }
}
