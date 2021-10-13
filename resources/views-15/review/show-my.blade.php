@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="/assets/js/rateit/rateit.css">
    <link type="text/css" rel="stylesheet" href="/assets/js/sweetalert/sweet-alert.css">
    <style type="text/css">
        .videoWrapper {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 */
            padding-top: 25px;
            height: 0;
        }

        .videoWrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <h1 class="f-s-48 f-w-400 text-uppercase text-danger text-center m-b-20">
            {{ $review->video->user->first_name . ' ' . $review->video->user->last_name }} - Review
        </h1>

        <div class="row m-b-20">
            <div class="col-xs-12">
                <h3 class="text-center m-b-0">
                    <span class="f-s-18 text-uppercase f-w-500 text-gray">Level:</span>
                    <span class="f-s-18">{{ $review->video->performance_level->name }}</span>
                </h3>
                {{--<h3 class="text-center m-b-0">
                    <span class="f-s-18 text-uppercase f-w-500 text-gray">Genre/s:</span>
                    <span class="f-s-18">{{ implode(',', $review->video->activity_genres->lists('name')->all()) }}</span>
                </h3>--}}
                <h3 class="text-center m-b-0">
                    <span class="f-s-18 text-uppercase f-w-500 text-gray">Video length:</span>
                    <span class="f-s-18" id="video_length"></span>
                </h3>
            </div>
        </div>

        <div class="row">

            <div class="col-xs-12 col-md-10 col-md-offset-1">
                <div class="videoWrapper">
                    <div id="player"></div>
                </div>
                <br/>
                <audio controls id="audio_player" hidden>
                    <source src="{{$review->url}}" type="audio/wav">
                    Your browser does not support the audio element.
                </audio>
                <div class="row" id="control_block" hidden>
                    <div class="col-md-12 m-b-10">
                        <button type="button" class="btn btn-black btn-outline m-r-5" id="video_control_btn"><i class="btn-icon fa fa-play"></i>Play</button>
                        <button type="button" class="btn btn-black btn-outline m-r-5" id="video_stop_btn"><i class="btn-icon fa fa-stop"></i>Stop</button>
                    </div>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-12 col-md-10 col-md-offset-1">
                        <div class="row m-t-15">
                            <div class="col-xs-12 col-md-6">
                                <h2 class="text-danger f-s-22 f-w-500 text-uppercase">Profile</h2>
                                <ul class="p-l-20">
                                    <li> <span class="text-uppercase">Location: </span> {{ $review->video->user->location }}</li>
                                    @php
                                        $age = '';
                                        try {
                                            $age = Carbon\Carbon::parse($review->video->user->birthday);
                                            $age = $age->diffInYears(Carbon\Carbon::now());
                                        } catch (\Exception $e) {
                                            $age = '';
                                        }
                                    @endphp
                                    <li> <span class="text-uppercase">Age: </span> {{ $age }}</li>
                                    <li> <span class="text-uppercase">Activity experience </span>(years): {{ $review->video->activity_experience }}</li>
                                    <li> <span class="text-uppercase">Seeking auditions: </span>
                                        <span class="text-capitalize">
                                            {{ str_replace('_', ' ', $review->video->seeking_auditions)}}
                                        </span>
                                    </li>
                                </ul>
                                @if(!is_null($review->performance_level_placement))
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h4 class="f-s-18 f-w-500">
                                                Level: {{ $review->performance_level_placement->name }}</h4>
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h4 class="f-s-18 f-w-500" style="display: inline;">Overall rating: </h4>
                                        <div class="f-s-18 f-w-500 m-r-5" style="display: inline;">
                                            {{ strlen($review->overall_rating) == 1 ? $review->overall_rating . '.0' : $review->overall_rating}}
                                        </div>
                                        <div class="rateit bigstars" data-rateit-value="{{$review->overall_rating}}"
                                             data-rateit-ispreset="true" data-rateit-readonly="true"></div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-xs-12 col-md-6">
                                <h2 class="text-danger f-s-22 f-w-500 text-uppercase">Feedback Summary</h2>
                                <p style="overflow-x: hidden" > {{ $review->message }}</p>
                            </div>
                        </div>
                        <div style="display: none;">
                            <span id="video_id" data-video-id="{{$review->video->id}}">{{$review->video->name}}</span>
                            <span id="user_id" data-user-id="{{$review->video->user_id}}">{{$review->video->user_first_name}}  {{$review->video->user_last_name}}</span>
                        </div>
                    </div>
                </div>
                <hr>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <p class="text-center text-danger f-w-500 f-s-22">TECHNIQUE SCORE</p>
                    <div class="row">
                        <div class="col-xs-12 col-md-4">
                            <span style="display: block" class="m-b-5 capitalize">Timing {{number_format($review->timing,1)}}</span>
                            <div class="rateit pull-right" data-rateit-value="{{$review->timing}}"
                                 data-rateit-ispreset="true" data-rateit-readonly="true"
                                 style="display: block; float: left; width: 100%"></div>
                            <fieldset style="float: left">
                                <blockquote style="display: block; border-left: 1px solid #262932;" class="p-l-10">
                                    Comments: {{ $review->timing_comment ? $review->timing_comment : 'none' }}
                                </blockquote>
                            </fieldset>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <span style="display: block" class="m-b-5 capitalize">Footwork {{number_format($review->footwork,1)}}</span>
                            <div class="rateit pull-right" data-rateit-value="{{$review->footwork}}"
                                 data-rateit-ispreset="true" data-rateit-readonly="true"
                                 style="display: block; float: left; width: 100%"></div>
                            <fieldset style="float: left">
                                <blockquote style="display: block; border-left: 1px solid #262932;" class="p-l-10">
                                    Comments: {{ $review->footwork_comment ? $review->footwork_comment : 'none' }}
                                </blockquote>
                            </fieldset>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <span style="display: block" class="m-b-5 capitalize">Alignment {{number_format($review->alingment,1)}}</span>
                            <div class="rateit pull-right" data-rateit-value="{{$review->alingment}}"
                                 data-rateit-ispreset="true" data-rateit-readonly="true"
                                 style="display: block; float: left; width: 100%"></div>
                            <fieldset style="float: left">
                                <blockquote style="display: block; border-left: 1px solid #262932;" class="p-l-10">
                                    Comments: {{ $review->alingment_comment ? $review->alingment_comment : 'none' }}
                                </blockquote>
                            </fieldset>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <span style="display: block" class="m-b-5 capitalize">balance {{number_format($review->balance,1)}}</span>
                            <div class="rateit pull-right" data-rateit-value="{{$review->balance}}"
                                 data-rateit-ispreset="true" data-rateit-readonly="true"
                                 style="display: block; float: left; width: 100%"></div>
                            <fieldset style="float: left">
                                <blockquote style="display: block; border-left: 1px solid #262932;" class="p-l-10">
                                    Comments: {{ $review->balance_comment ? $review->balance_comment : 'none' }}
                                </blockquote>
                            </fieldset>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <span style="display: block" class="m-b-5 capitalize">focus {{number_format($review->focus,1)}}</span>
                            <div class="rateit pull-right" data-rateit-value="{{$review->focus}}"
                                 data-rateit-ispreset="true" data-rateit-readonly="true"
                                 style="display: block; float: left; width: 100%"></div>
                            <fieldset style="float: left">
                                <blockquote style="display: block; border-left: 1px solid #262932;" class="p-l-10">
                                    Comments: {{ $review->focus_comment ? $review->focus_comment : 'none' }}
                                </blockquote>
                            </fieldset>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <span style="display: block" class="m-b-5 capitalize">precision {{number_format($review->precision,1)}}</span>
                            <div class="rateit pull-right" data-rateit-value="{{$review->precision}}"
                                 data-rateit-ispreset="true" data-rateit-readonly="true"
                                 style="display: block; float: left; width: 100%"></div>
                            <fieldset style="float: left">
                                <blockquote style="display: block; border-left: 1px solid #262932;" class="p-l-10">
                                    Comments: {{ $review->precision_comment ? $review->precision_comment : 'none' }}
                                </blockquote>
                            </fieldset>
                        </div>
                    </div>
                    <hr>
                </div>

                <div class="col-md-12">
                    <p class="text-center text-danger f-w-500 f-s-22 uppercase">Expression</p>
                    <div class="row">
                        <div class="col-xs-12 col-md-3">
                            <span style="display: block" class="m-b-5 capitalize">energy {{number_format($review->energy,1)}}</span>
                            <div class="rateit pull-right" data-rateit-value="{{$review->energy}}"
                                 data-rateit-ispreset="true" data-rateit-readonly="true"
                                 style="display: block; float: left; width: 100%"></div>
                            <fieldset style="float: left">
                                <blockquote style="display: block; border-left: 1px solid #262932;" class="p-l-10">
                                    Comments: {{ $review->energy_comment ? $review->energy_comment : 'none' }}
                                </blockquote>
                            </fieldset>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <span style="display: block" class="m-b-5 capitalize">style {{number_format($review->style,1)}}</span>
                            <div class="rateit pull-right" data-rateit-value="{{$review->style}}"
                                 data-rateit-ispreset="true" data-rateit-readonly="true"
                                 style="display: block; float: left; width: 100%"></div>
                            <fieldset style="float: left">
                                <blockquote style="display: block; border-left: 1px solid #262932;" class="p-l-10">
                                    Comments: {{ $review->style_comment ? $review->style_comment : 'none' }}
                                </blockquote>
                            </fieldset>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <span style="display: block" class="m-b-5 capitalize">creativity {{number_format($review->creativity,1)}}</span>
                            <div class="rateit pull-right" data-rateit-value="{{$review->creativity}}"
                                 data-rateit-ispreset="true" data-rateit-readonly="true"
                                 style="display: block; float: left; width: 100%"></div>
                            <fieldset style="float: left">
                                <blockquote style="display: block; border-left: 1px solid #262932;" class="p-l-10">
                                    Comments: {{ $review->creativity_comment ? $review->creativity_comment : 'none' }}
                                </blockquote>
                            </fieldset>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <span style="display: block" class="m-b-5 capitalize">interpretation {{number_format($review->interpretation,1)}}</span>
                            <div class="rateit pull-right" data-rateit-value="{{$review->interpretation}}"
                                 data-rateit-ispreset="true" data-rateit-readonly="true"
                                 style="display: block; float: left; width: 100%"></div>
                            <fieldset style="float: left">
                                <blockquote style="display: block; border-left: 1px solid #262932;" class="p-l-10">
                                    Comments: {{ $review->interpretation_comment ? $review->interpretation_comment : 'none' }}
                                </blockquote>
                            </fieldset>
                        </div>
                    </div>
                    <hr>
                </div>

                <div class="col-md-12">
                    <p class="text-center text-danger f-w-500 f-s-22 uppercase">Choreography</p>
                    <div class="row">
                        <div class="col-xs-12 col-md-4">
                            <span style="display: block" class="m-b-5 f-s-18 capitalize">formation {{number_format($review->formation,1)}}</span>
                            <div class="rateit pull-right" data-rateit-value="{{$review->formation}}"
                                 data-rateit-ispreset="true" data-rateit-readonly="true"
                                 style="display: block; float: left; width: 100%"></div>
                            <fieldset style="float: left">
                                <blockquote style="display: block; border-left: 1px solid #262932;" class="p-l-10">
                                    Comments: {{ $review->formation_comment ? $review->formation_comment : 'none' }}
                                </blockquote>
                            </fieldset>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <span style="display: block" class="m-b-5 f-s-18 capitalize">artistry {{number_format($review->formation,1)}}</span>
                            <div class="rateit pull-right" data-rateit-value="{{$review->artisty}}"
                                 data-rateit-ispreset="true" data-rateit-readonly="true"
                                 style="display: block; float: left; width: 100%"></div>
                            <fieldset style="float: left">
                                <blockquote style="display: block; border-left: 1px solid #262932;" class="p-l-10">
                                    Comments: {{ $review->artisty_comment ? $review->artisty_comment : 'none' }}
                                </blockquote>
                            </fieldset>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
            @if($review->video->questions->count())
                <div class="xol-xs-12">
                    <div class="row">
                        <h3 class="text-danger text-uppercase f-s-22 f-w-500 text-center m-b-15">
                            Questions and answers
                        </h3>
                        @foreach($review->video->questions as $QA)
                            <div style="overflow-x: hidden" class="col-xs-12 col-md-4">
                                <strong>{{ $QA->question }}</strong>
                                <p>{{ $QA->answer }}</p>
                            </div>
                        @endforeach
                    </div>
                    <hr>
                </div>
            @endif
            <div class="row">
                <div class="col-xs-12">
                    <h4 class="f-s-22 uppercase text-danger f-w-500 m-b-15">
                        Additional tips for {{ $review->video->user->first_name }}
                    </h4>
                    <p class="card-text" style="word-break:break-all;">
                        {{ $review->additional_tips  }}
                    </p>
                </div>
            </div>
            <hr>

            <div class="row">
                <div class="col-xs-12">
                    <a href="{{url('/')}}/review/create/{{$video_id}}">
                        <button class="btn btn-danger m-r-10 m-b-10" id="location_reload">
                            <i class="btn-icon fa fa-check"></i>Review again
                        </button>
                    </a>

                    @if ($video_status != 3)
                    <button class="btn btn-success m-r-10 m-b-10" id="approve_video" video_id="{{$video_id}}">
                        <i class="btn-icon fa fa-check"></i>Submit Review
                    </button>
                    @endif

                </div>
            </div>

            {{--<div class="row">
                <div class="col-xs-12">
                        <button class="btn btn-success m-r-10 m-b-10" id="location_reload">
                            <i class="btn-icon fa fa-check"></i>Approve video!
                        </button>
                </div>
            </div>--}}

        </div>

    </div>
<div id="js_str" hidden>{{$review->play_time}}</div>
@endsection

@section('js')
    <script type="text/javascript" src="/assets/js/sweetalert/sweet-alert.min.js"></script>
    <script>
        var tag = document.createElement('script');
        var play_time = JSON.parse($('#js_str').text());
        var audio_player = document.getElementById("audio_player");
        var timer, 
            pause_flag = false,
            play_flag = false,
            video_hash = false;
        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        var player;
        function onYouTubeIframeAPIReady() {
          player = new YT.Player('player', {
            height: '390',
            width: '640',
            videoId: '{{$review->video->url}}',
            events: {
              'onReady': onPlayerReady,
              'onStateChange': onPlayerStateChange
            },
            playerVars: { 
                'autoplay': 0,
                'controls': 0, 
                'rel' : 0,
                'modestbranding' : 1,
                'showinfo' : 0
            }
          });
        }


        $("#video_control_btn").click(function(e){
            if($(this).hasClass("play")){
                $(this).html('<i class="btn-icon fa fa-play"></i>Play').removeClass("play");
                pauseVideo();
                audio_player.pause();
            }else{
                $(this).html('<i class="btn-icon fa fa-pause"></i>Pause').addClass("play");
                playVideo();
                audio_player.play();
            }
        });
        
        $("#video_stop_btn").click(function(){
            $("#video_control_btn").html('<i class="btn-icon fa fa-play"></i>Play').removeClass("play");
            stopVideo();
            audio_player.pause();
            audio_player.currentTime = 0;
        });

        function onPlayerReady(event){
            player.mute();
            player.setVolume(0);
            //$("#control_block").removeAttr("hidden");
            var duration = event.target.getDuration();
            var date = new Date(null);
            date.setSeconds(duration);
            duration = date.getUTCMinutes() + ":" + (date.getUTCSeconds().toString().length != 1 ? date.getUTCSeconds()
                            : "0" + date.getUTCSeconds());
            document.getElementById('video_length').innerHTML = duration;
            player.playVideo();
            player.addEventListener("onStateChange", function(e) {
                if(e.data === 1 && !video_hash) {
                    video_hash = true;
                    player.pauseVideo();
                    player.seekTo(0);
                    $("#control_block").removeAttr("hidden");
                }
            });
        }

        function onPlayerStateChange(event) {
            if ((event.data === YT.PlayerState.PLAYING) && video_hash) {
                if(!timer){
                    showTime()
                }
                if (!play_flag && audio_player.duration > 0 && audio_player.paused) {
                    audio_player.play();
                }
                play_flag = false;
            }
            if ((event.data === YT.PlayerState.PAUSED) && video_hash) {
                if (!pause_flag && audio_player.duration > 0 && !audio_player.paused) {
                    audio_player.pause();
                }
                pause_flag = false;
            }
            if (event.data === YT.PlayerState.ENDED) {
                $("#video_control_btn").html('<i class="btn-icon fa fa-play"></i>Play');
            }
        }
        function playVideo() {
            play_flag = true;
            player.playVideo();
        }
        
        function pauseVideo() {
            pause_flag = true;
            player.pauseVideo();
        }
        
        function stopVideo() {
            player.stopVideo();
        }
//        $("#audio_player").on('play', function(){
//            playVideo();
//        });
//    
//        $("#audio_player").on('pause', function(){
//            pauseVideo();
//        });
        function showTime(){
            timer = setInterval(function(){
                var i = parseInt(audio_player.currentTime);
                if(play_time[i] && i > 0){
                    if(play_time[i] == "pause"){
                        pauseVideo();
                    }
                    if(play_time[i] == "play"){
                        playVideo();
                    }
                    if(play_time[i] == "stop"){
                        stopVideo();
                    }
                }
            }, 1000);
        }

    </script>
    <script src="/assets/js/rateit/jquery.rateit.js"></script>
@endsection
