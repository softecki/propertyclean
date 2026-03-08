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

        <div class="col-12"><div class="card"><div class="card-header"><h5>{{__('Rent Roll with Days Past Due')}}</h5></div><div class="card-body table-responsive">
            <table class="table"><thead><tr><th>{{__('Invoice')}}</th><th>{{__('Tenant')}}</th><th>{{__('Amount')}}</th><th>{{__('Status')}}</th><th>{{__('Due Date')}}</th><th>{{__('Days Past Due')}}</th></tr></thead><tbody>
            @foreach($rentRoll as $row)<tr><td>{{$row->invoice_id}}</td><td>{{$row->tenant_id}}</td><td>{{$row->amount}}</td><td>{{$row->status}}</td><td>{{$row->end_date}}</td><td>{{$row->days_past_due}}</td></tr>@endforeach
            </tbody></table>
        </div></div></div>

        <div class="col-md-6"><div class="card"><div class="card-header"><h5>{{__('Receivables Aging')}}</h5></div><div class="card-body">
            <p>{{__('Current')}}: <strong>{{$receivableAging['buckets']['current']}}</strong></p>
            <p>{{__('1-30 Days')}}: <strong>{{$receivableAging['buckets']['1_30']}}</strong></p>
            <p>{{__('31-60 Days')}}: <strong>{{$receivableAging['buckets']['31_60']}}</strong></p>
            <p>{{__('61-90 Days')}}: <strong>{{$receivableAging['buckets']['61_90']}}</strong></p>
            <p>{{__('90+ Days')}}: <strong>{{$receivableAging['buckets']['90_plus']}}</strong></p>
        </div></div></div>

        <div class="col-md-6"><div class="card"><div class="card-header"><h5>{{__('Excess Payment Statements')}}</h5></div><div class="card-body table-responsive">
            <table class="table"><thead><tr><th>{{__('Customer')}}</th><th>{{__('Agreement')}}</th><th>{{__('Amount')}}</th><th>{{__('Balance')}}</th><th>{{__('Action')}}</th><th>{{__('Status')}}</th></tr></thead><tbody>
            @foreach($excessStatements as $s)<tr><td>{{$s->customer_name}}</td><td>{{$s->agreement_no}}</td><td>{{$s->amount}}</td><td>{{$s->balance}}</td><td>{{$s->action}}</td><td>{{$s->status}}</td></tr>@endforeach
            </tbody></table>
        </div></div></div>

        <div class="col-12"><div class="card"><div class="card-header"><h5>{{__('Seller Settlement')}}</h5></div><div class="card-body table-responsive">
            <table class="table"><thead><tr><th>{{__('Seller')}}</th><th>{{__('Agreed')}}</th><th>{{__('Paid')}}</th><th>{{__('Remaining')}}</th><th>{{__('Linked Sales')}}</th><th>{{__('Linked Sales Value')}}</th></tr></thead><tbody>
            @foreach($sellerSettlement as $s)<tr><td>{{$s->name}}</td><td>{{$s->agreed_amount}}</td><td>{{$s->amount_paid}}</td><td>{{$s->amount_remaining}}</td><td>{{$s->linked_sales}}</td><td>{{$s->linked_sales_value}}</td></tr>@endforeach
            </tbody></table>
        </div></div></div>
    </div>
@endsection
