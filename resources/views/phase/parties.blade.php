@extends('layouts.app')
@section('page-title')
    {{__('Parties')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{route('phase.dashboard')}}"><h1>{{__('Phase Dashboard')}}</h1></a></li>
        <li class="breadcrumb-item active"><a href="#">{{__('Customers / Sellers / Agents')}}</a></li>
    </ul>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-4">
            <div class="card"><div class="card-header"><h5>{{__('Create Customer')}}</h5></div><div class="card-body">
                <form method="post" action="{{route('phase.customers.store')}}">@csrf
                    <select class="form-control mb-2" name="type" required>
                        <option value="buyer">{{__('Buyer')}}</option>
                        <option value="tenant">{{__('Tenant')}}</option>
                        <option value="investor">{{__('Investor')}}</option>
                    </select>
                    <input class="form-control mb-2" name="name" placeholder="{{__('Name')}}" required>
                    <input class="form-control mb-2" name="email" type="email" placeholder="{{__('Email')}}">
                    <input class="form-control mb-2" name="phone" placeholder="{{__('Phone')}}">
                    <input class="form-control mb-2" name="business_registration_number" placeholder="{{__('Business Registration Number')}}">
                    <input class="form-control mb-2" name="taxpayer_identification_number" placeholder="{{__('TIN')}}">
                    <button class="btn btn-primary btn-sm">{{__('Save Customer')}}</button>
                </form>
            </div></div>
        </div>
        <div class="col-lg-4">
            <div class="card"><div class="card-header"><h5>{{__('Create Seller')}}</h5></div><div class="card-body">
                <form method="post" action="{{route('phase.sellers.store')}}">@csrf
                    <input class="form-control mb-2" name="name" placeholder="{{__('Name')}}" required>
                    <input class="form-control mb-2" name="email" type="email" placeholder="{{__('Email')}}">
                    <input class="form-control mb-2" name="phone" placeholder="{{__('Phone')}}">
                    <input class="form-control mb-2" name="ownership_reference" placeholder="{{__('Ownership Reference')}}">
                    <textarea class="form-control mb-2" name="address" placeholder="{{__('Address')}}"></textarea>
                    <button class="btn btn-primary btn-sm">{{__('Save Seller')}}</button>
                </form>
            </div></div>
        </div>
        <div class="col-lg-4">
            <div class="card"><div class="card-header"><h5>{{__('Create Agent')}}</h5></div><div class="card-body">
                <form method="post" action="{{route('phase.agents.store')}}">@csrf
                    <input class="form-control mb-2" name="name" placeholder="{{__('Name')}}" required>
                    <input class="form-control mb-2" name="email" type="email" placeholder="{{__('Email')}}">
                    <input class="form-control mb-2" name="phone" placeholder="{{__('Phone')}}">
                    <select class="form-control mb-2" name="commission_type" required>
                        <option value="percentage">{{__('% of sale')}}</option>
                        <option value="fixed">{{__('Fixed amount')}}</option>
                    </select>
                    <input class="form-control mb-2" name="commission_value" type="number" step="0.0001" required>
                    <select class="form-control mb-2" name="commission_trigger" required>
                        <option value="sale_confirmed">{{__('On sale confirmed')}}</option>
                        <option value="full_payment">{{__('On full payment')}}</option>
                        <option value="collected_amount">{{__('On collected amount')}}</option>
                    </select>
                    <button class="btn btn-primary btn-sm">{{__('Save Agent')}}</button>
                </form>
            </div></div>
        </div>
        <div class="col-12">
            <div class="card"><div class="card-header"><h5>{{__('Customers')}}</h5></div><div class="card-body table-responsive">
                <table class="table"><thead><tr><th>{{__('Type')}}</th><th>{{__('Name')}}</th><th>{{__('Contact')}}</th><th>{{__('Credit')}}</th></tr></thead><tbody>
                @foreach($customers as $customer)<tr><td>{{$customer->type}}</td><td>{{$customer->name}}</td><td>{{$customer->phone}} / {{$customer->email}}</td><td>{{$customer->credit_balance}}</td></tr>@endforeach
                </tbody></table>
            </div></div>
        </div>
        <div class="col-md-6">
            <div class="card"><div class="card-header"><h5>{{__('Sellers')}}</h5></div><div class="card-body table-responsive">
                <table class="table"><thead><tr><th>{{__('Name')}}</th><th>{{__('Contact')}}</th></tr></thead><tbody>
                @foreach($sellers as $seller)<tr><td>{{$seller->name}}</td><td>{{$seller->phone}} / {{$seller->email}}</td></tr>@endforeach
                </tbody></table>
            </div></div>
        </div>
        <div class="col-md-6">
            <div class="card"><div class="card-header"><h5>{{__('Agents')}}</h5></div><div class="card-body table-responsive">
                <table class="table"><thead><tr><th>{{__('Name')}}</th><th>{{__('Rule')}}</th><th>{{__('Trigger')}}</th></tr></thead><tbody>
                @foreach($agents as $agent)<tr><td>{{$agent->name}}</td><td>{{$agent->commission_type}}: {{$agent->commission_value}}</td><td>{{$agent->commission_trigger}}</td></tr>@endforeach
                </tbody></table>
            </div></div>
        </div>
    </div>
@endsection
