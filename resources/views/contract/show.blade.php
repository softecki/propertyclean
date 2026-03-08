@extends('layouts.app')
@section('page-title')
    {{__('Contract Details')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a></li>
        <li class="breadcrumb-item"><a href="{{route('contract.index')}}">{{__('Contracts')}}</a></li>
        <li class="breadcrumb-item active"><a href="#">{{__('Details')}}</a></li>
    </ul>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4"><strong>{{__('Tenant ID')}}:</strong> {{$contract->tenant_id}}</div>
                <div class="col-md-4"><strong>{{__('Lease Tenure')}}:</strong> {{$contract->lease_tenure}}</div>
                <div class="col-md-4"><strong>{{__('Lease Rate')}}:</strong> {{$contract->lease_rate}}</div>
                <div class="col-md-4"><strong>{{__('Amount')}}:</strong> {{$contract->amount}}</div>
                <div class="col-md-4"><strong>{{__('Amount Paid')}}:</strong> {{$contract->amount_paid}}</div>
                <div class="col-md-4"><strong>{{__('Amount Remaining')}}:</strong> {{$contract->amount_remained}}</div>
                <div class="col-md-4"><strong>{{__('Start Date')}}:</strong> {{$contract->lease_start_date}}</div>
                <div class="col-md-4"><strong>{{__('End Date')}}:</strong> {{$contract->lease_end_date}}</div>
                <div class="col-md-4"><strong>{{__('Payment Cycle')}}:</strong> {{$contract->payment_cycle}}</div>
                <div class="col-md-4"><strong>{{__('Penalty')}}:</strong> {{$contract->penalty}}</div>
                <div class="col-md-4"><strong>{{__('Discount')}}:</strong> {{$contract->discount}}</div>
                <div class="col-md-4"><strong>{{__('Contract Status')}}:</strong> {{$contract->contract_status}}</div>
                <div class="col-md-4"><strong>{{__('Lifecycle Status')}}:</strong> {{$contract->status}}</div>
                <div class="col-12 mt-2"><strong>{{__('Lease Terms')}}:</strong><div>{{$contract->lease_terms}}</div></div>
            </div>
        </div>
    </div>
@endsection
