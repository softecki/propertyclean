@extends('layouts.app')
@section('page-title')
    {{__('Email Thread')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{route('phase.communications')}}"><h1>{{__('Portal & Communications')}}</h1></a></li>
        <li class="breadcrumb-item active"><a href="#">{{$thread->subject}}</a></li>
    </ul>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card"><div class="card-header"><h5>{{$thread->subject}}</h5></div><div class="card-body">
                <form method="post" action="{{route('phase.threads.messages.store',$thread->id)}}">
                    @csrf
                    <div class="row">
                        <div class="col-md-2">
                            <select class="form-control mb-2" name="direction">
                                <option value="outbound">{{__('Outbound')}}</option>
                                <option value="inbound">{{__('Inbound')}}</option>
                            </select>
                        </div>
                        <div class="col-md-5"><input class="form-control mb-2" name="from_address" placeholder="{{__('From')}}"></div>
                        <div class="col-md-5"><input class="form-control mb-2" name="to_address" placeholder="{{__('To')}}"></div>
                    </div>
                    <textarea class="form-control mb-2" name="body" rows="4" placeholder="{{__('Message body')}}" required></textarea>
                    <button class="btn btn-primary btn-sm">{{__('Add Message')}}</button>
                </form>
            </div></div>
        </div>
        <div class="col-12">
            <div class="card"><div class="card-header"><h5>{{__('Thread History')}}</h5></div><div class="card-body">
                @foreach($messages as $message)
                    <div class="border rounded p-2 mb-2">
                        <div><strong>{{strtoupper($message->direction)}}</strong> | {{$message->created_at}}</div>
                        <div>{{$message->from_address}} → {{$message->to_address}}</div>
                        <div class="mt-1">{{$message->body}}</div>
                    </div>
                @endforeach
            </div></div>
        </div>
    </div>
@endsection
