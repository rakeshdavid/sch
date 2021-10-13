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
	        <form action="{{url('challenge-review-new')}}/{{$participant_id}}" method="post" enctype="multipart/form-data">
	        	{!! csrf_field() !!}
	        	<input type="hidden" name="participant_id" value="{{$participant_id}}" />
	        	<input type="hidden" name="performer-name" value="{{$participant_detail->user->first_name}}" />
		        <div class="col-md-12">
		        	<div class="row">
			        	<div class="col-lg-8 col-lg-offset-2">
		                    <div class="card row">
		                        
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
		                               <video id="video-{{$participant_detail->id}}" width="100%" height="300" controls >
		                                    <source src="{{asset('uploads/challenge/').'/'. $participant_detail->video_link}}">
		                                    Your browser does not support HTML5 video.
		                                </video>
		                                    
		                                @endif
		                            </div>
		                        
		                    </div>
		                    <h3 class="p-20 text-center">{{$participant_detail->user->first_name}}</h3>
		                </div>
		                
		        </div>
		        <div class="row">
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
                            <label class="font-weight-bold">Feedback</label><br/>
                            <textarea name="feedback" class="summernote"></textarea>
                        </div>       
                    </div> 
	            </div>
		        <div class="form-row mt-5">
                	<div class="form-group">
                		<div class="col-md-12">
                			<input type="submit" name="audtion" value="Add Review" class="btn btn-danger" />
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
@endsection