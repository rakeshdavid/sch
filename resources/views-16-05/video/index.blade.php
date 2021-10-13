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