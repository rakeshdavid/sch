@extends('layouts.user')
@section('content')
	<div class="main-content challenges-wrap">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-6">
                    <div class="coach-list">
                        <div class="top-box mb-0">
                            <h3 class="page-title">List of Challenges</h3>
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
                        <div class="media @if($challenge->id == $challenge_detail->id) active @endif">
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
                                    <h3><a href="/challenges/{{$challenge->id}}">{{$challenge->challenges_name}}</a></h3>
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
                        
                        @endif
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="coach-info-scrolleable">
                        <div class="audition-info">
                        	@if ($challenge_detail)
    						
                            <div class="media">
                               
                                <div class="img-box"  @if($challenge_detail->header_image) style="background-image: url(/uploads/challenge/{{$challenge_detail->header_image}});background-size: cover;background-position: center center;" @endif>
                                </div>
                                 
                                <div class="media-body">
                                    <div class="top-content">
                                        <div class="designation">{{$challenge_detail->title}}</div>
                                        <h3>{{$challenge_detail->challenges_name}}</h3>
                                        <div class="deadline">Deadline: {{ date('m.d.y', strtotime($challenge_detail->deadline)) }}</div>
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
                                <div class="audition-detail">
                                    <h4>Entry Fee</h4>
                                    <div class="package-title">This package includes:</div>
                                    <ul>
                                        <li>Verbal & written feedback</li>
                                        <li>Scorecard & comments</li>
                                        <li>Performance level placement </li>
                                    </ul>
                                </div>
                                    @if(in_array($challenge_detail->id,$participated_challenge))

                                    <a href="#" data-toggle="modal" data-target="#modal-right-{{$challenge_detail->id}}" class="btn btn-danger">See participation</a>
                                @else
                                    
                                    <a href="{{ url('challenge/participation') }}/{{$challenge_detail->id}}" class="btn btn-danger">Participate</a>
                                @endif
                                
                            </div>
                           
                            @endif
                        </div>
                        <div class="scrollable audition-info">
                            @if ($challenge_detail)
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
                @if (count($challenges) == 0)
                    <div class="col-md-12">
                        <h2 class="text-center" style="padding-top: 120px;">Opps! No new challenges</h2>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection

@section('modal-right')
@if($p_audtion_data)
    @foreach($p_audtion_data as $challengdata)
        @php
            if($challengdata->review !=''){
                
                $overallrating = ($challengdata->review->performance_quality_rating + $challengdata->review->technical_ability_rating + $challengdata->review->energy_style_rating + $challengdata->review->storytelling_rating + $challengdata->review->look_appearance_rating) / 5;
                $avgRating = number_format($overallrating,1);
            }
        @endphp
    <div class="modal fade firs-modal auditions-model right" id="modal-right-{{$challengdata->challenge_id}}" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close"><img src="/platform/img/close.png" class="img-fluid pause-video"></a>
                <div class="firs-snow-content">
                    <div class="videos-box">
                            @if($challengdata->review !='')
                                @if($challengdata->review->review_url !="" )
                                    <video id="video-{{$challengdata->id}}" width="100%" height="301" controls class="video-pause radius-20">
                                        <source src="{{env('USER_PLATFORM_LINK')}}/reviews/completed/{{$challengdata->review->review_url}}">
                                        Your browser does not support HTML5 video.
                                    </video>
                                @else
                                    <video id="video-{{$challengdata->id}}" width="100%" height="301" controls class="video-pause radius-20">
                                        <source src="{{asset('uploads/challenge')}}/{{$challengdata->video_link}}">
                                        Your browser does not support HTML5 video.
                                    </video>
                                @endif
                            @else
                                <video id="video-{{$challengdata->id}}" width="100%" height="301" controls class="video-pause radius-20">
                                    <source src="{{asset('uploads/challenge')}}/{{$challengdata->video_link}}">
                                        Your browser does not support HTML5 video.
                                </video>
                            @endif
                        
                        
                    </div>
                    
                    <h3>{{$challengdata->challenges->challenges_name}}</h3>
                    
                    <div class="updated">Uploaded in 
                    @php
                    $date = new \Carbon\Carbon($challengdata->created_at);
                    echo $date->format("m.d.Y g:i A");
                    @endphp
                    </div>
                    <ul class="nav" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview-{{$challengdata->challenge_id}}" role="tab" aria-controls="overview" aria-selected="true">Overview</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="technique-tab" data-toggle="tab" href="#technique-{{$challengdata->challenge_id}}" role="tab" aria-controls="technique" aria-selected="false">Score</a>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="overview-{{$challengdata->challenge_id}}" role="tabpanel" aria-labelledby="overview-tab">
                            @if($challengdata->review !='')

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
                                    @if($challengdata->review !='')
                                        <h4>Notes</h4>
                                        <p>{!! $challengdata->review->feedback !!}</p>
                                    @else
                                    <h3 style="padding-bottom:250px;">We are still reviewing your video Please wait.</h3>
                                    @endif
                                </div>
                            </div>
    
                        </div>
                        <div class="tab-pane fade" id="technique-{{$challengdata->challenge_id}}" role="tabpanel" aria-labelledby="technique-tab">
                            @if($challengdata->review !='')
                            <ul class="tab-list">
                                
                                @if($challengdata->review->performance_quality)
                                <li>
                                    <div class="content-list">
                                        <div class="left-box">
                                            <h4>Performance quality</h4>
                                            <p>{!! $challengdata->review->performance_quality !!}</p>
                                        </div>
                                        <div class="right-box">
                                            <div class="rating">
                                                <div class="left-text">{{number_format($challengdata->review->performance_quality_rating,1)}}
                                                </div>
                                                @if($challengdata->review->performance_quality_rating)
                                                    @for ($i = 1; $i <= $challengdata->review->performance_quality_rating; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($challengdata->review->performance_quality_rating;$challengdata->review->performance_quality_rating<5;$challengdata->review->performance_quality_rating++)
                                                        <span class="grey-star"><i class="fas fa-star"></i></span>
                                                    @endfor
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                @if($challengdata->review->technical_ability)
                                <li>
                                    <div class="content-list">
                                        <div class="left-box">
                                            <h4>Technical ability</h4>
                                            <p>{!! $challengdata->review->technical_ability !!}</p>
                                        </div>
                                        <div class="right-box">
                                            <div class="rating">
                                                <div class="left-text">{{number_format($challengdata->review->technical_ability_rating,1)}}
                                                </div>
                                                @if($challengdata->review->technical_ability_rating)
                                                    @for ($i = 1; $i <= $challengdata->review->technical_ability_rating; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($challengdata->review->technical_ability_rating;$challengdata->review->technical_ability_rating<5;$challengdata->review->technical_ability_rating++)
                                                        <span class="grey-star"><i class="fas fa-star"></i></span>
                                                    @endfor
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                @if($challengdata->review->energy_style)
                                <li>
                                    <div class="content-list">
                                        <div class="left-box">
                                            <h4>Energy style</h4>
                                            <p>{!!$challengdata->review->energy_style!!}</p>
                                        </div>
                                        <div class="right-box">
                                            <div class="rating">
                                                <div class="left-text">{{number_format($challengdata->review->energy_style_rating,1)}}
                                                </div>
                                                @if($challengdata->review->energy_style_rating)
                                                    @for ($i = 1; $i <= $challengdata->review->energy_style_rating; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($challengdata->review->energy_style_rating;$challengdata->review->energy_style_rating<5;$challengdata->review->energy_style_rating++)
                                                        <span class="grey-star"><i class="fas fa-star"></i></span>
                                                    @endfor
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                @if($challengdata->review->storytelling)
                                <li>
                                    <div class="content-list">
                                        <div class="left-box">
                                            <h4>Focus</h4>
                                            <p>{!! $challengdata->review->storytelling !!}</p>
                                        </div>
                                        <div class="right-box">
                                            <div class="rating">
                                                <div class="left-text">{{number_format($challengdata->review->storytelling_rating,1) }}
                                                </div>
                                                @if($challengdata->review->storytelling_rating)
                                                    @for ($i = 1; $i <= $challengdata->review->storytelling_rating; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($challengdata->review->storytelling_rating;$challengdata->review->storytelling_rating<5;$challengdata->review->storytelling_rating++)
                                                        <span class="grey-star"><i class="fas fa-star"></i></span>
                                                    @endfor
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                @if($challengdata->review->look_appearance)
                                <li>
                                    <div class="content-list">
                                        <div class="left-box">
                                            <h4>Look appearance</h4>
                                            <p>{!! $challengdata->review->look_appearance !!}</p>
                                        </div>
                                        <div class="right-box">
                                            <div class="rating">
                                                <div class="left-text">{{number_format( $challengdata->review->look_appearance_rating,1) }}
                                                </div>
                                                @if($challengdata->review->look_appearance_rating)
                                                    @for ($i = 1; $i <= $challengdata->review->look_appearance_rating; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($challengdata->review->look_appearance_rating;$challengdata->review->look_appearance_rating<5;$challengdata->review->look_appearance_rating++)
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
                                <h3 style="padding-bottom:250px;">We are still reviewing your video Please wait.</h3>
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