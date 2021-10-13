@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @foreach($proposals as $proposal)
                    <p>Video name: {{$proposal->video_name}}</p>
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/{{$proposal->video_url}}" frameborder="0" allowfullscreen></iframe>
                    <p>Video description: {{$proposal->video_description}}</p>
                    <p>Posted date: {{$proposal->video_created_at}}</p>
                    <p>Video added by : <a href="/profile/{{$proposal->user_id}}">{{$proposal->user_first_name}} {{$proposal->user_last_name}}</a></p>
                    <p>Review: {{$proposal->description}}</p>
                    <p>Review date: {{$proposal->created_at}}</p>
                    @if($proposal->status == 0)
                        
                    @else
                        <p class="text-muted">Status: @if($proposal->status == 1) Accept @elseif($proposal->status == 2) Deny @endif</p>
                    @endif
                    @if($proposal->status == 1)
                        <a href="/review/create/{{$proposal->video_id}}" class="btn btn-default">Add review</a>
                    @endif
                    <hr>
                @endforeach
                @include('pagination.default', ['paginator' => $proposals])
            </div>
        </div>
    </div>
@endsection