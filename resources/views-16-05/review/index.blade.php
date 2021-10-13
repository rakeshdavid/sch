@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>My reviews</h3>
        <div class="row">
            @if(!empty($reviews))
                <div  class="col-md-8" style="min-width: 760px">
                    @foreach($reviews as $review)
                        <iframe width="728" height="410" src="https://www.youtube.com/embed/{{$review->video_url}}" frameborder="0" allowfullscreen></iframe>

                        <div class="m-b-40" style="width: 728px">
                            <ul class="list-group">
                                <li class="list-group-item">
                                    Video name: {{$review->video_name}}
                                </li>
                                <li class="list-group-item">
                                    Video description: {{$review->video_description}}
                                </li> 
                                <li class="list-group-item">
                                    Genres: {{$review->video_genres}}
                                </li>
                                <li class="list-group-item">
                                    Level: {{$review->video_level}}
                                </li>
                                <li class="list-group-item">
                                     Posted at: {{$review->created_at}}
                                </li>
                            </ul>
                            <a class="btn btn-warning m-r-10 m-b-10 m-t-10" href="/review/show-my/{{$review->video_id}}">Show review</a>
                        </div>
                        <hr>
                    @endforeach
                    @include('pagination.default', ['paginator' => $reviews])
                </div>
            @endif
        </div>
    </div>
@endsection