@extends('layouts.app')
@section('page-title')
    {{__('Phase Reports')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{route('phase.dashboard')}}"><h1>{{__('Phase Dashboard')}}</h1></a></li>
        <li class="breadcrumb-item active"><a href="#">{{__('Phase Reports')}}</a></li>
    </ul>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-3 col-md-6"><div class="card"><div class="card-body"><h6>{{__('Occupancy Rate')}}</h6><h4>{{$summary['occupancy_rate_percent']}}%</h4></div></div></div>
        <div class="col-xl-3 col-md-6"><div class="card"><div class="card-body"><h6>{{__('Outstanding')}}</h6><h4>{{$summary['outstanding_balances']}}</h4></div></div></div>
        <div class="col-xl-3 col-md-6"><div class="card"><div class="card-body"><h6>{{__('Excess Payments')}}</h6><h4>{{$summary['excess_payments']}}</h4></div></div></div>
        <div class="col-xl-3 col-md-6"><div class="card"><div class="card-body"><h6>{{__('Deferred Revenue')}}</h6><h4>{{$summary['deferred_revenue']}}</h4></div></div></div>

        <div class="col-md-6"><div class="card"><div class="card-header"><h5>{{__('Summary KPIs')}}</h5></div><div class="card-body">
            <p>{{__('Available area (m2)')}}: <strong>{{$summary['available_area_m2']}}</strong></p>
            <p>{{__('Occupied area (m2)')}}: <strong>{{$summary['occupied_area_m2']}}</strong></p>
            <p>{{__('Average lease rate')}}: <strong>{{$summary['average_lease_rate']}}</strong></p>
            <p>{{__('Sales total')}}: <strong>{{$summary['sales_total']}}</strong></p>
            <p>{{__('Cash flow in')}}: <strong>{{$summary['cash_flow_in']}}</strong></p>
            <p>{{__('Pending commissions')}}: <strong>{{$summary['pending_commissions']}}</strong></p>
            <p>{{__('Paid commissions')}}: <strong>{{$summary['paid_commissions']}}</strong></p>
            <p>{{__('Profit estimate')}}: <strong>{{$summary['profit_estimate']}}</strong></p>
        </div></div></div>

        <div class="col-md-6"><div class="card"><div class="card-header"><h5>{{__('Currency Valuation (Base: TZS)')}}</h5></div><div class="card-body table-responsive">
            <table class="table"><thead><tr><th>{{__('Currency')}}</th><th>{{__('Outstanding')}}</th><th>{{__('Rate')}}</th><th>{{__('Base Amount')}}</th></tr></thead><tbody>
            @foreach($valuation as $row)<tr><td>{{$row['currency']}}</td><td>{{$row['outstanding']}}</td><td>{{$row['rate']}}</td><td>{{$row['base_amount']}}</td></tr>@endforeach
            </tbody></table>
        </div></div></div>

        <div class="col-12"><div class="card"><div class="card-header"><h5>{{__('Profit by Project')}}</h5></div><div class="card-body table-responsive">
            <table class="table"><thead><tr><th>{{__('Project')}}</th><th>{{__('Revenue')}}</th><th>{{__('Commission')}}</th><th>{{__('Gross Profit')}}</th></tr></thead><tbody>
            @foreach($projectProfit as $p)<tr><td>{{$p->project_name}}</td><td>{{$p->revenue}}</td><td>{{$p->commission}}</td><td>{{$p->gross_profit}}</td></tr>@endforeach
            </tbody></table>
        </div></div></div>
    </div>
@endsection
