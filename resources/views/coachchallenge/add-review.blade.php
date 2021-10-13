@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="/assets/js/rateit/rateit.css">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<style type="text/css">
.challenge-review-section .coach-list h3 { text-align: center; margin-bottom: 20px; font-size: 24px;color: #21262F;font-weight: 900;}
.challenge-review-section h3 {  margin-bottom: 20px; font-size: 24px;color: #21262F;font-weight: 900; }
textarea{width: 100%;padding: 10px;}
@media(max-width: 767px) { .form-group { margin-top: 1rem; } }
</style>
	<link type="text/css" rel="stylesheet" href="/assets/js/toastr/toastr.min.css">
   <link type="text/css" rel="stylesheet" href="/assets/js/sweetalert/sweet-alert.css">
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
<div class="main-content auditions-wrap challenge-review-section">
    <div class="container-fluid">
        <div class="row">
	        <div class="col-md-12">
	            <div class="coach-list mb-4">
	                <h3>Review Participant Video</h3>
	            </div>
	            @if(session()->has('message'))
				    <div class="alert alert-success">
				        {{ session()->get('message') }}
				        <script>
                            setTimeout(function(){ 
                                window.location = "/challenge-review-edit/{{ $participant_detail->id}}";
                            }, 3000);
                        </script>
				    </div>
				@endif
				@if ($errors->any())
				    <div class="alert alert-danger">
				        <ul>
				            @foreach ($errors->all() as $error)
				                <li>{{ $error }}</li>
				            @endforeach
				        </ul>
				    </div>
				@endif
				@if(session()->has('error'))
				    <div class="alert alert-danger">
				        {{ session()->get('error') }}
				    </div>
				@endif
	        </div>
	        <form method="post" enctype="multipart/form-data" onsubmit="return saveReview(this)">
	        	{!! csrf_field() !!}
	        	<input type="hidden" name="participant_id" value="{{$participant_id}}" />
	        	<input type="hidden" name="performer-name" value="{{$participant_detail->user->first_name}}" />
		        <div class="col-md-12">
		        	<div class="row">
			        	<div class="col-lg-8 col-lg-offset-2">
		                    <div class="card row" id="user_video">
		                        
		                            <div class="videos-box">

		                                @if(strpos($participant_detail->video_link, 'youtube') !== false)

		                                    @php
		                                    $query ="";
		                                        $parts = parse_video_link($participant_detail->video_link);
		                                        if(array_key_exists('query',$parts)){
		                                            parse_str($parts['query'], $query);
		                                            $participant_detailID = $query['v'];
		                                            $youtubevideo_link = "https://www.youtube.com/ embed/".$participant_detailID; 
		                                        }else{
		                                            $youtubevideo_link = $participant_detail->video_link; 
		                                        }
		                                        
		                                        
		                                    @endphp
		                                   
		                                @else
		                               <video id="video_player" width="100%" height="300" controls >
		                                    <source src="{{asset('uploads/challenge/').'/'. $participant_detail->video_link}}">
		                                    Your browser does not support HTML5 video.
		                                </video>
		                                    
		                                @endif
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
		                    <h3 class="p-20 text-center">{{$participant_detail->user->first_name}}</h3>
		                    <h3 class="text-center m-b-0">
			                    <span class="f-s-18 text-uppercase f-w-500 text-gray">Video length:</span>
			                    <span class="f-s-18" id="video_length"></span>
			                </h3>
		                </div>
		                
		        </div>
		        <div style="display: none;">
		            <span id="video_id" data-video-id="{{$participant_detail->id}}">{{$participant_detail->user->first_name}}</span>
		            <span id="user_id" data-user-id="{{$participant_detail->user_id}}">{{$participant_detail->user->first_name}}</span>
		        </div>
		        <div class="row m-t-15">
                <div class="col-md-11 col-md-offset-1">
                <div class="col-md-8" id="control_wrapper">
                        <div class="col-md-3 m-b-10">
                            <button type="button" class="btn btn-black btn-outline m-r-5" id="record_btn"  onclick="toggleRecording(this);" autocomplete="off">
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
                        <a class="btn btn-danger" href="{{route('challengereview.rewrite', $participant_detail->id)}}">
                            <i class="btn-icon fa fa-check"></i>Re-record video
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
		        <div class="row" id="review-rating" style="display: none;">
		        	<div class="col-md-12">
                        <div class="right-box "><br />
                            <label class="font-weight-bold">Performace level placement</label><br/>
                            <select class="form-control" name="level-placement">
                            	<option value="1">Beginner</option>
                            	<option value="2">Intermediate</option>
                            	<option value="3">Advanced</option>
                            </select>
                        </div> 
                            
                    </div> 
                     <div class="col-xs-12"><hr></div>
	                <div class="col-md-12">
	                    <p class="text-center text-danger f-w-500">SCORE</p>
	                    <div class="col-xs-12 col-md-6 col-lg-4 m-b-15">
	                        <div class="row">
	                            <div class="col-xs-12">
	                                <span>Performance Quality</span>
	                                <span class="rateit-value"></span>
	                                <div class="rateit pull-right bigstars" data-rateit-span-id="1" data-rateit-name="pq-rating" data-rateit-min="0" data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0" data-rateit-value="" ></div>
	                                <input type="hidden" name="pq-rating" value="0" /> 
	                                <fieldset>
	                                    <small class="m-b-0">Comment</small>
	                                    <input class="form-control comment" type="text" name="performance-quality" autocomplete="off" value="" />
	                                </fieldset>
	                            </div>	                           
	                        </div>
	                    </div>
	                    <div class="col-xs-12 col-md-6 col-lg-4 m-b-15">
	                        <div class="row">
	                            <div class="col-xs-12">
	                                <span>Technical Ability</span>
	                                <span class="rateit-value"></span>
	                                <div class="rateit pull-right bigstars" data-rateit-span-id="1" data-rateit-name="ta-rating" data-rateit-min="0" data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0" data-rateit-value="" ></div>
									<input type="hidden" name="ta-rating" value="0" />
	                                <fieldset>
	                                    <small class="m-b-0">Comment</small>
	                                    <input class="form-control comment" type="text" name="technical-ability" autocomplete="off" value="{{old('footwork-comment')}}" />
	                                </fieldset>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col-xs-12 col-md-6 col-lg-4 m-b-15">
	                        <div class="row">	                      
	                            <div class="col-xs-12">
	                                <span>Energy and Style</span>
	                                <span class="rateit-value"></span>
	                                <div class="rateit pull-right bigstars" data-rateit-span-id="1" data-rateit-name="es-rating" data-rateit-min="0" data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0" data-rateit-value="" ></div>
	                                <input type="hidden" name="es-rating" value="0" />
	                                <fieldset>
	                                    <small class="m-b-0">Comment</small>
	                                    <input class="form-control comment" type="text" name="energy-and-style" autocomplete="off" value="" />
	                                </fieldset>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col-xs-12 col-md-6 col-lg-4 m-b-15">
	                        <div class="row">
	                            <div class="col-xs-12">
	                                <span>Storytelling</span>
	                                <span class="rateit-value"></span>
	                                <div class="rateit pull-right bigstars" data-rateit-span-id="1" data-rateit-name="storytelling-rating" data-rateit-min="0" data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0" data-rateit-value="" ></div>
					                <input type="hidden" name="storytelling-rating" value="0" />
	                                <fieldset>
	                                    <small class="m-b-0">Comment</small>
	                                    <input class="form-control comment" type="text" name="storytelling" autocomplete="off" value="" />
	                                </fieldset>
	                            </div>	                            
	                        </div>
	                    </div>
	                    <div class="col-xs-12 col-md-6 col-lg-4 m-b-15">
	                        <div class="row">
	                            <div class="col-xs-12">
	                                <span>Look and Appearance</span>
	                                <span class="rateit-value"></span>
	                                <div class="rateit pull-right bigstars" data-rateit-span-id="1" data-rateit-name="la-rating" data-rateit-min="0" data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0" data-rateit-value="" ></div>
					                <input type="hidden" name="la-rating" value="0" />
	                                
	                                <fieldset>
	                                    <small class="m-b-0">Comment</small>
	                                    <input class="form-control comment" type="text" name="look-and-appearance" autocomplete="off" value="" />
	                                </fieldset>
	                            </div>
	                        </div>
	                    </div>


	                   
	                    <div class="col-xs-12"><hr></div>
	                </div>

	      
                    <div class="col-md-12">
                        <div class="right-box "><br />
                            <label class="font-weight-bold">Notes</label><br/>
                            <textarea name="feedback" class="summernote"></textarea>
                        </div>       
                    </div>
                    <div class="form-row mt-5">
	                	<div class="form-group">
	                		<div class="col-md-12">
	                			<input type="submit" id="submitForm" name="audtion" value="Add Review" class="btn btn-danger" />
	                		</div>
	                	</div>
	                </div> 
	            </div>
		        
		    </form>
	    </div>
	</div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script type="text/javascript" src="/assets/js/sweetalert/sweet-alert.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
  		// $('.summernote').summernote({
    //     placeholder: '',
    //     tabsize: 2,
    //     height: 120,
    //     toolbar: [
    //       ['style', ['style']],
    //       ['font', ['bold', 'underline', 'clear']],
    //       ['color', ['color']],
    //       ['para', ['ul', 'ol', 'paragraph']],
    //       ['table', ['table']],
    //       ['insert', ['link']],
    //       ['view', ['fullscreen', 'codeview', 'help']]
    //     ]
    //   });
  		var dateformat = 'yyyy-mm-dd';

        $('.hasDatepicker').datepicker({
          format: dateformat,
          autoclose: true
        });
   	$(".rateit").bind('rated', function (event, value) {
            var rate = $(this);
            /*insert star rating value*/
            rate.closest('.row').find('.rateit-value').html(value.toFixed(1));
            if(value === null){
                value = 0;
            }
            rate.attr("data-rateit-value", value);
            var name = rate.attr("data-rateit-name");
            //ratings[name] = value;
            console.log(value);
            console.log(name);
            $('input[name="'+name+'"]').val(value);
//            $('#rate_' + rate.attr("data-rateit-span-id")).text(value);
        });
   
	});
   function saveReview(form){
      var formData = new FormData(form);
      $.ajax({
         method: "POST",
         url: "{{url('challenge-review-new')}}/{{$participant_id}}",
         processData: false,
         contentType: false,
         data: formData,
         dataType: 'json',
         beforeSend: function () {
            $("#submitForm").prop('disabled',true);
         },
         success: function(data){
            if(data.status == 'val_error'){ //field validation
                  $.each(data.errors, function(i, val){
                     toastr.options = {
                        "closeButton": true,
                        "positionClass": "toast-bottom-left",
                        "onclick": null,
                        "showDuration": "1000",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                     };
                     toastr.error(val, "Error");
                  });
            } else {
                  if( data.status == "success"){
                     swal({
                           title: "Success!",
                           text: "Review successfully added",
                           type: "success"
                     }, function() {
                        window.location.href = "/challenge-review-edit/{{$participant_id}}";
                     });
                  }else{
                     swal('Error!',data.message,'error');
                  }
            }
            $("#submitForm").prop('disabled',false);
         },
         error: function () {
            swal("Error!", "Oops something happens.", "error");
            $("#submitForm").prop('disabled',false);
         },
      });
      return false;
   }
</script>
 <script src="/assets/js/rateit/jquery.rateit.js"></script>
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
                    $.get( "{{route('temp_challenge_review.check-progress', $participant_detail->id)}}")
                        .done(function( data ) {
                            console.log(data);
                            if(data.ready){
                                $("#progressModal").modal('hide');
                                $('#second_step').removeClass('disabled');
                                $("#user_video").hide();
                                $("#coach_review").show();
                                $("#review-rating").show();
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
    <script src="/assets/js/newAudioRecorder/ac_audio_processor.js?test"></script>
    <script type="text/javascript" src="/assets/js/toastr/toastr.min.js"></script>
@endsection