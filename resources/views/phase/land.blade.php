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
            <div class="card"><div class="card-header"><h5>{{__('Record Land Rates Payment')}}</h5></div><div class="card-body">
                <form method="post" action="{{route('phase.land-rates.store')}}">@csrf
                    <div class="row">
                        <div class="col-md-3">
                            <select class="form-control mb-2" name="property_id" required>
                                <option value="">{{__('Select Property')}}</option>
                                @foreach($properties as $property)<option value="{{$property->id}}">{{$property->name}}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-md-2"><input class="form-control mb-2" type="number" step="0.01" name="amount" placeholder="{{__('Amount')}}" required></div>
                        <div class="col-md-2"><input class="form-control mb-2" type="date" name="payment_date" required></div>
                        <div class="col-md-2"><input class="form-control mb-2" name="reference" placeholder="{{__('Reference')}}"></div>
                        <div class="col-md-3"><input class="form-control mb-2" name="notes" placeholder="{{__('Notes')}}"></div>
                    </div>
                    <button class="btn btn-primary btn-sm">{{__('Save Land Rate Payment')}}</button>
                </form>
            </div></div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h5>{{__('Land Inventory')}}</h5></div>
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead><tr><th>{{__('Plot')}}</th><th>{{__('Title Deed')}}</th><th>{{__('Size')}}</th><th>{{__('Sale Price')}}</th><th>{{__('Status')}}</th><th>{{__('Actions')}}</th></tr></thead>
                        <tbody>
                        @foreach($plots as $plot)
                            <tr>
                                <td>{{$plot->plot_number}}</td>
                                <td>{{$plot->title_deed_no}}</td>
                                <td>{{$plot->size_sqm}}</td>
                                <td>{{$plot->sale_price}}</td>
                                <td>{{$plot->status}}</td>
                                <td>
                                    <form method="post" action="{{route('phase.plots.update',$plot->id)}}" class="d-inline">@csrf @method('PUT')
                                        <input class="form-control form-control-sm mb-1" name="plot_number" value="{{$plot->plot_number}}" required>
                                        <input class="form-control form-control-sm mb-1" type="number" step="0.01" name="sale_price" value="{{$plot->sale_price}}" required>
                                        <select class="form-control form-control-sm mb-1" name="status">
                                            <option value="available" {{$plot->status==='available'?'selected':''}}>{{__('Available')}}</option>
                                            <option value="reserved" {{$plot->status==='reserved'?'selected':''}}>{{__('Reserved')}}</option>
                                            <option value="sold" {{$plot->status==='sold'?'selected':''}}>{{__('Sold')}}</option>
                                            <option value="cancelled" {{$plot->status==='cancelled'?'selected':''}}>{{__('Cancelled')}}</option>
                                        </select>
                                        <button class="btn btn-sm btn-primary">{{__('Update')}}</button>
                                    </form>
                                    <form method="post" action="{{route('phase.plots.destroy',$plot->id)}}" class="d-inline">@csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">{{__('Delete')}}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card"><div class="card-header"><h5>{{__('Branches (CRUD)')}}</h5></div><div class="card-body table-responsive">
                <table class="table"><thead><tr><th>{{__('Name')}}</th><th>{{__('Code')}}</th><th>{{__('Actions')}}</th></tr></thead><tbody>
                @foreach($branches as $branch)
                    <tr>
                        <td>{{$branch->name}}</td><td>{{$branch->code}}</td>
                        <td>
                            <form method="post" action="{{route('phase.branches.update',$branch->id)}}" class="d-inline">@csrf @method('PUT')
                                <input class="form-control form-control-sm mb-1" name="name" value="{{$branch->name}}" required>
                                <input class="form-control form-control-sm mb-1" name="code" value="{{$branch->code}}">
                                <button class="btn btn-sm btn-primary">{{__('Update')}}</button>
                            </form>
                            <form method="post" action="{{route('phase.branches.destroy',$branch->id)}}" class="d-inline">@csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">{{__('Delete')}}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody></table>
            </div></div>
        </div>

        <div class="col-md-6">
            <div class="card"><div class="card-header"><h5>{{__('Projects (CRUD)')}}</h5></div><div class="card-body table-responsive">
                <table class="table"><thead><tr><th>{{__('Name')}}</th><th>{{__('Status')}}</th><th>{{__('Actions')}}</th></tr></thead><tbody>
                @foreach($projects as $project)
                    <tr>
                        <td>{{$project->name}}</td><td>{{$project->status}}</td>
                        <td>
                            <form method="post" action="{{route('phase.projects.update',$project->id)}}" class="d-inline">@csrf @method('PUT')
                                <input class="form-control form-control-sm mb-1" name="name" value="{{$project->name}}" required>
                                <button class="btn btn-sm btn-primary">{{__('Update')}}</button>
                            </form>
                            <form method="post" action="{{route('phase.projects.destroy',$project->id)}}" class="d-inline">@csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">{{__('Delete')}}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody></table>
            </div></div>
        </div>
        <div class="col-12">
            <div class="card"><div class="card-header"><h5>{{__('Blocks (CRUD)')}}</h5></div><div class="card-body table-responsive">
                <table class="table"><thead><tr><th>{{__('Project ID')}}</th><th>{{__('Name')}}</th><th>{{__('Code')}}</th><th>{{__('Actions')}}</th></tr></thead><tbody>
                @foreach($blocks as $block)
                    <tr>
                        <td>{{$block->project_id}}</td><td>{{$block->name}}</td><td>{{$block->code}}</td>
                        <td>
                            <form method="post" action="{{route('phase.blocks.update',$block->id)}}" class="d-inline">@csrf @method('PUT')
                                <input class="form-control form-control-sm mb-1" name="project_id" value="{{$block->project_id}}">
                                <input class="form-control form-control-sm mb-1" name="name" value="{{$block->name}}" required>
                                <input class="form-control form-control-sm mb-1" name="code" value="{{$block->code}}">
                                <button class="btn btn-sm btn-primary">{{__('Update')}}</button>
                            </form>
                            <form method="post" action="{{route('phase.blocks.destroy',$block->id)}}" class="d-inline">@csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">{{__('Delete')}}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody></table>
            </div></div>
        </div>
        <div class="col-12">
            <div class="card"><div class="card-header"><h5>{{__('Land Rate Payments')}}</h5></div><div class="card-body table-responsive">
                <table class="table"><thead><tr><th>{{__('Property')}}</th><th>{{__('Amount')}}</th><th>{{__('Date')}}</th><th>{{__('Reference')}}</th><th>{{__('Actions')}}</th></tr></thead><tbody>
                @foreach($landRatePayments as $payment)
                    <tr>
                        <td>{{$payment->property_name}}</td>
                        <td>{{$payment->amount}}</td>
                        <td>{{$payment->payment_date}}</td>
                        <td>{{$payment->reference}}</td>
                        <td>
                            <form method="post" action="{{route('phase.land-rates.update',$payment->id)}}" class="d-inline">@csrf @method('PUT')
                                <input class="form-control form-control-sm mb-1" type="number" step="0.01" name="amount" value="{{$payment->amount}}" required>
                                <input class="form-control form-control-sm mb-1" type="date" name="payment_date" value="{{$payment->payment_date}}" required>
                                <input class="form-control form-control-sm mb-1" name="reference" value="{{$payment->reference}}">
                                <button class="btn btn-sm btn-primary">{{__('Update')}}</button>
                            </form>
                            <form method="post" action="{{route('phase.land-rates.destroy',$payment->id)}}" class="d-inline">@csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">{{__('Delete')}}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody></table>
            </div></div>
        </div>
    </div>
@endsection
