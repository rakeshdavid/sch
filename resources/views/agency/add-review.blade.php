@extends('layouts.agency')

@section('css')
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/js/rateit/rateit.css">
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
    <style>
    	textarea{width: 100%;padding: 10px;height: 100px;}
    </style>
@endsection
@section('content')
<div class="main-content auditions-wrap">
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
                                window.location = "/update-review/{{$participant_id}}";
                            }, 3000);
                        </script>
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
				 @if(session()->has('error'))
				    <div class="alert alert-danger">
				        {{ session()->get('error') }}
				    </div>
				@endif
	        </div>
	        <div id="progressModal" class="modal fade" role="dialog">
		        <div class="modal-dialog">
		            <div class="modal-content">
		                <div class="modal-body">
		                    <h4 class="text-center">Please wait, video processing...</h4>
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
	        <div class="center-form">
		        <form id="addAuditionForm" action="{{url('audition-review-new')}}/{{$participant_id}}" method="post" enctype="multipart/form-data" onsubmit="return saveAudition(this)" >
		        	{!! csrf_field() !!}
		        	<input type="hidden" name="participant_id" value="{{$participant_id}}" />
		        	<input type="hidden" name="audition_id" value="{{$participant_detail->audition->id}}" />
			        <div class="col-md-10 col-xl-8 video-card">
			        	<div class="row">
				        	<div class="col-lg-12">
			                    <div class="card">
			                        <div class="card">
			                            <div class="videos-box" id="user_video">

			                                @if(strpos($participant_detail->video_link, 'youtube') !== false)

			                                    @php
			                                    $query ="";
			                                        $parts = parse_url($participant_detail->video_link);
			                                        if(array_key_exists('query',$parts)){
			                                            parse_str($parts['query'], $query);
			                                            $participant_detailID = $query['v'];
			                                            $youtubevideo_link = "https://www.youtube.com/embed/".$participant_detailID; 
			                                        }else{
			                                            $youtubevideo_link = $participant_detail->video_link; 
			                                        }
			                                        
			                                        
			                                    @endphp
			                                    <iframe width="100%" height="301" src="{{$youtubevideo_link}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			                                   
			                                @else
			                               <video id="video_player" width="100%" height="300" controls >
			                                    <source src="{{asset('uploads/auditions')}}/{{$participant_detail->video_link}}">
			                                    Your browser does not support HTML5 video.
			                                </video>
			                                    
			                                @endif
			                            </div>
			                            <div class="row" id="coach_review" style="display: none">
							                <div class="col-xs-12 col-md-12">
							                <div align="center" class="embed-responsive embed-responsive-16by9 videos-box">
							                        <video width="100%" height="300" controls id="coach_review_player" class="embed-responsive-item ">
							                            <source id="review_src" src="">
							                            Your browser does not support HTML5 video.
							                        </video>
							                </div>
							                </div>
							            </div>
			                            <div class="card-body">
			                                <h3>{{$participant_detail->user->first_name}}</h3>
			                                <h3 class="text-center m-b-0">
							                    <span class="f-s-18 text-uppercase f-w-500 text-gray">Video length:</span>
							                    <span class="f-s-18" id="video_length"></span>
							                </h3>
			                            </div>

			                        </div>
			                    </div>
			                </div>
			                <div style="display: none;">
					            <span id="video_id" data-video-id="{{$participant_detail->id}}">{{$participant_detail->user->first_name}}</span>
					            <span id="user_id" data-user-id="{{$participant_detail->user_id}}">{{$participant_detail->user->first_name}}</span>
					        </div>
					        <div class="col-md-12">
				                <div class="row mt-4">
				                <div class="col-md-8" id="control_wrapper">
				                	<div class="row">
				                        <div class="col-md-6 m-b-10">
				                            <button type="button" class="btn btn-danger" id="record_btn"  onclick="toggleRecording(this);" autocomplete="off" style="width: 170px">
				                                <i class="btn-icon fa fa-circle"></i>  Start record
				                            </button>
				                        </div>
				                        <div id="control_block" class="col-md-6" hidden>
				                            <div>
				                                <button type="button" class="btn btn-danger" id="video_control_btn" style="width: 150px;"><i class="btn-icon fa fa-play"></i>Play</button>
				                            </div>
				                        </div>
				                    </div>
				                </div>
				                <div class="col-md-4" id="reload_block" hidden>
				                        <a class="btn btn-danger" href="{{route('auditionreview.rewrite', $participant_detail->id)}}">
				                            <i class="btn-icon fa fa-check"></i>Re-record video
				                        </a>
				                </div>
				                </div>
				            </div>
			                <div id="review-rating" class="col-md-12 mt-5" style="display: none;">
			                	<h3>Add Your Review</h3>
								<div id="accordion" class="accordion">
                           <input type="hidden" name="performer-name" class="form-control" value="{{$participant_detail->user->first_name}}" />
	                            <div class="card">
	                                <div class="card-header" id="headingTwo">
	                                    <a href="#" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo"> Performance Quality </a>
	                                </div>
	                                
	                                
	                                <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordion">
	                                    <div class="card-body">
	                                        <div class="form-row">
						                        <div class="form-group col-sm-12 ">
						                          	<div class="right-box">
						                               <label class="font-weight-bold">Rating</label>
						                              
						                               <div class="rateit pull-right bigstars" data-rateit-span-id="1" data-rateit-name="pq-rating" data-rateit-min="0" data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0" data-rateit-value="" ></div>
													<input type="hidden" name="pq-rating" value="0" />
						                            </div>
						                        </div>
						                        <div class="form-group col-sm-12">
						                            <div class="right-box ">
						                                <label class="font-weight-bold">Note</label><br/>
						                                <textarea name="performance-quality" class="summernote"></textarea>
						                            </div>       
						                        </div> 
						                    </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="card">
	                                <div class="card-header" id="headingThree">
	                                    <a href="#" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree"> Technical Ability </a>

	                                </div>
	                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
	                                    <div class="card-body">
	                                        <div class="card-body">
		                                        <div class="form-row">
							                        <div class="form-group col-sm-12 ">
							                          	<div class="right-box">
							                               <label class="font-weight-bold">Rating</label>
							                               
						                               <div class="rateit pull-right bigstars" data-rateit-span-id="1" data-rateit-name="ta-rating" data-rateit-min="0" data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0" data-rateit-value="" ></div>
														<input type="hidden" name="ta-rating" value="0" />
							                            </div>
							                        </div>
							                        <div class="form-group col-sm-12">
							                            <div class="right-box ">
							                                <label class="font-weight-bold">Note</label><br/>
							                                <textarea name="technical-ability" class="summernote"></textarea>
							                            </div>       
							                        </div> 
							                    </div>
							                </div>
	                                    </div>
	                                </div>
	                            </div>
	                            
	                            <div class="card">
	                                <div class="card-header" id="headingThree">
	                                    <a href="#" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse4" aria-expanded="false" aria-controls="collapse4"> Energy and Style </a>

	                                </div>
	                                <div id="collapse4" class="collapse" aria-labelledby="heading4" data-parent="#accordion">
	                                    <div class="card-body">
	                                        <div class="card-body">
		                                        <div class="form-row">
							                        <div class="form-group col-sm-12 ">
							                          	<div class="right-box">
							                               	<label class="font-weight-bold">Rating</label>
							                               
						                               		<div class="rateit pull-right bigstars" data-rateit-span-id="1" data-rateit-name="es-rating" data-rateit-min="0" data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0" data-rateit-value="" ></div>
															<input type="hidden" name="es-rating" value="0" />
							                            </div>
							                        </div>
							                        <div class="form-group col-sm-12">
							                            <div class="right-box ">
							                                <label class="font-weight-bold">Note</label><br/>
							                                <textarea name="energy-and-style" class="summernote"></textarea>
							                            </div>       
							                        </div> 
							                    </div>
							                </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="card">
	                                <div class="card-header" id="heading5">
	                                    <a href="#" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse5" aria-expanded="false" aria-controls="collapse5"> Storytelling </a>

	                                </div>
	                                <div id="collapse5" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
	                                    <div class="card-body">
	                                        <div class="card-body">
		                                        <div class="form-row">
							                        <div class="form-group col-sm-12 ">
							                          	<div class="right-box">
							                               <label class="font-weight-bold">Rating</label>
							                               
						                               		<div class="rateit pull-right bigstars" data-rateit-span-id="1" data-rateit-name="storytelling-rating" data-rateit-min="0" data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0" data-rateit-value="" ></div>
						                               		<input type="hidden" name="storytelling-rating" value="0" />

							                            </div>
							                        </div>
							                        <div class="form-group col-sm-12">
							                            <div class="right-box ">
							                                <label class="font-weight-bold">Note</label><br/>
							                                <textarea name="storytelling" class="summernote"></textarea>
							                            </div>       
							                        </div> 
							                    </div>
							                </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="card">
	                                <div class="card-header" id="heading6">
	                                    <a href="#" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse6" aria-expanded="false" aria-controls="collapse6"> Look and Appearance </a>

	                                </div>
	                                <div id="collapse6" class="collapse" aria-labelledby="heading6" data-parent="#accordion">
	                                    <div class="card-body">
	                                        <div class="card-body">
		                                        <div class="form-row">
							                        <div class="form-group col-sm-12 ">
							                          	<div class="right-box">
							                               	<label class="font-weight-bold">Rating</label>
							                               
						                               		<div class="rateit pull-right bigstars" data-rateit-span-id="1" data-rateit-name="la-rating" data-rateit-min="0" data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0" data-rateit-value="" ></div>
						                               		<input type="hidden" name="la-rating" value="0" />

							                            </div>
							                        </div>
							                        <div class="form-group col-sm-12">
							                            <div class="right-box ">
							                                <label class="font-weight-bold">Note</label><br/>
							                                <textarea name="look-and-appearance" class="summernote"></textarea>
							                            </div>       
							                        </div> 
							                    </div>
							                </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="card">
	                                <div class="card-header" id="heading7">
	                                    <a href="#" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse7" aria-expanded="false" aria-controls="collapse7"> Notes </a>

	                                </div>
	                                <div id="collapse7" class="collapse" aria-labelledby="heading7" data-parent="#accordion">
	                                    <div class="card-body">
	                                        <div class="card-body">
		                                        <div class="form-row">
							                        
							                        <div class="form-group col-sm-12">
							                            <div class="right-box ">
							                                <label class="font-weight-bold">Notes</label><br/>
							                                <textarea name="feedback" class="summernote"></textarea>
							                            </div>       
							                        </div> 
							                    </div>
							                </div>
	                                    </div>
	                                </div>
	                            </div>
			                </div>
				            <div class="form-row mt-5">
			                	<div class="form-group">
			                		<div class="col-md-12">
			                			<input type="submit" id="submitAudition" name="audtion" value="Add Review" class="btn btn-danger" />
			                		</div>
			                	</div>
			                </div>   
		            	</div>
			        </div>
			        
			    </form>
			</div>
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
                value = '';
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
      function saveAudition(form){
         var formData = new FormData(form);
        $.ajax({
            method: "POST",
            url: "{{url('audition-review-new')}}/{{$participant_id}}",
            processData: false,
            contentType: false,
            data: formData,
            dataType: 'json',
            beforeSend: function () {
               $("#submitAudition").prop('disabled',true);
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
                           window.location.href = "/update-review/{{$participant_id}}";
                        });
                    }else{
                         swal('Error!',data.message,'error');
                     }
               }
               $("#submitAudition").prop('disabled',false);
            },
            error: function () {
               swal("Error!", "Oops something happens.", "error");
               $("#submitAudition").prop('disabled',false);
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
                    $.get( "{{route('temp_audition_review.check-progress', $participant_detail->id)}}")
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
    <script src="/assets/js/newAudioRecorder/audition_audio_processor.js?v=1"></script>
    <script type="text/javascript" src="/assets/js/toastr/toastr.min.js"></script>
@endsection
