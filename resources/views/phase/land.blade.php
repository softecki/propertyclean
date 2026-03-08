@extends('layouts.app')
@section('page-title')
    {{__('Land Master')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{route('phase.dashboard')}}"><h1>{{__('Phase Dashboard')}}</h1></a></li>
        <li class="breadcrumb-item active"><a href="#">{{__('Land Master')}}</a></li>
    </ul>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card"><div class="card-header"><h5>{{__('Create Branch')}}</h5></div><div class="card-body">
                <form method="post" action="{{route('phase.branches.store')}}">@csrf
                    <input class="form-control mb-2" name="name" placeholder="{{__('Name')}}" required>
                    <input class="form-control mb-2" name="code" placeholder="{{__('Code')}}">
                    <input class="form-control mb-2" name="address" placeholder="{{__('Address')}}">
                    <button class="btn btn-primary btn-sm">{{__('Save Branch')}}</button>
                </form>
            </div></div>
        </div>
        <div class="col-lg-6">
            <div class="card"><div class="card-header"><h5>{{__('Create Project')}}</h5></div><div class="card-body">
                <form method="post" action="{{route('phase.projects.store')}}">@csrf
                    <input class="form-control mb-2" name="name" placeholder="{{__('Project Name')}}" required>
                    <select class="form-control mb-2" name="branch_id">
                        <option value="">{{__('Branch (optional)')}}</option>
                        @foreach($branches as $branch)<option value="{{$branch->id}}">{{$branch->name}}</option>@endforeach
                    </select>
                    <textarea class="form-control mb-2" name="description" placeholder="{{__('Description')}}"></textarea>
                    <button class="btn btn-primary btn-sm">{{__('Save Project')}}</button>
                </form>
            </div></div>
        </div>
        <div class="col-lg-6">
            <div class="card"><div class="card-header"><h5>{{__('Create Block')}}</h5></div><div class="card-body">
                <form method="post" action="{{route('phase.blocks.store')}}">@csrf
                    <select class="form-control mb-2" name="project_id" required>
                        <option value="">{{__('Select Project')}}</option>
                        @foreach($projects as $project)<option value="{{$project->id}}">{{$project->name}}</option>@endforeach
                    </select>
                    <input class="form-control mb-2" name="name" placeholder="{{__('Block Name')}}" required>
                    <input class="form-control mb-2" name="code" placeholder="{{__('Block Code')}}">
                    <button class="btn btn-primary btn-sm">{{__('Save Block')}}</button>
                </form>
            </div></div>
        </div>
        <div class="col-lg-6">
            <div class="card"><div class="card-header"><h5>{{__('Create Plot')}}</h5></div><div class="card-body">
                <form method="post" action="{{route('phase.plots.store')}}">@csrf
                    <select class="form-control mb-2" name="block_id" required>
                        <option value="">{{__('Select Block')}}</option>
                        @foreach($blocks as $block)<option value="{{$block->id}}">{{$block->name}}</option>@endforeach
                    </select>
                    <select class="form-control mb-2" name="property_id">
                        <option value="">{{__('Linked Property (optional)')}}</option>
                        @foreach($properties as $property)<option value="{{$property->id}}">{{$property->name}}</option>@endforeach
                    </select>
                    <input class="form-control mb-2" name="plot_number" placeholder="{{__('Plot Number')}}" required>
                    <input class="form-control mb-2" name="title_deed_no" placeholder="{{__('Title Deed No')}}">
                    <input class="form-control mb-2" name="size_sqm" type="number" step="0.01" placeholder="{{__('Size (sqm)')}}">
                    <input class="form-control mb-2" name="sale_price" type="number" step="0.01" placeholder="{{__('Sale Price')}}" required>
                    <input class="form-control mb-2" name="rental_price" type="number" step="0.01" placeholder="{{__('Rental Price')}}">
                    <button class="btn btn-primary btn-sm">{{__('Save Plot')}}</button>
                </form>
            </div></div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h5>{{__('Land Inventory')}}</h5></div>
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead><tr><th>{{__('Plot')}}</th><th>{{__('Title Deed')}}</th><th>{{__('Size')}}</th><th>{{__('Sale Price')}}</th><th>{{__('Status')}}</th></tr></thead>
                        <tbody>
                        @foreach($plots as $plot)
                            <tr>
                                <td>{{$plot->plot_number}}</td>
                                <td>{{$plot->title_deed_no}}</td>
                                <td>{{$plot->size_sqm}}</td>
                                <td>{{$plot->sale_price}}</td>
                                <td>{{$plot->status}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
