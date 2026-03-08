@extends('layouts.app')
@section('page-title')
    {{__('Tenant Application Review')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a></li>
        <li class="breadcrumb-item"><a href="{{route('tenantapplication.index')}}">{{__('Tenant Applications')}}</a></li>
        <li class="breadcrumb-item active"><a href="#">{{__('Review')}}</a></li>
    </ul>
@endsection
@section('content')
    {{Form::model($tenant, ['route' => ['tenantapplication.update', $tenant->id], 'method' => 'PUT'])}}
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4"><strong>{{__('Applicant')}}</strong><p>{{$user->first_name}} {{$user->last_name}}</p></div>
                <div class="col-md-4"><strong>{{__('Email')}}</strong><p>{{$user->email}}</p></div>
                <div class="col-md-4"><strong>{{__('Phone')}}</strong><p>{{$user->phone_number}}</p></div>
                <div class="col-md-4"><strong>{{__('Business')}}</strong><p>{{$tenant->business_name}}</p></div>
                <div class="col-md-4"><strong>{{__('Tax ID')}}</strong><p>{{$tenant->tax_payer_identification}}</p></div>
                <div class="col-md-4"><strong>{{__('Property / Unit')}}</strong><p>{{$tenant->property}} / {{$tenant->unit}}</p></div>
                <div class="col-md-4">
                    {{Form::label('application_status',__('Application Status'),['class'=>'form-label'])}}
                    <select class="form-control" name="application_status" required>
                        <option value="new" {{$tenant->application_status==='new'?'selected':''}}>{{__('New')}}</option>
                        <option value="reviewed" {{$tenant->application_status==='reviewed'?'selected':''}}>{{__('Reviewed')}}</option>
                        <option value="on-hold" {{$tenant->application_status==='on-hold'?'selected':''}}>{{__('On Hold')}}</option>
                    </select>
                </div>
                <div class="col-md-4">
                    {{Form::label('verification_status',__('Verification Status'),['class'=>'form-label'])}}
                    <select class="form-control" name="verification_status" required>
                        <option value="pending" {{$tenant->verification_status==='pending'?'selected':''}}>{{__('Pending')}}</option>
                        <option value="verified" {{$tenant->verification_status==='verified'?'selected':''}}>{{__('Verified')}}</option>
                        <option value="rejected" {{$tenant->verification_status==='rejected'?'selected':''}}>{{__('Rejected')}}</option>
                    </select>
                </div>
                <div class="col-md-4">
                    {{Form::label('approval_status',__('Approval Status'),['class'=>'form-label'])}}
                    <select class="form-control" name="approval_status" required>
                        <option value="pending" {{$tenant->approval_status==='pending'?'selected':''}}>{{__('Pending')}}</option>
                        <option value="approved" {{$tenant->approval_status==='approved'?'selected':''}}>{{__('Approved')}}</option>
                        <option value="declined" {{$tenant->approval_status==='declined'?'selected':''}}>{{__('Declined')}}</option>
                    </select>
                </div>
                <div class="col-12">
                    {{Form::label('application_notes',__('Review Notes'),['class'=>'form-label'])}}
                    {{Form::textarea('application_notes',$tenant->application_notes,['class'=>'form-control','rows'=>4])}}
                </div>
            </div>
        </div>
    </div>
    <div class="text-end">{{Form::submit(__('Update Application Workflow'),['class'=>'btn btn-primary'])}}</div>
    {{Form::close()}}
@endsection

