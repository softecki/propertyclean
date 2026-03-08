@extends('layouts.app')
@section('page-title')
    {{__('Invoice')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{__('Invoice')}}</a>
        </li>
    </ul>
@endsection
@section('card-action-btn')
    @can('create invoice')
        <a class="btn btn-primary btn-sm ml-20" href="{{ route('invoice.create') }}"> <i
                class="ti-plus mr-5"></i>{{__('Create Invoice')}}</a>
    @endcan
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="display dataTable cell-border datatbl-advance">
                        <thead>
                        <tr>
                            <th>{{__('Invoice')}}</th>
                            <th>{{__('Property')}}</th>
                            <th>{{__('Unit')}}</th>
                            <th>{{__('Invoice Month')}}</th>
                            <th>{{__('End Date')}}</th>
                            <th>{{__('Amount')}}</th>
                            <th>{{__('Status')}}</th>
                            @if(Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice'))
                                <th class="text-right">{{__('Action')}}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($invoices as $invoice)
                            <tr role="row">
                                <td>{{invoicePrefix().$invoice->invoice_id}} </td>
                                <td>{{!empty($invoice->properties)?$invoice->properties->name:'-'}} </td>
                                <td>{{!empty($invoice->units)?$invoice->units->name:'-'}}  </td>
                                <td>{{date('F Y',strtotime($invoice->invoice_month))}} </td>
                                <td>{{dateFormat($invoice->end_date)}} </td>
                                <td>{{priceFormat($invoice->getInvoiceSubTotalAmount())}}</td>
                                <td>
                                    @if($invoice->status=='open')
                                        <span
                                            class="badge badge-primary">{{\App\Models\Invoice::$status[$invoice->status]}}</span>
                                    @elseif($invoice->status=='paid')
                                        <span
                                            class="badge badge-success">{{\App\Models\Invoice::$status[$invoice->status]}}</span>
                                    @elseif($invoice->status=='partial_paid')
                                        <span
                                            class="badge badge-warning">{{\App\Models\Invoice::$status[$invoice->status]}}</span>
                                    @endif
                                </td>
                                @if(Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice'))
                                    <td class="text-right">
                                        <div class="cart-action">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['invoice.destroy', $invoice->id]]) !!}
                                            @can('show invoice')
                                                <a class="text-warning" href="{{ route('invoice.show',$invoice->id) }}"
                                                   data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{__('View')}}"> <i
                                                        data-feather="eye"></i></a>
                                            @endcan
                                            @can('edit invoice')
                                                <a class="text-success" href="{{ route('invoice.edit',$invoice->id) }}"
                                                   data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{__('Edit')}}"> <i data-feather="edit"></i></a>
                                            @endcan
                                            @can('delete invoice')
                                                <a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{__('Detete')}}" href="#"> <i
                                                        data-feather="trash-2"></i></a>
                                            @endcan
                                            {!! Form::close() !!}
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

