@extends('layouts.user')
@section('content')
	<div class="main-content challenges-wrap">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-6">
                    <div class="coach-list">
                        <h3 class="page-title">List of Challenges</h3>
                        <div class="top-box mb-0">
                        	<form method="post" action="{{url('challenges')}}">
                            <div class="row">
                            	
                					{!! csrf_field() !!}
	                                <div class="col-lg-12">
	                                    <input type="search" name="challenge-name" class="form-control" placeholder="Search by name…" value="{{$filter['name']}}">
	                                </div>
	                              
                            </div>
                        </form>
                        </div>
						@if (count($challenges) > 0)
						@foreach($challenges as $challenge)
                        <div class="media">
                            <div class="left-box">
                            	@if($challenge->gift)
                                <span class="challenge-title">{{$challenge->gift}}</span>
                                <hr>
                                @endif
                                @if($challenge->additional_gift)
                                
                                <span class="challenge-title">{{$challenge->additional_gift}}</span>
                                @endif
                            </div>
                            <div class="media-body">
                                <div class="info-box">
                                    <h3>{{$challenge->challenges_name}}</h3>
                                    <div class="user-name">
                                    	<p>{{ $challenge->short_desc}}</p>
                                    </div>
                                </div>
                                <div class="right-list">
                                    <ul>
                                        <li>
                                    @if(in_array($challenge->id,$participated_challenge))
                                        <a href="#" data-toggle="modal" data-target="#modal-right-{{$challenge->id}}"><span>$ {{$challenge->challenges_fee}}</span>Entry Fee <div class="hover-text">See participation</div></a>
                                    @else
                                        <a href="{{url('challenges')}}/{{$challenge->id}}"><span>$ {{$challenge->challenges_fee}}</span>Entry Fee <div class="hover-text">Read More</div></a>
                                    @endif
                                    </li>
                                  
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                        	<h2>No Challenge found</h2>
                        @endif
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="audition-info">
                    	@if ($challenge_detail)
						
                        <div class="media">
                            <div class="img-box">
                            	@if($challenge_detail->header_image)
                            	<img src="{{ asset('/uploads/challenge/') }}/{{$challenge_detail->header_image}}" alt="" class="img-fluid">
                                
                                @endif
                            </div>
                            <div class="media-body">
                                <div class="top-content">
                                    <div class="designation">{{$challenge_detail->title}}</div>
                                    <h3>{{$challenge_detail->challenges_name}}</h3>
                                    <div class="deadline">Deadline: {{ date('j.m', strtotime($challenge_detail->deadline)) }}</div>
                                </div>
                                <div class="audition">
                                    <div class="right-content">

                                        <ul class="info-list">
                                        	@if($challenge_detail->gift)
                                            <li>
                                                <h4>{{$challenge_detail->gift}}</h4>
                                            </li>
                                            @endif
                                            @if($challenge_detail->additional_gift)
                                            <li>
                                                <h4>{{$challenge_detail->additional_gift}}</h4>
                                            </li>
                                            @endif
                                        </ul>
                                        <h5>Prize</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="entry-info">
                            <div class="price"><sub>$</sub> {{$challenge_detail->challenges_fee}}</div>
                            <h4>Entry Fee</h4>
                            <div class="package-title">This package includes:</div>
                            {!! $challenge_detail->challenges_detail !!}
                            @if(in_array($challenge_detail->id,$participated_challenge))
                                <a href="#" data-toggle="modal" data-target="#modal-right-{{$challenge_detail->id}}" class="btn btn-danger">See participation</a>
                            @else
                                
                                <a href="{{ url('challenge/participation') }}/{{$challenge_detail->id}}" class="btn btn-danger">Participate</a>
                            @endif
                            
                        </div>

                        <div id="accordion" class="accordion">
                        	@if($challenge_detail->description)
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <a href="#" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"> Description </a>
                                </div>

                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                    <div class="card-body">
                                        <p>{!! $challenge_detail->description !!}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if($challenge_detail->requirement)
                            <div class="card">
                                <div class="card-header" id="headingTwo">
                                    <a href="#" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"> Requirements</a>
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                    <div class="card-body">
                                        <p>{!! $challenge_detail->requirement !!}</p>
                                    </div>
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
@endsection

@section('modal-right')
@if(count($p_audtion_data) > 0)
    @foreach($p_audtion_data as $challengdata)
        @php
            $reviews = $challenge_review[$challengdata->id];
            if(count($reviews) > 0){
                $review = $reviews[0];
                $overallrating = ($review->feedback + $review->footwork + $review->alingment + $review->balance + $review->focus + $review->artisty) / 6;
                $avgRating = number_format($overallrating,2);
            }
        @endphp
    <div class="modal fade firs-modal auditions-model right" id="modal-right-{{$challengdata->challenge_id}}" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close"><img src="/assets/img/close.png" class="img-fluid"></a>
                <div class="firs-snow-content">
                    <div class="videos-box">
                        @if($challengdata->video_type == 'file')
                            <video width="100%" height="301" controls >
                                <source src="{{asset('uploads/challenge')}}/{{$challengdata->video_link}}">
                                Your browser does not support HTML5 video.
                            </video>
                        @else
                            <iframe width="100%" height="301" src="{{$challengdata->video_link}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        @endif
                        
                    </div>
                    @php 
                    $temp = $model->challengeById($challengdata->challenge_id); 
                    @endphp
                    <h3>{{$temp->challenges_name}}</h3>
                    
                    <div class="updated">Uploaded in 
                    @php
                    $date = new \Carbon\Carbon($challengdata->created_at);
                    echo $date->format("m.d.Y g:i A");
                    @endphp
                    </div>
                    <ul class="nav" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Overview</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="technique-tab" data-toggle="tab" href="#technique" role="tab" aria-controls="technique" aria-selected="false">Score</a>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                            @if(count($reviews) > 0)

                            <div class="rating overal-rating">
                                <div class="left-text">{{$avgRating}}</div>
                                <div class="right-star">
                                    @if($avgRating)
                                        @for ($i = 1; $i < $avgRating; $i++)
                                            <i class="fas fa-star"></i>
                                        @endfor
                                        @for($avgRating;$avgRating<=5;$avgRating++)
                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                        @endfor
                                    @endif
                                    
                                    <span class="overal">Overal Rating</span>
                                </div>
                            </div>
                            @endif
                            <div class="content-list">
                                <div class="left-box">
                                    @if(count($reviews) > 0)
                                        <h4>Feedback Summary</h4>
                                        <p>{{$review->feedback_summary}}</p>
                                    @else
                                    <h3>We are still reviewing your video Please wait.</h3>
                                    @endif
                                </div>
                            </div>
    
                        </div>
                        <div class="tab-pane fade" id="technique" role="tabpanel" aria-labelledby="technique-tab">
                            @if(count($reviews) > 0)
                            <ul class="tab-list">
                                @if($review->feedback_summary)
                                <li>
                                    <div class="content-list">
                                        <div class="left-box">
                                            <h4>Feedback Summary</h4>
                                            <p>{{$review->feedback_summary}}</p>
                                        </div>
                                        <div class="right-box">
                                            <div class="rating">
                                                <div class="left-text">{{$review->feedback}}
                                                </div>
                                                @if($review->feedback)
                                                    @for ($i = 1; $i < $review->feedback; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($review->feedback;$review->feedback<=5;$review->feedback++)
                                                        <span class="grey-star"><i class="fas fa-star"></i></span>
                                                    @endfor
                                                @endif
                                                
                                                
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                @if($review->footwork)
                                <li>
                                    <div class="content-list">
                                        <div class="left-box">
                                            <h4>Footwork</h4>
                                            <p>{{$review->footwork_comment}}</p>
                                        </div>
                                        <div class="right-box">
                                            <div class="rating">
                                                <div class="left-text">{{$review->footwork}}
                                                </div>
                                                @if($review->footwork)
                                                    @for ($i = 1; $i < $review->footwork; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($review->footwork;$review->footwork<=5;$review->footwork++)
                                                        <span class="grey-star"><i class="fas fa-star"></i></span>
                                                    @endfor
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                @if($review->alingment)
                                <li>
                                    <div class="content-list">
                                        <div class="left-box">
                                            <h4>Alignment</h4>
                                            <p>{{$review->alingment_comment}}</p>
                                        </div>
                                        <div class="right-box">
                                            <div class="rating">
                                                <div class="left-text">{{$review->alingment}}
                                                </div>
                                                @if($review->alingment)
                                                    @for ($i = 1; $i < $review->alingment; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($review->alingment;$review->alingment<=5;$review->alingment++)
                                                        <span class="grey-star"><i class="fas fa-star"></i></span>
                                                    @endfor
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                @if($review->balance)
                                <li>
                                    <div class="content-list">
                                        <div class="left-box">
                                            <h4>Balanced</h4>
                                            <p>{{$review->balance_comment}}</p>
                                        </div>
                                        <div class="right-box">
                                            <div class="rating">
                                                <div class="left-text">{{$review->balance}}
                                                </div>
                                                @if($review->balance)
                                                    @for ($i = 1; $i < $review->balance; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($review->balance;$review->balance<=5;$review->balance++)
                                                        <span class="grey-star"><i class="fas fa-star"></i></span>
                                                    @endfor
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                @if($review->focus)
                                <li>
                                    <div class="content-list">
                                        <div class="left-box">
                                            <h4>Focus</h4>
                                            <p>{{$review->focus_comment}}</p>
                                        </div>
                                        <div class="right-box">
                                            <div class="rating">
                                                <div class="left-text">{{$review->focus}}
                                                </div>
                                                @if($review->focus)
                                                    @for ($i = 1; $i < $review->focus; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($review->focus;$review->focus<=5;$review->focus++)
                                                        <span class="grey-star"><i class="fas fa-star"></i></span>
                                                    @endfor
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                @if($review->artisty)
                                <li>
                                    <div class="content-list">
                                        <div class="left-box">
                                            <h4>Artisty</h4>
                                            <p>{{$review->artisty_comment}}</p>
                                        </div>
                                        <div class="right-box">
                                            <div class="rating">
                                                <div class="left-text">{{$review->artisty}}
                                                </div>
                                                @if($review->artisty)
                                                    @for ($i = 1; $i < $review->artisty; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($review->artisty;$review->artisty<=5;$review->artisty++)
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endif
@endsection