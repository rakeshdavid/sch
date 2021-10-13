@extends('layouts.agency')
@section('css')
	<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/js/rateit/rateit.css">
    <link type="text/css" rel="stylesheet" href="/assets/js/toastr/toastr.min.css">
    <link type="text/css" rel="stylesheet" href="/assets/js/sweetalert/sweet-alert.css">
    <style>
    	textarea{width: 100%;padding: 10px;height:100px;}
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
	        <div class="center-form">
		        <form action="{{url('update-review-new')}}/{{$participant_id}}" method="post" enctype="multipart/form-data">
		        	{!! csrf_field() !!}
		        	<input type="hidden" name="participant_id" value="{{$participant_id}}" />
		        	<input type="hidden" name="review_id" value="{{$review->id}}" />
			        <div class="col-md-10 col-xl-8 video-card">
			        	<div class="row">
				        	<div class="col-lg-12">
			                    <div class="card">
			                        <div class="card">
			                            <div class="videos-box">

			                                @if(strpos($participant_detail->video_link, 'youtube') !== false)

			                                    @php
			                                    $query ="";
			                                        $parts = parse_video_link($participant_detail->video_link);
			                                        if(array_key_exists('query',$parts)){
			                                            parse_str($parts['query'], $query);
			                                            $participant_detailID = $query['v'];
			                                            $youtubevideo_link = "https://www.youtube.com/embed/".$participant_detailID; 
			                                        }else{
			                                            $youtubevideo_link = $participant_detail->video_link; 
			                                        }
			                                        
			                                        
			                                    @endphp
			                                   
			                                @else
			                               <video id="video-{{$participant_detail->id}}" width="100%" height="300" controls >
				                               	@if($review->review_url)
			                               			<source src="{{env('AGENCY_PLATFORM_LINK')}}/reviews/completed/{{$review->review_url}}">
			                               		@else
				                                    <source src="{{asset('uploads/auditions/').'/'. $participant_detail->video_link}}">
				                                @endif
			                                    Your browser does not support HTML5 video.
			                                </video>
			                                    
			                                @endif
			                            </div>
			                            <div class="card-body">
			                                <h3>{{$participant_detail->user->first_name}}</h3>
			                            </div>
			                        </div>
			                    </div>
			                </div>
			                <div class="col-md-12 mt-4">
					        	<div id="reload_block">
			                        <a class="btn btn-danger" href="{{route('auditionreview.rewrite', $participant_detail->id)}}">
				                        <i class="btn-icon fa fa-check"></i>Re-record video
				                    </a>
			                	</div>
					        </div>
			                <div class="col-md-12 mt-5">
			                	<h3>Your Review</h3>
								<div id="accordion" class="accordion">
                              <input type="hidden" name="performer-name" class="form-control" value="{{$review->name}}"/>
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
						                              
						                               <div class="rateit pull-right bigstars" data-rateit-span-id="1" data-rateit-name="pq-rating" data-rateit-min="0" data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0" data-rateit-value="{{$review->performance_quality_rating}}" ></div>
													<input type="hidden" name="pq-rating" value="{{$review->performance_quality_rating}}" />
						                            </div>
						                        </div>
						                        <div class="form-group col-sm-12">
						                            <div class="right-box ">
						                                <label class="font-weight-bold">Note</label><br/>
						                                <textarea name="performance-quality" class="summernote">{!! $review->performance_quality !!}</textarea>
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
							                               
						                               <div class="rateit pull-right bigstars" data-rateit-span-id="1" data-rateit-name="ta-rating" data-rateit-min="0" data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0" data-rateit-value="{{$review->technical_ability_rating}}" ></div>
														<input type="hidden" name="ta-rating" value="{{$review->technical_ability_rating}}" />
							                            </div>
							                        </div>
							                        <div class="form-group col-sm-12">
							                            <div class="right-box ">
							                                <label class="font-weight-bold">Note</label><br/>
							                                <textarea name="technical-ability" class="summernote">{!! $review->technical_ability !!}</textarea>
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
							                               
						                               		<div class="rateit pull-right bigstars" data-rateit-span-id="1" data-rateit-name="es-rating" data-rateit-min="0" data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0" data-rateit-value="{{ $review->energy_style_rating}}" ></div>
															<input type="hidden" name="es-rating" value="{{ $review->energy_style_rating}}" />
							                            </div>
							                        </div>
							                        <div class="form-group col-sm-12">
							                            <div class="right-box ">
							                                <label class="font-weight-bold">Note</label><br/>
							                                <textarea name="energy-and-style" class="summernote">{!! $review->energy_style !!}</textarea>
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
							                               
						                               		<div class="rateit pull-right bigstars" data-rateit-span-id="1" data-rateit-name="storytelling-rating" data-rateit-min="0" data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0" data-rateit-value="{{ $review->storytelling_rating}}" ></div>
						                               		<input type="hidden" name="storytelling-rating" value="{{ $review->storytelling_rating}}" />

							                            </div>
							                        </div>
							                        <div class="form-group col-sm-12">
							                            <div class="right-box ">
							                                <label class="font-weight-bold">Note</label><br/>
							                                <textarea name="storytelling" class="summernote">{!! $review->storytelling !!}</textarea>
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
							                               
						                               		<div class="rateit pull-right bigstars" data-rateit-span-id="1" data-rateit-name="la-rating" data-rateit-min="0" data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0" data-rateit-value="{{ $review->look_appearance_rating}}" ></div>
						                               		<input type="hidden" name="la-rating" value="{{ $review->look_appearance_rating}}" />

							                            </div>
							                        </div>
							                        <div class="form-group col-sm-12">
							                            <div class="right-box ">
							                                <label class="font-weight-bold">Note</label><br/>
							                                <textarea name="look-and-appearance" class="summernote">{!! $review->look_appearance !!}</textarea>
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
							                                <label class="font-weight-bold"></label><br/>
							                                <textarea name="feedback" class="summernote">{!! $review->feedback !!}</textarea>
							                            </div>       
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
	                			<input type="submit" name="audtion" value="Update Review" class="btn btn-danger" />
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
	</script>
    <script src="/assets/js/rateit/jquery.rateit.js"></script>
    <script type="text/javascript" src="/assets/js/toastr/toastr.min.js"></script>
@endsection
