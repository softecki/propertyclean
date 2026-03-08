@extends('layouts.app')
@section('page-title')
    {{__('Sales & Collections')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{route('phase.dashboard')}}"><h1>{{__('Phase Dashboard')}}</h1></a></li>
        <li class="breadcrumb-item active"><a href="#">{{__('Sales & Collections')}}</a></li>
    </ul>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card"><div class="card-header"><h5>{{__('Create Sale')}}</h5></div><div class="card-body">
                <form method="post" action="{{route('phase.sales.store')}}">
                    @csrf
                    <div class="row">
                        <div class="col-md-2">
                            <select class="form-control mb-2" name="project_id">
                                <option value="">{{__('Project')}}</option>
                                @foreach($projects as $project)<option value="{{$project->id}}">{{$project->name}}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control mb-2" name="block_id">
                                <option value="">{{__('Block')}}</option>
                                @foreach($blocks as $block)<option value="{{$block->id}}">{{$block->name}}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control mb-2" name="plot_id" required>
                                <option value="">{{__('Plot')}}</option>
                                @foreach($plots as $plot)<option value="{{$plot->id}}">{{$plot->plot_number}}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control mb-2" name="customer_id" required>
                                <option value="">{{__('Customer')}}</option>
                                @foreach($customers as $customer)<option value="{{$customer->id}}">{{$customer->name}}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control mb-2" name="seller_id">
                                <option value="">{{__('Seller')}}</option>
                                @foreach($sellers as $seller)<option value="{{$seller->id}}">{{$seller->name}}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control mb-2" name="agent_id">
                                <option value="">{{__('Agent')}}</option>
                                @foreach($agents as $agent)<option value="{{$agent->id}}">{{$agent->name}}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-md-2"><input class="form-control mb-2" type="number" step="0.01" name="sale_price" placeholder="{{__('Sale Price')}}" required></div>
                        <div class="col-md-2"><input class="form-control mb-2" type="number" step="0.01" name="discount" placeholder="{{__('Discount')}}"></div>
                        <div class="col-md-2">
                            <select class="form-control mb-2" name="commission_rule_type">
                                <option value="">{{__('Commission Rule')}}</option>
                                <option value="percentage">{{__('Percentage')}}</option>
                                <option value="fixed">{{__('Fixed')}}</option>
                            </select>
                        </div>
                        <div class="col-md-2"><input class="form-control mb-2" type="number" step="0.0001" name="commission_rule_value" placeholder="{{__('Commission Value')}}"></div>
                        <div class="col-md-2">
                            <select class="form-control mb-2" name="commission_trigger">
                                <option value="sale_confirmed">{{__('Sale Confirmed')}}</option>
                                <option value="full_payment">{{__('Full Payment')}}</option>
                                <option value="collected_amount">{{__('Collected Amount')}}</option>
                            </select>
                        </div>
                        <div class="col-md-2"><input class="form-control mb-2" name="currency_code" value="TZS" placeholder="{{__('Currency')}}"></div>
                    </div>
                    <button class="btn btn-primary btn-sm">{{__('Create Sale')}}</button>
                </form>
            </div></div>
        </div>
        <div class="col-12">
            <div class="card"><div class="card-header"><h5>{{__('Sales Ledger')}}</h5></div><div class="card-body table-responsive">
                <table class="table">
                    <thead>
                    <tr><th>{{__('Agreement')}}</th><th>{{__('Plot')}}</th><th>{{__('Customer')}}</th><th>{{__('Agent')}}</th><th>{{__('Contract')}}</th><th>{{__('Paid')}}</th><th>{{__('Outstanding')}}</th><th>{{__('Status')}}</th><th>{{__('Actions')}}</th></tr>
                    </thead>
                    <tbody>
                    @foreach($sales as $sale)
                        <tr>
                            <td>{{$sale->agreement_no}}</td>
                            <td>{{$sale->plot_number}}</td>
                            <td>{{$sale->customer_name}}</td>
                            <td>{{$sale->agent_name}}</td>
                            <td>{{$sale->total_contract_value}}</td>
                            <td>{{$sale->total_paid}}</td>
                            <td>{{$sale->outstanding_balance}}</td>
                            <td>{{$sale->status}}</td>
                            <td>
                                <form class="mb-1" method="post" action="{{route('phase.sales.status.update',$sale->id)}}">@csrf
                                    <div class="d-flex">
                                        <select class="form-control form-control-sm" name="status">
                                            <option value="draft">{{__('Draft')}}</option>
                                            <option value="confirmed">{{__('Confirmed')}}</option>
                                            <option value="completed">{{__('Completed')}}</option>
                                            <option value="cancelled">{{__('Cancelled')}}</option>
                                        </select>
                                        <button class="btn btn-sm btn-primary ms-1">{{__('Set')}}</button>
                                    </div>
                                </form>
                                <form class="mb-1" method="post" action="{{route('phase.sales.charges.store',$sale->id)}}">@csrf
                                    <div class="d-flex">
                                        <input class="form-control form-control-sm" name="charge_type" placeholder="{{__('Charge Type')}}" required>
                                        <input class="form-control form-control-sm ms-1" name="amount" type="number" step="0.01" placeholder="{{__('Amount')}}" required>
                                        <button class="btn btn-sm btn-warning ms-1">{{__('Add')}}</button>
                                    </div>
                                </form>
                                <form method="post" action="{{route('phase.sales.payments.store',$sale->id)}}">@csrf
                                    <div class="d-flex">
                                        <input class="form-control form-control-sm" name="amount" type="number" step="0.01" placeholder="{{__('Payment')}}" required>
                                        <input class="form-control form-control-sm ms-1" name="payment_method" placeholder="{{__('Method')}}">
                                        <button class="btn btn-sm btn-success ms-1">{{__('Pay')}}</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div></div>
        </div>
    </div>
@endsection
