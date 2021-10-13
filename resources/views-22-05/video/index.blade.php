@extends('layouts.user')
@section('css')
    <style>
        .stripe-button-el{display: none !important;}
    </style>
    <link type="text/css" rel="stylesheet" href="/assets/js/sweetalert/sweet-alert.css">
@endsection
@section('content')
<div class="main-content">
            <div class="container-fluid">
    <div class="top-box">
        <div class="row">
            <div class="col-xl-5">
                <input type="search" name="" class="form-control" placeholder="Search by name…">
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
                    @foreach($videos as $video)
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card">
                                <div class="videos-box">
                                    @if(strpos($video->url, 'youtube') !== false)
                                        <iframe width="100%" height="300" src="{{$video->url}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    @else
                                   <video width="100%" height="300" controls >
                                        <source src="{{url('/') . config('video.user_video_path') . $video->url}}">
                                        Your browser does not support HTML5 video.
                                    </video>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h3>{{$video->name}}&nbsp;</h3>
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
                                                        <span><img src="/assets/img/progress.png" alt="" class="img-fluid"></span>
                                                        Accepted by Coach
                                                    </a>
                                                    @elseif($video->status == 2)
                                                        <a href="#" class="review-progress-btn">
                                                            <span><img src="/assets/img/progress.png" alt="" class="img-fluid"></span>
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
                    <div class="modal fade firs-modal right" id="modal-right-{{$video->id}}" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <a href="#" class="close" data-dismiss="modal" aria-label="Close"><img src="/assets/img/close.png" class="img-fluid"></a>
                                <div class="firs-snow-content">
                                    <div class="videos-box">
                                        <iframe width="100%" height="301" src="https://www.youtube.com/embed/rd4UgCQRzJ8" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                    <h3>Firs Snow</h3>
                                    <div class="level">Level: Intermediate</div>
                                    <div class="updated">Uploaded in 10.01.2020 at 9:35am</div>
                                    <ul class="nav" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview-{{$video->id}}" role="tab" aria-controls="overview" aria-selected="true">Overview</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="technique-tab" data-toggle="tab" href="#technique-{{$video->id}}" role="tab" aria-controls="technique" aria-selected="false">Technique</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="expression-tab" data-toggle="tab" href="#expression-{{$video->id}}" role="tab" aria-controls="expression" aria-selected="false">Expression</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="choreography-tab" data-toggle="tab" href="#choreography-{{$video->id}}" role="tab" aria-controls="choreography" aria-selected="false">Choreography</a>
                                        </li>
                                    </ul>

                                    <div class="tab-content" id="nav-tabContent">
                                        <div class="tab-pane fade show active" id="overview-{{$video->id}}" role="tabpanel" aria-labelledby="overview-tab">
                                            <div class="rating overal-rating">
                                                <div class="left-text">4.1</div>
                                                <div class="right-star"><i class="fas fa-star"></i> <i class="fas fa-star"></i><i class="fas fa-star"></i> <i class="fas fa-star"></i><span class="grey-star"><i class="fas fa-star"></i></span>
                                                    <span class="overal">Overal Rating</span>
                                                </div>
                                            </div>
                                            <div class="content-list">
                                                <div class="left-box">
                                                    <h4>Feedback Summary</h4>
                                                    <p>Feedback text sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis.</p>
                                                </div>
                                            </div>
                                            <div class="content-list d-block">
                                                <div class="media">
                                                    <div class="left-box">
                                                        <h4>Choach</h4>
                                                        <h3>Michelle Barber</h3>
                                                    </div>
                                                    <div class="img-box">
                                                        <img src="/assets/img/michelle-barber.jpg" class="img-fluid">
                                                    </div>
                                                </div>
                                                <ul class="question-list">
                                                    <li>
                                                        <h4>Question 1:</h4>
                                                        <h3>Consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore?</h3>
                                                        <p>Feedback text sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis.</p>
                                                    </li>
                                                    <li>
                                                        <h4>Question 2:</h4>
                                                        <h3>Consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore?</h3>
                                                        <p>Feedback text sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis.</p>
                                                    </li>
                                                </ul>
                                                <div class="new-question">
                                                    <a href="#" class="btn btn-outline-danger">New Question</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="technique-{{$video->id}}" role="tabpanel" aria-labelledby="technique-tab">
                                            <ul class="tab-list">
                                                <li>
                                                    <div class="content-list">
                                                        <div class="left-box">
                                                            <h4>Feedback Summary</h4>
                                                            <p>Feedback text sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis.</p>
                                                        </div>
                                                        <div class="right-box">
                                                            <div class="rating">
                                                                <div class="left-text">4.1</div>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="content-list">
                                                        <div class="left-box">
                                                            <h4>Footwork</h4>
                                                            <p>Short comment doloremque, totam.</p>
                                                        </div>
                                                        <div class="right-box">
                                                            <div class="rating">
                                                                <div class="left-text">4.1</div>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="content-list">
                                                        <div class="left-box">
                                                            <h4>Alignment</h4>
                                                            <p>Long comment doloremque, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur.</p>
                                                        </div>
                                                        <div class="right-box">
                                                            <div class="rating">
                                                                <div class="left-text">4.1</div>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="content-list">
                                                        <div class="left-box">
                                                            <h4>Balanced</h4>
                                                            <p>Timing comment doloremque, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis.</p>
                                                        </div>
                                                        <div class="right-box">
                                                            <div class="rating">
                                                                <div class="left-text">4.1</div>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="content-list">
                                                        <div class="left-box">
                                                            <h4>Focus</h4>
                                                            <p>Short comment doloremque, totam.</p>
                                                        </div>
                                                        <div class="right-box">
                                                            <div class="rating">
                                                                <div class="left-text">4.1</div>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="content-list">
                                                        <div class="left-box">
                                                            <h4>Precision</h4>
                                                            <p>Long comment doloremque, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur.</p>
                                                        </div>
                                                        <div class="right-box">
                                                            <div class="rating">
                                                                <div class="left-text">4.1</div>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
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
                                                            <h4>Feedback Summary</h4>
                                                            <p>Feedback text sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis.</p>
                                                        </div>
                                                        <div class="right-box">
                                                            <div class="rating">
                                                                <div class="left-text">4.1</div>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="content-list">
                                                        <div class="left-box">
                                                            <h4>Footwork</h4>
                                                            <p>Short comment doloremque, totam.</p>
                                                        </div>
                                                        <div class="right-box">
                                                            <div class="rating">
                                                                <div class="left-text">4.1</div>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="content-list">
                                                        <div class="left-box">
                                                            <h4>Alignement</h4>
                                                            <p>Long comment doloremque, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur.</p>
                                                        </div>
                                                        <div class="right-box">
                                                            <div class="rating">
                                                                <div class="left-text">4.1</div>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="content-list">
                                                        <div class="left-box">
                                                            <h4>Balanced</h4>
                                                            <p>Timing comment doloremque, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis.</p>
                                                        </div>
                                                        <div class="right-box">
                                                            <div class="rating">
                                                                <div class="left-text">4.1</div>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="content-list">
                                                        <div class="left-box">
                                                            <h4>Focus</h4>
                                                            <p>Short comment doloremque, totam.</p>
                                                        </div>
                                                        <div class="right-box">
                                                            <div class="rating">
                                                                <div class="left-text">4.1</div>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="content-list">
                                                        <div class="left-box">
                                                            <h4>Precision</h4>
                                                            <p>Long comment doloremque, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur.</p>
                                                        </div>
                                                        <div class="right-box">
                                                            <div class="rating">
                                                                <div class="left-text">4.1</div>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
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
                                                            <h4>Feedback Summary</h4>
                                                            <p>Feedback text sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis.</p>
                                                        </div>
                                                        <div class="right-box">
                                                            <div class="rating">
                                                                <div class="left-text">4.1</div>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="content-list">
                                                        <div class="left-box">
                                                            <h4>Footwork</h4>
                                                            <p>Short comment doloremque, totam.</p>
                                                        </div>
                                                        <div class="right-box">
                                                            <div class="rating">
                                                                <div class="left-text">4.1</div>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="content-list">
                                                        <div class="left-box">
                                                            <h4>Alignement</h4>
                                                            <p>Long comment doloremque, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur.</p>
                                                        </div>
                                                        <div class="right-box">
                                                            <div class="rating">
                                                                <div class="left-text">4.1</div>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="content-list">
                                                        <div class="left-box">
                                                            <h4>Balanced</h4>
                                                            <p>Timing comment doloremque, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis.</p>
                                                        </div>
                                                        <div class="right-box">
                                                            <div class="rating">
                                                                <div class="left-text">4.1</div>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="content-list">
                                                        <div class="left-box">
                                                            <h4>Focus</h4>
                                                            <p>Short comment doloremque, totam.</p>
                                                        </div>
                                                        <div class="right-box">
                                                            <div class="rating">
                                                                <div class="left-text">4.1</div>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="content-list">
                                                        <div class="left-box">
                                                            <h4>Precision</h4>
                                                            <p>Long comment doloremque, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur.</p>
                                                        </div>
                                                        <div class="right-box">
                                                            <div class="rating">
                                                                <div class="left-text">4.1</div>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                                <span class="grey-star"><i class="fas fa-star"></i></span>
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
                    @foreach($reviewed as $video)
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card">
                                <div class="videos-box">
                                   <video width="100%" height="300" controls >
                                        <source src="{{url('/') . config('video.user_video_path') . $video->url}}">
                                        Your browser does not support HTML5 video.
                                    </video>
                                </div>
                                <div class="card-body">
                                    <h3>{{$video->name}}</h3>
                                    <p>Uploaded in  @php
                                    $v_data = new \Carbon\Carbon($video->created_at);
                                    echo 'Posted date: ' . $v_data->format("m.d.Y g:i A");
                                @endphp</p>
                                @if($video->pay_status)
                                    <div class="row">
                                        <div class="col-md-12" style="min-height: 48px">
                                            @if($video->status == 3)
                                               
                                                <a href="javascript:void(0);" class="show-review-btn" data-toggle="modal" data-target="#reviewd-modal-right-{{$video->id}}"><span class="check-mark" ><i class="far fa-check-circle"></i></span>Show Review</a>
                                            @endif

                                            
                                                @if($video->status == 1)
                                                    <a href="#" class="review-progress-btn">
                                                        <span><img src="/assets/img/progress.png" alt="" class="img-fluid"></span>
                                                        Accepted by Coach
                                                    </a>
                                                    @elseif($video->status == 2)
                                                        <a href="#" class="review-progress-btn">
                                                            <span><img src="/assets/img/progress.png" alt="" class="img-fluid"></span>
                                                        Under Review
                                                    </a>
                                                @endif
                                           
                                            
                                            
                                        </div>
                                    </div>
                                    @else
                                        <div class="row">
                                            <form action="/pay-video" method="post">
                                                <div class="col-md-12" style="min-height: 25px">
                                                    {!! csrf_field() !!}
                                                    <input type="hidden" name="v_id" value="{{$video->id}}">
                                                    <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                                        data-key="{{config('services.stripe.key')}}"
                                                        data-description="Video name: {{$video->name}}"
                                                        data-amount="{{(int)$video->video_price}}"
                                                        data-locale="auto"></script>
                                                    <button type="submit" class="btn btn-outline-danger">Payment</button>
                                                </div>
                                            </form>

                                        </div>
                                    @endif
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @endforeach
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
                    @foreach($waitingreview as $video)
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card">
                                <div class="videos-box">
                                   <video width="100%" height="300" controls >
                                        <source src="{{url('/') . config('video.user_video_path') . $video->url}}">
                                        Your browser does not support HTML5 video.
                                    </video>
                                </div>
                                <div class="card-body">
                                    <h3>{{$video->name}}</h3>
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
                                                        <span><img src="/assets/img/progress.png" alt="" class="img-fluid"></span>
                                                        Accepted by Coach
                                                    </a>
                                                    @elseif($video->status == 2)
                                                        <a href="#" class="review-progress-btn">
                                                            <span><img src="/assets/img/progress.png" alt="" class="img-fluid"></span>
                                                        Under Review
                                                    </a>
                                                @endif
                                           
                                            
                                            
                                        </div>
                                    </div>
                                    @else
                                        <div class="row">
                                            <form action="/pay-video" method="post">
                                                <div class="col-md-12" style="min-height: 25px">
                                                    {!! csrf_field() !!}
                                                    <input type="hidden" name="v_id" value="{{$video->id}}">
                                                    <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                                        data-key="{{config('services.stripe.key')}}"
                                                        data-description="Video name: {{$video->name}}"
                                                        data-amount="{{(int)$video->video_price}}"
                                                        data-locale="auto"></script>
                                                    <button type="submit" class="btn btn-outline-danger">Payment</button>
                                                </div>
                                            </form>

                                        </div>
                                    @endif
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
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
                    @foreach($pendingpayment as $video)
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card">
                                <div class="videos-box">
                                   <video width="100%" height="300" controls >
                                        <source src="{{url('/') . config('video.user_video_path') . $video->url}}">
                                        Your browser does not support HTML5 video.
                                    </video>
                                </div>
                                <div class="card-body">
                                    <h3>{{$video->name}}</h3>
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
                                                        <span><img src="/assets/img/progress.png" alt="" class="img-fluid"></span>
                                                        Accepted by Coach
                                                    </a>
                                                    @elseif($video->status == 2)
                                                        <a href="#" class="review-progress-btn">
                                                            <span><img src="/assets/img/progress.png" alt="" class="img-fluid"></span>
                                                        Under Review
                                                    </a>
                                                @endif
                                           
                                            
                                            
                                        </div>
                                    </div>
                                    @else
                                        <div class="row">
                                            <form action="/pay-video" method="post">
                                                <div class="col-md-12" style="min-height: 25px">
                                                    {!! csrf_field() !!}
                                                    <input type="hidden" name="v_id" value="{{$video->id}}">
                                                    <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                                        data-key="{{config('services.stripe.key')}}"
                                                        data-description="Video name: {{$video->name}}"
                                                        data-amount="{{(int)$video->video_price}}"
                                                        data-locale="auto"></script>
                                                    <button type="submit" class="btn btn-outline-danger">Payment</button>
                                                </div>
                                            </form>

                                        </div>
                                    @endif
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="row">
                    <div class="col-md-12 justify-content-center">
                        {{ $pendingpayment->links() }}
                    </div>
                </div>
            </div>
        </div>
        
        @foreach($videoratings as $rating)
       <div class="modal fade firs-modal right" id="reviewd-modal-right-{{$rating->id}}" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close"><img src="/assets/img/close.png" class="img-fluid"></a>
                    <div class="firs-snow-content">
                        <div class="videos-box">
                        	@if(strpos($rating->url, 'youtube') !== false)
                                <iframe width="100%" height="300" src="{{$rating->url}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            @else
	                           	<video width="100%" height="300" controls >
	                                <source src="{{url('/') . config('video.user_video_path') . $rating->url}}">
	                                Your browser does not support HTML5 video.
	                            </video>
                            @endif
                        </div>
                        <h3>{{$rating->name}}</h3>
                        <div class="level">Level: @if($rating->level == 3) Advance @elseif($rating->level == 2) Intermediate @else Bigginer @endif</div>
                        <div class="updated">
                        	@php
                            	$v_data = new \Carbon\Carbon($rating->created_at);
                            	echo 'Uploaded in ' . $v_data->format("m.d.Y g:i A");
                    		@endphp
                    		@php
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

				             
					            
					        @endphp
                		</div>
                        <ul class="nav" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview-{{$rating->id}}" role="tab" aria-controls="overview" aria-selected="true">Overview {{$rating->review->id}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="technique-tab" data-toggle="tab" href="#technique-{{$rating->id}}" role="tab" aria-controls="technique" aria-selected="false">Technique</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="expression-tab" data-toggle="tab" href="#expression-{{$rating->id}}" role="tab" aria-controls="expression" aria-selected="false">Expression</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="choreography-tab" data-toggle="tab" href="#choreography-{{$rating->id}}" role="tab" aria-controls="choreography" aria-selected="false">Choreography</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="overview-{{$rating->id}}" role="tabpanel" aria-labelledby="overview-tab">
                                <div class="rating overal-rating">
                                    <div class="left-text">{{$overallrating}}</div>
                                    <div class="right-star">
                                    	@if($overallrating)
	                                        @for ($i = 1; $i < $overallrating; $i++)
	                                            <i class="fas fa-star"></i>
	                                        @endfor
	                                        @for($overallrating;$overallrating<=5;$overallrating++)
	                                            <span class="grey-star"><i class="fas fa-star"></i></span>
	                                        @endfor
                                    	@endif
                                    	
                                        <span class="overal">Overal Rating</span>
                                    </div>
                                </div>
                                <div class="content-list">
                                	@if($rating->review->message)
	                                    <div class="left-box">
	                                        <h4>Feedback Summary</h4>
	                                        <p>{{$rating->review->message}}</p>
	                                    </div>
                                    @endif
                                </div>
                                <div class="content-list d-block">
                                    <div class="media">
                                        <div class="left-box">
                                            <h4>Choach</h4>
                                            <h3>Michelle Barber</h3>
                                        </div>
                                        <div class="img-box">
                                            <img src="/assets/img/michelle-barber.jpg" class="img-fluid">
                                        </div>
                                    </div>
                                    <ul class="question-list">
                                        <li>
                                            <h4>Question 1:</h4>
                                            <h3>Consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore?</h3>
                                            <p>Feedback text sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis.</p>
                                        </li>
                                        <li>
                                            <h4>Question 2:</h4>
                                            <h3>Consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore?</h3>
                                            <p>Feedback text sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis.</p>
                                        </li>
                                    </ul>
                                    <div class="new-question">
                                        <a href="#" class="btn btn-outline-danger">New Question</a>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="technique-{{$rating->id}}" role="tabpanel" aria-labelledby="technique-tab">
                                <ul class="tab-list">
                                	@if($rating->review->message)
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Feedback Summary</h4>
                                                <p>{{$rating->review->message}}</p>
                                            </div>
                                            <!-- <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">4.1</div>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <span class="grey-star"><i class="fas fa-star"></i></span>
                                                </div>
                                            </div> -->
                                        </div>
                                    </li>
                                    @endif
                                    @if($rating->review->footwork)
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Footwork</h4>
                                                <p>{{$rating->review->footwork_comment}}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{$rating->review->footwork}}</div>
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
                                                    <div class="left-text">{{$rating->review->alingment}}</div>
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
                                                <h4>Balanced</h4>
                                                <p>{{$rating->review->balance_comment}}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{$rating->review->balance}}</div>
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
                                                    <div class="left-text">{{$rating->review->focus}}</div>
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
                                                    <div class="left-text">{{$rating->review->precision}}</div>
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
                                </ul>
                            </div>

                            <div class="tab-pane fade" id="expression-{{$rating->id}}" role="tabpanel" aria-labelledby="expression-tab">
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
                                                    <div class="left-text">{{$rating->review->energy}}</div>
                                                    @if($rating->review->energy)
                                                    @for ($i = 1; $i < $rating->review->energy; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($rating->review->energy;$rating->review->energy<5;$rating->review->energy++)
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
                                                    <div class="left-text">{{$rating->review->style}}</div>
                                                    @if($rating->review->style)
                                                    @for ($i = 1; $i < $rating->review->style; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($rating->review->style;$rating->review->style<5;$rating->review->style++)
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
                                                    <div class="left-text">{{$rating->review->creativity}}</div>
                                                    @if($rating->review->creativity)
                                                    @for ($i = 1; $i < $rating->review->creativity; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($rating->review->creativity;$rating->review->creativity<5;$rating->review->creativity++)
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
                                                    <div class="left-text">{{$rating->review->interpretation}}</div>
                                                    @if($rating->review->interpretation)
                                                    @for ($i = 1; $i < $rating->review->interpretation; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($rating->review->interpretation;$rating->review->interpretation<5;$rating->review->interpretation++)
                                                        <span class="grey-star"><i class="fas fa-star"></i></span>
                                                    @endfor
                                                	@endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    @if($rating->review->formation)
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Formation</h4>
                                                <p>{{$rating->review->formation_comment}}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">4.1</div>
                                                    @if($rating->review->formation)
                                                    @for ($i = 1; $i < $rating->review->formation; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($rating->review->formation;$rating->review->formation<5;$rating->review->formation++)
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
                                                    <div class="left-text">{{$rating->review->artisty}}</div>
                                                    @if($rating->review->artisty)
                                                    @for ($i = 1; $i < $rating->review->artisty; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($rating->review->artisty;$rating->review->artisty<5;$rating->review->artisty++)
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
							<div class="tab-pane fade" id="choreography-{{$rating->id}}" role="tabpanel" aria-labelledby="expression-tab">
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
                                                    <div class="left-text">{{$rating->review->energy}}</div>
                                                    @if($rating->review->energy)
                                                    @for ($i = 1; $i <= $rating->review->energy; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($rating->review->energy;$rating->review->energy<5;$rating->review->energy++)
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
                                                    <div class="left-text">{{$rating->review->style}}</div>
                                                    @if($rating->review->style)
                                                    @for ($i = 1; $i <= $rating->review->style; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($rating->review->style;$rating->review->style<5;$rating->review->style++)
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
                                                    <div class="left-text">{{$rating->review->creativity}}</div>
                                                    @if($rating->review->creativity)
                                                    @for ($i = 1; $i <= $rating->review->creativity; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($rating->review->creativity;$rating->review->creativity<5;$rating->review->creativity++)
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
                                                <p>{{$rating->review->interpretation}}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">{{$rating->review->interpretation_comment}}</div>
                                                    @if($rating->review->interpretation)
                                                    @for ($i = 1; $i <= $rating->review->interpretation; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($rating->review->interpretation;$rating->review->interpretation<5;$rating->review->interpretation++)
                                                        <span class="grey-star"><i class="fas fa-star"></i></span>
                                                    @endfor
                                                	@endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    @if($rating->review->formation)
                                    <li>
                                        <div class="content-list">
                                            <div class="left-box">
                                                <h4>Formation</h4>
                                                <p>{{$rating->review->formation_comment}}</p>
                                            </div>
                                            <div class="right-box">
                                                <div class="rating">
                                                    <div class="left-text">4.1</div>
                                                    @if($rating->review->formation)
                                                    @for ($i = 1; $i <= $rating->review->formation; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($rating->review->formation;$rating->review->formation<5;$rating->review->formation++)
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
                                                    <div class="left-text">{{$rating->review->artisty}}</div>
                                                    @if($rating->review->artisty)
                                                    @for ($i = 1; $i <= $rating->review->artisty; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for($rating->review->artisty;$rating->review->artisty<5;$rating->review->artisty++)
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
            

                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
  </div>
</div>
@endsection
@section('js')
    <script type="text/javascript" src="/assets/js/sweetalert/sweet-alert.min.js"></script>
    <script>
        $(document).ready(function () {
            var success_payment = $('#success_payment').attr('data-value');
            var error_payment = $('#error_payment').attr('data-value');
            if(success_payment){
                swal("Success!", "Review successfully paid!", "success");
            }
            if(error_payment){
                swal("Error!", "Oops, something happened. Try later.", "error");
            }
        });
    </script>
@endsection