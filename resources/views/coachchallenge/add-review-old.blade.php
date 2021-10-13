@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="/assets/js/rateit/rateit.css">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
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
	        <form action="{{url('challenge-review')}}/{{$participant_id}}" method="post" enctype="multipart/form-data">
	        	{!! csrf_field() !!}
	        	<input type="hidden" name="participant_id" value="{{$participant_id}}" />
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
		                                            $youtubevideo_link = "https://www.youtube.com/embed/".$participant_detailID; 
		                                        }else{
		                                            $youtubevideo_link = $participant_detail->video_link; 
		                                        }
		                                        
		                                        
		                                    @endphp
		                                   
		                                @else
		                               <video id="video-{{$participant_detail->id}}" width="100%" height="300" controls >
		                                    <source src="{{asset('uploads/auditions/').'/'. $participant_detail->video_link}}">
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
	                    <p class="text-center text-danger f-w-500">TECHNIQUE SCORE</p>
	                    <div class="col-xs-12 col-md-4 m-b-15">
	                        <div class="row">
	                            <div class="col-xs-12 col-md-8">
	                                <span>Timing</span>
	                                <span class="rateit-value"></span>
	                                <div class="rateit pull-right" data-rateit-span-id="1" data-rateit-name="timing-rating" data-rateit-min="0"
	                                     data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0"
	                                     data-rateit-value="{{old('timing-rating')}}"></div>
	                                <input type="hidden" name="timing-rating" value="{{old('timing-rating')}}" />  
	                                <fieldset>
	                                    <small class="m-b-0">Comment</small>
	                                    <input class="form-control comment" type="text" name="timing-comment" autocomplete="off" value="{{old('timing-comment')}}" />
	                                </fieldset>
	                            </div>
	                            <div class="col-xs-12 col-md-4">
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col-xs-12 col-md-4 m-b-15">
	                        <div class="row">
	                            <div class="col-xs-12 col-md-8 col-md-offset-2">
	                                <span>Footwork</span>
	                                <span class="rateit-value"></span>
	                                <div class="rateit pull-right" data-rateit-span-id="2" data-rateit-name="footwork-rating" data-rateit-min="0"
	                                     data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0"
	                                     data-rateit-value="{{old('footwork-rating')}}"></div>
	                                <input type="hidden" class="form-control" placeholder="" name="footwork-rating" min="1" max="5" step="0.5" autocomplete="off" value="{{old('footwork-rating')}}">
	                                <fieldset>
	                                    <small class="m-b-0">Comment</small>
	                                    <input class="form-control comment" type="text" name="footwork-comment" autocomplete="off" value="{{old('footwork-comment')}}" />
	                                </fieldset>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col-xs-12 col-md-4 m-b-15">
	                        <div class="row">
	                            <div class="col-xs-12 col-md-4">
	                            </div>
	                            <div class="col-xs-12 col-md-8">
	                                <span>Alignment</span>
	                                <span class="rateit-value"></span>
	                                <div class="rateit pull-right" data-rateit-span-id="3" data-rateit-name="alignment-rating" data-rateit-min="0"
	                                     data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0"
	                                     data-rateit-value="{{old('alignment-rating')}}"></div>
	                                <input type="hidden" class="form-control" placeholder="" name="alignment-rating" min="1" max="5" step="0.5" autocomplete="off" value="{{old('alignment-rating')}}">
	                                <fieldset>
	                                    <small class="m-b-0">Comment</small>
	                                    <input class="form-control comment" type="text" name="alingment-comment" autocomplete="off" value="{{old('alingment-comment')}}" />
	                                </fieldset>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col-xs-12 col-md-4 m-b-15">
	                        <div class="row">
	                            <div class="col-xs-12 col-md-8">
	                                <span>Balance</span>
	                                <span class="rateit-value"></span>
	                                <div class="rateit pull-right" data-rateit-span-id="4" data-rateit-name="balance-rating" data-rateit-min="0"
	                                     data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0"
	                                     data-rateit-value="{{old('balance-rating')}}"></div>
	                                <input type="hidden" class="form-control" placeholder="" name="balance-rating" min="1" max="5" step="0.5" autocomplete="off" value="{{old('balance-rating')}}">
	                                <fieldset>
	                                    <small class="m-b-0">Comment</small>
	                                    <input class="form-control comment" type="text" name="balance-comment" autocomplete="off" value="{{old('balance-comment')}}" />
	                                </fieldset>
	                            </div>
	                            <div class="col-xs-12 col-md-4">
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col-xs-12 col-md-4 m-b-15">
	                        <div class="row">
	                            <div class="col-xs-12 col-md-8 col-md-offset-2">
	                                <span>Focus</span>
	                                <span class="rateit-value"></span>
	                                <div class="rateit pull-right" data-rateit-span-id="5" data-rateit-name="focus-rating" data-rateit-min="0"
	                                     data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0"
	                                     data-rateit-value="{{old('focus-rating')}}"></div>
	                                <input type="hidden" class="form-control" placeholder="" name="focus-rating" min="1" max="5" step="0.5" autocomplete="off" value="{{old('focus-rating')}}">
	                                <fieldset>
	                                    <small class="m-b-0">Comment</small>
	                                    <input class="form-control comment" type="text" name="focus-comment" autocomplete="off" value="{{old('focus-comment')}}" />
	                                </fieldset>
	                            </div>
	                        </div>
	                    </div>


	                    <div class="col-xs-12 col-md-4 m-b-15">
	                        <div class="row">
	                            <div class="col-xs-12 col-md-4">
	                            </div>
	                            <div class="col-xs-12 col-md-8">
	                                <span>Precision</span>
	                                <span class="rateit-value"></span>
	                                <div class="rateit pull-right"  data-rateit-span-id="6" data-rateit-name="precision-rating" data-rateit-min="0" data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0" data-rateit-value="{{old('precision-rating')}}"></div>
	                                <input type="hidden" class="form-control" placeholder="" name="precision-rating" min="1" max="5" step="0.5" autocomplete="off" value="{{old('precision-rating')}}">
	                                <fieldset>
	                                    <small class="m-b-0">Comment</small>
	                                    <input class="form-control comment" type="text" name="precision-comment" autocomplete="off" value="{{old('precision-comment')}}" />
	                                </fieldset>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col-xs-12"><hr></div>
	                </div>

	                <div class="col-md-12">
	                    <p class="text-center text-danger f-w-500">EXPRESSION</p>
	                    <div class="col-xs-12 col-md-3 m-b-15">
	                        <div class="row">
	                            <div class="col-xs-12 col-md-11">
	                                <span>Energy</span>
	                                <span class="rateit-value"></span>
	                                <div class="rateit pull-right" data-rateit-span-id="7" data-rateit-name="energy-rating" data-rateit-min="0"
	                                     data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0"
	                                     data-rateit-value="{{old('energy-rating')}}"></div>
	                                <input type="hidden" class="form-control" placeholder="" name="energy-rating" min="1" max="5" step="0.5" autocomplete="off" value="{{old('energy-rating')}}">
	                                <fieldset>
	                                    <small class="m-b-0">Comment</small>
	                                    <input class="form-control comment" type="text" name="energy-comment" autocomplete="off" value="{{old('energy-comment')}}" />
	                                </fieldset>
	                            </div>
	                            <div class="col-xs-0 col-md-1"></div>
	                        </div>

	                    </div>
	                    <div class="col-xs-12 col-md-6">
	                        <div class="row">
	                            <div class="col-xs-12 col-md-6 m-b-15">
	                                <div class="row">
	                                    <div class="col-xs-12 col-md-11" style="margin-left: 3%;">
	                                        <span>Style</span>
	                                        <span class="rateit-value"></span>
	                                        <div class="rateit pull-right" data-rateit-span-id="8" data-rateit-name="style-rating" data-rateit-min="0"
	                                             data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0"
	                                             data-rateit-value="{{old('style-rating')}}"></div>
	                                        <input type="hidden" class="form-control" placeholder="" name="style-rating" min="1" max="5" step="0.5" autocomplete="off" value="{{old('style-rating')}}" >
	                                        <fieldset>
	                                            <small class="m-b-0">Comment</small>
	                                            <input class="form-control comment" type="text" name="style-comment" autocomplete="off" value="{{old('style-comment')}}" />
	                                        </fieldset>
	                                    </div>
	                                    <div class="col-xs-0 col-md-1"></div>
	                                </div>
	                            </div>
	                            <div class="col-xs-12 col-md-6 m-b-15">
	                                <div class="row">
	                                    <div class="col-xs-12 col-md-11 col-md-offset-1" style="margin-right: 3%;">
	                                        <span>Creativity</span>
	                                        <span class="rateit-value"></span>
	                                        <div class="rateit pull-right" data-rateit-span-id="9" data-rateit-name="creativity-rating" data-rateit-min="0"
	                                             data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0"
	                                             data-rateit-value="{{old('creativity-rating')}}"></div>
	                                        <input type="hidden" class="form-control" placeholder="" name="creativity-rating" min="1" max="5" step="0.5" autocomplete="off" value="{{old('creativity-rating')}}">
	                                        <fieldset>
	                                            <small class="m-b-0">Comment</small>
	                                            <input class="form-control comment" type="text" name="creativity-comment" autocomplete="off" value="{{old('creativity-comment')}}"/>
	                                        </fieldset>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>

	                    <div class="col-xs-12 col-md-3 m-b-15">
	                        <div class="row">

	                            <div class="col-xs-12 col-md-11 col-md-offset-1">
	                                <span>Interpretation</span>
	                                <span class="rateit-value"></span>
	                                <div class="rateit pull-right" data-rateit-span-id="10" data-rateit-name="interpretation-rating" data-rateit-min="0"
	                                     data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0"
	                                     data-rateit-value="{{old('interpretation-rating')}}"></div>
	                                <input type="hidden" class="form-control" placeholder="" name="interpretation-rating" value="{{old('interpretation-rating')}}">
	                                <fieldset>
	                                    <small class="m-b-0">Comment</small>
	                                    <input class="form-control comment" type="text" name="interpretation-comment" autocomplete="off" value="{{old('interpretation-comment')}}" />
	                                </fieldset>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col-xs-12"><hr></div>
	                </div>

	                <div class="col-md-12">
	                    <p class="text-center text-danger f-w-500">CHOREOGRAPHY</p>
	                    <div class="col-xs-12 col-md-6 m-b-15">
	                        <div class="row">
	                            <div class="col-xs-12 col-md-6 col-md-offset-4">
	                                <div class="row">
	                                    <div class="col-xs-12 col-md-11 col-md-offset-1">
	                                        <span>Formation</span>
	                                        <span class="rateit-value"></span>
	                                        <div class="rateit pull-right" data-rateit-span-id="11" data-rateit-name="formation-rating" data-rateit-min="0"
	                                             data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0"
	                                             data-rateit-value="{{old('formation-rating')}}"></div>
	                                        <input type="hidden" class="form-control" placeholder="" name="formation-rating" min="1" max="5" step="0.5" autocomplete="off" value="{{old('formation-rating')}}">
	                                        <fieldset>
	                                            <small class="m-b-0">Comment</small>
	                                            <input class="form-control comment" type="text" name="formation-comment" autocomplete="off" value="{{old('formation-comment')}}" />
	                                        </fieldset>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col-xs-12 col-md-6 m-b-15">
	                        <div class="row">
	                            <div class="col-xs-0 col-md-2"></div>
	                            <div class="col-xs-12 col-md-6">
	                                <div class="row">
	                                    <div class="col-xs-12 col-md-11">
	                                        <span>Artistry</span>
	                                        <span class="rateit-value"></span>
	                                        <div class="rateit pull-right" data-rateit-span-id="12" data-rateit-name="artisty-rating" data-rateit-min="0"
	                                             data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0"
	                                             data-rateit-value="{{old('artisty-rating')}}"></div>
	                                        <input type="hidden" class="form-control" placeholder="" name="artisty-rating" min="1" max="5" step="0.5" autocomplete="off" value="{{old('artisty-rating')}}">
	                                        <fieldset>
	                                            <small class="m-b-0">Comment</small>
	                                            <input class="form-control comment" type="text" name="artisty-comment" autocomplete="off" value="{{old('artisty-comment')}}"/>
	                                        </fieldset>
	                                    </div>
	                                    <div class="col-xs-12 col-md-1">
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="col-xs-0 col-md-4"></div>
	                        </div>
	                    </div>

	                </div>
	                <div class="col-md-12">
                        <div class="right-box ">
                        	<br />
                            <label class="font-weight-bold">Summary of Performance Video</label><br/>
                            <textarea name="feedback_summary" class="summernote">{{old('feedback_summary')}}</textarea>
                        </div>       
                    </div>
                    <div class="col-md-12">
                        <div class="right-box "><br />
                            <label class="font-weight-bold">Additional Tips</label><br/>
                            <textarea name="additional_tips" class="summernote">{{old('additional_tips')}}</textarea>
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
  		$('.summernote').summernote({
        placeholder: '',
        tabsize: 2,
        height: 120,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });
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