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