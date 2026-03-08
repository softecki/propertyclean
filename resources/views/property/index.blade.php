@extends('layouts.app')
@section('page-title')
    {{__('Property')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{__('Property')}}</a>
        </li>
    </ul>
@endsection
@section('card-action-btn')
    @can('create property')
        <a class="btn btn-primary btn-sm ml-20" href="{{ route('property.create') }}" data-size="md"> <i
                class="ti-plus mr-5"></i>{{__('Create Property')}}</a>
    @endcan
@endsection
@section('content')

<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Link</th>
            <th scope="col">Property Location</th>
            <th scope="col">Property Name</th>
            <th scope="col">No. Spaces</th>
            <th scope="col">Property Type</th>
            <th scope="col">Action</th>
            <!-- Add more headers as needed -->
        </tr>
    </thead>
    <tbody>
        @foreach($properties as $property)
        @if(!empty($property->thumbnail) && !empty($property->thumbnail->image))
            @php  $thumbnail= $property->thumbnail->image; @endphp
        @else
            @php  $thumbnail= 'default.jpg'; @endphp
        @endif
        <tr class="bg-white">
            <td>   <div class="imgwrapper">
                <a class="hover-link" href="{{route('property.show',$property->id)}}"><i
                    data-feather="link"></i></a>
                {{-- <img class="img-fluid property-img"
                     src="{{asset(Storage::url('upload/thumbnail')).'/'.$thumbnail}}" alt="{{$property->name}}"> --}}
                </div>
                    </td>
                    <td> <a href="@can('show property') {{route('property.show',$property->id)}} @endcan">
                        <h4>Arusha, Arusha City</h4></a>
                    </td>
            <td> <a href="@can('show property') {{route('property.show',$property->id)}} @endcan">
                <h4>{{$property->name}}</h4></a>
            </td>
            <td> <ul class="blogsoc-list">
                <li><a href="#"><i data-feather="layers"></i>{{$property->totalUnit()}}  {{__('Space')}}</a></li>
                {{-- <li><a href="#"><i data-feather="layout"></i>{{$property->totalRoom()}} {{__('Rooms')}}</a></li> --}}
            </ul>
        </td>
        <td>
            <div class="date-info">
                <span class="badge badge-primary" data-bs-toggle="tooltip"
                      data-bs-original-title="{{__('Type')}}">{{\App\Models\Property::$Type[$property->type]}}</span>
            </div>
        </td>
        <td>

            {!! Form::open(['method' => 'DELETE', 'route' => ['property.destroy', $property->id]]) !!}
            <div class="date-info">
                @can('edit property')
                    <a class="text-success" data-bs-toggle="tooltip"
                       data-bs-original-title="{{__('Edit')}}"
                       href="{{ route('property.edit',$property->id) }}"> <i
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
    <div class="row">
        @foreach($properties as $property)
            @if(!empty($property->thumbnail) && !empty($property->thumbnail->image))
                @php  $thumbnail= $property->thumbnail->image; @endphp
            @else
                @php  $thumbnail= 'default.jpg'; @endphp
            @endif

{{--
            <div class="col-xl-3 col-sm-6 cdx-xl-50">
                <div class="card blog-wrapper">

                    <div class="imgwrapper">
                        <img class="img-fluid property-img"
                             src="{{asset(Storage::url('upload/thumbnail')).'/'.$thumbnail}}" alt="{{$property->name}}">
                        <a class="hover-link" href="{{route('property.show',$property->id)}}"><i
                                data-feather="link"></i></a></div>

                    <div class="detailwrapper">
                        <a href="@can('show property') {{route('property.show',$property->id)}} @endcan">
                            <h4>{{$property->name}}</h4></a>
                        <ul class="blogsoc-list">
                            <li><a href="#"><i data-feather="layers"></i>{{$property->totalUnit()}}  {{__('Unit')}}</a></li>
                            <li><a href="#"><i data-feather="layout"></i>{{$property->totalRoom()}} {{__('Rooms')}}</a></li>
                        </ul>
                        <p class="text-justify">{{substr($property->description, 0, 200)}}{{!empty($property->description)?'...':''}}</p>

                        <div class="blog-footer">

                            <div class="date-info">
                                <span class="badge badge-primary" data-bs-toggle="tooltip"
                                      data-bs-original-title="{{__('Type')}}">{{\App\Models\Property::$Type[$property->type]}}</span>
                            </div>

                            {!! Form::open(['method' => 'DELETE', 'route' => ['property.destroy', $property->id]]) !!}
                            <div class="date-info">
                                @can('edit property')
                                    <a class="text-success" data-bs-toggle="tooltip"
                                       data-bs-original-title="{{__('Edit')}}"
                                       href="{{ route('property.edit',$property->id) }}"> <i
                                            data-feather="edit"></i></a>
                                @endcan
                                @can('delete property')
                                    <a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
                                       data-bs-original-title="{{__('Detete')}}" href="#"> <i
                                            data-feather="trash-2"></i></a>
                                @endcan
                            </div>
                            {!! Form::close() !!}


                        </div>
                    </div>
                </div>
            </div> --}}
        @endforeach
    </div>
@endsection

