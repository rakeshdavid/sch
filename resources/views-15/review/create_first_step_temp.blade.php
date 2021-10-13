@extends('layouts.app')
@section('content')
    <div class="container">
        <h1 class="f-s-48 color-danger text-center f-w-500 m-b-15 text-uppercase">
            <a href="/profile/{{ $video->user->id }}" target="_blank" style="color: #d9534f !important">
                {{ $video->user->first_name . ' ' . $video->user->last_name }}
            </a>
        </h1>
        <div class="row m-b-20">
            <div class="col-xs-12">
                <h3 class="text-center m-b-0">
                    <span class="f-s-18 text-uppercase f-w-500 text-gray">Level:</span>
                    <span class="f-s-18">{{ $video->performance_level->name }}</span>
                </h3>
            </div>
        </div>
        <div id="video_blk" class="">
            <div class="row" id="coach_review" >
                <div class="col-xs-12 col-md-10 col-md-offset-1">
                <div align="center" class="embed-responsive embed-responsive-16by9">
                        <video width="728" height="410" controls id="coach_review_player" class="embed-responsive-item">
                            <source id="review_src"
                                    src="{{url('/') . config('video.temp_review_path') . $video->temporary_review->review_url}}">
                            Your browser does not support HTML5 video.
                        </video>
                </div>
                </div>
            </div>
            <div class="row m-t-15">
                <div class="col-xs-12 col-md-10 col-md-offset-1">
                <div class="col-md-12" id="reload_block">
                        <a class="btn btn-danger" href="{{route('review.rewrite', $video->id)}}">
                            <i class="btn-icon fa fa-check"></i>Re-record video
                        </a>
                </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-10 col-md-offset-1">
                    <div class="row m-t-15">
                        <div class="col-xs-12 col-md-6">
                            <h2 class="text-danger f-s-22 f-w-500 text-uppercase">Profile</h2>
                            <ul class="p-l-25">
                                <li> <span class="text-uppercase">Location: </span> {{ $video->user->location }}</li>
                                @php
                                    $age = '';
                                    try {
                                        $age = Carbon\Carbon::parse($video->user->birthday);
                                        $age = $age->diffInYears(Carbon\Carbon::now());
                                    } catch (\Exception $e) {
                                        $age = '';
                                    }
                                @endphp
                                <li> <span class="text-uppercase">Age: </span> {{ $age }}</li>
                                <li> <span class="text-uppercase">Activity experience </span>(years): {{ $video->activity_experience }}</li>
                                <li> <span class="text-uppercase">Seeking auditions: </span>
                                    <span class="text-capitalize">
                                        {{ str_replace('_', ' ', $video->seeking_auditions)}}
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <h2 class="text-danger f-s-22 f-w-500 text-uppercase">Feedback Summary</h2>
                            <p style="overflow-x: hidden" > {{ $video->description }}</p>
                        </div>
                    </div>
                    <div style="display: none;">
                        <span id="video_id" data-video-id="{{$video->id}}">{{$video->name}}</span>
                        <span id="user_id" data-user-id="{{$video->user_id}}">{{$video->user_first_name}}  {{$video->user_last_name}}</span>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div style="text-align: center;" class="col-xs-12">
                <a href="{{route('review.create-second-step', $video->id)}}" class="btn btn-warning m-r-10 m-b-10">
                    <i class="btn-icon fa fa-check"></i>Report
                </a>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready(function () {

        })
    </script>
@endsection
