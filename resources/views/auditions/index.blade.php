@extends('layouts.user')
@section('content')
<div class="main-content auditions-wrap">
    <div class="container-fluid">
        <div class="row">
         @if(!request()->exists('single'))
        <div class="col-xl-6">
            <div class="coach-list">
                <div class="top-box mb-0">
                    <h3 >List of Auditions</h3>
                	<form method="post" action="{{url('filter-auditions')}}">
                		{!! csrf_field() !!}
	                    <div class="row">
	                    	<div class="col-lg-6">
	                            <input type="search" name="audition-name" class="form-control" placeholder="Search by name…" value="{{$filter['name']}}">
	                        </div>
	                        <div class="col-lg-6">
	                            <div class="btn-group">
	                                <div class="btn-group">
	                                    <select name="sortby" class="form-control">
	                                    	<option value="asc" @if($filter['sortby'] == 'asc') selected="selected" @endif>Ascending</option>
	                                    	<option value="desc" @if($filter['sortby'] == 'desc') selected="selected" @endif>Descending</option>
	                                    	
	                                    </select>
	                                </div>
	                                <div class="btn-group">
	                                    <select name="talent" class="form-control">
	                                    	<option value="">Talent</option>
	                                    	@foreach($activity_genres as $talent)
	                                    		<option value="{{$talent->id}}" @if($filter['talent'] == $talent->id) selected="selected" @endif>{{$talent->name}}</option>
	                                    	@endforeach
	                                    </select>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="col-lg-12">
	                        	<input type="submit" name="filter" value="filter" class="btn btn-danger" />
	                        </div>
	                    </div>
                    </form>
                </div>
				@if(count($auditions) > 0)
				@foreach($auditions as $audition)
                <div class="media @if($audition->id == $audition_detail->id) active @endif ">
                    <div class="date-box">
                        <div class="deadline">Deadline</div>
                        <div class="date">{{ date('d', strtotime($audition->deadline)) }}</div>
                        <div class="month">{{ date('F', strtotime($audition->deadline)) }}</div>
                    </div>
                    <div class="media-body">
                        <div class="info-box">
                            <div class="designation">{{$audition->title}}</div>
                            <h3><a href="/auditions/{{$audition->id}}">@if($audition){{$audition->audition_name}}@endif</a></h3>
                            <div class="loaction">{{$audition->location}}</div>
                        </div>
                        <div class="right-list">
                            <ul>
                                <li>
                                	@if(in_array($audition->id,$participated_audition))
                                		<a href="#" data-toggle="modal" data-target="#modal-right-{{$audition->id}}" class="fixed-text">See participation</a>
                                	@else
                                		<a href="{{url('auditions')}}/{{$audition->id}}"><span>$ {{$audition->audition_fee}}</span>Entry Fee <div class="hover-text">Read More</div></a>
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

         @endif
        <div class="@if(!request()->exists('single')) col-xl-6 @else col-xl-8 @endif ">
            <div class="coach-info-scrolleable">
                <div class="audition-info">
                	@if($audition_detail)
    					
    		                <div class="media">
                                <div class="img-box" @if( $audition_detail->header_image !="") style="background-image: url({{asset('uploads/auditions')}}/{{ $audition_detail->header_image}});background-size: cover;background-position: center center;" @endif>
    		                    </div>
                                
    		                    <div class="media-body">
    		                        <div class="aerial-logo">
    		                        	@if( $audition_detail->logo !="")
    		                        		<img src="{{asset('uploads/auditions')}}/{{ $audition_detail->logo}}" alt="" class="img-fluid">
    		                        	@endif
    		                        </div>
    		                        <div class="right-content">
    		                            <div class="designation">{{ $audition_detail->title}}</div>
    		                            <h3>@if($audition_detail){{$audition_detail->audition_name}}@endif</h3>
    		                            <ul class="info-list">
    		                                <li>
    		                                    <h4>{{ date('m.d.y', strtotime( $audition_detail->deadline)) }}</h4>
    		                                     <h5>Deadline</h5>
    		                                </li>
    		                                 <li>
    		                                    <h4>{{ $audition_detail->location}}</h4>
    		                                   <h5>Location</h5>
    		                                </li>
    		                            </ul>
    		                        </div>
    		                    </div>
    		                </div>
    		    
    		                <div class="entry-info">
    		                    <div class="price"><sub>$</sub> {{ $audition_detail->audition_fee}}</div>
    		                    <h4>Entry Fee</h4>
                                <div class="package-title">This package includes:</div>
                                <div class="audition-detail">
        		                    <ul>
                                        <li>Verbal & written feedback</li>
                                        <li>Scorecard & comments</li>
                                        <li>Performance level placement </li>
                                    </ul>
                                </div>
    		                    @if(in_array($audition_detail->id,$participated_audition))
    								<a href="#" class="btn btn-danger" data-toggle="modal" data-target="#modal-right-{{$audition_detail->id}}">See participation</a>
    		                    @else
    		                    	<a href="{{url('auditions/participation')}}/{{ $audition_detail->id}}" class="btn btn-danger">Participate</a>
    		                    @endif
    		                    
    		                </div>
    		                	
    		            @endif
                </div>
                <div class="scrollable audition-info">
                            @if ($audition_detail)
                             <div id="accordion" class="accordion">
                                @if( $audition_detail->description)
                                    <div class="card">
                                        <div class="card-header" id="headingOne">
                                            <a href="#" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"> Description </a>
                                        </div>

                                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                            <div class="card-body">
                                                <p>{!!  $audition_detail->description !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if( $audition_detail->requirement)
                                <div class="card">
                                    <div class="card-header" id="headingTwo">
                                        <a href="#" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"> Requirements</a>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                        <div class="card-body">
                                            <p>{!! $audition_detail->requirement!!}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif
                 </div>
            </div>
        </div>
        @if (count($auditions) == 0 && !request()->exists('single'))
            <div class="col-md-12">
                <h2 class="text-center" style="padding-top: 120px;">Opps! No new auditions</h2>
            </div>
        @endif
     </div>

    </div>
</div>
@endsection
@section('modal-right')
@if(isset($audition_reviews))
    @foreach($audition_reviews as $auditiondata)
        <div class="modal fade firs-modal auditions-model right" id="modal-right-{{$auditiondata->audition_id}}" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close"><img src="/platform/img/close.png" class="img-fluid pause-video"></a>
                    <div class="firs-snow-content">
                        <div class="videos-box">
                            @if($auditiondata->video_type == 'file')
                                @if(!empty($auditiondata->auditionreviewnew) && $auditiondata->auditionreviewnew->review_url !="" )
                                    <video id="video-{{$auditiondata->id}}" width="100%" height="301" controls class="video-pause radius-20">
                                        <source src="{{env('USER_PLATFORM_LINK')}}/reviews/completed/{{$auditiondata->auditionreviewnew->review_url}}">
                                        Your browser does not support HTML5 video.
                                    </video>
                                @else
                                    <video id="video-{{$auditiondata->id}}" width="100%" height="301" controls class="video-pause radius-20">
                                        <source src="{{asset('uploads/auditions')}}/{{$auditiondata->video_link}}">
                                        Your browser does not support HTML5 video.
                                    </video>
                                @endif
                            @else
                               
                                @if(strpos($auditiondata->video_link, 'youtube') !== false)

                                    @php
                                    $query ="";
                                    $parts = parse_url($auditiondata->video_link);
                                    if(array_key_exists('query',$parts)){
                                        parse_str($parts['query'], $query);
                                        $videoID = $query['v'];
                                        $youtubeurl = "https://www.youtube.com/embed/".$videoID; 
                                    }else{
                                        $youtubeurl = $auditiondata->video_link; 
                                    }
                                        
                                        
                                    @endphp
                                    <iframe width="100%" height="301" src="{{$youtubeurl}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    @if($auditiondata->thumbnail !='')
                                        <img class="video-thumb" data-videoid="all-video-{{$auditiondata->id}}" src="{{$auditiondata->thumbnail}}" />
                                    @endif
                                @else
                                    <iframe width="100%" height="301" src="{{$auditiondata->video_link}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                @endif
                            @endif
                            
                        </div>
                        
                        <h3>@if($auditiondata->audition){{$auditiondata->audition->audition_name}}@endif</h3>
                        <div class="level">Level: 
                            @if($auditiondata->audition && $auditiondata->audition->level == 3)
                                Advanced
                            @elseif($auditiondata->audition && $auditiondata->audition->level == 2)
                                Intermediate
                            @else
                                Beginner
                            @endif
                        </div>
                        <div class="updated">Uploaded on 
                        @php
                        $date = new \Carbon\Carbon($auditiondata->created_at);
                        echo $date->format("m.d.Y g:i A");
                        @endphp
                        </div>
                        <ul class="nav" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="overview-tab-{{$auditiondata->id}}" data-toggle="tab" href="#overview-{{$auditiondata->id}}" role="tab" aria-controls="overview" aria-selected="true">Overview</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="technique-tab-{{$auditiondata->id}}" data-toggle="tab" href="#technique-{{$auditiondata->id}}" role="tab" aria-controls="technique" aria-selected="false">Score</a>
                            </li>
                        </ul>
                        
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="overview-{{$auditiondata->id}}" role="tabpanel" aria-labelledby="overview-tab">
                                @if($auditiondata->auditionreviewnew !='')
                                    @php
                                        $overallrating = ($auditiondata->auditionreviewnew->performance_quality_rating + $auditiondata->auditionreviewnew->technical_ability_rating + $auditiondata->auditionreviewnew->energy_style_rating + $auditiondata->auditionreviewnew->storytelling_rating + $auditiondata->auditionreviewnew->look_appearance_rating) / 5;
                                        $avgRating = number_format($overallrating,1);
                                    
                                    @endphp
                                <div class="rating overal-rating">
                                    <div class="left-text">{{$avgRating}}</div>
                                    <div class="right-star">
                                        @if($avgRating)
                                            @for ($i = 1; $i <= $avgRating; $i++)
                                                <i class="fas fa-star"></i>
                                            @endfor
                                            @for($avgRating;$avgRating<5;$avgRating++)
                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                            @endfor
                                        @endif
                                        
                                        <span class="overal">Overal Rating</span>
                                    </div>
                                </div>
                                @endif
                                <div class="content-list">
                                    <div class="left-box">
                                        @if($auditiondata->auditionreviewnew !='')
                                            <h4>Notes</h4>
                                            <p>{!! $auditiondata->auditionreviewnew->feedback !!}</p>
                                        @else
                                        <h3 style="padding-bottom:250px;">We are still reviewing your video Please wait.</h3>
                                        @endif
                                    </div>
                                </div>
        
                            </div>
                            <div class="tab-pane fade" id="technique-{{$auditiondata->id}}" role="tabpanel" aria-labelledby="technique-tab">
                                @if($auditiondata->auditionreviewnew !='')
                                <ul class="tab-list">
                                    
                                    @if($auditiondata->auditionreviewnew->performance_quality)
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Performance Quality</h4>
                                                <p>{!! $auditiondata->auditionreviewnew->performance_quality !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{number_format($auditiondata->auditionreviewnew->performance_quality_rating, 1)}}
                                                    </div>
                                                    @if($auditiondata->auditionreviewnew->performance_quality_rating)
                                                        @for ($i = 1; $i <= $auditiondata->auditionreviewnew->performance_quality_rating; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($auditiondata->auditionreviewnew->performance_quality_rating;$auditiondata->auditionreviewnew->performance_quality_rating<5;$auditiondata->auditionreviewnew->performance_quality_rating++)
                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    @if($auditiondata->auditionreviewnew->technical_ability)
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Technical Ability</h4>
                                                <p>{!! $auditiondata->auditionreviewnew->technical_ability !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{number_format($auditiondata->auditionreviewnew->technical_ability_rating, 1)}}
                                                    </div>
                                                    @if($auditiondata->auditionreviewnew->technical_ability_rating)
                                                        @for ($i = 1; $i <= $auditiondata->auditionreviewnew->technical_ability_rating; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($auditiondata->auditionreviewnew->technical_ability_rating;$auditiondata->auditionreviewnew->technical_ability_rating<5;$auditiondata->auditionreviewnew->technical_ability_rating++)
                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    @if($auditiondata->auditionreviewnew->energy_style)
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Energy/Style</h4>
                                                <p>{!! $auditiondata->auditionreviewnew->energy_style !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{number_format($auditiondata->auditionreviewnew->energy_style_rating,1)}}
                                                    </div>
                                                    @if($auditiondata->auditionreviewnew->energy_style_rating)
                                                        @for ($i = 1; $i <= $auditiondata->auditionreviewnew->energy_style_rating; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($auditiondata->auditionreviewnew->energy_style_rating;$auditiondata->auditionreviewnew->energy_style_rating<5;$auditiondata->auditionreviewnew->energy_style_rating++)
                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    @if($auditiondata->auditionreviewnew->storytelling)
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Storytelling</h4>
                                                <p>{!! $auditiondata->auditionreviewnew->storytelling !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{number_format($auditiondata->auditionreviewnew->storytelling_rating,1)}}
                                                    </div>
                                                    @if($auditiondata->auditionreviewnew->storytelling_rating)
                                                        @for ($i = 1; $i <= $auditiondata->auditionreviewnew->storytelling_rating; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($auditiondata->auditionreviewnew->storytelling_rating;$auditiondata->auditionreviewnew->storytelling_rating<5;$auditiondata->auditionreviewnew->storytelling_rating++)
                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    @if($auditiondata->auditionreviewnew->look_appearance)
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Look/Appearance</h4>
                                                <p>{!! $auditiondata->auditionreviewnew->look_appearance !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{number_format($auditiondata->auditionreviewnew->look_appearance_rating,1)}}
                                                    </div>
                                                    @if($auditiondata->auditionreviewnew->look_appearance_rating)
                                                        @for ($i = 1; $i <= $auditiondata->auditionreviewnew->look_appearance_rating; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($auditiondata->auditionreviewnew->look_appearance_rating;$auditiondata->auditionreviewnew->look_appearance_rating<5;$auditiondata->auditionreviewnew->look_appearance_rating++)
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