@extends('layouts.app')
@section('page-title')
    {{__('Tenant')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{__('Tenant')}}</a>
        </li>
    </ul>
@endsection
@section('card-action-btn')
    @can('create tenant')
        <a class="btn btn-primary btn-sm ml-20" href="{{ route('tenant.create') }}" data-size="md"> <i
                class="ti-plus mr-5"></i>{{__('Create Tenant')}}</a>
    @endcan
@endsection
@section('content')


<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Link</th>
            <th scope="col">Title</th>
            <th scope="col">Busness Name</th>
            <th scope="col">Businnes number</th>
            <th scope="col">Tax Payer Identification</th>
            <th scope="col">Contact Information</th>
            <th scope="col">Property </th>
            <th scope="col">Room </th>
            <th scope="col">Action</th>
            <!-- Add more headers as needed -->
        </tr>
    </thead>
    <tbody>
        @foreach($tenants as $property)

        <tr class="bg-white">
            <td>   <div class="imgwrapper">
                <a class="hover-link" href="{{route('property.show',$property->id)}}"><i
                    data-feather="link"></i></a>
                {{-- <img class="img-fluid property-img"
                     src="{{asset(Storage::url('upload/thumbnail')).'/'.$thumbnail}}" alt="{{$property->name}}"> --}}
                </div>
                    </td>
            <td>
                <div>
                    {{ $property->title }}
                </div>
            </td>
            <td>
                <div>
                    {{ $property->business_name }}
                </div>
        </td>
        <td>
            <div>
                {{ $property->business_number }}
            </div>
        </td>
        <td>
            <div>
                {{ $property->tax_payer_identification }}
            </div>
        </td>
        <td>
            <div>
                {{ $property->contact_information }}
            </div>
        </td>
        <td>
            <div>
                {{ $property->property }}
            </div>
        </td>
        <td>
            <div class="">
                {{ $property->unit }}
            </div>
        </td>
        <td>

            {!! Form::open(['method' => 'DELETE', 'route' => ['property.destroy', $property->id]]) !!}
            <div class="date-info">
                @can('edit property')
                <a class="text-success" data-bs-toggle="tooltip"
                   data-bs-original-title="{{__('Contract')}}"
                   href="{{ route('contract.create',$property->id) }}"> <i
                        data-feather="key"></i></a>
            @endcan
                @can('edit property')
                <a class="text-success" data-bs-toggle="tooltip"
                   data-bs-original-title="{{__('View')}}"
                   href="{{ route('tenant.edit',$property->id) }}"> <i
                        data-feather="eye"></i></a>
            @endcan
                @can('edit property')
                    <a class="text-success" data-bs-toggle="tooltip"
                       data-bs-original-title="{{__('Edit')}}"
                       href="{{ route('tenant.edit',$property->id) }}"> <i
                            data-feather="edit"></i></a>
                @endcan
                @can('delete property')
                    <a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
                       data-bs-original-title="{{__('Detete')}}" href="#"> <i
                            data-feather="trash-2"></i></a>
                @endcan
            </div>
            {!! Form::close() !!}
        </td>
            <!-- Add more data columns as needed -->
        </tr>
        @endforeach
        <!-- Add more rows as needed -->
    </tbody>
</table>


{{--
    <div class="row">
        @foreach($tenants as $tenant)
            <div class="col-xl-3 col-md-6 cdx-xxl-50 cdx-xl-50 ">
                <div class="card custom contact-card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="user-imgwrapper">
                                <img class="img-fluid rounded-50"
                                     src="{{(!empty($tenant->user) && !empty($tenant->user->profile))? asset(Storage::url("upload/profile/".$tenant->user->profile)): asset(Storage::url("upload/profile/avatar.png"))}}"
                                     alt="">
                            </div>
                            <div class="media-body">
                                <a href="{{ route('tenant.show',$tenant->id) }}">
                                    <h4>{{ucfirst(!empty($tenant->user)?$tenant->user->first_name:'').' '.ucfirst(!empty($tenant->user)?$tenant->user->last_name:'')}}</h4>
                                    <h6 class="text-light">{{!empty($tenant->user)?$tenant->user->email:'-'}}</h6>
                                </a>
                            </div>
                            @if(Gate::check('edit tenant') || Gate::check('delete tenant') || Gate::check('show tenant'))
                                <div class="user-setting">
                                    <div class="action-menu">
                                        <div class="action-toggle"><i data-feather="more-vertical"></i></div>
                                        <ul class="action-dropdown">
                                            @can('edit tenant')
                                                <li>
                                                    <a class="" href="{{ route('tenant.edit',$tenant->id) }}"> <i
                                                            data-feather="edit"> </i>{{__('Edit Tenant')}}</a>
                                                </li>
                                            @endcan
                                            @can('delete tenant')
                                                <li>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['tenant.destroy', $tenant->id],'id'=>'tenant-'.$tenant->id]) !!}
                                                    <a href="#" class="confirm_dialog"> <i
                                                            data-feather="trash"></i>{{__('Delete Tenant')}}</a>
                                                    {!! Form::close() !!}
                                                </li>
                                            @endcan
                                            @can('show tenant')
                                                <li>
                                                    <a href="{{ route('tenant.show',$tenant->id) }}"> <i
                                                            data-feather="eye"> </i>{{__('View Tenant')}}</a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="user-detail">
                            <h5 class="text-primary mb-10"><i class="fa fa-info-circle mr-10"></i>{{__('Infomation')}}
                            </h5>
                            <p class="text-light">{{$tenant->address}}</p>
                            <ul class="info-list">
                                <li><span>{{__('Phone')}} : </span>{{!empty($tenant->user)?$tenant->user->phone_number:'-'}}</li>
                                <li><span>{{__('Family Member')}} :</span>{{$tenant->family_member}}</li>
                                <li>
                                    <span>{{__('Property')}} : </span>{{!empty($tenant->properties)?$tenant->properties->name:'-'}}
                                </li>
                                <li><span>{{__('Unit')}} : </span>{{!empty($tenant->units)?$tenant->units->name:'-'}}
                                </li>
                                <li>
                                    <span>{{__('Lease Start Date')}} : </span>{{dateFormat($tenant->lease_start_date)}}
                                </li>
                                <li>
                                    <span>{{__('Lease End Date')}} : </span>{{dateFormat($tenant->lease_end_date)}}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div> --}}
@endsection

