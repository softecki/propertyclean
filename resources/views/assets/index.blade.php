@extends('layouts.app')
@section('page-title')
    {{__('Assets Register')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{__('Assets')}}</a>
        </li>
    </ul>
@endsection
@section('card-action-btn')
    @can('create expense')
        <a class="btn btn-primary btn-sm ml-20" href="{{ route('asset.create') }}">
            <i class="ti-plus mr-5"></i>{{__('Create Asset')}}
        </a>
    @endcan
@endsection
@section('content')
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>{{__('Name')}}</th>
                    <th>{{__('Category')}}</th>
                    <th>{{__('Acquisition Date')}}</th>
                    <th>{{__('Cost')}}</th>
                    <th>{{__('Book Value')}}</th>
                    <th>{{__('Useful Life')}}</th>
                    <th>{{__('Action')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($assets as $asset)
                    <tr>
                        <td>{{$asset->name}}</td>
                        <td>{{$asset->category}}</td>
                        <td>{{$asset->acquisition_date}}</td>
                        <td>{{$asset->cost}}</td>
                        <td>{{$asset->book_value}}</td>
                        <td>{{$asset->useful_life_years}}</td>
                        <td>
                            @can('show expense')
                                <a class="text-warning" href="{{ route('asset.show',$asset->id) }}"><i data-feather="eye"></i></a>
                            @endcan
                            @can('edit expense')
                                <a class="text-success" href="{{ route('asset.edit',$asset->id) }}"><i data-feather="edit"></i></a>
                            @endcan
                            @can('delete expense')
                                {!! Form::open(['method' => 'DELETE', 'route' => ['asset.destroy', $asset->id], 'class' => 'd-inline']) !!}
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

