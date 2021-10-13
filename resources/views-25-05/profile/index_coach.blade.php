@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.3/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.3/assets/owl.theme.default.min.css" />
    <style type="text/css">
        @media screen and (min-width: 480px) {
            .heightable {
                display: -webkit-box;
                display: -webkit-flex;
                display: -ms-flexbox;
                display:         flex;
            }
            .heightable-nest {
                display: flex;
                flex-direction: column;
            }
            .bottom-button {
                position: absolute;
                bottom: 0;
            }
        }
        .owl-prev {
            display: inline !important;
        }
        .owl-next {
            display: inline !important;
        }
    </style>
@endsection

@section('content')

        @if( isset($vacation_msg) && $vacation_msg)
            <div class="alert alert-danger alert-dismissible">
                   {{$vacation_msg}}
            </div>
        @endif

        <!-- default modal -->
        <div class="modal fade" id="book_details" tabindex="-1" role="dialog" aria-labelledby="default-modalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                                <h2 class="color-danger text-center f-s-36 uppercase f-w-400"> Get feedback from {{ $user->first_name }}</h2>
                                <div class="col-xs-12">
                                    <div class="row m-t-15 heightable">
                                        <div class="col-xs-12 col-md-6 heightable-nest m-b-15">
                                            <h3 class="f-s-28 uppercase">Summary<br> package</h3>
                                            <h4 class="f-s-22 color-danger m-t-10">${{$user->price_summary ? $user->price_summary : env('DEFAULT_SUMMARY_PRICE')}}</h4>

                                            <h5 class="f-s-18 uppercase f-w-600">This package includes:</h5>
                                            <ul>
                                                <li>Audio review of your performance</li>
                                                <li>Written summary of feedback</li>
                                                <li>Summary performance review scorecard</li>
                                                <li>​​Level placement</li>
                                            </ul>

                                        </div>
                                        <div class="col-xs-12 col-md-6 heightable-nest m-b-15">
                                            <h3 class="f-s-28 uppercase">Detailed<br> package</h3>
                                            <h4 class="f-s-22 color-danger m-t-10">${{$user->price_detailed ? $user->price_detailed : env('DEFAULT_SUMMARY_DETAILED')}}</h4>

                                            <h5 class="f-s-18 uppercase f-w-600">This package includes:</h5>
                                            <ul>
                                                <li>Audio review of your performance</li>
                                                <li>Written summary of feedback</li>
                                                <li>Level placement</li>
                                                <li>Detailed performance review scorecard</li>
                                                <li>Answers to your 3 questions</li>
                                            </ul>

                                        </div>
                                    </div>
                                </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>
        <!-- default modal -->

    {{--@include('profile.header')--}}

    <div class="row m-b-20">
        <div class="col-xs-12 col-lg-12">
            <div class="bs-nav-tabs nav-tabs-warning">
                @if(Auth::user()->id == $user->id)
                    <ul class="nav nav-tabs nav-animated-border-from-left">
                        <li class="nav-item">
                            <a class="nav-link active">View Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/profile/{{Auth::user()->id}}/edit">Edit Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/profile/account_settings">Account settings</a>
                        </li>
                    </ul>
                @endif
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane in active" id="nav-tabs-0-1">
                        <div class="row">
                            <div class="col-xs-12 col-md-10 col-md-offset-1">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="row">
                                            <div class="col-xs-12 col-md-4">
                                                <div class="row m-t-25">
                                                    <div class="col-xs-12 col-md-10 col-md-offset-1">
                                                        {!! $user->getCoachAvatar() !!} {{-- video or img. Danger html in model! --}}
                                                        {{--<img src="{{ $user->avatar }}" style="width: 100%; max-width: 250px !important;">--}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-md-8">
                                                <h1  class="color-danger f-s-64 uppercase f-w-400">
                                                    {{$user->first_name}} {{$user->last_name}}
                                                </h1>
                                                <h2 class="f-s-32 uppercase">{{ $user->title }}</h2>

                                                @if( count($user->activity_genres->all()) > 0)
                                                    <h3 class="uppercase m-t-15">Genres</h3>
                                                    <ul>
                                                        @foreach($user->activity_genres->all() as $genre)
                                                            <li class="m-t-5">{{ $genre->name }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endif

                                                @if( count($user->performance_levels->all()) > 0)
                                                    <h3 class="uppercase m-t-15">Gives feedback to</h3>
                                                    <ul>
                                                        @foreach($user->performance_levels->all() as $level)
                                                            <li class="m-t-5">{{ $level->name }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endif

                                            </div>
                                        </div>

                                        <div class="row m-t-30">
                                            <div class="col-xs-12 col-md-offset-2 col-md-8">
                                                <h2 class="color-danger text-center f-s-36 uppercase f-w-400"> Get feedback from {{ $user->first_name }}</h2>
                                                <br>
                                                <div class="col-md-12 text-center">
                                                    <button id="show_detail" type="button" class="btn btn-danger">
                                                        Show detail
                                                    </button>
                                                </div>

                                                @php
                                                    $_auser = \Auth::user()->role;
                                                @endphp

                                                <div class="col-xs-12">

                                                    @if( isset($vacation_msg) && !$vacation_msg)
                                                    <div class="row m-t-15 heightable">
                                                        <div class="col-xs-12 col-md-6 heightable-nest m-b-15 text-center">
                                                            <div class="pack-type">
                                                            <h3 class="f-s-24 uppercase">Summary package</h3>
                                                            </div>
                                                            <h4 class="f-s-22 color-danger m-t-10">${{$user->price_summary ? $user->price_summary : env('DEFAULT_SUMMARY_PRICE')}}</h4>
                                                            {{--<h5 class="f-s-18 uppercase f-w-600">This package includes:</h5>
                                                            <ul>
                                                            </ul>--}}
                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <button {{( $_auser == 2 )?'disabled':''}} type="button" class="btn btn-danger btn-lg"
                                                                            onclick="window.location.href='/video/create?coach={{ $user->id }}';">
                                                                        BOOK NOW</button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-12 col-md-6 heightable-nest m-b-15 text-center">
                                                            <div class="pack-type">
                                                            <h3 class="f-s-24 uppercase">Detailed package</h3>
                                                            </div>
                                                            <h4 class="f-s-22 color-danger m-t-10">${{$user->price_detailed ? $user->price_detailed : env('DEFAULT_SUMMARY_DETAILED')}}</h4>
                                                            {{--<h5 class="f-s-18 uppercase f-w-600">This package includes:</h5>
                                                            <ul>
                                                            </ul>--}}
                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <button {{( $_auser == 2 )?'disabled':''}} type="button" class="btn btn-danger btn-lg"
                                                                            onclick="window.location.href='/video/create?coach={{ $user->id }}&package=detailed';">
                                                                        BOOK NOW
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    @endif


                                                </div>
                                            </div>
                                        </div>

                                        <div class="row m-t-15">
                                            <div class="col-xs-12 col-md-4">
                                                {{--<hr>--}}
                                                <div class="row m-t-15">
                                                    <div class="col-xs-12">

                                                        @php
                                                            $certifications = explode(';', $user->certifications);
                                                        @endphp
                                                        @if(!empty($certifications[0]))
                                                            <h3 class="f-s-22 uppercase color-danger text-center m-t-15">Certifications</h3>
                                                            <ul class="m-t-10">
                                                                @foreach($certifications as $certification)
                                                                    <li>{{ $certification }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif

                                                        @php
                                                            $teaching_positions = explode(';', $user->teaching_positions);
                                                        @endphp
                                                        @if(!empty($teaching_positions[0]))
                                                        <h3 class="f-s-22 uppercase color-danger text-center m-t-15">Teaching positions</h3>
                                                            <ul class="m-t-10">
                                                                @foreach($teaching_positions as $teaching_position)
                                                                    <li>{{ $teaching_position }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif


                                                        @php
                                                        $performance_credits = explode(';', $user->performance_credits);
                                                        @endphp
                                                        @if(!empty($performance_credits[0]))
                                                        <h3 class="f-s-22 uppercase color-danger text-center m-t-15">Performance credits</h3>
                                                        <ul class="m-t-10">
                                                            @foreach($performance_credits as $performance_credit)
                                                                <li>{{ $performance_credit }}</li>
                                                            @endforeach
                                                        </ul>
                                                        <hr>
                                                        @endif

                                                        @if( count($user->gallery->all()) > 0 || count($coachDocuments) )
                                                            <h3 class="f-s-28 f-w-400 uppercase color-danger text-center m-t-15">Gallery</h3>
                                                            <div class="owl-carousel" id="owl_carousel">
                                                                @foreach($user->gallery->all() as $gallery_item)
                                                                    <div>
                                                                        @if($gallery_item->type == 'image')
                                                                            <img src="/gallery/{{ $gallery_item->path }}">
                                                                        @else
                                                                            <iframe allowfullscreen="allowfullscreen" mozallowfullscreen="mozallowfullscreen" msallowfullscreen="msallowfullscreen" oallowfullscreen="oallowfullscreen" webkitallowfullscreen="webkitallowfullscreen"
                                                                            style="max-width: 278px;" src="https://www.youtube.com/embed/{{ $gallery_item->path }}">
                                                                            </iframe>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                                @foreach($coachDocuments as $document)
                                                                    <div class="item">
                                                                        <embed src="{{ asset(env('UPLOADS_FOLDER') . '/' . env('COACHES_DOCUMENTS_FOLDER') . '/' . $document['document_name']) }}"
                                                                               class="col-xs-12" type="{{ \File::mimeType($userDocunentFolder . '/' . $document['document_name']) }}" ></embed>
                                                                        <u><a href="{{ asset(env('UPLOADS_FOLDER') . '/' . env('COACHES_DOCUMENTS_FOLDER') . '/' . $document['document_name']) }}" target="_blank">
                                                                                View document{{-- $document['document_name'] --}}
                                                                        </a></u>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif

                                                        <div class="row m-b-20">
                                                            <div class="col-xs-12">
                                                                <div id="owl-nav" class="center-block" style="display: table"></div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-md-8">
                                                <div class="row m-t-15">
                                                    <div class="col-xs-12">
                                                        <h2 class="text-center uppercase color-danger m-t-15 f-s-22">Overview</h2>
                                                {!! $user->about !!}
                                            </div>
                                            </div>
                                            </div>


                                            @if( $user->social_links || $user->other_site_spec || $user->coachs_site || $user->wevsites )
                                            <div class="col-xs-12 col-md-8">
                                                <div class="row m-t-15">
                                                    <div class="col-xs-12">
                                                        <h2 class="text-center uppercase color-danger m-t-15 f-s-22">Social</h2>

                                                @if($user->wevsites)
                                                    <h3 class="f-s-22 uppercase color-danger m-t-15">Website</h3>
                                                    {{$user->wevsites}}
                                                @endif

                                                @if($user->social_links)
                                                <h3 class="f-s-22 uppercase color-danger m-t-15">Social media</h3>
                                                {{$user->social_links}}
                                                @endif
                                                @if($user->other_site_spec)
                                                    <h3 class="f-s-22 uppercase color-danger m-t-15">Other Blog sites</h3>
                                                    {{$user->other_site_spec}}
                                                @endif
                                                @if($user->coachs_site)
                                                    <h3 class="f-s-22 uppercase color-danger m-t-15">Links to Coach's site</h3>
                                                    {{$user->coachs_site}}
                                                @endif

                                            </div>
                                            </div>
                                            </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- old book button --}}

                        <div class="col-xs-12 m-t-10">
                        <h4 class="f-s-22 uppercase m-b-10">Terms & conditions</h4>
                        <ol>
                            <li>Submission videos must be between 1-3 min long.</li>
                            {{--<li>Please allow 3-5 business days for {{ $user->name }} to submit feedback.</li>--}}
                            <li>Please allow 3-5 business days to receive feedback.</li>
                        </ol>
                        <hr>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" style="font-family: 'Montserrat', sans-serif;font-weight: bold">WELCOME TO SHOWCASEHUB</h4>
                    </div>
                    <div class="modal-body">
                        <p><b>YOUR PASSWORD:</b> <b style="color: #be1900"> {{ session()->get('site_password') }}</b></p>
                        <p><b>USE IT TO LOGIN TO THE SITE THROUGH THE LOGIN BUTTON.</b></p>
                        <p><b>YOU CAN CHANGE THE PASSWORD IN PROFILE SETTINGS.</b></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">CLOSE</button>
                    </div>
                </div>

            </div>
        </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.3/owl.carousel.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            $('#show_detail').on('click', function () {
                $("#book_details").modal('show');
            });

            $("#owl_carousel").owlCarousel({
                nav:true,
                items:1,
                margin:10,
                navText: ['<button type="button" class="btn btn-warning btn-outline btn-circle m-r-5">' +
                    '<i class="fa fa-angle-left"></i> </button>', '<button type="button" ' +
                    'class="btn btn-warning btn-outline btn-circle m-r-5"><i class="fa fa-angle-right"></i></button>'],
                center:true,
                navContainer: "#owl-nav",
                loop:true
                /*autoHeight:true,
                autoplayHoverPause:true,
                autoplay:true*/
            });

            $("#owl_carousel_pdf").owlCarousel({
                nav:true,
                items:1,
                center:true,
                navContainer: "#owl-nav-pdf",
                loop:true,
                navText: [
                    '<button type="button" class="btn btn-warning btn-outline btn-circle m-r-5">' +
                    '<i class="fa fa-angle-left"></i> </button>', '<button type="button" ' +
                    'class="btn btn-warning btn-outline btn-circle m-r-5"><i class="fa fa-angle-right"></i></button>'
                ]
            });
        });

        $(window).on('load',function(){
            @if(session()->has('site_password'))
               $('#myModal').modal('show');
            @endif
        });
    </script>
@endsection