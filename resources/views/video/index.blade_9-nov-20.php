@extends('layouts.user')
@section('css')
    <style>
        .stripe-button-el{display: none !important;}
        .firs-modal .nav .nav-item.width-50 {width: 50%;}
    </style>
    <link type="text/css" rel="stylesheet" href="/assets/js/sweetalert/sweet-alert.css">
@endsection
@section('content')
<div class="main-content my-video-section">
            <div class="container-fluid">
    <div class="top-box fix-video-box">
        <div class="row">
            <div class="col-xl-5">
                <form action="{{url('video')}}" method="post" >
                    {!! csrf_field() !!}
                    <input type="text" name="video-name" class="form-control" placeholder="Search by name…" value="{{$searchterm}}">
                </form>
            </div>
            <div class="col-xl-7">
                <ul class="nav" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="view-all-tab" data-toggle="tab" href="#view-all" role="tab" aria-controls="view-all" aria-selected="true">View All</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="reviewed-tab" data-toggle="tab" href="#reviewed" role="tab" aria-controls="reviewed" aria-selected="false">Reviewed</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="waiting-review-tab" data-toggle="tab" href="#waiting-review" role="tab" aria-controls="waiting-review" aria-selected="false">Waiting for Review</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="payment-tab" data-toggle="tab" href="#payment" role="tab" aria-controls="payment" aria-selected="false">Waiting for Payment</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="view-all" role="tabpanel" aria-labelledby="view-all-tab">
            <div class="videos-wrap">
                <div class="row">
                    @if(count($videos) > 0)
                        @foreach($videos as $video)
                        <div class="col-xl-4 col-md-6 ">
                            <div class="card">
                                <div class="card">
                                    <div class="videos-box">

                                        @if(strpos($video->url, 'youtube') !== false)

                                            @php
                                            $query ="";
                                                $parts = parse_url($video->url);
                                                if(array_key_exists('query',$parts)){
                                                    parse_str($parts['query'], $query);
                                                    $videoID = $query['v'];
                                                    $youtubeurl = "https://www.youtube.com/embed/".$videoID; 
                                                }else{
                                                    $youtubeurl = $video->url; 
                                                }
                                                
                                                
                                            @endphp
                                            <iframe width="100%" height="300" src="{{$youtubeurl}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            @if($video->thumbnail !='')
                                                <img class="video-thumb" data-videoid="all-video-{{$video->id}}" src="{{$video->thumbnail}}" />
                                            @endif
                                        @else
                                       <video data-videoid="all-video-{{$video->id}}" id="all-video-{{$video->id}}" width="100%" height="300" controls class="video-pause" onplay="stopall(this)">
                                            <source src="{{url('/') . config('video.user_video_path') . $video->url}}">
                                            Your browser does not support HTML5 video.
                                        </video>
                                            @if($video->thumbnail !='')
                                                <img class="video-thumb" data-videoid="all-video-{{$video->id}}"  src="{{url('/') . config('video.thumbnail_path')}}{{$video->thumbnail}}" />
                                            @endif
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h3>{!! Str::words($video->name,5,'...') !!}</h3>
                                        <p>Uploaded in  @php
                                        $v_data = new \Carbon\Carbon($video->created_at);
                                        echo $v_data->format("m.d.Y g:i A");
                                    @endphp</p>
                                    @if($video->pay_status)
                                        <div class="row">
                                            <div class="col-md-12" style="min-height: 48px">
                                                @if($video->status == 3)
                                                   
                                                    <a href="javascript:void(0);" class="show-review-btn" data-toggle="modal" data-target="#modal-right-{{$video->id}}"><span class="check-mark" ><i class="far fa-check-circle"></i></span>Show Review</a>
                                                @endif

                                                
                                                    @if($video->status == 1)
                                                        <a href="#" class="review-progress-btn">
                                                            <span><img src="/platform/img/progress.png" alt="" class="img-fluid"></span>
                                                            Accepted by Coach
                                                        </a>
                                                        @elseif($video->status == 2)
                                                            <a href="#" class="review-progress-btn">
                                                                <span><img src="/platform/img/progress.png" alt="" class="img-fluid"></span>
                                                            Under Review
                                                        </a>
                                                    @endif
                                               
                                                
                                                
                                            </div>
                                        </div>
                                        @else
                                            @if($video->coach_id == 0)
                                                <a href="{{url('select-coache')}}/{{$video->id}}" class="btn btn-outline-danger" style="padding: 11px 10px;">Select Coach</a>
                                            @else
                                                <a href="{{url('payment')}}/{{$video->id}}" class="btn btn-outline-danger" style="padding: 11px 10px;">Payment</a>
                                            @endif
                                        
                                            
                                        @endif
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($video->started_review_status == 1)

                        @php
                            $rating = $model->getReviewByVideoId($video->id);
                        @endphp
                        @if($rating !='')
                        <div class="modal fade firs-modal right" id="modal-right-{{$video->id}}" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <a href="#" class="close" data-dismiss="modal" aria-label="Close"><img src="/platform/img/close.png" class="img-fluid pause-video"></a>
                                    <div class="firs-snow-content">
                                        <div class="videos-box">
                                        @if(strpos($video->url, 'youtube') !== false)

                                        @php
                                        $query ="";
                                            $parts = parse_url($video->url);
                                            if(array_key_exists('query',$parts)){
                                                parse_str($parts['query'], $query);
                                                $videoID = $query['v'];
                                                $youtubeurl = "https://www.youtube.com/embed/".$videoID; 
                                            }else{
                                                $youtubeurl = $video->url; 
                                            }
                                            
                                            
                                        @endphp
                                            <iframe width="100%" height="300" src="{{$youtubeurl}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            @if($video->thumbnail !='')
                                                <img class="video-thumb" data-videoid="rating-video-{{$video->id}}" src="{{$video->thumbnail}}" />
                                            @endif
                                        @else
                                            @if($rating->review_url)
                                                <video data-videoid="rating-video-{{$video->id}}" id="rating-video-{{$video->id}}" width="100%" height="300" controls class="video-pause radius-20" onplay="stopall(this)">
                                                    <source src="{{url('/') . config('video.completed_review_path') . $rating->review_url}}">
                                                    Your browser does not support HTML5 video.
                                                </video>
                                            @else
                                                <video data-videoid="rating-video-{{$video->id}}" id="rating-video-{{$video->id}}" width="100%" height="300" controls class="video-pause radius-20" onplay="stopall(this)">
                                                    <source src="{{url('/') . config('video.user_video_path') . $video->url}}">
                                                    Your browser does not support HTML5 video.
                                                </video>
                                            @endif
                                            @if($video->thumbnail !='')
                                                <img class="video-thumb radius-18" data-videoid="rating-video-{{$video->id}}"  src="{{url('/') . config('video.thumbnail_path')}}{{$video->thumbnail}}" />
                                            @endif
                                        @endif
                                        </div>
                                        <h3>{!! Str::words($video->name,5,'...') !!}</h3>
                                        <div class="level">Level: @if($rating->performance_level_id == 3) Advance @elseif($rating->performance_level_id == 2) Intermediate @else Bigginer @endif</div>
                                        <div class="updated"><p>Uploaded in  @php
                                        $v_data = new \Carbon\Carbon($video->created_at);
                                        echo $v_data->format("m.d.Y g:i A");
                                        @endphp</p></div>
                                        <ul class="nav" id="myTab" role="tablist">
                                            <li class="nav-item @if($video->package_id == 1) width-50 @endif">
                                                <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview-{{$video->id}}" role="tab" aria-controls="overview" aria-selected="true">Overview</a>
                                            </li>
                                            @if($video->package_id == 1)
                                            <li class="nav-item @if($video->package_id == 1) width-50 @endif">
                                                <a class="nav-link" id="score-tab" data-toggle="tab" href="#all-score-{{$video->id}}" role="tab" aria-controls="technique" aria-selected="false">Score</a>
                                            </li>
                                            @else
                                            <li class="nav-item">
                                                <a class="nav-link" id="technique-tab" data-toggle="tab" href="#technique-{{$video->id}}" role="tab" aria-controls="technique" aria-selected="false">Technique</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="expression-tab" data-toggle="tab" href="#expression-{{$video->id}}" role="tab" aria-controls="expression" aria-selected="false">Expression</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="choreography-tab" data-toggle="tab" href="#choreography-{{$video->id}}" role="tab" aria-controls="choreography" aria-selected="false">Choreography</a>
                                            </li>
                                            @endif
                                        </ul>

                                @php
                                    if($video->package_id == 1){
                                        $overallrating = round((
                                        $rating->performance_quality_rating +
                                        $rating->technical_ability_rating +
                                        $rating->energy_style_rating +
                                        $rating->storytelling_rating +
                                        $rating->look_appearance_rating
                                        
                                        ) / 5, 2);
                                    }else{
                                        $overallrating = round((
                                        $rating->artisty +
                                        $rating->formation +
                                        $rating->interpretation +
                                        $rating->creativity +
                                        $rating->style +
                                        $rating->energy +
                                        $rating->precision +
                                        $rating->timing +
                                        $rating->footwork +
                                        $rating->alingment +
                                        $rating->balance +
                                        $rating->focus
                                    ) / 12, 2);
                                    }
                                    

                                 
                                    
                                @endphp
                                        <div class="tab-content" id="nav-tabContent">
                                            <div class="tab-pane fade show active" id="overview-{{$video->id}}" role="tabpanel" aria-labelledby="overview-tab">
                                                <div class="rating overal-rating">
                                                    <div class="left-text">
                                                    {{ number_format($overallrating, 1) }}</div>
                                                    <div class="right-star">
                                                        @if($overallrating)
                                                            @for ($i = 1; $i <= $overallrating; $i++)
                                                                <i class="fas fa-star"></i>
                                                            @endfor
                                                            @for($overallrating;$overallrating<5;$overallrating++)
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                            @endfor
                                                        @endif
                                                        <span class="overal">Overal Rating</span>
                                                    </div>
                                                </div>
                                                <div class="content-list">
                                                    
                                                    <div class="left-box">
                                                        @if($video->package_id == 1)
                                                            <h4>Notes</h4>
                                                            <p>{!! $rating->message !!}</p>
                                                        @else
                                                            <h4>Feedback Summary</h4>
                                                            <p>{!! $rating->message !!}</p>
                                                        @endif
                                                    </div>

                                                </div>
                                                <div class="content-list d-block">
                                                    <div class="media">
                                                        @php
                                                        $coach = $model->getCoachDetail($video->coach_id);
                                                        @endphp
                                                        @if($coach)
                                                            <div class="left-box coach-name-scroll">
                                                                <h4>Coach</h4>
                                                                <h3>{{$coach->first_name}} 
                                                                {!! Str::words($coach->last_name,1,'...') !!}</h3>
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
                                                        @endif
                                                    </div>
                                                    @if($video->package_id !=1)
                                                    <ul class="question-list pt-4">
                                                        @php
                                                            $questions = $model->getReviewQuestion($video->id);
                                                            $index = 1;
                                                        @endphp
                                                        @if(count($questions) > 0)
                                                            @foreach($questions as $question)
                                                                <li>
                                                                    <h4>Question {{ $index++ }}:</h4>
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
                                                        <form method="post" class="question-form" action="" id="ask-question-{{$video->id}}">
                                                            {!! csrf_field() !!}
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <input type="hidden" name="video-id" value="{{$video->id}}" />
                                                                    <input type="text" name="question-title" class="form-control" placeholder="Ask your question..." />
                                                                </div>
                                                                <div class="col-lg-12 pt-3">

                                                                    <button class="btn btn-outline-danger submit-question" data-videoid="{{$video->id}}">Ask Question</button>
                                                                </div>
                                                                <div class="col-lg-12 response">
                                                                </div>
                                                            </div>
                                                        </form>
                                                        @if(count($questions) < 3)
                                                            <a href="javascript:void(0)" class="btn btn-outline-danger new-question1" data-videoid="{{$video->id}}">New Question</a>
                                                        @endif
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($video->package_id == 1)
                                            <div class="tab-pane fade" id="all-score-{{$video->id}}" role="tabpanel" aria-labelledby="score-tab">
                                                @if($rating !="" )
                                                <ul class="tab-list">
                                                    
                                                    @if($rating->performance_quality !=NULL)
                                                    <li>
                                                        <div class="content-list">
                                                            <div class="left-box">
                                                                <h4>Performance Quality</h4>
                                                                <p>{!! $rating->performance_quality !!}</p>
                                                            </div>
                                                            <div class="right-box">
                                                                <div class="rating">
                                                                    <div class="left-text">
                                                                        {{ number_format($rating->performance_quality_rating, 1) }}
                                                                
                                                                    </div>
                                                                    @if($rating->performance_quality_rating)
                                                                        @for ($i = 1; $i <= $rating->performance_quality_rating; $i++)
                                                                            <i class="fas fa-star"></i>
                                                                        @endfor
                                                                        @for($rating->performance_quality_rating;$rating->performance_quality_rating<5;$rating->performance_quality_rating++)
                                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                                        @endfor
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    @endif
                                                    @if($rating->technical_ability)
                                                    <li>
                                                        <div class="content-list">
                                                            <div class="left-box">
                                                                <h4>Technical Ability</h4>
                                                                <p>{!! $rating->technical_ability !!}</p>
                                                            </div>
                                                            <div class="right-box">
                                                                <div class="rating">
                                                                    <div class="left-text">
                                                                        {{ number_format($rating->technical_ability_rating, 1) }}
                                                                        
                                                                    </div>
                                                                    @if($rating->technical_ability_rating)
                                                                        @for ($i = 1; $i <= $rating->technical_ability_rating; $i++)
                                                                            <i class="fas fa-star"></i>
                                                                        @endfor
                                                                        @for($rating->technical_ability_rating;$rating->technical_ability_rating<5;$rating->technical_ability_rating++)
                                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                                        @endfor
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    @endif
                                                    @if($rating->energy_style)
                                                    <li>
                                                        <div class="content-list">
                                                            <div class="left-box">
                                                                <h4>Energy Style</h4>
                                                                <p>{!! $rating->energy_style !!}</p>
                                                            </div>
                                                            <div class="right-box">
                                                                <div class="rating">
                                                                    <div class="left-text">
                                                                        {{ number_format($rating->energy_style_rating, 1) }}
                                                                    </div>
                                                                    @if($rating->energy_style_rating)
                                                                        @for ($i = 1; $i <= $rating->energy_style_rating; $i++)
                                                                            <i class="fas fa-star"></i>
                                                                        @endfor
                                                                        @for($rating->energy_style_rating;$rating->energy_style_rating<5;$rating->energy_style_rating++)
                                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                                        @endfor
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    @endif
                                                    @if($rating->storytelling)
                                                    <li>
                                                        <div class="content-list">
                                                            <div class="left-box">
                                                                <h4>Story Telling</h4>
                                                                <p>{!! $rating->storytelling !!}</p>
                                                            </div>
                                                            <div class="right-box">
                                                                <div class="rating">
                                                                    <div class="left-text">
                                                                        {{ number_format($rating->storytelling_rating, 1) }}
                                                                    </div>
                                                                    @if($rating->storytelling_rating)
                                                                        @for ($i = 1; $i <= $rating->storytelling_rating; $i++)
                                                                            <i class="fas fa-star"></i>
                                                                        @endfor
                                                                        @for($rating->storytelling_rating;$rating->storytelling_rating<5;$rating->storytelling_rating++)
                                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                                        @endfor
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    @endif
                                                    @if($rating->look_appearance)
                                                    <li>
                                                        <div class="content-list">
                                                            <div class="left-box">
                                                                <h4>Look Appearance</h4>
                                                                <p>{!! $rating->look_appearance !!}</p>
                                                            </div>
                                                            <div class="right-box">
                                                                <div class="rating">
                                                                    <div class="left-text">
                                                                        {{ number_format($rating->look_appearance_rating, 1) }}
                                                                    </div>
                                                                    @if($rating->look_appearance_rating)
                                                                        @for ($i = 1; $i <= $rating->look_appearance_rating; $i++)
                                                                            <i class="fas fa-star"></i>
                                                                        @endfor
                                                                        @for($rating->look_appearance_rating;$rating->look_appearance_rating<5;$rating->look_appearance_rating++)
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
                                            <div class="tab-pane fade" id="technique-{{$video->id}}" role="tabpanel" aria-labelledby="technique-tab">
                                                <ul class="tab-list">
                                                    
                                                    <li>
                                                        <div class="content-list">
                                                            <div class="left-box">
                                                                <h4>Footwork</h4>
                                                                <p>{!! $rating->footwork_comment !!}</p>
                                                            </div>
                                                            <div class="right-box">
                                                                <div class="rating">
                                                                    <div class="left-text">
                                                                        {{ number_format($rating->footwork, 1) }}
                                                                        
                                                                    </div>
                                                                    @if($rating->footwork)
                                                                        @for ($i = 1; $i <= $rating->footwork; $i++)
                                                                            <i class="fas fa-star"></i>
                                                                        @endfor
                                                                        @for($rating->footwork;$rating->footwork<5;$rating->footwork++)
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
                                                                <p>{!! $rating->alingment_comment !!}</p>
                                                            </div>
                                                            <div class="right-box">
                                                                <div class="rating">
                                                                    <div class="left-text">
                                                                        {{ number_format($rating->alingment , 1) }}</div>
                                                                    @if($rating->alingment)
                                                                        @for ($i = 1; $i <= $rating->alingment; $i++)
                                                                            <i class="fas fa-star"></i>
                                                                        @endfor
                                                                        @for($rating->alingment;$rating->alingment<5;$rating->alingment++)
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
                                                                <p>{!! $rating->balance_comment !!}</p>
                                                            </div>
                                                            <div class="right-box">
                                                                <div class="rating">
                                                                    <div class="left-text">
                                                        {{ number_format($rating->balance , 1) }}</div>
                                                                    @if($rating->balance)
                                                                        @for ($i = 1; $i <= $rating->balance; $i++)
                                                                            <i class="fas fa-star"></i>
                                                                        @endfor
                                                                        @for($rating->balance;$rating->balance<5;$rating->balance++)
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
                                                                <p>{!! $rating->focus_comment !!}</p>
                                                            </div>
                                                            <div class="right-box">
                                                                <div class="rating">
                                                                    <div class="left-text">
                                                            {{ number_format($rating->focus , 1) }}
                                                                       </div>
                                                                    @if($rating->focus)
                                                                        @for ($i = 1; $i <= $rating->focus; $i++)
                                                                            <i class="fas fa-star"></i>
                                                                        @endfor
                                                                        @for($rating->focus;$rating->focus<5;$rating->focus++)
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
                                                                <p>{!! $rating->precision_comment !!}</p>
                                                            </div>
                                                            <div class="right-box">
                                                                <div class="rating">
                                                                    <div class="left-text">
                                                            {{ number_format($rating->precision , 1) }}
                                                                        </div>
                                                                    @if($rating->precision)
                                                                        @for ($i = 1; $i <= $rating->precision; $i++)
                                                                            <i class="fas fa-star"></i>
                                                                        @endfor
                                                                        @for($rating->precision;$rating->precision<5;$rating->precision++)
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
                                                                <h4>Timing</h4>
                                                                <p>{!! $rating->timing_comment !!}</p>
                                                            </div>
                                                            <div class="right-box">
                                                                <div class="rating">
                                                                    <div class="left-text">
                                                        {{ number_format($rating->timing , 1) }}</div>
                                                                    @if($rating->timing)
                                                                        @for ($i = 1; $i <= $rating->timing; $i++)
                                                                            <i class="fas fa-star"></i>
                                                                        @endfor
                                                                        @for($rating->timing;$rating->timing<5;$rating->timing++)
                                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                                        @endfor
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="tab-pane fade" id="expression-{{$video->id}}" role="tabpanel" aria-labelledby="expression-tab">
                                                <ul class="tab-list">
                                                    
                                                    <li>
                                                        <div class="content-list">
                                                            <div class="left-box">
                                                                <h4>Energy</h4>
                                                                <p>{!! $rating->energy_comment !!}</p>
                                                            </div>
                                                            <div class="right-box">
                                                                <div class="rating">
                                                                    <div class="left-text">
                                                            {{ number_format($rating->energy , 1) }}
                                                                    </div>
                                                                    @if($rating->energy)
                                                                        @for ($i = 1; $i <= $rating->energy; $i++)
                                                                            <i class="fas fa-star"></i>
                                                                        @endfor
                                                                        @for($rating->energy;$rating->energy<5;$rating->energy++)
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
                                                                <p>{!! $rating->style_comment !!}</p>
                                                            </div>
                                                            <div class="right-box">
                                                                <div class="rating">
                                                                    <div class="left-text">
                                                            {{ number_format($rating->style , 1) }}
                                                                    </div>
                                                                    @if($rating->style)
                                                                        @for ($i = 1; $i <= $rating->style; $i++)
                                                                            <i class="fas fa-star"></i>
                                                                        @endfor
                                                                        @for($rating->style;$rating->style<5;$rating->style++)
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
                                                                <p>{!! $rating->creativity_comment !!}</p>
                                                            </div>
                                                            <div class="right-box">
                                                                <div class="rating">
                                                                    <div class="left-text">
                                                        {{ number_format($rating->creativity , 1) }}
                                                                        
                                                                    </div>
                                                                    @if($rating->creativity)
                                                                        @for ($i = 1; $i <= $rating->creativity; $i++)
                                                                            <i class="fas fa-star"></i>
                                                                        @endfor
                                                                        @for($rating->creativity;$rating->creativity<5;$rating->creativity++)
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
                                                                <p>{!! $rating->interpretation_comment !!}</p>
                                                            </div>
                                                            <div class="right-box">
                                                                <div class="rating">
                                                                    <div class="left-text">
                                                    {{ number_format($rating->interpretation , 1) }}</div>
                                                                    @if($rating->interpretation)
                                                                        @for ($i = 1; $i <= $rating->interpretation; $i++)
                                                                            <i class="fas fa-star"></i>
                                                                        @endfor
                                                                        @for($rating->interpretation;$rating->interpretation<5;$rating->interpretation++)
                                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                                        @endfor
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    
                                                </ul>
                                            </div>

                                            <div class="tab-pane fade" id="choreography-{{$video->id}}" role="tabpanel" aria-labelledby="choreography-tab">
                                                <ul class="tab-list">
                                                    
                                                    <li>
                                                        <div class="content-list">
                                                            <div class="left-box">
                                                                <h4>Formation</h4>
                                                                <p>{!! $rating->formation_comment !!}</p>
                                                            </div>
                                                            <div class="right-box">
                                                                <div class="rating">
                                                                    <div class="left-text">
                                                        {{ number_format($rating->formation , 1) }}
                                                                    </div>
                                                                    @if($rating->formation)
                                                                        @for ($i = 1; $i <= $rating->formation; $i++)
                                                                            <i class="fas fa-star"></i>
                                                                        @endfor
                                                                        @for($rating->formation;$rating->formation<5;$rating->formation++)
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
                                                                <p>{!! $rating->artisty_comment !!}</p>
                                                            </div>
                                                            <div class="right-box">
                                                                <div class="rating">
                                                                    <div class="left-text">
                                                            {{ number_format($rating->artisty , 1) }}
                                                                        </div>
                                                                    @if($rating->artisty)
                                                                        @for ($i = 1; $i <= $rating->artisty; $i++)
                                                                            <i class="fas fa-star"></i>
                                                                        @endfor
                                                                        @for($rating->artisty;$rating->artisty<5;$rating->artisty++)
                                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                                        @endfor
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    
                                                </ul>
                                                @if($rating->additional_tips)
                                                <div class="content-list pl-2 pt-4">
                                                    <div class="left-box">

                                                        <h4>Additional Tips</h4>
                                                        <p>{!! $rating->additional_tips !!}</p>
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
                        @endif
                        @endif
                        @endforeach
                    @else
                    <div class="col-md-12">
                        <h3>No video found</h3>
                    </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-12 justify-content-center">
                        {{ $videos->links() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="reviewed" role="tabpanel" aria-labelledby="reviewed-tab">
            <div class="videos-wrap">
                <div class="row">
                    @if(count($reviewed) > 0)
                        @foreach($reviewed as $video)
                        <div class="col-xl-4 col-md-6 ">
                            <div class="card">
                                <div class="card">
                                    <div class="videos-box">
                                       @if(strpos($video->url, 'youtube') !== false)

                                        @php
                                        $query ="";
                                            $parts = parse_url($video->url);
                                            if(array_key_exists('query',$parts)){
                                                parse_str($parts['query'], $query);
                                                $videoID = $query['v'];
                                                $youtubeurl = "https://www.youtube.com/embed/".$videoID; 
                                            }else{
                                                $youtubeurl = $video->url; 
                                            }
                                            
                                            
                                        @endphp
                                            <iframe width="100%" height="300" src="{{$youtubeurl}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            @if($video->thumbnail !='')
                                                <img class="video-thumb" data-videoid="waiting-review-{{$video->id}}" src="{{$video->thumbnail}}" />
                                            @endif
                                        @else
                                            @if($video->review !='')
                                                <video data-videoid="waiting-review-{{$video->id}}" id="waiting-review-{{$video->id}}" width="100%" height="300" controls class="video-pause" onplay="stopall(this)">
                                                    <source src="{{url('/') . config('video.completed_review_path') . $video->review->review_url}}">
                                                    Your browser does not support HTML5 video.
                                                </video>
                                            @else
                                                <video data-videoid="waiting-review-{{$video->id}}" id="waiting-review-{{$video->id}}" width="100%" height="300" controls class="video-pause" onplay="stopall(this)">
                                                    <source src="{{url('/') . config('video.user_video_path') . $video->url}}">
                                                    Your browser does not support HTML5 video.
                                                </video>
                                            @endif
                                            @if($video->thumbnail !='')
                                                <img class="video-thumb" data-videoid="waiting-review-{{$video->id}}"  src="{{url('/') . config('video.thumbnail_path')}}{{$video->thumbnail}}" />
                                            @endif
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h3>{!! Str::words($video->name,5,'...') !!}</h3>
                                        <p>Uploaded in  @php
                                        $v_data = new \Carbon\Carbon($video->created_at);
                                        echo $v_data->format("m.d.Y g:i A");
                                        @endphp</p>
                                    @if($video->pay_status)
                                        <div class="row">
                                            <div class="col-md-12" style="min-height: 48px">
                                                @if($video->status == 3)
                                                   
                                                    <a href="javascript:void(0);" class="show-review-btn" data-toggle="modal" data-target="#reviewd-modal-right-{{$video->id}}"><span class="check-mark" ><i class="far fa-check-circle"></i></span>Show Review</a>
                                                @endif

                                                
                                                    @if($video->status == 1)
                                                        <a href="#" class="review-progress-btn">
                                                            <span><img src="/platform/img/progress.png" alt="" class="img-fluid"></span>
                                                            Accepted by Coach
                                                        </a>
                                                        @elseif($video->status == 2)
                                                            <a href="#" class="review-progress-btn">
                                                                <span><img src="/platform/img/progress.png" alt="" class="img-fluid"></span>
                                                            Under Review
                                                        </a>
                                                    @endif
                                               
                                                
                                                
                                            </div>
                                        </div>
                                        @else
                                            <a href="{{url('payment')}}/{{$video->id}}" class="btn btn-outline-danger">Payment</a>
                                            
                                        @endif
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @endforeach
                    @else
                    <div class="col-md-12">
                        <h3>No video found</h3>
                    </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-12 justify-content-center">
                        {{ $reviewed->links() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="waiting-review" role="tabpanel" aria-labelledby="waiting-review-tab">
            <div class="videos-wrap">
                <div class="row">
                    @if(count($waitingreview) > 0 || count($auditionwaitingreview) > 0)
                        @if(count($waitingreview) > 0)
                        @foreach($waitingreview as $video)
                        <div class="col-xl-4 col-md-6 ">
                            <div class="card">
                                <div class="card">
                                    <div class="videos-box">
                                       @if(strpos($video->url, 'youtube') !== false)

                                        @php
                                        $query ="";
                                            $parts = parse_url($video->url);
                                            if(array_key_exists('query',$parts)){
                                                parse_str($parts['query'], $query);
                                                $videoID = $query['v'];
                                                $youtubeurl = "https://www.youtube.com/embed/".$videoID; 
                                            }else{
                                                $youtubeurl = $video->url; 
                                            }
                                            
                                            
                                        @endphp
                                            <iframe width="100%" height="300" src="{{$youtubeurl}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            @if($video->thumbnail !='')
                                                <img class="video-thumb" data-videoid="payment-video-{{$video->id}}" src="{{$video->thumbnail}}" />
                                            @endif
                                        @else
                                        <video data-videoid="payment-video-{{$video->id}}" id="payment-video-{{$video->id}}" width="100%" height="300" controls  class="video-pause" onplay="stopall(this)">
                                            <source src="{{url('/') . config('video.user_video_path') . $video->url}}">
                                            Your browser does not support HTML5 video.
                                        </video>
                                            @if($video->thumbnail !='')
                                                <img class="video-thumb" data-videoid="payment-video-{{$video->id}}"  src="{{url('/') . config('video.thumbnail_path')}}{{$video->thumbnail}}" />
                                            @endif
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h3>{!! Str::words($video->name,5,'...') !!}</h3>
                                        <p>Uploaded in  @php
                                        $v_data = new \Carbon\Carbon($video->created_at);
                                        echo $v_data->format("m.d.Y g:i A");
                                    @endphp</p>
                                    @if($video->pay_status)
                                        <div class="row">
                                            <div class="col-md-12" style="min-height: 48px">
                                                @if($video->status == 3)
                                                   
                                                    <a href="javascript:void(0);" class="show-review-btn" data-toggle="modal" data-target="#modal-right-{{$video->id}}"><span class="check-mark" ><i class="far fa-check-circle"></i></span>Show Review</a>
                                                @endif

                                                
                                                    @if($video->status == 1)
                                                        <a href="#" class="review-progress-btn">
                                                            <span><img src="/platform/img/progress.png" alt="" class="img-fluid"></span>
                                                            Accepted by Coach
                                                        </a>
                                                        @elseif($video->status == 2)
                                                            <a href="#" class="review-progress-btn">
                                                                <span><img src="/platform/img/progress.png" alt="" class="img-fluid"></span>
                                                            Under Review
                                                        </a>
                                                    @endif
                                               
                                                
                                                
                                            </div>
                                        </div>
                                        @else
                                            <div class="row">
                                                
                                                <a href="{{url('payment')}}/{{$video->id}}" class="btn btn-outline-danger">Payment</button>
                                                   

                                            </div>
                                        @endif
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                        @if(count($auditionwaitingreview) > 0)
                            @foreach($auditionwaitingreview as $video)
                            @if($video->auditionreviewnew == "")
                            <div class="col-xl-4 col-md-6 audition-{{$video->audition_id}} pid-{{$video->id}}">
                                <div class="card">
                                    <div class="card">
                                        <div class="videos-box">
                                           @if($video->video_type == 'youtube')

                                            @php
                                            $query ="";
                                                $parts = parse_url($video->video_link);
                                                if(array_key_exists('query',$parts)){
                                                    parse_str($parts['query'], $query);
                                                    $videoID = $query['v'];
                                                    $youtubeurl = "https://www.youtube.com/embed/".$videoID; 
                                                }else{
                                                    $youtubeurl = $video->video_link; 
                                                }
                                                
                                                
                                            @endphp
                                                <iframe width="100%" height="300" src="{{$youtubeurl}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                @if($video->thumbnail !='')
                                                    <img class="video-thumb" data-videoid="payment-video-{{$video->id}}" src="{{$video->thumbnail_url}}" />
                                                @endif
                                            @else
                                            <video data-videoid="payment-video-{{$video->id}}" id="payment-video-{{$video->id}}" width="100%" height="300" controls  class="video-pause" onplay="stopall(this)">
                                                <source src="{{url('/') . config('video.audition_video_path') . $video->video_link}}">
                                                Your browser does not support HTML5 video.
                                            </video>
                                                @if($video->thumbnail_url !='')
                                                    <img class="video-thumb" data-videoid="payment-video-{{$video->id}}"  src="{{url('/') . config('video.thumbnail_path')}}{{$video->thumbnail_url}}" />
                                                @endif
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <h3>{{$video->audition->audition_name}}</h3>
                                            <p>Uploaded in  @php
                                            $v_data = new \Carbon\Carbon($video->created_at);
                                            echo $v_data->format("m.d.Y g:i A");
                                        @endphp</p>
                                        <div class="row">
                                            <div class="col-md-12" style="min-height: 48px;">
                                                 <a href="#" class="review-progress-btn">
                                                                    <span><img src="/platform/img/progress.png" alt="" class="img-fluid"></span>
                                                                Under Review
                                                            </a>
                                            </div>
                                        </div>
                                       
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        
                            @endforeach
                        @endif
                        @if(count($challengewaitingreview) > 0)
                            @foreach($challengewaitingreview as $video)
                            @if($video->review == "")
                            <div class="col-xl-4 col-md-6 challenge-{{$video->challenge_id}} pid-{{$video->id}}">
                                <div class="card">
                                    <div class="card">
                                        <div class="videos-box">
                                           @if($video->video_type == 'youtube')

                                            @php
                                            $query ="";
                                                $parts = parse_url($video->video_link);
                                                if(array_key_exists('query',$parts)){
                                                    parse_str($parts['query'], $query);
                                                    $videoID = $query['v'];
                                                    $youtubeurl = "https://www.youtube.com/embed/".$videoID; 
                                                }else{
                                                    $youtubeurl = $video->video_link; 
                                                }
                                                
                                                
                                            @endphp
                                                <iframe width="100%" height="300" src="{{$youtubeurl}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                @if($video->thumbnail_url !='')
                                                    <img class="video-thumb" data-videoid="payment-video-{{$video->id}}" src="{{$video->thumbnail_url}}" />
                                                @endif
                                            @else
                                            <video data-videoid="payment-video-{{$video->id}}" id="payment-video-{{$video->id}}" width="100%" height="300" controls  class="video-pause" onplay="stopall(this)">
                                                <source src="{{url('/') . config('video.challenge_video_path') . $video->video_link}}">
                                                Your browser does not support HTML5 video.
                                            </video>
                                                @if($video->thumbnail_url !='')
                                                    <img class="video-thumb" data-videoid="payment-video-{{$video->id}}"  src="{{url('/') . config('video.thumbnail_path')}}{{$video->thumbnail_url}}" />
                                                @endif
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <h3>{{$video->challenges->challenges_name}}</h3>
                                            <p>Uploaded in  @php
                                            $v_data = new \Carbon\Carbon($video->created_at);
                                            echo $v_data->format("m.d.Y g:i A");
                                        @endphp</p>
                                        <div class="row">
                                            <div class="col-md-12" style="min-height: 48px;">
                                                 <a href="#" class="review-progress-btn">
                                                    <span><img src="/platform/img/progress.png" alt="" class="img-fluid"></span>
                                                                Under Review
                                                            </a>
                                            </div>
                                        </div>
                                       
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        
                            @endforeach
                        @endif
                    @else
                    <div class="col-md-12">
                        <h3>No video found</h3>
                    </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-12 justify-content-center">
                        {{ $waitingreview->links() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
            <div class="videos-wrap">
                <div class="row">
                    @if(count($pendingpayment) > 0)
                        @foreach($pendingpayment as $video)
                        <div class="col-xl-4 col-md-6 ">
                            <div class="card">
                                <div class="card">
                                    <div class="videos-box">
                                       @if(strpos($video->url, 'youtube') !== false)

                                        @php
                                        $query ="";
                                            $parts = parse_url($video->url);
                                            if(array_key_exists('query',$parts)){
                                                parse_str($parts['query'], $query);
                                                $videoID = $query['v'];
                                                $youtubeurl = "https://www.youtube.com/embed/".$videoID; 
                                            }else{
                                                $youtubeurl = $video->url; 
                                            }
                                            
                                            
                                        @endphp
                                            <iframe width="100%" height="300" src="{{$youtubeurl}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            @if($video->thumbnail !='')
                                                <img class="video-thumb" data-videoid="payment2-{{$video->id}}" src="{{$video->thumbnail}}" />
                                            @endif
                                        @else
                                        <video id="payment2-{{$video->id}}" width="100%" height="300" controls class="video-pause">
                                            <source src="{{url('/') . config('video.user_video_path') . $video->url}}">
                                            Your browser does not support HTML5 video.
                                        </video>
                                            @if($video->thumbnail !='')
                                                <img class="video-thumb" data-videoid="payment2-{{$video->id}}"  src="{{url('/') . config('video.thumbnail_path')}}{{$video->thumbnail}}" />
                                            @endif
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h3>{!! Str::words($video->name,5,'...') !!}</h3>
                                        <p>Uploaded in  @php
                                        $v_data = new \Carbon\Carbon($video->created_at);
                                        echo 'Posted date: ' . $v_data->format("m.d.Y g:i A");
                                    @endphp</p>
                                    @if($video->pay_status)
                                        <div class="row">
                                            <div class="col-md-12" style="min-height: 48px">
                                                @if($video->status == 3)
                                                   
                                                    <a href="javascript:void(0);" class="show-review-btn" data-toggle="modal" data-target="#modal-right-{{$video->id}}"><span class="check-mark" ><i class="far fa-check-circle"></i></span>Show Review</a>
                                                @endif

                                                
                                                    @if($video->status == 1)
                                                        <a href="#" class="review-progress-btn">
                                                            <span><img src="/platfrom/img/progress.png" alt="" class="img-fluid"></span>
                                                            Accepted by Coach
                                                        </a>
                                                        @elseif($video->status == 2)
                                                            <a href="#" class="review-progress-btn">
                                                                <span><img src="/platfrom/img/progress.png" alt="" class="img-fluid"></span>
                                                            Under Review
                                                        </a>
                                                    @endif
                                               
                                                
                                                
                                            </div>
                                        </div>
                                        @else
                                            <div class="row">
                                                <a href="{{url('payment')}}/{{$video->id}}" class="btn btn-outline-danger" style="padding: 10px 11px;">Payment</a>

                                            </div>
                                        @endif
                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                        @endforeach
                        @if(count($autidionpendingpay) > 0)
                            @foreach($autidionpendingpay as $video)
                            @if($video->payment_status == 0)
                            <div class="col-xl-4 col-md-6 audition-{{$video->audition_id}} pid-{{$video->id}}">
                                <div class="card">
                                    <div class="card">
                                        <div class="videos-box">
                                           @if($video->video_type == 'youtube')

                                            @php
                                            $query ="";
                                                $parts = parse_url($video->video_link);
                                                if(array_key_exists('query',$parts)){
                                                    parse_str($parts['query'], $query);
                                                    $videoID = $query['v'];
                                                    $youtubeurl = "https://www.youtube.com/embed/".$videoID; 
                                                }else{
                                                    $youtubeurl = $video->video_link; 
                                                }
                                                
                                                
                                            @endphp
                                                <iframe width="100%" height="300" src="{{$youtubeurl}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                @if($video->thumbnail !='')
                                                    <img class="video-thumb" data-videoid="payment-video-{{$video->id}}" src="{{$video->thumbnail_url}}" />
                                                @endif
                                            @else
                                            <video id="payment-video-{{$video->id}}" width="100%" height="300" controls  class="video-pause">
                                                <source src="{{url('/') . config('video.audition_video_path') . $video->video_link}}">
                                                Your browser does not support HTML5 video.
                                            </video>
                                                @if($video->thumbnail_url !='')
                                                    <img class="video-thumb" data-videoid="payment-video-{{$video->id}}"  src="{{url('/') . config('video.thumbnail_path')}}{{$video->thumbnail_url}}" />
                                                @endif
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <h3>{{$video->audition->audition_name}}</h3>
                                            <p>Uploaded in  @php
                                            $v_data = new \Carbon\Carbon($video->created_at);
                                            echo $v_data->format("m.d.Y g:i A");
                                        @endphp</p>
                                        
                                        <a href="/auditions/payment/{{$video->id}}" class="btn btn-outline-danger" style="padding: 10px 11px;">Payment</a>
                                           
                                       
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        
                            @endforeach
                        @endif
                        @if(count($challengependingpay) > 0)
                            @foreach($challengependingpay as $video)
                            @if($video->payment_status == 0)
                            <div class="col-xl-4 col-md-6 challenge-{{$video->challenge_id}} pid-{{$video->id}}">
                                <div class="card">
                                    <div class="card">
                                        <div class="videos-box">
                                           @if($video->video_type == 'youtube')

                                            @php
                                            $query ="";
                                                $parts = parse_url($video->video_link);
                                                if(array_key_exists('query',$parts)){
                                                    parse_str($parts['query'], $query);
                                                    $videoID = $query['v'];
                                                    $youtubeurl = "https://www.youtube.com/embed/".$videoID; 
                                                }else{
                                                    $youtubeurl = $video->video_link; 
                                                }
                                                
                                                
                                            @endphp
                                                <iframe width="100%" height="300" src="{{$youtubeurl}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                @if($video->thumbnail_url !='')
                                                    <img class="video-thumb" data-videoid="payment-video-{{$video->id}}" src="{{$video->thumbnail_url}}" />
                                                @endif
                                            @else
                                            <video id="payment-video-{{$video->id}}" width="100%" height="300" controls  class="video-pause">
                                                <source src="{{url('/') . config('video.challenge_video_path') . $video->video_link}}">
                                                Your browser does not support HTML5 video.
                                            </video>
                                                @if($video->thumbnail_url !='')
                                                    <img class="video-thumb" data-videoid="payment-video-{{$video->id}}"  src="{{url('/') . config('video.thumbnail_path')}}{{$video->thumbnail_url}}" />
                                                @endif
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <h3>{{$video->challenges->challenges_name}}</h3>
                                            <p>Uploaded in  @php
                                            $v_data = new \Carbon\Carbon($video->created_at);
                                            echo $v_data->format("m.d.Y g:i A");
                                        @endphp</p>
                                        
                                        <a href="/challenge/pay/{{$video->id}}" class="btn btn-outline-danger" style="padding: 10px 11px;">Payment</a>
                                           
                                       
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        
                            @endforeach
                        @endif
                    @else
                    <div class="col-md-12">
                        <h3>No video found</h3>
                    </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-12 justify-content-center">
                        {{ $pendingpayment->links() }}
                    </div>
                </div>
            </div>
        </div>
        
    @foreach($videoratings as $rating)
        
        @if($rating->review !='')
       <div class="modal fade firs-modal right" id="reviewd-modal-right-{{$rating->id}}" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close"><img src="/platform/img/close.png" class="img-fluid pause-video"></a>
                    <div class="firs-snow-content">
                        <div class="videos-box">
                            @if(strpos($rating->url, 'youtube') !== false)

                                @php
                                $query ="";
                                    $parts = parse_url($rating->url);
                                    if(array_key_exists('query',$parts)){
                                        parse_str($parts['query'], $query);
                                        $videoID = $query['v'];
                                        $youtubeurl = "https://www.youtube.com/embed/".$videoID; 
                                    }else{
                                        $youtubeurl = $rating->url; 
                                    }
                                    
                                    
                                @endphp
                                <iframe width="100%" height="300" src="{{$youtubeurl}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                @if($rating->thumbnail !='')
                                    <img class="video-thumb" data-videoid="videorating-{{$rating->id}}" src="{{$rating->thumbnail}}" />
                                @endif
                            @else
                           <video data-videoid="videorating-{{$rating->id}}" id="videorating-{{$rating->id}}" width="100%" height="300" controls class="video-pause radius-20" onplay="stopall(this)">
                                <source src="{{url('/') . config('video.completed_review_path') . $rating->review->review_url}}">
                                Your browser does not support HTML5 video.
                            </video>
                                @if($rating->thumbnail !='')
                                    <img class="video-thumb radius-18" data-videoid="videorating-{{$rating->id}}"  src="{{url('/') . config('video.thumbnail_path')}}{{$rating->thumbnail}}" />
                                @endif
                            @endif
                        </div>
                        <h3>{!! Str::words($rating->name,5,'...') !!}</h3>
                        <div class="level">Level: @if($rating->level == 3) Advance @elseif($rating->level == 2) Intermediate @else Bigginer @endif</div>
                        <div class="updated">
                            @php
                                $v_data = new \Carbon\Carbon($rating->created_at);
                                echo 'Uploaded in ' . $v_data->format("m.d.Y g:i A");
                            @endphp
                            @php
                                if($rating->package_id == 1){
                                    $overallrating = round((
                                    $rating->review->performance_quality_rating +
                                    $rating->review->technical_ability_rating +
                                    $rating->review->energy_style_rating +
                                    $rating->review->storytelling_rating +
                                    $rating->review->look_appearance_rating
                                    
                                    ) / 5, 2);
                                }else{
                                    $overallrating = round((
                                    $rating->review->artisty +
                                    $rating->review->formation +
                                    $rating->review->interpretation +
                                    $rating->review->creativity +
                                    $rating->review->style +
                                    $rating->review->energy +
                                    $rating->review->precision +
                                    $rating->review->timing +
                                    $rating->review->footwork +
                                    $rating->review->alingment +
                                    $rating->review->balance +
                                    $rating->review->focus
                                ) / 12, 2);
                                }
                                
                            @endphp
                        </div>
                        <ul class="nav" id="myTab" role="tablist">
                            <li class="nav-item @if($rating->package_id == 1) width-50 @endif">
                                <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview-review-{{$rating->id}}" role="tab" aria-controls="overview" aria-selected="true">Overview</a>
                            </li>
                            @if($rating->package_id == 1)
                            <!-- <li class="nav-item @if($rating->package_id == 1) width-50 @endif">
                                <a class="nav-link" id="technique-tab" data-toggle="tab" href="#score-{{$rating->id}}" role="tab" aria-controls="technique" aria-selected="false">Score</a>
                            </li> -->
                            <li class="nav-item @if($rating->package_id == 1) width-50 @endif">
                                <a class="nav-link" id="score-tab" data-toggle="tab" href="#score-{{$rating->id}}" role="tab" aria-controls="technique" aria-selected="false">Score</a>
                            </li>
                            @else
                            <li class="nav-item">
                                <a class="nav-link" id="technique-tab" data-toggle="tab" href="#technique1-{{$rating->id}}" role="tab" aria-controls="technique" aria-selected="false">Technique</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="expression-tab" data-toggle="tab" href="#expression1-{{$rating->id}}" role="tab" aria-controls="expression" aria-selected="false">Expression</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="choreography-tab" data-toggle="tab" href="#choreography1-{{$rating->id}}" role="tab" aria-controls="choreography" aria-selected="false">Choreography</a>
                            </li>
                            @endif
                        </ul>

                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="overview-review-{{$rating->id}}" role="tabpanel" aria-labelledby="overview-tab">
                                <div class="rating overal-rating">
                                    <div class="left-text">{{ number_format($overallrating , 1) }} </div>
                                    <div class="right-star">
                                        @if($overallrating)
                                            @for ($i = 1; $i <= $overallrating; $i++)
                                                <i class="fas fa-star"></i>
                                            @endfor
                                            @for($overallrating;$overallrating<5;$overallrating++)
                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                            @endfor
                                        @endif
                                        
                                        <span class="overal">Overal Rating</span>
                                    </div>
                                </div>
                                <div class="content-list">
                                    @if($rating->review->message)
                                        <div class="left-box">
                                            @if($rating->package_id == 1)
                                                <h4>Notes</h4>
                                            @else
                                                <h4>Feedback Summary</h4>
                                            @endif
                                            <p>{{$rating->review->message}}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="content-list d-block">
                                    <div class="media">
                                        @php
                                        $coach = $model->getCoachDetailByVideoId($rating->review->video_id);
                                        @endphp
                                        @if($coach)
                                            <div class="left-box coach-name-scroll">
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
                                        @endif
                                    </div>
                                    @if($rating->package_id != 1)
                                    <ul class="question-list pt-4">
                                        @php
                                            $questions = $model->getReviewQuestion($rating->review->video_id);
                                            $index = 1;
                                        @endphp
                                        @if(count($questions) > 0)
                                            @foreach($questions as $key=>$question)
                                                <li>
                                                    <h4>Question {{ $index++ }}:</h4>
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
                                        <form method="post" class="question-form" action="" id="ask-question-{{$rating->review->video_id}}">
                                            {!! csrf_field() !!}
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <input type="hidden" name="video-id" value="{{$rating->review->video_id}}" />
                                                    <input type="text" name="question-title" class="form-control" placeholder="Ask your question..." />
                                                </div>
                                                <div class="col-lg-12 pt-3">

                                                    <button class="btn btn-outline-danger submit-question" data-videoid="{{$rating->review->video_id}}">Ask Question</button>
                                                </div>
                                                <div class="col-lg-12 response">
                                                </div>
                                            </div>
                                        </form>
                                        @if(count($questions) < 3)
                                            <a href="javascript:void(0)" class="btn btn-outline-danger new-question1" data-videoid="{{$rating->review->video_id}}">New Question</a>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @if($rating->package_id == 1)
                                <div class="tab-pane fade" id="score-{{$rating->id}}" role="tabpanel" aria-labelledby="score-tab">
                                @if($rating->review !="" )
                                <ul class="tab-list">
                                    
                                    @if($rating->review->performance_quality !=NULL)
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Performance Quality</h4>
                                                <p>{!! $rating->review->performance_quality !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                        {{ number_format($rating->review->performance_quality_rating, 1) }} 
                                                    </div>
                                                    @if($rating->review->performance_quality_rating)
                                                        @for ($i = 1; $i <= $rating->review->performance_quality_rating; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($rating->review->performance_quality_rating;$rating->review->performance_quality_rating<5;$rating->review->performance_quality_rating++)
                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    @if($rating->review->technical_ability)
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Technical Ability</h4>
                                                <p>{!! $rating->review->technical_ability !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                        {{ number_format($rating->review->technical_ability_rating, 1) }}
                                                    </div>
                                                    @if($rating->review->technical_ability_rating)
                                                        @for ($i = 1; $i <= $rating->review->technical_ability_rating; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($rating->review->technical_ability_rating;$rating->review->technical_ability_rating<5;$rating->review->technical_ability_rating++)
                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    @if($rating->review->energy_style)
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Energy Style</h4>
                                                <p>{!! $rating->review->energy_style !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                        {{ number_format($rating->review->energy_style_rating, 1) }}

                                                    </div>
                                                    @if($rating->review->energy_style_rating)
                                                        @for ($i = 1; $i <= $rating->review->energy_style_rating; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($rating->review->energy_style_rating;$rating->review->energy_style_rating<5;$rating->review->energy_style_rating++)
                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    @if($rating->review->storytelling)
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Storytelling</h4>
                                                <p>{!! $rating->review->storytelling !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                        {{ number_format($rating->review->storytelling_rating, 1) }}
                                                    </div>
                                                    @if($rating->review->storytelling_rating)
                                                        @for ($i = 1; $i <= $rating->review->storytelling_rating; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($rating->review->storytelling_rating;$rating->review->storytelling_rating<5;$rating->review->storytelling_rating++)
                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    @if($rating->review->look_appearance)
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Look Appearance</h4>
                                                <p>{!! $rating->review->look_appearance !!}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                        {{ number_format($rating->review->look_appearance_rating, 1) }}
                                                    </div>
                                                    @if($rating->review->look_appearance_rating)
                                                        @for ($i = 1; $i <= $rating->review->look_appearance_rating; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($rating->review->look_appearance_rating;$rating->review->look_appearance_rating<5;$rating->review->look_appearance_rating++)
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
                            
                            <div class="tab-pane fade" id="technique1-{{$rating->id}}" role="tabpanel" aria-labelledby="technique-tab">
                                <ul class="tab-list">
                                    
                                    @if($rating->review->footwork)
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Footwork</h4>
                                                <p>{{$rating->review->footwork_comment}}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                        {{ number_format($rating->review->footwork, 1) }}
                                                    </div>
                                                    @if($rating->review->footwork)
                                                        @for ($i = 1; $i <= $rating->review->footwork; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @for($rating->review->footwork;$rating->review->footwork<5;$rating->review->footwork++)
                                                            <span class="grey-star"><i class="fas fa-star"></i></span>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    @if($rating->review->alingment)
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Alignment</h4>
                                                <p>{{$rating->review->alingment_comment}}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                        {{ number_format($rating->review->alingment, 1) }}
                                                    </div>
                                                    @if($rating->review->alingment)
                                                    @for ($i = 1; $i <= $rating->review->alingment; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($rating->review->alingment;$rating->review->alingment<5;$rating->review->alingment++)
                                                        <span class="grey-star"><i class="fas fa-star"></i></span>
                                                    @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    @if($rating->review->balance)
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Balance</h4>
                                                <p>{{$rating->review->balance_comment}}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                    {{ number_format($rating->review->balance, 1) }}
                                                        
                                                    </div>
                                                    @if($rating->review->balance)
                                                    @for ($i = 1; $i <= $rating->review->balance; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($rating->review->balance;$rating->review->balance<5;$rating->review->balance++)
                                                        <span class="grey-star"><i class="fas fa-star"></i></span>
                                                    @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    @if($rating->review->focus)
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Focus</h4>
                                                <p>{{$rating->review->focus_comment}}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                        {{ number_format($rating->review->focus, 1) }}
                                                        
                                                    </div>
                                                    @if($rating->review->focus)
                                                    @for ($i = 1; $i <= $rating->review->focus; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($rating->review->focus;$rating->review->focus<5;$rating->review->focus++)
                                                        <span class="grey-star"><i class="fas fa-star"></i></span>
                                                    @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    @if($rating->review->precision)
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Precision</h4>
                                                <p>{{$rating->review->precision_comment}}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                        {{ number_format($rating->review->precision, 1) }}
                                                    </div>
                                                    @if($rating->review->precision)
                                                    @for ($i = 1; $i <= $rating->review->precision; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($rating->review->precision;$rating->review->precision<5;$rating->review->precision++)
                                                        <span class="grey-star"><i class="fas fa-star"></i></span>
                                                    @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    @if($rating->review->timing)
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Timing</h4>
                                                <p>{{$rating->review->timing_comment}}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                        {{ number_format($rating->review->timing, 1) }}
                                                        
                                                    </div>
                                                    @if($rating->review->timing)
                                                    @for ($i = 1; $i <= $rating->review->timing; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($rating->review->timing;$rating->review->timing<5;$rating->review->timing++)
                                                        <span class="grey-star"><i class="fas fa-star"></i></span>
                                                    @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                </ul>
                            </div>

                            <div class="tab-pane fade" id="expression1-{{$rating->id}}" role="tabpanel" aria-labelledby="expression-tab">
                                <ul class="tab-list">
                                    @if($rating->review->energy)
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Energy</h4>
                                                <p>{{$rating->review->energy_comment}}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                        {{ number_format($rating->review->energy, 1) }}
                                                        
                                                    </div>
                                                    @if($rating->review->energy)
                                                    @for ($i = 1; $i <= $rating->review->energy; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($rating->review->energy+1;$rating->review->energy<5;$rating->review->energy++)
                                                        <span class="grey-star"><i class="fas fa-star"></i></span>
                                                    @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    @if($rating->review->style)
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Style</h4>
                                                <p>{{$rating->review->style_comment}}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                        {{ number_format($rating->review->style, 1) }}
                                                        
                                                    </div>
                                                    @if($rating->review->style)
                                                    @for ($i = 1; $i <= $rating->review->style; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($rating->review->style+1;$rating->review->style<5;$rating->review->style++)
                                                        <span class="grey-star"><i class="fas fa-star"></i></span>
                                                    @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    @if($rating->review->creativity)
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Creativity</h4>
                                                <p>{{$rating->review->creativity_comment}}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                    {{ number_format($rating->review->creativity, 1) }}

                                                    </div>
                                                    @if($rating->review->creativity)
                                                    @for ($i = 1; $i <= $rating->review->creativity; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($rating->review->creativity+1;$rating->review->creativity<5;$rating->review->creativity++)
                                                        <span class="grey-star"><i class="fas fa-star"></i></span>
                                                    @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    @if($rating->review->interpretation)
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Interpretation</h4>
                                                <p>{{$rating->review->interpretation_comment}}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                {{ number_format($rating->review->interpretation, 1) }}</div>
                                                    @if($rating->review->interpretation)
                                                    @for ($i = 1; $i <= $rating->review->interpretation; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($rating->review->interpretation+1;$rating->review->interpretation<5;$rating->review->interpretation++)
                                                        <span class="grey-star"><i class="fas fa-star"></i></span>
                                                    @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    
                                </ul>
                            </div>
                            <div class="tab-pane fade" id="choreography1-{{$rating->id}}" role="tabpanel" aria-labelledby="expression-tab">
                                <ul class="tab-list">
                                    @if($rating->review->formation)
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Formation</h4>
                                                <p>{{$rating->review->formation_comment}}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                    {{ number_format($rating->review->formation, 1) }}</div>
                                                    @if($rating->review->formation)
                                                    @for ($i = 1; $i <= $rating->review->formation; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($rating->review->formation+1;$rating->review->formation<5;$rating->review->formation++)
                                                        <span class="grey-star"><i class="fas fa-star"></i></span>
                                                    @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    @if($rating->review->artisty)
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Artisty</h4>
                                                <p>{{$rating->review->artisty_comment}}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">
                                                {{ number_format($rating->review->artisty, 1) }}</div>
                                                    @if($rating->review->artisty)
                                                    @for ($i = 1; $i <= $rating->review->artisty; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($rating->review->artisty+1;$rating->review->artisty<5;$rating->review->artisty++)
                                                        <span class="grey-star"><i class="fas fa-star"></i></span>
                                                    @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                </ul>
                                @if($rating->review->additional_tips)
                                    <div class="content-list pl-2 pt-4">
                                        <div class="left-box">

                                            <h4>Additional Tips</h4>
                                            <p>{!! $rating->review->additional_tips !!}</p>
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
        @endif
        @endforeach
    </div>
  </div>
</div>
@endsection
@section('js')
    
    <script>
        $(document).ready(function () {
            $('.video-thumb').click(function(e){
                e.stopPropagation();
                var videoid = "#"+$(this).data('videoid');
                $('.video-pause').each(function() {
                    $(this).get(0).pause();
                });
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