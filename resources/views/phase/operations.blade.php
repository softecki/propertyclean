@extends('layouts.app')
@section('page-title')
    {{__('Operations & Assets')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{route('phase.dashboard')}}"><h1>{{__('Phase Dashboard')}}</h1></a></li>
        <li class="breadcrumb-item active"><a href="#">{{__('Operations & Assets')}}</a></li>
    </ul>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-4">
            <div class="card"><div class="card-header"><h5>{{__('Create Utility Bill')}}</h5></div><div class="card-body">
                <form method="post" action="{{route('phase.utility-bills.store')}}">@csrf
                    <select class="form-control mb-2" name="property_id"><option value="">{{__('Property')}}</option>@foreach($properties as $p)<option value="{{$p->id}}">{{$p->name}}</option>@endforeach</select>
                    <select class="form-control mb-2" name="property_unit_id"><option value="">{{__('Unit')}}</option>@foreach($units as $u)<option value="{{$u->id}}">{{$u->name}}</option>@endforeach</select>
                    <input class="form-control mb-2" name="bill_type" placeholder="{{__('Bill Type (TANESCO/EWURA/etc)')}}" required>
                    <input class="form-control mb-2" name="provider" placeholder="{{__('Provider')}}">
                    <input class="form-control mb-2" name="period" placeholder="{{__('Period')}}">
                    <input class="form-control mb-2" type="number" step="0.01" name="amount" placeholder="{{__('Amount')}}" required>
                    <input class="form-control mb-2" name="currency_code" value="TZS" required>
                    <input class="form-control mb-2" type="date" name="due_date">
                    <button class="btn btn-primary btn-sm">{{__('Save Bill')}}</button>
                </form>
            </div></div>
        </div>
        <div class="col-lg-4">
            <div class="card"><div class="card-header"><h5>{{__('Create Maintenance Schedule')}}</h5></div><div class="card-body">
                <form method="post" action="{{route('phase.maintenance-schedules.store')}}">@csrf
                    <select class="form-control mb-2" name="property_id" required><option value="">{{__('Property')}}</option>@foreach($properties as $p)<option value="{{$p->id}}">{{$p->name}}</option>@endforeach</select>
                    <select class="form-control mb-2" name="property_unit_id"><option value="">{{__('Unit')}}</option>@foreach($units as $u)<option value="{{$u->id}}">{{$u->name}}</option>@endforeach</select>
                    <input class="form-control mb-2" name="service_type" placeholder="{{__('Service Type')}}" required>
                    <input class="form-control mb-2" type="date" name="last_maintenance_date">
                    <input class="form-control mb-2" type="date" name="next_maintenance_date" required>
                    <input class="form-control mb-2" type="number" step="0.01" name="cost_estimate" placeholder="{{__('Estimated Cost')}}">
                    <input class="form-control mb-2" name="currency_code" value="TZS">
                    <button class="btn btn-primary btn-sm">{{__('Save Schedule')}}</button>
                </form>
            </div></div>
        </div>
        <div class="col-lg-4">
            <div class="card"><div class="card-header"><h5>{{__('Create Asset')}}</h5></div><div class="card-body">
                <form method="post" action="{{route('phase.assets.store')}}">@csrf
                    <input class="form-control mb-2" name="name" placeholder="{{__('Asset Name')}}" required>
                    <input class="form-control mb-2" name="category" placeholder="{{__('Category')}}">
                    <select class="form-control mb-2" name="branch_id"><option value="">{{__('Branch')}}</option>@foreach($branches as $b)<option value="{{$b->id}}">{{$b->name}}</option>@endforeach</select>
                    <input class="form-control mb-2" type="date" name="acquisition_date" required>
                    <input class="form-control mb-2" type="number" step="0.01" name="cost" placeholder="{{__('Cost')}}" required>
                    <input class="form-control mb-2" type="number" step="0.01" name="salvage_value" placeholder="{{__('Salvage Value')}}">
                    <input class="form-control mb-2" type="number" name="useful_life_years" placeholder="{{__('Useful Life Years')}}" required>
                    <button class="btn btn-primary btn-sm">{{__('Save Asset')}}</button>
                </form>
            </div></div>
        </div>

        <div class="col-12"><div class="card"><div class="card-header"><h5>{{__('Utility Bills')}}</h5></div><div class="card-body table-responsive">
            <table class="table"><thead><tr><th>{{__('Type')}}</th><th>{{__('Amount')}}</th><th>{{__('Due')}}</th><th>{{__('Status')}}</th><th>{{__('Action')}}</th></tr></thead><tbody>
            @foreach($utilityBills as $bill)
                <tr>
                    <td>{{$bill->bill_type}}</td><td>{{$bill->amount}} {{$bill->currency_code}}</td><td>{{$bill->due_date}}</td><td>{{$bill->status}}</td>
                    <td>
                        @if($bill->status !== 'paid')
                            <form method="post" action="{{route('phase.utility-bills.paid',$bill->id)}}">@csrf
                                <button class="btn btn-sm btn-success">{{__('Mark Paid')}}</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody></table>
        </div></div></div>

        <div class="col-md-6"><div class="card"><div class="card-header"><h5>{{__('Maintenance Schedule')}}</h5></div><div class="card-body table-responsive">
            <table class="table"><thead><tr><th>{{__('Service')}}</th><th>{{__('Last')}}</th><th>{{__('Next')}}</th><th>{{__('Status')}}</th></tr></thead><tbody>
            @foreach($maintenanceSchedules as $m)<tr><td>{{$m->service_type}}</td><td>{{$m->last_maintenance_date}}</td><td>{{$m->next_maintenance_date}}</td><td>{{$m->status}}</td></tr>@endforeach
            </tbody></table>
        </div></div></div>

        <div class="col-md-6"><div class="card"><div class="card-header"><h5>{{__('Asset Register')}}</h5></div><div class="card-body table-responsive">
            <table class="table"><thead><tr><th>{{__('Name')}}</th><th>{{__('Cost')}}</th><th>{{__('Book Value')}}</th><th>{{__('Depreciation')}}</th></tr></thead><tbody>
            @foreach($assets as $asset)
                <tr>
                    <td>{{$asset->name}}</td><td>{{$asset->cost}}</td><td>{{$asset->book_value}}</td>
                    <td>
                        <form method="post" action="{{route('phase.assets.depreciate',$asset->id)}}">@csrf
                            <div class="d-flex">
                                <input class="form-control form-control-sm" type="date" name="period_start" required>
                                <input class="form-control form-control-sm ms-1" type="date" name="period_end" required>
                                <button class="btn btn-sm btn-warning ms-1">{{__('Post')}}</button>
                            </div>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody></table>
        </div></div></div>
    </div>
@endsection
