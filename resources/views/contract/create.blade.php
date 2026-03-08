@extends('layouts.app')
@section('page-title')
    {{__('Create Contract')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{route('contract.index')}}">{{__('Contracts')}}</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{__('Create')}}</a>
        </li>
    </ul>
@endsection
@section('content')
    {{Form::open(['url'=>'contract','method'=>'post'])}}
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                {{Form::label('tenant_id',__('Tenant'),['class'=>'form-label'])}}
                <select class="form-control" name="tenant_id" required>
                    <option value="">{{__('Select Tenant')}}</option>
                    @foreach($tenants as $tenant)
                        <option value="{{$tenant->id}}">{{__('Tenant #')}}{{$tenant->id}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{Form::label('lease_start_date',__('Lease Start Date'),['class'=>'form-label'])}}
                {{Form::date('lease_start_date',null,['class'=>'form-control','required'])}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{Form::label('lease_end_date',__('Lease End Date'),['class'=>'form-label'])}}
                {{Form::date('lease_end_date',null,['class'=>'form-control','required'])}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{Form::label('lease_tenure',__('Lease Tenure'),['class'=>'form-label'])}}
                {{Form::text('lease_tenure',null,['class'=>'form-control','required'])}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{Form::label('amount',__('Contract Amount'),['class'=>'form-label'])}}
                {{Form::number('amount',null,['class'=>'form-control','step'=>'0.01','required'])}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{Form::label('amount_paid',__('Amount Paid'),['class'=>'form-label'])}}
                {{Form::number('amount_paid',0,['class'=>'form-control','step'=>'0.01'])}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{Form::label('lease_rate',__('Lease Rate'),['class'=>'form-label'])}}
                {{Form::number('lease_rate',null,['class'=>'form-control','step'=>'0.01','required'])}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{Form::label('increments',__('Annual Increment (%)'),['class'=>'form-label'])}}
                {{Form::number('increments',null,['class'=>'form-control','step'=>'0.01'])}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{Form::label('payment_cycle',__('Payment Cycle'),['class'=>'form-label'])}}
                {{Form::text('payment_cycle',null,['class'=>'form-control','required'])}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{Form::label('penalty',__('Penalty'),['class'=>'form-label'])}}
                {{Form::text('penalty',null,['class'=>'form-control'])}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{Form::label('discount',__('Discount'),['class'=>'form-label'])}}
                {{Form::text('discount',null,['class'=>'form-control'])}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{Form::label('contract_status',__('Contract Status'),['class'=>'form-label'])}}
                <select class="form-control" name="contract_status" required>
                    <option value="draft">{{__('Draft')}}</option>
                    <option value="approved">{{__('Approved')}}</option>
                    <option value="active">{{__('Active')}}</option>
                    <option value="expired">{{__('Expired')}}</option>
                    <option value="renewed">{{__('Renewed')}}</option>
                    <option value="cancelled">{{__('Cancelled')}}</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{Form::label('status',__('Lifecycle Status'),['class'=>'form-label'])}}
                <select class="form-control" name="status" required>
                    <option value="open">{{__('Open')}}</option>
                    <option value="closed">{{__('Closed')}}</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{Form::label('payment_date',__('Payment Date'),['class'=>'form-label'])}}
                {{Form::date('payment_date',null,['class'=>'form-control'])}}
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{Form::label('lease_terms',__('Lease Terms'),['class'=>'form-label'])}}
                {{Form::textarea('lease_terms',null,['class'=>'form-control','rows'=>3])}}
            </div>
        </div>
        <div class="col-12 text-end">
            {{Form::submit(__('Create Contract'),['class'=>'btn btn-primary'])}}
        </div>
    </div>
    {{Form::close()}}
@endsection

