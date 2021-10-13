@extends('layouts.app')

@section('content')
    <div class="row m-b-20">
        <div class="col-xs-12">
            <h3>Reviews</h3>
        </div>
    </div>
    <div class="row m-b-20">
        <div class="col-xs-12">
            <div class="m-b-40">
                <div class="activity-widget-5">
                    <div class="row">
                        <div class="col-xs-12 bs-media">
                            @foreach($proposals as $proposal)
                                <div class="media">
                                    <a class="media-left media-middle">
                                        <img class="media-object img-circle h-40 w-40" alt="/assets/faces/m1.png" src="{{$proposal->user_avatar}}">
                                    </a>
                                    <div class="media-body">
                                        <h5 class="media-heading"> {{$proposal->user_first_name}} {{$proposal->user_last_name}} </h5> 
                                        <p>{{$proposal->description}}</p>
                                        <p class="text-muted">{{$proposal->created_at}}</p> 
                                        @if($proposal->status == 0)
                                            <button class="btn btn-default btn-accept-proposal" data-video_id="{{$video_id}}" data-proposal-id="{{$proposal->id}}">Accept</button>
                                            <button class="btn btn-default btn-deny-proposal" data-video_id="{{$video_id}}" data-proposal-id="{{$proposal->id}}">Deny</button>
                                        @else
                                            <p class="text-muted">Status: @if($proposal->status == 1) Accept @elseif($proposal->status == 2) Deny @endif</p>
                                        @endif
                                    </div> 
                                </div> 
                            @endforeach
                            @include('pagination.default', ['paginator' => $proposals])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection