@extends('layouts.user')
@section('content')
<div class="main-content my-reviews-content">
            <div class="container-fluid">
                <div class="top-box review-fix">
                    <div class="row align-items-center">
                        <div class="col-xl-5">
                            <input type="search" name="" class="form-control" placeholder="Search by name…">
                        </div>
                        <div class="col-xl-7">
                            <ul class="nav" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="view-all-tab" data-toggle="tab" href="#view-all" role="tab" aria-controls="view-all" aria-selected="true">View All</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="reviewed-tab" data-toggle="tab" href="#advanced" role="tab" aria-controls="reviewed" aria-selected="false">Advanced</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="waiting-review-tab" data-toggle="tab" href="#intermediate" role="tab" aria-controls="waiting-review" aria-selected="false">Intermediate</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="payment-tab" data-toggle="tab" href="#begginer" role="tab" aria-controls="payment" aria-selected="false">Begginer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div> 
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="view-all" role="tabpanel" aria-labelledby="view-all-tab">
                        <div class="videos-wrap reviews-wrap">
                            <div class="row">
                            	@foreach($reviews as $review)
                                <div class="col-xl-4 col-md-6">
                                    <div class="card">
                                        <div class="videos-box">
                                        	<video data-videoid="all-video-{{$review->id}}" width="100%" height="300" controls class="embed-responsive-item video-pause" id="all-video-{{$review->id}}" onplay="stopall(this)">
					                            <source src="{{url('/') . config('video.completed_review_path') . $review->review_url}}">
					                            Your browser does not support HTML5 video.
					                        </video>
                                            @if($review->thumbnail !='')
                                                <img class="video-thumb" data-videoid="all-video-{{$review->id}}" src="{{url('/') . config('video.thumbnail_path')}}/{{$review->thumbnail}}" />
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <h3  class="cursor-pointer" data-toggle="modal" data-target="#modal-right-{{$review->id}}">
												{!! Str::words($review->video_name,5,'...') !!}
                                            </h3>
                                            <div class="level"> Level: 
											@if($review->performance_level_id == 3)
												Advance
											@elseif($review->performance_level_id == 2)
												Intermediate
											@else
											Beginner
											@endif
                                            </div>
                                            <div class="rating"><span class="rating-text">{{number_format($review->overall_rating,1)}}</span>
                                        	@if($review->overall_rating)
                                                @for ($i = 1; $i <= $review->overall_rating; $i++)
                                                    <i class="fas fa-star"></i>
                                                @endfor
                                                @for($review->overall_rating;$review->overall_rating<5;$review->overall_rating++)
                                                    <span class="grey-star"><i class="fas fa-star"></i></span>
                                                @endfor
                                            @endif
                                            	
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="advanced" role="tabpanel" aria-labelledby="waiting-review-tab">
                    	<div class="videos-wrap reviews-wrap">
                            <div class="row">
                            	@if($advance)
	                            	@foreach($advance as $review)
	                                <div class="col-xl-4 col-md-6">
	                                    <div class="card">
	                                        <div class="videos-box">
	                                        	<video data-videoid="advance-video-{{$review->id}}" width="100%" height="300" controls class="embed-responsive-item video-pause" id="advance-video-{{$review->id}}" onplay="stopall(this)">
						                            <source src="{{url('/') . config('video.completed_review_path') . $review->review_url}}">
						                            Your browser does not support HTML5 video.
						                        </video>
	                                            @if($review->thumbnail !='')
                                                	<img class="video-thumb" data-videoid="advance-video-{{$review->id}}" src="{{url('/') . config('video.thumbnail_path')}}/{{$review->thumbnail}}" />
                                            	@endif
	                                        </div>
	                                        <div class="card-body">
	                                            <h3  class="cursor-pointer" data-toggle="modal" data-target="#modal-right-{{$review->id}}">
													{!! Str::words($review->video_name,5,'...') !!}
                                            	</h3>
	                                            <div class="level"> Level: 
												@if($review->performance_level_id == 3)
													Advance
												@elseif($review->performance_level_id == 2)
													Intermediate
												@else
													Beginner
												@endif
	                                            </div>
	                                            <div class="rating"><span class="rating-text">{{number_format($review->overall_rating,1)}}</span>
	                                            	@if($review->overall_rating)
		                                                @for ($i = 1; $i <= $review->overall_rating; $i++)
		                                                    <i class="fas fa-star"></i>
		                                                @endfor
		                                                @for($review->overall_rating;$review->overall_rating<5;$review->overall_rating++)
		                                                    <span class="grey-star"><i class="fas fa-star"></i></span>
		                                                @endfor
		                                            @endif
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                                @endforeach
                                @else
                                <div class="col-md-12">
	                            	<h2>No Advence Video Found</h2>
	                            </div>
                                @endif
                            </div>
                        </div>

                    </div>

                    <div class="tab-pane fade" id="intermediate" role="tabpanel" aria-labelledby="waiting-review-tab">
                    	<div class="videos-wrap reviews-wrap">
                            <div class="row">
                            	@if($intermediate)
	                            	@foreach($intermediate as $review)
	                                <div class="col-xl-4 col-md-6">
	                                    <div class="card">
	                                        <div class="videos-box">
	                                        	<video data-videoid="inter-video-{{$review->id}}" width="100%" height="300" controls class="embed-responsive-item video-pause" id="inter-video-{{$review->id}}" onplay="stopall(this)">
						                            <source src="{{url('/') . config('video.completed_review_path') . $review->review_url}}">
						                            Your browser does not support HTML5 video.
						                        </video>
	                                            @if($review->thumbnail !='')
                                                	<img class="video-thumb" data-videoid="inter-video-{{$review->id}}" src="{{url('/') . config('video.thumbnail_path')}}/{{$review->thumbnail}}" />
                                            	@endif
	                                        </div>
	                                        <div class="card-body">
	                                            <h3  class="cursor-pointer" data-toggle="modal" data-target="#modal-right-{{$review->id}}">
													{!! Str::words($review->video_name,5,'...') !!}
                                            	</h3>
	                                            <div class="level"> Level: 
												@if($review->performance_level_id == 3)
													Advance
												@elseif($review->performance_level_id == 2)
													Intermediate
												@else
												Beginner
												@endif
	                                            </div>
	                                            <div class="rating"><span class="rating-text">{{number_format($review->overall_rating,1)}}</span>
	                                            	@if($review->overall_rating)
		                                                @for ($i = 1; $i <= $review->overall_rating; $i++)
		                                                    <i class="fas fa-star"></i>
		                                                @endfor
		                                                @for($review->overall_rating;$review->overall_rating<5;$review->overall_rating++)
		                                                    <span class="grey-star"><i class="fas fa-star"></i></span>
		                                                @endfor
		                                            @endif
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                                @endforeach
	                            @else
	                            <div class="col-md-12">
	                            	<h2>No Intermediate Video Found</h2>
	                            </div>
	                            @endif
                                
                            </div>
                        </div>
                    </div>
					<div class="tab-pane fade" id="begginer" role="tabpanel" aria-labelledby="reviewed-tab">
						<div class="videos-wrap reviews-wrap">
                            <div class="row">
                            	@if($beginners)
	                            	@foreach($beginners as $review)
	                                <div class="col-xl-4 col-md-6">
	                                    <div class="card">
	                                        <div class="videos-box">
	                                        	<video data-videoid="beg-video-{{$review->id}}" width="100%" height="300" controls class="embed-responsive-item video-pause" id="beg-video-{{$review->id}}" onplay="stopall(this)">
						                            <source src="{{url('/') . config('video.completed_review_path') . $review->review_url}}">
						                            Your browser does not support HTML5 video.
						                        </video>
	                                            @if($review->thumbnail !='')
                                                	<img class="video-thumb" data-videoid="beg-video-{{$review->id}}" src="{{url('/') . config('video.thumbnail_path')}}/{{$review->thumbnail}}" />
                                            	@endif
	                                        </div>
	                                        <div class="card-body">
	                                            <!-- <h3>{{$review->video_name}}</h3> -->
	                                            <h3  class="cursor-pointer" data-toggle="modal" data-target="#modal-right-{{$review->id}}">
												{!! Str::words($review->video_name,5,'...') !!}
                                            </h3>
	                                            <div class="level"> Level: 
												@if($review->performance_level_id == 3)
													Advance
												@elseif($review->performance_level_id == 2)
													Intermediate
												@else
												Beginner
												@endif
	                                            </div>
	                                            <div class="rating"><span class="rating-text">{{number_format($review->overall_rating,1)}}</span>
	                                            	@if($review->overall_rating)
		                                                @for ($i = 1; $i <= $review->overall_rating; $i++)
		                                                    <i class="fas fa-star"></i>
		                                                @endfor
		                                                @for($review->overall_rating;$review->overall_rating<5;$review->overall_rating++)
		                                                    <span class="grey-star"><i class="fas fa-star"></i></span>
		                                                @endfor
		                                            @endif
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                                @endforeach
	                            @else
	                            <div class="col-md-12">
	                            	<h2>No Begginer Video Found</h2>
	                            </div>
	                            @endif
                                
                            </div>
                        </div>
                    </div>
                    

                </div>
            </div>
        </div>
@endsection
@section("modal-right")
	@foreach($allreview as $review1)
		<div class="modal fade firs-modal right" id="modal-right-{{$review1->id}}" tabindex="-1" role="dialog">
	        <div class="modal-dialog" role="document">
	            <div class="modal-content">
	                <a href="#" class="close" data-dismiss="modal" aria-label="Close"><img src="/assets/img/close.png" class="img-fluid pause-video"></a>
	                <div class="firs-snow-content">
	                    <div class="videos-box reviewed-video-box">
	                        @if(strpos($review1->video_url, 'youtube') !== false)

                                @php
                                $query ="";
                                    $parts = parse_url($review1->video_url);
                                    if(array_key_exists('query',$parts)){
                                        parse_str($parts['query'], $query);
                                        $videoID = $query['v'];
                                        $youtubeurl = "https://www.youtube.com/embed/".$videoID; 
                                    }else{
                                        $youtubeurl = $review1->video_url; 
                                    }
                                    
                                    
                                @endphp
                                    <iframe width="100%" height="300" src="{{$youtubeurl}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    
                            @else
                                <video class="video-pause" id="review-video-{{$review1->id}}" width="100%" height="300" controls >
                                	<source src="{{url('/') . config('video.completed_review_path') . $review1->review_url}}">
                                    <!-- <source src="{{url('/') . config('video.user_video_path') . $review1->video_url}}"> -->
                                    Your browser does not support HTML5 video.
                                </video>
                                @if($review1->thumbnail !='')
                                    <img class="video-thumb review-thumbnail" data-videoid="review-video-{{$review1->id}}" src="{{url('/') . config('video.thumbnail_path')}}/{{$review1->thumbnail}}" />
                                @endif    
                            @endif
	                    </div>
	                    <h3>{!! Str::words($review1->video_name,5,'...') !!}</h3>
	                    <div class="level">Level: 
	                    	@if($review1->performance_level_id == 3)
								Advance
							@elseif($review1->performance_level_id == 2)
								Intermediate
							@else
								Beginner
							@endif
						</div>
	                    <div class="updated">Uploaded in @php
                                    $v_data = new \Carbon\Carbon($review1->created_at);
                                    echo $v_data->format("m.d.Y g:i A");
                                    @endphp</div>
	                    <ul class="nav" id="myTab" role="tablist">
	                        <li class="nav-item @if($review1->package_id == 1) width-50 @endif">
	                            <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview-{{$review1->id}}" role="tab" aria-controls="overview" aria-selected="true">Overview</a>
	                        </li>
	                        @if($review1->package_id == 1)
                                <li class="nav-item @if($review1->package_id == 1) width-50 @endif">
                                    <a class="nav-link" id="score-tab" data-toggle="tab" href="#all-score-{{$review1->id}}" role="tab" aria-controls="technique" aria-selected="false">Score</a>
                                </li>
                            @else
	                        <li class="nav-item">
	                            <a class="nav-link" id="technique-tab" data-toggle="tab" href="#technique-{{$review1->id}}" role="tab" aria-controls="technique" aria-selected="false">Technique</a>
	                        </li>
	                        <li class="nav-item">
	                            <a class="nav-link" id="expression-tab" data-toggle="tab" href="#expression-{{$review1->id}}" role="tab" aria-controls="expression" aria-selected="false">Expression</a>
	                        </li>
	                        <li class="nav-item">
	                            <a class="nav-link" id="choreography-tab" data-toggle="tab" href="#choreography-{{$review1->id}}" role="tab" aria-controls="choreography" aria-selected="false">Choreography</a>
	                        </li>
	                        @endif
	                    </ul>

	                    <div class="tab-content" id="nav-tabContent">
	                        <div class="tab-pane fade show active" id="overview-{{$review1->id}}" role="tabpanel" aria-labelledby="overview-tab">
	                            <div class="rating overal-rating">
	                                <div class="left-text">{{number_format($review1->overall_rating_backup,1)}}</div>
	                                <div class="right-star">
	                                	@if($review1->overall_rating_backup)
                                            @for ($i = 1; $i <= $review1->overall_rating_backup; $i++)
                                                <i class="fas fa-star"></i>
                                            @endfor
                                            @for($review1->overall_rating_backup;$review1->overall_rating_backup<5;$review1->overall_rating_backup++)
                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                            @endfor
                                        @endif
	                                    <span class="overal">Overall Rating</span>
	                                </div>

	                            </div>
	                            <div class="content-list">
	                                <div class="left-box">
	                                	@if($review1->message !="")
	                                		@if($review1->package_id != 1)
		                                    <h4>Feedback Summary</h4>
		                                    @else
		                                    <h4>Notes</h4>
		                                    @endif
		                                    <p>{!! $review1->message !!}</p>
	                                    @endif
	                                </div>
	                            </div>
	                            <div class="content-list d-block">
	                            	@php
	                                    $coach = $model->getCoachDetailByVideoId($review1->video_id);
	                                @endphp
	                                @if($coach)
	                                <div class="media">
	                                    
	                                    <div class="left-box">
	                                        <h4>Coach</h4>
	                                        <h3>{{$coach->first_name}} {!! Str::words($coach->last_name,1,'...') !!}</h3>
	                                    </div>
	                                    <div class="img-box coach-image">
	                                        @if($coach->avatar !='')
                                                @if(Str::startsWith($coach->avatar,'http'))
                                                    <img src="{{url('/')}}{{$coach->avatar}}" class="img-fluid">
                                                @else
                                                    <img src="{{url('/')}}@if( ! Str::startsWith($coach->avatar,'/'))/@endif{{ $coach->avatar }}" class="img-fluid">
                                                    
                                                @endif
                                            @else
                                                <img src="{{url('/')}}/images/default_avatar_new.png" class="img-fluid">
                                            @endif
	                                    </div>
	                                </div>
	                                @endif
	                                @php
                                        $questions = $model->getReviewQuestion($review1->video_id);
                                        $index=1;
                                    @endphp
	                                @if($review1->package_id != 1)
		                                <ul class="question-list">
		                                    
	                                        @if(count($questions) > 0)
	                                            @foreach($questions as $question)
	                                                <li>
	                                                    <h4>Question {{$index++}}:</h4>
	                                                    <h3>{{$question->question}}</h3>
	                                                    @if($question->answer !='')
	                                                        <p>{{$question->answer}}</p>
	                                                    @else
	                                                        <p>Waiting for reply</p>
	                                                    @endif
	                                                </li>
	                                            @endforeach
	                                        @endif
		                                </ul>
		                            
	                                <div class="new-question">
	                                    <form method="post" class="question-form" action="" id="ask-question-{{$review1->video_id}}">
                                            {!! csrf_field() !!}
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <input type="hidden" name="video-id" value="{{$review1->video_id}}" />
                                                    <input type="text" name="question-title" class="form-control" placeholder="Ask your question..." />
                                                </div>
                                                <div class="col-lg-12 pt-3">

                                                    <button class="btn btn-outline-danger submit-question" data-videoid="{{$review1->video_id}}">Ask Question</button>
                                                </div>
                                                <div class="col-lg-12 response">
                                                </div>
                                            </div>
                                        </form>
                                        @if(count($questions) < 3)
                                        	<a href="javascript:void(0)" class="btn btn-outline-danger new-question1" data-videoid="{{$review1->video_id}}">New Question</a>
                                        @endif
	                                </div>
	                                @endif
	                            </div>
	                        </div>
	                        @if($review1->package_id == 1)
                                <div class="tab-pane fade" id="all-score-{{$review1->id}}" role="tabpanel" aria-labelledby="score-tab">
                                    @if($review1 !="" )
                                    <ul class="tab-list">
                                        
                                        @if($review1->performance_quality !=NULL)
                                        <li>
                                            <div class="content-list">
                                                <div class="left-box">
                                                    <h4>Performance Quality</h4>
                                                    <p>{!! $review1->performance_quality !!}</p>
                                                </div>
                                                <div class="right-box">
                                                    <div class="rating">
                                                        <div class="left-text">
                                                        	{{ number_format($review1->performance_quality_rating, 1) }}
                                                        </div>
                                                        @if($review1->performance_quality_rating)
                                                            @for ($i = 1; $i <= $review1->performance_quality_rating; $i++)
                                                                <i class="fas fa-star"></i>
                                                            @endfor
                                                            @for($review1->performance_quality_rating;$review1->performance_quality_rating<5;$review1->performance_quality_rating++)
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                            @endfor
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        @endif
                                        @if($review1->technical_ability)
                                        <li>
                                            <div class="content-list">
                                                <div class="left-box">
                                                    <h4>Technical Ability</h4>
                                                    <p>{!! $review1->technical_ability !!}</p>
                                                </div>
                                                <div class="right-box">
                                                    <div class="rating">
                                                        <div class="left-text">
                                                        	{{ number_format($review1->technical_ability_rating, 1) }}

                                                        </div>
                                                        @if($review1->technical_ability_rating)
                                                            @for ($i = 1; $i <= $review1->technical_ability_rating; $i++)
                                                                <i class="fas fa-star"></i>
                                                            @endfor
                                                            @for($review1->technical_ability_rating;$review1->technical_ability_rating<5;$review1->technical_ability_rating++)
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                            @endfor
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        @endif
                                        @if($review1->energy_style)
                                        <li>
                                            <div class="content-list">
                                                <div class="left-box">
                                                    <h4>Energy Style</h4>
                                                    <p>{!! $review1->energy_style !!}</p>
                                                </div>
                                                <div class="right-box">
                                                    <div class="rating">
                                                        <div class="left-text">
                                                   {{ number_format($review1->energy_style_rating, 1) }} 
                                                        </div>
                                                        @if($review1->energy_style_rating)
                                                            @for ($i = 1; $i <= $review1->energy_style_rating; $i++)
                                                                <i class="fas fa-star"></i>
                                                            @endfor
                                                            @for($review1->energy_style_rating;$review1->energy_style_rating<5;$review1->energy_style_rating++)
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                            @endfor
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        @endif
                                        @if($review1->storytelling)
                                        <li>
                                            <div class="content-list">
                                                <div class="left-box">
                                                    <h4>Storytelling</h4>
                                                    <p>{!! $review1->storytelling !!}</p>
                                                </div>
                                                <div class="right-box">
                                                    <div class="rating">
                                                        <div class="left-text">
                                                   {{ number_format($review1->storytelling_rating, 1) }} 
                                                        </div>
                                                        @if($review1->storytelling_rating)
                                                            @for ($i = 1; $i <= $review1->storytelling_rating; $i++)
                                                                <i class="fas fa-star"></i>
                                                            @endfor
                                                            @for($review1->storytelling_rating;$review1->storytelling_rating<5;$review1->storytelling_rating++)
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                            @endfor
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        @endif
                                        @if($review1->look_appearance)
                                        <li>
                                            <div class="content-list">
                                                <div class="left-box">
                                                    <h4>Look Appearance</h4>
                                                    <p>{!! $review1->look_appearance !!}</p>
                                                </div>
                                                <div class="right-box">
                                                    <div class="rating">
                                                        <div class="left-text"> 
                                                        	{{ number_format($review1->look_appearance_rating, 1) }}
                                                        </div>
                                                        @if($review1->look_appearance_rating)
                                                            @for ($i = 1; $i <= $review1->look_appearance_rating; $i++)
                                                                <i class="fas fa-star"></i>
                                                            @endfor
                                                            @for($review1->look_appearance_rating;$review1->look_appearance_rating<5;$review1->look_appearance_rating++)
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                            @endfor
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        @endif
                                    </ul>
                                    
                                    @else
                                         <h3>We are still reviewing your video Please wait.</h3>
                                    @endif
                                </div>
                            @else
	                        <div class="tab-pane fade" id="technique-{{$review1->id}}" role="tabpanel" aria-labelledby="technique-tab">
	                            <ul class="tab-list">
                                    
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Footwork</h4>
                                                <p>{!! $review1->footwork_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                    	{{ number_format($review1->footwork, 1) }} </div>
                                                    @if($review1->footwork)
                                                        @for ($i = 1; $i <= $review1->footwork; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review1->footwork;$review1->footwork<5;$review1->footwork++)
                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Alignment</h4>
                                                <p>{!! $review1->alingment_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                    	{{ number_format($review1->alingment, 1) }}</div>
                                                    @if($review1->alingment)
                                                        @for ($i = 1; $i <= $review1->alingment; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review1->alingment;$review1->alingment<5;$review1->alingment++)
                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Balance</h4>
                                                <p>{!! $review1->balance_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                    	{{ number_format($review1->balance, 1) }}</div>
                                                    @if($review1->balance)
                                                        @for ($i = 1; $i <= $review1->balance; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review1->balance;$review1->balance<5;$review1->balance++)
                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Focus</h4>
                                                <p>{!! $review1->focus_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                    	{{ number_format($review1->focus, 1) }}</div>
                                                    @if($review1->focus)
                                                        @for ($i = 1; $i <= $review1->focus; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review1->focus;$review1->focus<5;$review1->focus++)
                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Precision</h4>
                                                <p>{!! $review1->precision_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                    	{{ number_format($review1->precision, 1) }}</div>
                                                    @if($review1->precision)
                                                        @for ($i = 1; $i <= $review1->precision; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review1->precision;$review1->precision<5;$review1->precision++)
                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
	                        </div>

	                        <div class="tab-pane fade" id="expression-{{$review1->id}}" role="tabpanel" aria-labelledby="expression-tab">
	                            <ul class="tab-list">
                                    <!-- <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Feedback Summary</h4>
                                                <p>{!! $review1->feedback_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{$review1->feedback}}</div>
                                                    @if($review1->feedback)
                                                        @for ($i = 1; $i <= $review1->feedback; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review1->feedback;$review1->feedback<5;$review1->feedback++)
                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li> -->
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Energy</h4>
                                                <p>{!! $review1->energy_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                    	{{ number_format($review1->energy, 1) }}</div>
                                                    @if($review1->energy)
                                                        @for ($i = 1; $i <= $review1->energy; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review1->energy;$review1->energy<5;$review1->energy++)
                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Style</h4>
                                                <p>{!! $review1->style_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                    	{{ number_format($review1->style, 1) }}</div>
                                                    @if($review1->style)
                                                        @for ($i = 1; $i <= $review1->style; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review1->style;$review1->style<5;$review1->style++)
                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Creativity</h4>
                                                <p>{!! $review1->creativity_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                    	{{ number_format($review1->creativity, 1) }} </div>
                                                    @if($review1->creativity)
                                                        @for ($i = 1; $i <= $review1->creativity; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review1->creativity;$review1->creativity<5;$review1->creativity++)
                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Interpretation</h4>
                                                <p>{!! $review1->interpretation_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                    	{{ number_format($review1->interpretation, 1) }} </div>
                                                    @if($review1->interpretation)
                                                        @for ($i = 1; $i <= $review1->interpretation; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review1->interpretation;$review1->interpretation<5;$review1->interpretation++)
                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    
                                </ul>
	                        </div>

	                        <div class="tab-pane fade" id="choreography-{{$review1->id}}" role="tabpanel" aria-labelledby="choreography-tab">
	                            <ul class="tab-list">
                                    <!-- <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Feedback Summary</h4>
                                                <p>{!! $review1->feedback_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{$review1->feedback}}</div>
                                                    @if($review1->feedback)
                                                        @for ($i = 1; $i <= $review1->feedback; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review1->feedback;$review1->feedback<5;$review1->feedback++)
                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li> -->
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Formation</h4>
                                                <p>{!! $review1->formation_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                    	{{ number_format($review1->formation, 1) }} </div>
                                                    @if($review1->formation)
                                                        @for ($i = 1; $i <= $review1->formation; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review1->formation;$review1->formation<5;$review1->formation++)
                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Artisty</h4>
                                                <p>{!! $review1->artisty_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                    	{{ number_format($review1->artisty, 1) }} </div>
                                                    @if($review1->artisty)
                                                        @for ($i = 1; $i <= $review1->artisty; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review1->artisty;$review1->artisty<5;$review1->artisty++)
                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                   
                                </ul>
                                @if($review1->additional_tips)
                                    <div class="content-list pl-2 pt-4">
                                        <div class="left-box">

                                            <h4>Additional Tips</h4>
                                            <p>{!! $review1->additional_tips !!}</p>
                                        </div>
                                    </div>
                                @endif
	                        </div>
	                        @endif
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	@endforeach
@endsection
@section('js')
    
    <script>
        $(document).ready(function () {
            $('.video-thumb').click(function(e){
            	e.stopPropagation();
                var videoid = "#"+$(this).data('videoid');
                $(this).hide();
                $(videoid)[0].play();
               
            });

            $('.new-question1').click(function(){
                var formid = "#ask-question-"+$(this).data('videoid');
                $(this).hide();
                $(formid).show();
            });
             $('.submit-question').click(function(e){
                e.preventDefault();
                var formid = "#ask-question-"+$(this).data('videoid');
                if( $(formid + ' input[name="question-title"]').val() !=""){
                    var formdata = $(formid).serialize(); // here $(this) refere to the form its submitting
                    $.ajax({
                        type: 'POST',
                        url: "{{ url('/') }}/ask-question",
                        data: formdata, // here $(this) refers to the ajax object not form
                        success: function (data) {
                           console.log(data);
                           $(formid + ' .response').html('<h4>Question has been posted.</h4>');
                           $(formid + ' input[name="question-title"]').val('');
                        },
                    });
                }else{
                    $(formid + ' input[name="question-title"]').css('border-color','red');
                }
            });
        });


        
    </script>
@endsection
@section('js')
    
    <script>
        $(document).ready(function () {
            $('.video-thumb').click(function(){
                var videoid = "#"+$(this).data('videoid');
                $(this).hide();
                $(videoid)[0].play();
                $(videoid)[0].play();
            });
        });
    </script>
@endsection