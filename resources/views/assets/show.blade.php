@extends('layouts.app')
@section('page-title')
    {{__('Asset Details')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a></li>
        <li class="breadcrumb-item"><a href="{{route('asset.index')}}">{{__('Assets')}}</a></li>
        <li class="breadcrumb-item active"><a href="#">{{__('Details')}}</a></li>
    </ul>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4"><strong>{{__('Name')}}:</strong> {{$asset->name}}</div>
                <div class="col-md-4"><strong>{{__('Category')}}:</strong> {{$asset->category}}</div>
                <div class="col-md-4"><strong>{{__('Acquisition Date')}}:</strong> {{$asset->acquisition_date}}</div>
                <div class="col-md-4"><strong>{{__('Cost')}}:</strong> {{$asset->cost}}</div>
                <div class="col-md-4"><strong>{{__('Salvage')}}:</strong> {{$asset->salvage_value}}</div>
                <div class="col-md-4"><strong>{{__('Book Value')}}:</strong> {{$asset->book_value}}</div>
                <div class="col-md-4"><strong>{{__('Useful Life')}}:</strong> {{$asset->useful_life_years}}</div>
                <div class="col-md-4"><strong>{{__('Method')}}:</strong> {{$asset->method}}</div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h5>{{__('Depreciation Entries')}}</h5></div>
        <div class="card-body table-responsive">
            <table class="table">
                <thead><tr><th>{{__('Period Start')}}</th><th>{{__('Period End')}}</th><th>{{__('Amount')}}</th><th>{{__('Accumulated')}}</th><th>{{__('Book Value')}}</th></tr></thead>
                <tbody>
                @foreach($depreciationEntries as $entry)
                    <tr>
                        <td>{{$entry->period_start}}</td>
                        <td>{{$entry->period_end}}</td>
                        <td>{{$entry->depreciation_amount}}</td>
                        <td>{{$entry->accumulated_depreciation}}</td>
                        <td>{{$entry->book_value}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
