@extends('layouts.app')
@section('css')
    <link type="text/css" rel="stylesheet" href="/assets/js/sweetalert/sweet-alert.css">
    <style type="text/css">
        .videoWrapper {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 */
            padding-top: 25px;
            height: 0;
        }

        .videoWrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .color-gray {
            color: #818190 !important;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <ul class="nav nav-tabs nav-animated-border-from-right">
            <li class="nav-item">
                <a class="nav-link {{ $show == 'myreviews' ? '' : 'active' }}"
                   href="{{ $show == 'myreviews' ? url('/myreviews') : 'javascript:void(0);'}}">
                    <h1 class="text-uppercase text-center f-w-400">New submissions</h1>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $show != 'myreviews' ? '' : 'active' }}"
                   href="{{ $show != 'myreviews' ? url('/myreviews?show=myreviews') : 'javascript:void(0);'}}">
                    <h1 class="text-uppercase text-center f-w-400">My reviews</h1>
                </a>
            </li>
        </ul>
        <div class="row m-t-20">
            <div class="col-md-12">
                @if(!count($videos) && $search_text)
                    <h2 class="text-center f-w-400 m-t-20">Sorry, no results were found.</h2>
                @elseif(!count($videos))
                    <h2 class="text-center f-w-400 m-t-20">List is empty.</h2>
                @endif

                @foreach($videos as $video)
                    <div class="row m-b-20 p-t-15">
                        <div class="col-xs-12 col-md-7">
                            <div class="row" id="video__{{ $video->id }}">
                                <div class="col-xs-12 col-md-12">
                                    <h2 class="uppercase f-s-28">
                                        <a href="{{ url('/profile/'.$video->user->id) }}">
                                            {{ $video->user->first_name . " " . $video->user->last_name }}
                                        </a>
                                    </h2>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-0 col-md-2"></div>
                                <div class="col-xs-12 col-md-10">
                                    <span class="uppercase f-s-16 color-gray">Video name:</span>
                                    <span>{{ $video->name }}</span> <br>
                                    <span class="uppercase f-s-16 color-gray">Level:</span>
                                    <span>{{ $video->performance_level->name }}</span> <br>
                                    {{--<span class="uppercase f-s-16 color-gray">Genres:</span>
                                    <span>{{ implode(', ', $video->activity_genres->lists('name')->all()) }}</span> <br>--}}
                                    <span class="uppercase f-s-16 color-gray">Location:</span>
                                    <span>{{ $video->user->location }}</span> <br>
                                    <span class="uppercase f-s-16 color-gray">Date submitted:</span>
                                    <span>{{ $video->created_at }}</span> <br>
                                    <span class="uppercase f-s-16 color-gray">Video length:</span>
                                    <span id="length-{{ $video->url }}" style="display: none;"></span> <br>
                                    <span class="uppercase f-s-16 color-gray">Review package:</span>
                                    <span>
                                        @if($video->questions->count())
                                            DETAILED PACKAGE
                                        @else
                                            SUMMARY PACKAGE
                                        @endif
                                    </span> <br>
                                    <hr class="m-b-5">
                                    <span class="uppercase f-s-18 color-danger">Profile summary</span> <br>
                                    <span>{!! str_limit($video->user->about, 350, "...") !!}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-5">
                            <div class="row">
                                <div class="col-xs-12 col-md-10">
                                        <video width="100%" height="310" controls>
                                            @if($show == 'myreviews')
                                            <source src="{{url('/') . config('video.completed_review_path') . $video->review->review_url}}">
                                            @else
                                            <source src="{{url('/') . config('video.user_video_path') . $video->url}}">
                                                @endif
                                            Your browser does not support HTML5 video.
                                        </video>
                                </div>
                                <div class="col-xs-0 col-md-2"></div>
                            </div>
                            <div class="col-xs-12 col-md-10">

                                @if($show != 'myreviews')
                                    <button type="button" class="check-stripe btn btn-warning-800 btn-flat m-t-10 center-block uppercase"
                                            onclick-url="{{ url('/review/create/'.$video->id) }}">
                                        Review now
                                    </button>
                                @else
                                    <button type="button" class="check-stripe btn btn-warning-800 btn-flat m-t-10 center-block uppercase"
                                            onclick-url="{{ url('/review/show-my/'.$video->id) }}">
                                        View review
                                    </button>

                                    <button type="button" class="check-stripe btn btn-danger-800 btn-flat m-t-10 center-block uppercase"
                                            onclick-url="{{ url('/review/create/'.$video->id) }}">
                                        Edit review
                                    </button>

                                @endif
                            </div>
                            <div class="col-xs-0 col-md-2"></div>
                        </div>
                    </div>
                    <hr class="m-b-5">
                @endforeach
                    @php
                        $show = ( isset($_GET['show']) ) ? $_GET['show'] : '';
                    @endphp
                @include('pagination.default', ['paginator' => $videos->appends( ['show' => $show ,'search' => $search_text])])

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="/assets/js/sweetalert/sweet-alert.min.js"></script>
    <script type="text/javascript">

        function checkStripeEvent() {

            $('.check-stripe').on('click', function () {

                var that = $(this);
                var formData = new FormData;
                formData.append("_token", "{{ csrf_token() }}");
                formData.append("user_id", "{{$user->id}}");

                $.ajax({
                    url: "/checkStripeConnect",
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        $('.check-stripe').off('click');
                    },
                    success: function(data){
                        if(data.stripe_connect != 0){
                            window.location.href = that.attr('onclick-url');
                        } else {
                            swal("Error!", "Please connect to showcase-hub stripe platform in your profile account settings!", "error");
                        }
                        checkStripeEvent()
                    },
                    error: function () {
                        alert("Oops something happens.");
                        location.reload();
                        checkStripeEvent()
                    }
                });

            })
        }
        checkStripeEvent();

    </script>
@endsection
