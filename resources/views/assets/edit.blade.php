@extends('layouts.app')
@section('page-title')
    {{__('Edit Asset')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a></li>
        <li class="breadcrumb-item"><a href="{{route('asset.index')}}">{{__('Assets')}}</a></li>
        <li class="breadcrumb-item active"><a href="#">{{__('Edit')}}</a></li>
    </ul>
@endsection
@section('content')
    {{Form::open(['route'=>['asset.update',$asset->id],'method'=>'PUT'])}}
    <div class="row">
        <div class="col-md-4">{{Form::label('name',__('Name'),['class'=>'form-label'])}}{{Form::text('name',$asset->name,['class'=>'form-control','required'])}}</div>
        <div class="col-md-4">{{Form::label('category',__('Category'),['class'=>'form-label'])}}{{Form::text('category',$asset->category,['class'=>'form-control'])}}</div>
        <div class="col-md-4">{{Form::label('branch_id',__('Branch ID'),['class'=>'form-label'])}}{{Form::number('branch_id',$asset->branch_id,['class'=>'form-control'])}}</div>
        <div class="col-md-4">{{Form::label('acquisition_date',__('Acquisition Date'),['class'=>'form-label'])}}{{Form::date('acquisition_date',$asset->acquisition_date,['class'=>'form-control','required'])}}</div>
        <div class="col-md-4">{{Form::label('cost',__('Cost'),['class'=>'form-label'])}}{{Form::number('cost',$asset->cost,['class'=>'form-control','step'=>'0.01','required'])}}</div>
        <div class="col-md-4">{{Form::label('salvage_value',__('Salvage Value'),['class'=>'form-label'])}}{{Form::number('salvage_value',$asset->salvage_value,['class'=>'form-control','step'=>'0.01'])}}</div>
        <div class="col-md-4">{{Form::label('useful_life_years',__('Useful Life Years'),['class'=>'form-label'])}}{{Form::number('useful_life_years',$asset->useful_life_years,['class'=>'form-control','required'])}}</div>
        <div class="col-md-4">{{Form::label('method',__('Method'),['class'=>'form-label'])}}{{Form::text('method',$asset->method,['class'=>'form-control'])}}</div>
        <div class="col-12 text-end mt-2">{{Form::submit(__('Update Asset'),['class'=>'btn btn-primary'])}}</div>
    </div>
    {{Form::close()}}
@endsection
