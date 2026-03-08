@extends('layouts.app')
@section('page-title')
    {{__('Contracts')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{__('Contracts')}}</a>
        </li>
    </ul>
@endsection
@section('card-action-btn')
    @can('create contract')
        <a class="btn btn-primary btn-sm ml-20" href="{{ route('contract.create') }}">
            <i class="ti-plus mr-5"></i>{{__('Create Contract')}}
        </a>
    @endcan
@endsection
@section('content')
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>{{__('Tenant')}}</th>
                    <th>{{__('Lease Tenure')}}</th>
                    <th>{{__('Lease Rate')}}</th>
                    <th>{{__('Amount')}}</th>
                    <th>{{__('Paid')}}</th>
                    <th>{{__('Remaining')}}</th>
                    <th>{{__('Payment Cycle')}}</th>
                    <th>{{__('Contract Status')}}</th>
                    <th>{{__('Lifecycle Status')}}</th>
                    <th>{{__('Action')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($contracts as $contract)
                    <tr>
                        <td>{{optional($contract->tenant)->id ? 'Tenant #'.optional($contract->tenant)->id : '-'}}</td>
                        <td>{{$contract->lease_tenure}}</td>
                        <td>{{$contract->lease_rate}}</td>
                        <td>{{$contract->amount}}</td>
                        <td>{{$contract->amount_paid}}</td>
                        <td>{{$contract->amount_remained}}</td>
                        <td>{{$contract->payment_cycle}}</td>
                        <td>{{$contract->contract_status}}</td>
                        <td>{{$contract->status}}</td>
                        <td>
                            @can('show contract')
                                <a class="text-warning" href="{{ route('contract.show',$contract->id) }}"><i data-feather="eye"></i></a>
                            @endcan
                            @can('edit contract')
                                <a class="text-success" href="{{ route('contract.edit',$contract->id) }}"><i data-feather="edit"></i></a>
                            @endcan
                            @can('delete contract')
                                {!! Form::open(['method' => 'DELETE', 'route' => ['contract.destroy', $contract->id], 'class' => 'd-inline']) !!}
                                <button class="btn p-0 border-0 bg-transparent text-danger"><i data-feather="trash-2"></i></button>
                                {!! Form::close() !!}
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

