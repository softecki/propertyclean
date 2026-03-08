@extends('layouts.app')
@section('page-title')
    {{__('Edit Contract')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a></li>
        <li class="breadcrumb-item"><a href="{{route('contract.index')}}">{{__('Contracts')}}</a></li>
        <li class="breadcrumb-item active"><a href="#">{{__('Edit')}}</a></li>
    </ul>
@endsection
@section('content')
    {{Form::model($contract,['route'=>['contract.update',$contract->id],'method'=>'PUT'])}}
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                {{Form::label('tenant_id',__('Tenant'),['class'=>'form-label'])}}
                <select class="form-control" name="tenant_id" required>
                    @foreach($tenants as $tenant)
                        <option value="{{$tenant->id}}" {{$contract->tenant_id==$tenant->id?'selected':''}}>{{__('Tenant #')}}{{$tenant->id}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">{{Form::label('lease_start_date',__('Lease Start Date'),['class'=>'form-label'])}}{{Form::date('lease_start_date',$contract->lease_start_date,['class'=>'form-control','required'])}}</div>
        <div class="col-md-4">{{Form::label('lease_end_date',__('Lease End Date'),['class'=>'form-label'])}}{{Form::date('lease_end_date',$contract->lease_end_date,['class'=>'form-control','required'])}}</div>
        <div class="col-md-4">{{Form::label('lease_tenure',__('Lease Tenure'),['class'=>'form-label'])}}{{Form::text('lease_tenure',$contract->lease_tenure,['class'=>'form-control','required'])}}</div>
        <div class="col-md-4">{{Form::label('amount',__('Amount'),['class'=>'form-label'])}}{{Form::number('amount',$contract->amount,['class'=>'form-control','step'=>'0.01','required'])}}</div>
        <div class="col-md-4">{{Form::label('amount_paid',__('Amount Paid'),['class'=>'form-label'])}}{{Form::number('amount_paid',$contract->amount_paid,['class'=>'form-control','step'=>'0.01'])}}</div>
        <div class="col-md-4">{{Form::label('lease_rate',__('Lease Rate'),['class'=>'form-label'])}}{{Form::number('lease_rate',$contract->lease_rate,['class'=>'form-control','step'=>'0.01','required'])}}</div>
        <div class="col-md-4">{{Form::label('increments',__('Increments'),['class'=>'form-label'])}}{{Form::number('increments',$contract->increments,['class'=>'form-control','step'=>'0.01'])}}</div>
        <div class="col-md-4">{{Form::label('payment_cycle',__('Payment Cycle'),['class'=>'form-label'])}}{{Form::text('payment_cycle',$contract->payment_cycle,['class'=>'form-control','required'])}}</div>
        <div class="col-md-4">{{Form::label('penalty',__('Penalty'),['class'=>'form-label'])}}{{Form::text('penalty',$contract->penalty,['class'=>'form-control'])}}</div>
        <div class="col-md-4">{{Form::label('discount',__('Discount'),['class'=>'form-label'])}}{{Form::text('discount',$contract->discount,['class'=>'form-control'])}}</div>
        <div class="col-md-4">
            {{Form::label('contract_status',__('Contract Status'),['class'=>'form-label'])}}
            <input class="form-control" name="contract_status" value="{{$contract->contract_status}}" required>
        </div>
        <div class="col-md-4">
            {{Form::label('status',__('Lifecycle Status'),['class'=>'form-label'])}}
            <input class="form-control" name="status" value="{{$contract->status}}" required>
        </div>
        <div class="col-md-4">{{Form::label('payment_date',__('Payment Date'),['class'=>'form-label'])}}{{Form::date('payment_date',$contract->payment_date,['class'=>'form-control'])}}</div>
        <div class="col-12">{{Form::label('lease_terms',__('Lease Terms'),['class'=>'form-label'])}}{{Form::textarea('lease_terms',$contract->lease_terms,['class'=>'form-control','rows'=>3])}}</div>
        <div class="col-12 text-end mt-2">{{Form::submit(__('Update Contract'),['class'=>'btn btn-primary'])}}</div>
    </div>
    {{Form::close()}}
@endsection
