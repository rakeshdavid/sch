@extends('layouts.user')

@section('content')
<div class="main-content my-reviews-content">
            <div class="container-fluid">
                <div class="top-box">
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
                                        	<video width="100%" height="300" controls class="embed-responsive-item">
					                            <source src="{{url('/') . config('video.completed_review_path') . $review->review_url}}">
					                            Your browser does not support HTML5 video.
					                        </video>
                                            
                                        </div>
                                        <div class="card-body">
                                            <h3  class="cursor-pointer" data-toggle="modal" data-target="#modal-right-{{$review->id}}">
												{{$review->video_name}}
                                            </h3>
                                            <div class="level"> Level: 
											@if($review->video_level == 3)
												Advance
											@elseif($review->video_level == 2)
												Intermediate
											@else
											Beginner
											@endif
                                            </div>
                                            <div class="rating"><span class="rating-text">{{ strlen($review->overall_rating) == 1 ? $review->overall_rating . '.0' : $review->overall_rating}}</span>
                                            	<i class="fa fa-star"></i> <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><span class="grey-star"><i class="fa fa-star"></i></span>
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
	                                        	<video width="100%" height="300" controls class="embed-responsive-item">
						                            <source src="{{url('/') . config('video.completed_review_path') . $review->review_url}}">
						                            Your browser does not support HTML5 video.
						                        </video>
	                                            
	                                        </div>
	                                        <div class="card-body">
	                                            <h3  class="cursor-pointer" data-toggle="modal" data-target="#modal-right-{{$review->id}}">
													{{$review->video_name}}
                                            	</h3>
	                                            <div class="level"> Level: 
												@if($review->video_level == 3)
													Advance
												@elseif($review->video_level == 2)
													Intermediate
												@else
													Beginner
												@endif
	                                            </div>
	                                            <div class="rating"><span class="rating-text">{{ strlen($review->overall_rating) == 1 ? $review->overall_rating . '.0' : $review->overall_rating}}</span>
	                                            	<i class="fa fa-star"></i> <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><span class="grey-star"><i class="fa fa-star"></i></span>
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
	                                        	<video width="100%" height="300" controls class="embed-responsive-item">
						                            <source src="{{url('/') . config('video.completed_review_path') . $review->review_url}}">
						                            Your browser does not support HTML5 video.
						                        </video>
	                                            
	                                        </div>
	                                        <div class="card-body">
	                                            <h3  class="cursor-pointer" data-toggle="modal" data-target="#modal-right-{{$review->id}}">
													{{$review->video_name}}
                                            	</h3>
	                                            <div class="level"> Level: 
												@if($review->video_level == 3)
													Advance
												@elseif($review->video_level == 2)
													Intermediate
												@else
												Beginner
												@endif
	                                            </div>
	                                            <div class="rating"><span class="rating-text">{{ strlen($review->overall_rating) == 1 ? $review->overall_rating . '.0' : $review->overall_rating}}</span>
	                                            	<i class="fa fa-star"></i> <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><span class="grey-star"><i class="fa fa-star"></i></span>
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
	                                        	<video width="100%" height="300" controls class="embed-responsive-item">
						                            <source src="{{url('/') . config('video.completed_review_path') . $review->review_url}}">
						                            Your browser does not support HTML5 video.
						                        </video>
	                                            
	                                        </div>
	                                        <div class="card-body">
	                                            <h3>{{$review->video_name}}</h3>
	                                            <div class="level"> Level: 
												@if($review->video_level == 3)
													Advance
												@elseif($review->video_level == 2)
													Intermediate
												@else
												Beginner
												@endif
	                                            </div>
	                                            <div class="rating"><span class="rating-text">{{ strlen($review->overall_rating) == 1 ? $review->overall_rating . '.0' : $review->overall_rating}}</span>
	                                            	<i class="fa fa-star"></i> <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><span class="grey-star"><i class="fa fa-star"></i></span>
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
	@foreach($reviews as $review)
		<div class="modal fade firs-modal right" id="modal-right-{{$review->id}}" tabindex="-1" role="dialog">
	        <div class="modal-dialog" role="document">
	            <div class="modal-content">
	                <a href="#" class="close" data-dismiss="modal" aria-label="Close"><img src="/assets/img/close.png" class="img-fluid"></a>
	                <div class="firs-snow-content">
	                    <div class="videos-box">
	                        @if(strpos($review->video_url, 'youtube') !== false)

                                @php
                                $query ="";
                                    $parts = parse_url($review->video_url);
                                    if(array_key_exists('query',$parts)){
                                        parse_str($parts['query'], $query);
                                        $videoID = $query['v'];
                                        $youtubeurl = "https://www.youtube.com/embed/".$videoID; 
                                    }else{
                                        $youtubeurl = $review->video_url; 
                                    }
                                    
                                    
                                @endphp
                                    <iframe width="100%" height="300" src="{{$youtubeurl}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    
                            @else
                                <video id="video-{{$review->id}}" width="100%" height="300" controls >
                                    <source src="{{url('/') . config('video.user_video_path') . $review->video_url}}">
                                    Your browser does not support HTML5 video.
                                </video>
                                    
                            @endif
	                    </div>
	                    <h3>{{$review->video_name}}</h3>
	                    <div class="level">Level: 
	                    	@if($review->video_level == 3)
								Advance
							@elseif($review->video_level == 2)
								Intermediate
							@else
								Beginner
							@endif
						</div>
	                    <div class="updated">Uploaded in @php
                                    $v_data = new \Carbon\Carbon($review->created_at);
                                    echo $v_data->format("m.d.Y g:i A");
                                    @endphp</div>
	                    <ul class="nav" id="myTab" role="tablist">
	                        <li class="nav-item">
	                            <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Overview</a>
	                        </li>
	                        <li class="nav-item">
	                            <a class="nav-link" id="technique-tab" data-toggle="tab" href="#technique" role="tab" aria-controls="technique" aria-selected="false">Technique</a>
	                        </li>
	                        <li class="nav-item">
	                            <a class="nav-link" id="expression-tab" data-toggle="tab" href="#expression" role="tab" aria-controls="expression" aria-selected="false">Expression</a>
	                        </li>
	                        <li class="nav-item">
	                            <a class="nav-link" id="choreography-tab" data-toggle="tab" href="#choreography" role="tab" aria-controls="choreography" aria-selected="false">Choreography</a>
	                        </li>
	                    </ul>

	                    <div class="tab-content" id="nav-tabContent">
	                        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
	                            <div class="rating overal-rating">
	                                <div class="left-text">{{ strlen($review->overall_rating) == 1 ? $review->overall_rating . '.0' : $review->overall_rating}}</div>
	                                <div class="right-star">
	                                	<i class="fas fa-star"></i> <i class="fas fa-star"></i><i class="fas fa-star"></i> <i class="fas fa-star"></i><span class="grey-star"><i class="fas fa-star"></i></span>
	                                    <span class="overal">Overal Rating</span>
	                                </div>
	                            </div>
	                            <div class="content-list">
	                                <div class="left-box">
	                                	@if($review->feedback_comment !="")
		                                    <h4>Feedback Summary</h4>
		                                    <p>{!! $review->feedback_comment !!}</p>
	                                    @endif
	                                </div>
	                            </div>
	                            <div class="content-list d-block">
	                                <div class="media">
	                                    @php
		                                    $coach = $model->getCoachDetailByVideoId($review->video_id);
		                                @endphp
	                                    <div class="left-box">
	                                        <h4>Choach</h4>
	                                        <h3>{{$coach->first_name}} {{$coach->last_name}}</h3>
	                                    </div>
	                                    <div class="img-box coach-image">
	                                        @if($coach->avatar)
	                                            <img src="{{url('/')}}{{$coach->avatar}}" class="img-fluid">
	                                        @endif
	                                    </div>
	                                </div>
	                                <ul class="question-list">
	                                    @php
                                            $questions = $model->getReviewQuestion($review->video_id);
                                        @endphp
                                        @if(count($questions) > 0)
                                            @foreach($questions as $question)
                                                <li>
                                                    <h4>Question {{$question->question_number}}:</h4>
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
	                                    <form method="post" class="question-form" action="" id="ask-question-{{$review->video_id}}">
                                            {!! csrf_field() !!}
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <input type="hidden" name="video-id" value="{{$review->video_id}}" />
                                                    <input type="text" name="question-title" class="form-control" placeholder="Ask your question..." />
                                                </div>
                                                <div class="col-lg-12 pt-3">

                                                    <button class="btn btn-outline-danger submit-question" data-videoid="{{$review->video_id}}">Ask Question</button>
                                                </div>
                                                <div class="col-lg-12 response">
                                                </div>
                                            </div>
                                        </form>
                                        <a href="javascript:void(0)" class="btn btn-outline-danger new-question1" data-videoid="{{$review->video_id}}">New Question</a>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="tab-pane fade" id="technique" role="tabpanel" aria-labelledby="technique-tab">
	                            <ul class="tab-list">
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Feedback Summary</h4>
                                                <p>{!! $review->feedback_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{$review->feedback}}</div>
                                                    @if($review->feedback)
                                                        @for ($i = 1; $i <= $review->feedback; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review->feedback;$review->feedback<5;$review->feedback++)
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
                                                <h4>Footwork</h4>
                                                <p>{!! $review->footwork_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{$review->footwork}}</div>
                                                    @if($review->footwork)
                                                        @for ($i = 1; $i <= $review->footwork; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review->footwork;$review->footwork<5;$review->footwork++)
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
                                                <p>{!! $review->alingment_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{$review->alingment }}</div>
                                                    @if($review->alingment)
                                                        @for ($i = 1; $i <= $review->alingment; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review->alingment;$review->alingment<5;$review->alingment++)
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
                                                <h4>Balanced</h4>
                                                <p>{!! $review->balance_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{$review->balance}}</div>
                                                    @if($review->balance)
                                                        @for ($i = 1; $i <= $review->balance; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review->balance;$review->balance<5;$review->balance++)
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
                                                <p>{!! $review->focus_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{$review->focus}}</div>
                                                    @if($review->focus)
                                                        @for ($i = 1; $i <= $review->focus; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review->focus;$review->focus<5;$review->focus++)
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
                                                <p>{!! $review->precision_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{$review->precision}}</div>
                                                    @if($review->precision)
                                                        @for ($i = 1; $i <= $review->precision; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review->precision;$review->precision<5;$review->precision++)
                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
	                        </div>

	                        <div class="tab-pane fade" id="expression" role="tabpanel" aria-labelledby="expression-tab">
	                            <ul class="tab-list">
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Feedback Summary</h4>
                                                <p>{!! $review->feedback_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{$review->feedback}}</div>
                                                    @if($review->feedback)
                                                        @for ($i = 1; $i <= $review->feedback; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review->feedback;$review->feedback<5;$review->feedback++)
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
                                                <h4>Footwork</h4>
                                                <p>{!! $review->footwork_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{$review->footwork}}</div>
                                                    @if($review->footwork)
                                                        @for ($i = 1; $i <= $review->footwork; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review->footwork;$review->footwork<5;$review->footwork++)
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
                                                <p>{!! $review->alingment_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{$review->alingment }}</div>
                                                    @if($review->alingment)
                                                        @for ($i = 1; $i <= $review->alingment; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review->alingment;$review->alingment<5;$review->alingment++)
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
                                                <h4>Balanced</h4>
                                                <p>{!! $review->balance_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{$review->balance}}</div>
                                                    @if($review->balance)
                                                        @for ($i = 1; $i <= $review->balance; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review->balance;$review->balance<5;$review->balance++)
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
                                                <p>{!! $review->focus_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{$review->focus}}</div>
                                                    @if($review->focus)
                                                        @for ($i = 1; $i <= $review->focus; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review->focus;$review->focus<5;$review->focus++)
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
                                                <p>{!! $review->precision_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{$review->precision}}</div>
                                                    @if($review->precision)
                                                        @for ($i = 1; $i <= $review->precision; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review->precision;$review->precision<5;$review->precision++)
                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
	                        </div>

	                        <div class="tab-pane fade" id="choreography" role="tabpanel" aria-labelledby="choreography-tab">
	                            <ul class="tab-list">
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Feedback Summary</h4>
                                                <p>{!! $review->feedback_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{$review->feedback}}</div>
                                                    @if($review->feedback)
                                                        @for ($i = 1; $i <= $review->feedback; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review->feedback;$review->feedback<5;$review->feedback++)
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
                                                <h4>Footwork</h4>
                                                <p>{!! $review->footwork_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{$review->footwork}}</div>
                                                    @if($review->footwork)
                                                        @for ($i = 1; $i <= $review->footwork; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review->footwork;$review->footwork<5;$review->footwork++)
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
                                                <p>{!! $review->alingment_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{$review->alingment }}</div>
                                                    @if($review->alingment)
                                                        @for ($i = 1; $i <= $review->alingment; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review->alingment;$review->alingment<5;$review->alingment++)
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
                                                <h4>Balanced</h4>
                                                <p>{!! $review->balance_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{$review->balance}}</div>
                                                    @if($review->balance)
                                                        @for ($i = 1; $i <= $review->balance; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review->balance;$review->balance<5;$review->balance++)
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
                                                <p>{!! $review->focus_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{$review->focus}}</div>
                                                    @if($review->focus)
                                                        @for ($i = 1; $i <= $review->focus; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review->focus;$review->focus<5;$review->focus++)
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
                                                <p>{!! $review->precision_comment !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{$review->precision}}</div>
                                                    @if($review->precision)
                                                        @for ($i = 1; $i <= $review->precision; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($review->precision;$review->precision<5;$review->precision++)
                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
	                        </div>

	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	@endforeach
@endsection