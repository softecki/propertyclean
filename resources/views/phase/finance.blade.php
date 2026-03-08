@extends('layouts.app')
@section('page-title')
    {{__('Finance & Accrual')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{route('phase.dashboard')}}"><h1>{{__('Phase Dashboard')}}</h1></a></li>
        <li class="breadcrumb-item active"><a href="#">{{__('Finance & Accrual')}}</a></li>
    </ul>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card"><div class="card-header"><h5>{{__('Generate Control Number')}}</h5></div><div class="card-body">
                <form method="post" action="{{route('phase.control-numbers.store')}}">@csrf
                    <input class="form-control mb-2" name="reference_type" placeholder="{{__('Reference Type (sale/invoice)')}}" required>
                    <input class="form-control mb-2" name="reference_id" type="number" placeholder="{{__('Reference ID')}}" required>
                    <input class="form-control mb-2" name="amount" type="number" step="0.01" placeholder="{{__('Amount')}}" required>
                    <input class="form-control mb-2" name="currency_code" value="TZS" required>
                    <input class="form-control mb-2" name="bank_name" placeholder="{{__('Bank Name')}}">
                    <button class="btn btn-primary btn-sm">{{__('Generate')}}</button>
                </form>
            </div></div>
        </div>
        <div class="col-lg-6">
            <div class="card"><div class="card-header"><h5>{{__('Add Currency Rate')}}</h5></div><div class="card-body">
                <form method="post" action="{{route('phase.currency-rates.store')}}">@csrf
                    <div class="row">
                        <div class="col-3"><input class="form-control mb-2" name="base_currency" value="TZS" required></div>
                        <div class="col-3"><input class="form-control mb-2" name="quote_currency" placeholder="{{__('USD')}}" required></div>
                        <div class="col-3"><input class="form-control mb-2" type="number" step="0.000001" name="rate" required></div>
                        <div class="col-3"><input class="form-control mb-2" type="date" name="effective_date" required></div>
                    </div>
                    <button class="btn btn-primary btn-sm">{{__('Save Rate')}}</button>
                </form>
            </div></div>
        </div>

        <div class="col-12"><div class="card"><div class="card-header"><h5>{{__('Receivables')}}</h5></div><div class="card-body table-responsive">
            <table class="table"><thead><tr><th>{{__('Customer')}}</th><th>{{__('Expected')}}</th><th>{{__('Received')}}</th><th>{{__('Balance')}}</th><th>{{__('Status')}}</th></tr></thead><tbody>
            @foreach($receivables as $r)<tr><td>{{$r->customer_name}}</td><td>{{$r->expected_amount}}</td><td>{{$r->received_amount}}</td><td>{{$r->balance}}</td><td>{{$r->status}}</td></tr>@endforeach
            </tbody></table>
        </div></div></div>

        <div class="col-12"><div class="card"><div class="card-header"><h5>{{__('Commissions')}}</h5></div><div class="card-body table-responsive">
            <table class="table"><thead><tr><th>{{__('Agreement')}}</th><th>{{__('Agent')}}</th><th>{{__('Commission')}}</th><th>{{__('Paid')}}</th><th>{{__('Status')}}</th><th>{{__('Pay')}}</th></tr></thead><tbody>
            @foreach($commissions as $c)
                <tr>
                    <td>{{$c->agreement_no}}</td><td>{{$c->agent_name}}</td><td>{{$c->commission_amount}}</td><td>{{$c->paid_amount}}</td><td>{{$c->status}}</td>
                    <td>
                        <form method="post" action="{{route('phase.commissions.pay',$c->id)}}">@csrf
                            <div class="d-flex">
                                <input class="form-control form-control-sm" type="number" step="0.01" name="amount" placeholder="{{__('Amount')}}" required>
                                <input class="form-control form-control-sm ms-1" type="date" name="payment_date" required>
                                <button class="btn btn-sm btn-success ms-1">{{__('Pay')}}</button>
                            </div>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody></table>
        </div></div></div>

        <div class="col-md-6"><div class="card"><div class="card-header"><h5>{{__('Customer Credits (Excess Payments)')}}</h5></div><div class="card-body table-responsive">
            <table class="table"><thead><tr><th>{{__('Customer')}}</th><th>{{__('Amount')}}</th><th>{{__('Balance')}}</th><th>{{__('Action')}}</th><th>{{__('Status')}}</th></tr></thead><tbody>
            @foreach($credits as $cc)<tr><td>{{$cc->customer_name}}</td><td>{{$cc->amount}}</td><td>{{$cc->balance}}</td><td>{{$cc->action}}</td><td>{{$cc->status}}</td></tr>@endforeach
            </tbody></table>
        </div></div></div>

        <div class="col-md-6"><div class="card"><div class="card-header"><h5>{{__('Control Numbers')}}</h5></div><div class="card-body table-responsive">
            <table class="table"><thead><tr><th>{{__('Control No')}}</th><th>{{__('Reference')}}</th><th>{{__('Amount')}}</th><th>{{__('Status')}}</th><th>{{__('Actions')}}</th></tr></thead><tbody>
            @foreach($controlNumbers as $cn)
                <tr>
                    <td>{{$cn->control_number}}</td><td>{{$cn->reference_type}} #{{$cn->reference_id}}</td><td>{{$cn->amount}} {{$cn->currency_code}}</td><td>{{$cn->status}}</td>
                    <td>
                        <form method="post" action="{{route('phase.control-numbers.destroy',$cn->id)}}">@csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">{{__('Delete')}}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody></table>
        </div></div></div>

        <div class="col-12"><div class="card"><div class="card-header"><h5>{{__('Currency Rates')}}</h5></div><div class="card-body table-responsive">
            <table class="table"><thead><tr><th>{{__('Base')}}</th><th>{{__('Quote')}}</th><th>{{__('Rate')}}</th><th>{{__('Effective Date')}}</th><th>{{__('Actions')}}</th></tr></thead><tbody>
            @foreach($currencyRates as $rate)
                <tr>
                    <td>{{$rate->base_currency}}</td><td>{{$rate->quote_currency}}</td><td>{{$rate->rate}}</td><td>{{$rate->effective_date}}</td>
                    <td>
                        <form method="post" action="{{route('phase.currency-rates.update',$rate->id)}}" class="d-inline">@csrf @method('PUT')
                            <input class="form-control form-control-sm mb-1" name="base_currency" value="{{$rate->base_currency}}" required>
                            <input class="form-control form-control-sm mb-1" name="quote_currency" value="{{$rate->quote_currency}}" required>
                            <input class="form-control form-control-sm mb-1" type="number" step="0.000001" name="rate" value="{{$rate->rate}}" required>
                            <input class="form-control form-control-sm mb-1" type="date" name="effective_date" value="{{$rate->effective_date}}" required>
                            <button class="btn btn-sm btn-primary">{{__('Update')}}</button>
                        </form>
                        <form method="post" action="{{route('phase.currency-rates.destroy',$rate->id)}}" class="d-inline">@csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">{{__('Delete')}}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody></table>
        </div></div></div>
    </div>
@endsection
