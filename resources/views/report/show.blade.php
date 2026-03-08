@extends('layouts.app')
@section('page-title')
    {{__('Invoice')}}
@endsection
@php
    $admin_logo=getSettingsValByName('company_logo');
    $settings=settings();
@endphp
@push('script-page')
    <script>
        $(document).on('click', '.print', function () {
            var printContents = document.getElementById('invoice-print').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;

        });

    </script>
    <script src="https://js.stripe.com/v3/"></script>

    <script type="text/javascript">
        @if( $invoicePaymentSettings['STRIPE_PAYMENT'] == 'on' && !empty($invoicePaymentSettings['STRIPE_KEY']) && !empty($invoicePaymentSettings['STRIPE_SECRET']))
        var stripe_key = Stripe('{{ $invoicePaymentSettings['STRIPE_KEY'] }}');
        var stripe_elements = stripe_key.elements();
        var strip_css = {
            base: {
                fontSize: '14px',
                color: '#32325d',
            },
        };
        var stripe_card = stripe_elements.create('card', {style: strip_css});
        stripe_card.mount('#card-element');

        var stripe_form = document.getElementById('stripe-payment');
        stripe_form.addEventListener('submit', function (event) {
            event.preventDefault();

            stripe_key.createToken(stripe_card).then(function (result) {
                if (result.error) {
                    $("#stripe_card_errors").html(result.error.message);
                    $.NotificationApp.send("Error", result.error.message, "top-right", "rgba(0,0,0,0.2)", "error");
                } else {
                    var token = result.token;
                    var stripeForm = document.getElementById('stripe-payment');
                    var stripeHiddenData = document.createElement('input');
                    stripeHiddenData.setAttribute('type', 'hidden');
                    stripeHiddenData.setAttribute('name', 'stripeToken');
                    stripeHiddenData.setAttribute('value', token.id);
                    stripeForm.appendChild(stripeHiddenData);
                    stripeForm.submit();
                }
            });
        });
        @endif

    </script>
@endpush
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{route('invoice.index')}}">{{__('Invoice')}}</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{__('Details')}}</a>
        </li>
    </ul>
@endsection
@section('content')

    <div class="row mb-10">
        <div class="invoice-action ">
            <a class="btn btn-info float-end print" href="javascript:void(0);"> {{__('Print Invoice')}}</a>
            @if($invoice->status!='paid')
                @can('create invoice payment')
                    @if(\Auth::user()->type=='tenant')
                        <a class="btn btn-primary float-end me-2 collapsed" data-bs-toggle="collapse"
                           href="#paymentModal" role="button" aria-expanded="false"
                           aria-controls="collapse1">{{__('Payment')}}</a>
                    @else
                        <a class="btn btn-primary float-end me-2 customModal" href="#" data-size="md"
                           data-url="{{ route('invoice.payment.create',$invoice->id) }}"
                           data-title="{{__('Add Payment')}}"> {{__('Add Payment')}}</a>
                    @endif
                @endcan
            @endif
        </div>
    </div>
    <div class="mt-25 collapse" id="paymentModal" style="">
        <div class="card card-body ">
            <div class="col-xxl-12 cdx-xxl-100">
                <div class="payment-method">
                    <div class="card-body">
                        <ul class="nav nav-tabs border-0 mb-15">
                            @if($settings['bank_transfer_payment'] == 'on')
                                <li><a class="btn active" data-bs-toggle="tab"
                                       href="#bank_transfer">{{__('Bank Transfer')}} </a></li>
                            @endif
                            @if($settings['STRIPE_PAYMENT'] == 'on' && !empty($settings['STRIPE_KEY']) && !empty($settings['STRIPE_SECRET']))
                                <li><a class="btn " data-bs-toggle="tab"
                                       href="#stripe_payment">{{__('Stripe')}} </a></li>
                            @endif
                            @if($settings['paypal_payment'] == 'on' && !empty($settings['paypal_client_id']) && !empty($settings['paypal_secret_key']))
                                <li><a class="btn" data-bs-toggle="tab" href="#paypal_payment">{{__('Paypal')}}</a></li>
                            @endif
                        </ul>
                        <div class="tab-content">
                            @if($settings['bank_transfer_payment'] == 'on')
                                <div class="tab-pane fade active show" id="bank_transfer">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class=" profile-user-box">
                                                <form
                                                    action="{{ route('invoice.banktransfer.payment',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)) }}"
                                                    method="post" class="require-validation" id="bank-payment"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="card-name-on"
                                                                       class="form-label text-dark">{{__('Bank Name')}}</label>
                                                                <p>{{$settings['bank_name']}}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="card-name-on"
                                                                       class="form-label text-dark">{{__('Bank Holder Name')}}</label>
                                                                <p>{{$settings['bank_holder_name']}}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="card-name-on"
                                                                       class="form-label text-dark">{{__('Bank Account Number')}}</label>
                                                                <p>{{$settings['bank_account_number']}}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="card-name-on"
                                                                       class="form-label text-dark">{{__('Bank IFSC Code')}}</label>
                                                                <p>{{$settings['bank_ifsc_code']}}</p>
                                                            </div>
                                                        </div>
                                                        @if(!empty($settings['bank_other_details']))
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="card-name-on"
                                                                           class="form-label text-dark">{{__('Bank Other Details')}}</label>
                                                                    <p>{{$settings['bank_other_details']}}</p>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="amount"
                                                                       class="form-label text-dark">{{__('Amount')}}</label>
                                                                <input type="number" name="amount" id="amount"
                                                                       class="form-control required"
                                                                       value="{{$invoice->getInvoiceDueAmount()}}"
                                                                       placeholder="{{__('Enter Amount')}}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="card-name-on"
                                                                       class="form-label text-dark">{{__('Attachment')}}</label>
                                                                <input type="file" name="receipt" id="receipt"
                                                                       class="form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="notes" class="form-label text-dark">{{__('Notes')}}</label>
                                                                <input type="text" name="notes" id="amount"
                                                                       class="form-control "
                                                                       value=""
                                                                       placeholder="{{__('Enter notes')}}" >
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 ">
                                                            <input type="submit" value="{{__('Pay')}}" class="btn btn-primary">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($settings['STRIPE_PAYMENT'] == 'on' && !empty($settings['STRIPE_KEY']) && !empty($settings['STRIPE_SECRET']))
                                <div class="tab-pane fade " id="stripe_payment">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class=" profile-user-box">
                                                <form
                                                    action="{{ route('invoice.stripe.payment',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)) }}"
                                                    method="post" class="require-validation" id="stripe-payment">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="amount"
                                                                       class="form-label text-dark">{{__('Amount')}}</label>
                                                                <input type="number" name="amount" id="amount"
                                                                       class="form-control required"
                                                                       value="{{$invoice->getInvoiceDueAmount()}}"
                                                                       placeholder="{{__('Enter Amount')}}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="card-name-on"
                                                                       class="form-label text-dark">{{__('Card Name')}}</label>
                                                                <input type="text" name="name" id="card-name-on"
                                                                       class="form-control required"
                                                                       placeholder="{{__('Card Holder Name')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label for="card-name-on"
                                                                   class="form-label text-dark">{{__('Card Details')}}</label>
                                                            <div id="card-element">
                                                            </div>
                                                            <div id="card-errors" role="alert"></div>
                                                        </div>
                                                        <div class="col-sm-12 mt-15">

                                                            <input type="submit" value="{{__('Pay Now')}}"
                                                                   class="btn btn-primary">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($settings['paypal_payment'] == 'on' && !empty($settings['paypal_client_id']) && !empty($settings['paypal_secret_key']))
                                <div class="tab-pane fade" id="paypal_payment">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class=" profile-user-box">
                                                <form
                                                    action="{{ route('invoice.paypal',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)) }}"
                                                    method="post" class="require-validation">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="amount"
                                                                       class="form-label text-dark">{{__('Amount')}}</label>
                                                                <input type="number" name="amount" id="amount"
                                                                       class="form-control required"
                                                                       value="{{$invoice->getInvoiceDueAmount()}}"
                                                                       placeholder="{{__('Enter Amount')}}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 ">
                                                            <input type="submit" value="{{__('Pay Now')}}"
                                                                   class="btn btn-primary">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="invoice-print">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body cdx-invoice">
                        <div id="cdx-invoice">
                            <div class="head-invoice">
                                <div class="codex-brand">
                                    <a class="codexbrand-logo" href="Javascript:void(0);">
                                        <img class="img-fluid invoice-logo"
                                             src=" {{asset(Storage::url('upload/logo/')).'/'.(isset($admin_logo) && !empty($admin_logo)?$admin_logo:'logo.png')}}"
                                             alt="invoice-logo">
                                    </a>
                                    <a class="codexdark-logo" href="Javascript:void(0);">
                                        <img class="img-fluid invoice-logo"
                                             src=" {{asset(Storage::url('upload/logo/')).'/'.(isset($admin_logo) && !empty($admin_logo)?$admin_logo:'logo.png')}}"
                                             alt="invoice-logo">
                                    </a>
                                </div>
                                <ul class="contact-list">

                                    <li>
                                        <div class="icon-wrap"><i class="fa fa-user"></i>
                                        </div>{{$settings['company_name']}}
                                    </li>
                                    <li>
                                        <div class="icon-wrap"><i class="fa fa-phone"></i>
                                        </div>{{$settings['company_phone']}}
                                    </li>
                                    <li>
                                        <div class="icon-wrap"><i class="fa fa-envelope"></i>
                                        </div>{{$settings['company_email']}}
                                    </li>

                                </ul>
                            </div>
                            <div class="invoice-user">
                                <div class="left-user">
                                    <h5>{{__('Inovice to')}}:</h5>
                                    <ul class="detail-list">
                                        <li>
                                            <div class="icon-wrap"><i class="fa fa-user"></i>
                                            </div>{{!empty($tenant) && !empty($tenant->user)?$tenant->user->first_name.' '.$tenant->user->last_name:''}}
                                        </li>
                                        <li>
                                            <div class="icon-wrap"><i class="fa fa-phone"></i>
                                            </div>{{!empty($tenant) && !empty($tenant->user) ?$tenant->user->phone_number:'-'}}
                                        </li>
                                        <li>
                                            <div class="icon-wrap"><i class="fa fa-map-marker"></i></div>
                                            {{!empty($tenant)?$tenant->address:''}}
                                        </li>
                                    </ul>
                                </div>
                                <div class="right-user">
                                    <ul class="detail-list">
                                        <li>{{__('Status')}}:
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
                                        </li>
                                        <li>{{__('Invoice No')}}: <span>{{invoicePrefix().$invoice->invoice_id}} </span>
                                        </li>
                                        <li>{{__('Invoice Month')}}:
                                            <span> {{date('F Y',strtotime($invoice->invoice_month))}} </span></li>
                                        <li>{{__('End Date')}}: <span>{{dateFormat($invoice->end_date)}}</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="body-invoice">
                                <div class="table-responsive1">
                                    <table class="table ml-1">
                                        <thead>
                                        <tr>
                                            <th>{{__('Type')}}</th>
                                            <th>{{__('Description')}}</th>
                                            <th>{{__('Amount')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($invoice->types as $k=>$type)
                                            <tr>
                                                <td>{{!empty($type->types)?$type->types->title:'-'}}</td>
                                                <td>{{$type->description}}</td>
                                                <td>{{priceFormat($type->amount)}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="footer-invoice">
                                <table class="table">
                                    <tr>
                                        <td>{{__('Total')}}</td>
                                        <td>{{priceFormat($invoice->getInvoiceSubTotalAmount())}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('Due Amount')}}</td>
                                        <td>{{priceFormat($invoice->getInvoiceDueAmount())}} </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>{{__('Payment History')}}</h5>
                    </div>
                    <div class="card-body">
                        <table class="display dataTable cell-border datatbl-advance1">
                            <thead>
                            <tr>
                                <th>{{__('Transaction Id')}}</th>
                                <th>{{__('Payment Date')}}</th>
                                <th>{{__('Amount')}}</th>
                                <th>{{__('Type')}}</th>
                                <th>{{__('Notes')}}</th>
                                <th>{{__('Receipt')}}</th>
                                @can('delete invoice payment')
                                    <th class="text-right">{{__('Action')}}</th>
                                @endcan
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($invoice->payments as $payment)
                                <tr role="row">
                                    <td>{{$payment->transaction_id}} </td>
                                    <td>{{dateFormat($payment->payment_date)}} </td>
                                    <td>{{priceFormat($payment->amount)}} </td>
                                    <td>{{__($payment->payment_type)}} </td>
                                    <td>{{$payment->notes}} </td>
                                    <td>
                                        @if(!empty($payment->receipt))
                                            @if($payment->payment_type=='Stripe')
                                                <a href="{{$payment->receipt}}" target="_blank"
                                                ><i data-feather="eye"></i></a>
                                            @else
                                                <a href="{{asset(Storage::url('upload/receipt')).'/'.$payment->receipt}}"
                                                   download="download"><i data-feather="download"></i></a>
                                            @endif

                                        @else
                                            -
                                        @endif
                                    </td>
                                    @can('delete invoice payment')
                                        <td class="text-right">
                                            <div class="cart-action">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['invoice.payment.destroy', $invoice->id,$payment->id]]) !!}
                                                <a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{__('Detete')}}" href="#"> <i
                                                        data-feather="trash-2"></i></a>
                                                {!! Form::close() !!}
                                            </div>
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

