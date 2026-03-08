@extends('layouts.app')
@section('page-title')
    {{__('Phase Dashboard')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item active"><a href="#">{{__('Phase Dashboard')}}</a></li>
    </ul>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <a href="{{route('phase.land')}}">
                <div class="card">
                    <div class="card-body">
                        <h5>{{__('Land Master')}}</h5>
                        <p class="mb-0">{{__('Branches, projects, blocks, plots')}}</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="{{route('phase.parties')}}">
                <div class="card">
                    <div class="card-body">
                        <h5>{{__('Parties')}}</h5>
                        <p class="mb-0">{{__('Customers, sellers, agents')}}</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="{{route('phase.sales')}}">
                <div class="card">
                    <div class="card-body">
                        <h5>{{__('Sales & Collections')}}</h5>
                        <p class="mb-0">{{__('Sales, payments, overpayment, status')}}</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="{{route('phase.finance')}}">
                <div class="card">
                    <div class="card-body">
                        <h5>{{__('Finance & Accrual')}}</h5>
                        <p class="mb-0">{{__('Receivables, commissions, control no, FX')}}</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="{{route('phase.operations')}}">
                <div class="card">
                    <div class="card-body">
                        <h5>{{__('Operations & Assets')}}</h5>
                        <p class="mb-0">{{__('Utility bills, maintenance, depreciation')}}</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="{{route('phase.communications')}}">
                <div class="card">
                    <div class="card-body">
                        <h5>{{__('Portal & Communications')}}</h5>
                        <p class="mb-0">{{__('Listings, feedback, email inbox, reminders')}}</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="{{route('phase.reports')}}">
                <div class="card">
                    <div class="card-body">
                        <h5>{{__('Phase Reports')}}</h5>
                        <p class="mb-0">{{__('Summary and valuation')}}</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection
