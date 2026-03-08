@extends('layouts.app')
@section('page-title')
    {{__('Portal & Communications')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{route('phase.dashboard')}}"><h1>{{__('Phase Dashboard')}}</h1></a></li>
        <li class="breadcrumb-item active"><a href="#">{{__('Portal & Communications')}}</a></li>
    </ul>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-4">
            <div class="card"><div class="card-header"><h5>{{__('Create Email Thread')}}</h5></div><div class="card-body">
                <form method="post" action="{{route('phase.threads.store')}}">@csrf
                    <input class="form-control mb-2" name="subject" placeholder="{{__('Subject')}}" required>
                    <input class="form-control mb-2" name="linked_type" placeholder="{{__('Linked Type (customer/property/etc)')}}">
                    <input class="form-control mb-2" type="number" name="linked_id" placeholder="{{__('Linked ID')}}">
                    <button class="btn btn-primary btn-sm">{{__('Create Thread')}}</button>
                </form>
            </div></div>
        </div>
        <div class="col-lg-4">
            <div class="card"><div class="card-header"><h5>{{__('Log Notification')}}</h5></div><div class="card-body">
                <form method="post" action="{{route('phase.notifications.store')}}">@csrf
                    <select class="form-control mb-2" name="channel" required>
                        <option value="email">{{__('Email')}}</option>
                        <option value="sms">{{__('SMS')}}</option>
                        <option value="whatsapp">{{__('WhatsApp')}}</option>
                        <option value="in_app">{{__('In App')}}</option>
                    </select>
                    <input class="form-control mb-2" name="recipient" placeholder="{{__('Recipient')}}" required>
                    <input class="form-control mb-2" name="subject" placeholder="{{__('Subject')}}">
                    <textarea class="form-control mb-2" name="message" placeholder="{{__('Message')}}" required></textarea>
                    <button class="btn btn-primary btn-sm">{{__('Queue Notification')}}</button>
                </form>
                <form method="post" action="{{route('phase.reminders.generate')}}" class="mt-2">@csrf
                    <button class="btn btn-warning btn-sm">{{__('Generate Due Reminders')}}</button>
                </form>
            </div></div>
        </div>
        <div class="col-lg-4">
            <div class="card"><div class="card-header"><h5>{{__('Capture Portal Feedback')}}</h5></div><div class="card-body">
                <form method="post" action="{{route('phase.feedback.store')}}">@csrf
                    <input class="form-control mb-2" name="name" placeholder="{{__('Name')}}" required>
                    <input class="form-control mb-2" name="email" type="email" placeholder="{{__('Email')}}">
                    <input class="form-control mb-2" name="phone" placeholder="{{__('Phone')}}">
                    <textarea class="form-control mb-2" name="message" placeholder="{{__('Feedback / Query')}}" required></textarea>
                    <button class="btn btn-primary btn-sm">{{__('Save Feedback')}}</button>
                </form>
            </div></div>
        </div>

        <div class="col-md-6"><div class="card"><div class="card-header"><h5>{{__('Email Inbox Threads')}}</h5></div><div class="card-body table-responsive">
            <table class="table"><thead><tr><th>{{__('Subject')}}</th><th>{{__('Last Message')}}</th><th>{{__('Status')}}</th><th>{{__('Open')}}</th><th>{{__('Actions')}}</th></tr></thead><tbody>
            @foreach($threads as $thread)
                <tr>
                    <td>{{$thread->subject}}</td><td>{{$thread->last_message_at}}</td><td>{{$thread->status}}</td><td><a class="btn btn-sm btn-info" href="{{route('phase.threads.show',$thread->id)}}">{{__('View')}}</a></td>
                    <td>
                        <form method="post" action="{{route('phase.threads.update',$thread->id)}}" class="d-inline">@csrf @method('PUT')
                            <input class="form-control form-control-sm mb-1" name="subject" value="{{$thread->subject}}" required>
                            <input class="form-control form-control-sm mb-1" name="status" value="{{$thread->status}}">
                            <button class="btn btn-sm btn-primary">{{__('Update')}}</button>
                        </form>
                        <form method="post" action="{{route('phase.threads.destroy',$thread->id)}}" class="d-inline">@csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">{{__('Delete')}}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody></table>
        </div></div></div>

        <div class="col-md-6"><div class="card"><div class="card-header"><h5>{{__('Latest Notifications')}}</h5></div><div class="card-body table-responsive">
            <table class="table"><thead><tr><th>{{__('Channel')}}</th><th>{{__('Recipient')}}</th><th>{{__('Status')}}</th><th>{{__('Time')}}</th><th>{{__('Actions')}}</th></tr></thead><tbody>
            @foreach($notifications as $n)
                <tr>
                    <td>{{$n->channel}}</td><td>{{$n->recipient}}</td><td>{{$n->status}}</td><td>{{$n->created_at}}</td>
                    <td>
                        <form method="post" action="{{route('phase.notifications.update',$n->id)}}" class="d-inline">@csrf @method('PUT')
                            <input class="form-control form-control-sm mb-1" name="status" value="{{$n->status}}" required>
                            <button class="btn btn-sm btn-primary">{{__('Update')}}</button>
                        </form>
                        <form method="post" action="{{route('phase.notifications.destroy',$n->id)}}" class="d-inline">@csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">{{__('Delete')}}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody></table>
        </div></div></div>

        <div class="col-md-6"><div class="card"><div class="card-header"><h5>{{__('Portal Feedback')}}</h5></div><div class="card-body table-responsive">
            <table class="table"><thead><tr><th>{{__('Name')}}</th><th>{{__('Message')}}</th><th>{{__('Status')}}</th><th>{{__('Actions')}}</th></tr></thead><tbody>
            @foreach($feedback as $f)
                <tr>
                    <td>{{$f->name}}</td><td>{{$f->message}}</td><td>{{$f->status}}</td>
                    <td>
                        <form method="post" action="{{route('phase.feedback.update',$f->id)}}" class="d-inline">@csrf @method('PUT')
                            <input class="form-control form-control-sm mb-1" name="status" value="{{$f->status}}" required>
                            <button class="btn btn-sm btn-primary">{{__('Update')}}</button>
                        </form>
                        <form method="post" action="{{route('phase.feedback.destroy',$f->id)}}" class="d-inline">@csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">{{__('Delete')}}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody></table>
        </div></div></div>

        <div class="col-md-6"><div class="card"><div class="card-header"><h5>{{__('Portal Listings')}}</h5></div><div class="card-body">
            <p class="mb-2"><strong>{{__('Available Plots')}}:</strong> {{count($plots)}}</p>
            <p class="mb-2"><strong>{{__('Available Properties')}}:</strong> {{count($properties)}}</p>
            <div class="table-responsive">
                <table class="table"><thead><tr><th>{{__('Plot')}}</th><th>{{__('Sale Price')}}</th></tr></thead><tbody>
                @foreach($plots as $plot)<tr><td>{{$plot->plot_number}}</td><td>{{$plot->sale_price}}</td></tr>@endforeach
                </tbody></table>
            </div>
        </div></div></div>
    </div>
@endsection
