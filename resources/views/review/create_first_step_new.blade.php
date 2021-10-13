@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="/assets/js/rateit/rateit.css">
    <link type="text/css" rel="stylesheet" href="/assets/js/toastr/toastr.min.css">
    <link type="text/css" rel="stylesheet" href="/assets/marino/styles/components/load-progress.css">
    <style type="text/css">
        .text-gray {
            color: #818181 !important;
        }
        #loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            border-bottom: 16px solid #3498db;
            width: 120px;
            height: 120px;
            -webkit-animation: spin 2s linear infinite; /* Safari */
            animation: spin 2s linear infinite;

            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;

            display: none;
            z-index:10000000;
        }
        /* Safari */
        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
@endsection
@section('content')
    <div id="loader"></div>

    <div class="container">
        @if (session()->has('message'))
            <div class="alert alert-info">
                <p>{{ session('message') }}</p>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger" style="background-color: #EF0025">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
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
                <h3 class="text-center m-b-0">
                    <span class="f-s-18 text-uppercase f-w-500 text-gray">Video length:</span>
                    <span class="f-s-18" id="video_length"></span>
                </h3>
            </div>
        </div>

        <div class="alert" id="status_block" hidden>
            <button type="button" class="close" onclick="$('#status_block').attr('hidden', 'hidden')"><span aria-hidden="true">&times;</span></button>
            <span id="status"></span>
        </div>


        <div id="video_blk" class="">
            <div class="row" id="user_video">
                <div class="col-xs-12 col-md-10 col-md-offset-1">
                <div align="center" class="embed-responsive embed-responsive-16by9">
                        <video width="728" height="410" id="video_player" >
                            <source src="{{url('/') . config('video.user_video_path') . $video->url}}">
                            Your browser does not support HTML5 video.
                        </video>
                </div>
                </div>
            </div>
            <div class="row" id="coach_review" style="display: none">
                <div class="col-xs-12 col-md-10 col-md-offset-1">
                <div align="center" class="embed-responsive embed-responsive-16by9">
                        <video width="728" height="410" controls id="coach_review_player" class="embed-responsive-item">
                            <source id="review_src" src="">
                            Your browser does not support HTML5 video.
                        </video>
                </div>
                </div>
            </div>
            <div class="row m-t-15">
                <div class="col-md-11 col-md-offset-1">
                <div class="col-md-8" id="control_wrapper">
                        <div class="col-md-3 m-b-10">
                            <button type="button" class="btn btn-black btn-outline m-r-5" id="record_btn" hidden
                                    onclick="toggleRecording(this);" autocomplete="off">
                                <i class="btn-icon fa fa-circle"></i>Start record
                            </button>
                        </div>
                        <div id="control_block" hidden>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-black btn-outline m-r-5" id="video_control_btn"><i class="btn-icon fa fa-play"></i>Play</button>
                            </div>
                        </div>
                </div>
                <div class="col-md-4" id="reload_block" hidden>
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
                <a href="{{route('review.create-second-step', $video->id)}}" id="second_step"
                   class="btn btn-warning m-r-10 m-b-10 disabled">
                    <i class="btn-icon fa fa-check"></i>Report
                </a>

            </div>
        </div>


    </div>

    <div id="progressModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h2 class="text-center">Please wait, video processing...</h2>
                    <br>
                    <div class="load-progress">
                        <div class="load-progress-bar load-progress-bar-striped active bg-danger" role="progressbar" style="width:0%">
                            0%
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            var start_time = 0;
            var stop_flag = false;
            window.play_time = {};
            var video_player = document.getElementById("video_player");
            var coach_review_player = document.getElementById("coach_review_player");
            video_player.load();
            video_player.oncanplaythrough = function() {
                var date = new Date(null);
                date.setSeconds(video_player.duration);
                duration = date.getUTCMinutes() + ":" + (date.getUTCSeconds().toString().length != 1 ? date.getUTCSeconds()
                                : "0" + date.getUTCSeconds());
                document.getElementById('video_length').innerHTML = duration;
                $("#record_btn").removeAttr("hidden");
                initRecordButton();
            };

            video_player.onpause = function() {
                if(start_time && !stop_flag) {
                    var pause_time = new Date();
                    window.play_time[parseInt((pause_time.getTime() - start_time.getTime()))] = "pause";
                    console.log('pause', window.play_time);
                }
            };
            video_player.onplaying = function() {
                if(!start_time){
                    start_time = new Date();
                }else {
                    var play_time = new Date();
                    window.play_time[parseInt((play_time.getTime() - start_time.getTime()))] = "play";
                    console.log('play', window.play_time);
                }
            };
            video_player.onended = function() {
                $("#control_block").hide();
            };
            function initRecordButton() {
                $("#record_btn").click(function() {
                    $("#control_block").removeAttr("hidden");
                    $("#reload_block").removeAttr("hidden");

                    if($(this).hasClass("recording")){
                        $(this).html('<i class="btn-icon fa fa-save"></i>Save record');
                        $("#video_control_btn").html('<i class="btn-icon fa fa-pause"></i>Pause').addClass("play");
                        video_player.play();
                    }else{
                        $("#control_block").hide();
                        console.log('stop', window.play_time);
                        if(start_time){
                            var stop_time = new Date();
                            window.play_time[parseInt((stop_time.getTime() - start_time.getTime()))] = "stop";
                            checkConcatVideoProcess();
                        }
                        stop_flag = true;
                        video_player.pause();
                    }
                });
            }

            $("#video_control_btn").click(function(e){
                $('#video_control_btn').prop('disabled', true);
                $('#record_btn').prop('disabled', true);
                setTimeout(function () {
                    $('#video_control_btn').prop('disabled', false);
                    $('#record_btn').prop('disabled', false);
                },1100);
                if($(this).hasClass("play")){
                    $(this).html('<i class="btn-icon fa fa-play"></i>Play').removeClass("play");
                    video_player.pause();
                }else{
                    $(this).html('<i class="btn-icon fa fa-pause"></i>Pause').addClass("play");
                    video_player.play();
                }
            });

            function checkConcatVideoProcess() {
                $("#progressModal").modal({backdrop: 'static', keyboard: false})
                var checkProgressInterval = setInterval(function () {
                    $.get( "{{route('temp_review.check-progress', $video->id)}}")
                        .done(function( data ) {
                            console.log(data);
                            if(data.ready){
                                $("#progressModal").modal('hide');
                                $('#second_step').removeClass('disabled');
                                $("#user_video").hide();
                                $("#coach_review").show();
                                document.getElementById("review_src").src = data.url;
                                coach_review_player.load();
                                clearInterval(checkProgressInterval);
                            }else{
                                if(data.progress){
                                    $(".load-progress-bar").text(data.progress + '% ');
                                    $(".load-progress-bar").css('width', data.progress + '%');
                                }
                            }
                        });
                }, 2000)
            }
        });
    </script>
    <script src="/assets/js/newAudioRecorder/recorder.js"></script>
    <script src="/assets/js/newAudioRecorder/audio_processor.js"></script>
    <script type="text/javascript" src="/assets/js/toastr/toastr.min.js"></script>
@endsection
