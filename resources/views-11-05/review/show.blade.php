@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="/assets/js/rateit/rateit.css">
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

        .text-gray {
            color: #818181 !important;
        }
    </style>
@endsection

@section('content')
    <div class="container">

        <h1 class="f-s-48 f-w-400 text-uppercase text-danger text-center m-b-20">
            {{ $review->video->user->first_name . ' ' . $review->video->user->last_name }} - Review
        </h1>

        <div class="row m-b-20">
            <div class="col-xs-12">
                <h3 class="text-center m-b-0">
                    <span class="f-s-18 text-uppercase f-w-500 text-gray">Level:</span>
                    <span class="f-s-18">{{ $review->video->performance_level->name }}</span>
                </h3>
            </div>
        </div>

        <div class="row">

            <div class="col-xs-12 col-md-10 col-md-offset-1">
                <div class="row" >
                    <div align="center" class="embed-responsive embed-responsive-16by9">
                        <video width="728" height="410" controls class="embed-responsive-item">
                            <source src="{{url('/') . config('video.completed_review_path') . $review->review_url}}">
                            Your browser does not support HTML5 video.
                        </video>
                    </div>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-12 col-md-10 col-md-offset-1">
                        <div class="row m-t-15">
                            <div class="col-xs-12 col-md-6">
                                <h2 class="text-danger f-s-22 f-w-500 text-uppercase">Profile</h2>
                                <ul class="p-l-20">
                                    <li> <span class="text-uppercase">Location: </span> {{ $review->video->user->location }}</li>
                                    @php
                                        $age = '';
                                        try {
                                            $age = Carbon\Carbon::parse($review->video->user->birthday);
                                            $age = $age->diffInYears(Carbon\Carbon::now());
                                        } catch (\Exception $e) {
                                            $age = '';
                                        }
                                    @endphp
                                    <li> <span class="text-uppercase">Age: </span> {{ $age }}</li>
                                    <li> <span class="text-uppercase">Activity experience </span>(years): {{ $review->video->activity_experience }}</li>
                                    <li> <span class="text-uppercase">Seeking auditions: </span>
                                        <span class="text-capitalize">
                                            {{ str_replace('_', ' ', $review->video->seeking_auditions)}}
                                        </span>
                                    </li>
                                </ul>
                                @if(!is_null($review->performance_level_placement))
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h4 class="f-s-18 f-w-500">
                                                Level: {{ $review->performance_level_placement->name }}</h4>
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h4 class="f-s-18 f-w-500" style="display: inline;">Overall rating: </h4>
                                        <div class="f-s-18 f-w-500 m-r-5" style="display: inline;">
                                            {{ strlen($review->overall_rating) == 1 ? $review->overall_rating . '.0' : $review->overall_rating}}
                                        </div>
                                        <div class="rateit bigstars" data-rateit-value="{{$review->overall_rating}}"
                                             data-rateit-ispreset="true" data-rateit-readonly="true"></div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-xs-12 col-md-6">
                                <h2 class="text-danger f-s-22 f-w-500 text-uppercase">Feedback Summary</h2>
                                <p> {{ $review->message }}</p>
                            </div>
                        </div>
                        <div style="display: none;">
                            <span id="video_id" data-video-id="{{$review->video->id}}">{{$review->video->name}}</span>
                            <span id="user_id" data-user-id="{{$review->video->user_id}}">{{$review->video->user_first_name}}  {{$review->video->user_last_name}}</span>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <p class="text-center text-danger f-w-500 f-s-22">TECHNIQUE SCORE</p>
                    <div class="row">
                        <div class="col-xs-12 col-md-4">
                            <span style="display: block" class="m-b-5 capitalize">Timing {{number_format($review->timing,1)}}</span>
                            <div class="rateit pull-right" data-rateit-value="{{$review->timing}}"
                                 data-rateit-ispreset="true" data-rateit-readonly="true"
                                 style="display: block; float: left; width: 100%"></div>
                            <fieldset style="float: left">
                                <blockquote style="display: block; border-left: 1px solid #262932;" class="p-l-10">
                                    Comments: {{ $review->timing_comment ? $review->timing_comment : 'none' }}
                                </blockquote>
                            </fieldset>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <span style="display: block" class="m-b-5 capitalize">Footwork {{number_format($review->footwork,1)}}</span>
                            <div class="rateit pull-right" data-rateit-value="{{$review->footwork}}"
                                 data-rateit-ispreset="true" data-rateit-readonly="true"
                                 style="display: block; float: left; width: 100%"></div>
                            <fieldset style="float: left">
                                <blockquote style="display: block; border-left: 1px solid #262932;" class="p-l-10">
                                    Comments: {{ $review->footwork_comment ? $review->footwork_comment : 'none' }}
                                </blockquote>
                            </fieldset>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <span style="display: block" class="m-b-5 capitalize">Alignment {{number_format($review->alingment,1)}}</span>
                            <div class="rateit pull-right" data-rateit-value="{{$review->alingment}}"
                                 data-rateit-ispreset="true" data-rateit-readonly="true"
                                 style="display: block; float: left; width: 100%"></div>
                            <fieldset style="float: left">
                                <blockquote style="display: block; border-left: 1px solid #262932;" class="p-l-10">
                                    Comments: {{ $review->alingment_comment ? $review->alingment_comment : 'none' }}
                                </blockquote>
                            </fieldset>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <span style="display: block" class="m-b-5 capitalize">balance {{number_format($review->balance,1)}}</span>
                            <div class="rateit pull-right" data-rateit-value="{{$review->balance}}"
                                 data-rateit-ispreset="true" data-rateit-readonly="true"
                                 style="display: block; float: left; width: 100%"></div>
                            <fieldset style="float: left">
                                <blockquote style="display: block; border-left: 1px solid #262932;" class="p-l-10">
                                    Comments: {{ $review->balance_comment ? $review->balance_comment : 'none' }}
                                </blockquote>
                            </fieldset>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <span style="display: block" class="m-b-5 capitalize">focus {{number_format($review->focus,1)}}</span>
                            <div class="rateit pull-right" data-rateit-value="{{$review->focus}}"
                                 data-rateit-ispreset="true" data-rateit-readonly="true"
                                 style="display: block; float: left; width: 100%"></div>
                            <fieldset style="float: left">
                                <blockquote style="display: block; border-left: 1px solid #262932;" class="p-l-10">
                                    Comments: {{ $review->focus_comment ? $review->focus_comment : 'none' }}
                                </blockquote>
                            </fieldset>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <span style="display: block" class="m-b-5 capitalize">precision {{number_format($review->precision,1)}}</span>
                            <div class="rateit pull-right" data-rateit-value="{{$review->precision}}"
                                 data-rateit-ispreset="true" data-rateit-readonly="true"
                                 style="display: block; float: left; width: 100%"></div>
                            <fieldset style="float: left">
                                <blockquote style="display: block; border-left: 1px solid #262932;" class="p-l-10">
                                    Comments: {{ $review->precision_comment ? $review->precision_comment : 'none' }}
                                </blockquote>
                            </fieldset>
                        </div>
                    </div>
                    <hr>
                </div>

                <div class="col-md-12">
                    <p class="text-center text-danger f-w-500 f-s-22 uppercase">Expression</p>
                    <div class="row">
                        <div class="col-xs-12 col-md-3">
                            <span style="display: block" class="m-b-5 capitalize">energy {{number_format($review->energy,1)}}</span>
                            <div class="rateit pull-right" data-rateit-value="{{$review->energy}}"
                                 data-rateit-ispreset="true" data-rateit-readonly="true"
                                 style="display: block; float: left; width: 100%"></div>
                            <fieldset style="float: left">
                                <blockquote style="display: block; border-left: 1px solid #262932;" class="p-l-10">
                                    Comments: {{ $review->energy_comment ? $review->energy_comment : 'none' }}
                                </blockquote>
                            </fieldset>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <span style="display: block" class="m-b-5 capitalize">style {{number_format($review->style,1)}}</span>
                            <div class="rateit pull-right" data-rateit-value="{{$review->style}}"
                                 data-rateit-ispreset="true" data-rateit-readonly="true"
                                 style="display: block; float: left; width: 100%"></div>
                            <fieldset style="float: left">
                                <blockquote style="display: block; border-left: 1px solid #262932;" class="p-l-10">
                                    Comments: {{ $review->style_comment ? $review->style_comment : 'none' }}
                                </blockquote>
                            </fieldset>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <span style="display: block" class="m-b-5 capitalize">creativity {{number_format($review->creativity,1)}}</span>
                            <div class="rateit pull-right" data-rateit-value="{{$review->creativity}}"
                                 data-rateit-ispreset="true" data-rateit-readonly="true"
                                 style="display: block; float: left; width: 100%"></div>
                            <fieldset style="float: left">
                                <blockquote style="display: block; border-left: 1px solid #262932;" class="p-l-10">
                                    Comments: {{ $review->creativity_comment ? $review->creativity_comment : 'none' }}
                                </blockquote>
                            </fieldset>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <span style="display: block" class="m-b-5 capitalize">interpretation {{number_format($review->interpretation,1)}}</span>
                            <div class="rateit pull-right" data-rateit-value="{{$review->interpretation}}"
                                 data-rateit-ispreset="true" data-rateit-readonly="true"
                                 style="display: block; float: left; width: 100%"></div>
                            <fieldset style="float: left">
                                <blockquote style="display: block; border-left: 1px solid #262932;" class="p-l-10">
                                    Comments: {{ $review->interpretation_comment ? $review->interpretation_comment : 'none' }}
                                </blockquote>
                            </fieldset>
                        </div>
                    </div>
                    <hr>
                </div>

                <div class="col-md-12">
                    <p class="text-center text-danger f-w-500 f-s-22 uppercase">Choreography</p>
                    <div class="row">
                        <div class="col-xs-12 col-md-4">
                            <span style="display: block" class="m-b-5 f-s-18 capitalize">formation {{number_format($review->formation,1)}}</span>
                            <div class="rateit pull-right" data-rateit-value="{{$review->formation}}"
                                 data-rateit-ispreset="true" data-rateit-readonly="true"
                                 style="display: block; float: left; width: 100%"></div>
                            <fieldset style="float: left">
                                <blockquote style="display: block; border-left: 1px solid #262932;" class="p-l-10">
                                    Comments: {{ $review->formation_comment ? $review->formation_comment : 'none' }}
                                </blockquote>
                            </fieldset>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <span style="display: block" class="m-b-5 f-s-18 capitalize">artistry {{number_format($review->artisty,1)}}</span>
                            <div class="rateit pull-right" data-rateit-value="{{$review->artisty}}"
                                 data-rateit-ispreset="true" data-rateit-readonly="true"
                                 style="display: block; float: left; width: 100%"></div>
                            <fieldset style="float: left">
                                <blockquote style="display: block; border-left: 1px solid #262932;" class="p-l-10">
                                    Comments: {{ $review->artisty_comment ? $review->artisty_comment : 'none' }}
                                </blockquote>
                            </fieldset>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
            @if($review->video->questions->count())
                <div class="xol-xs-12">
                    <div class="row">
                        <h3 class="text-danger text-uppercase f-s-22 f-w-500 text-center m-b-15">
                            Questions and answers
                        </h3>
                        @foreach($review->video->questions as $QA)
                            <div class="col-xs-12 col-md-4">
                                <strong>{{ $QA->question }}</strong>
                                <p>{{ $QA->answer }}</p>
                            </div>
                        @endforeach
                    </div>
                    <hr>
                </div>
            @endif
            <div class="row">
                <div class="col-xs-12">
                    <h4 class="f-s-22 uppercase text-danger f-w-500 m-b-15">
                        Additional tips for {{ $review->video->user->first_name }}
                    </h4>
                    <p class="card-text" style="word-break:break-all;">
                        {{ $review->additional_tips  }}
                    </p>
                </div>
                <hr>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script src="/assets/js/rateit/jquery.rateit.js"></script>
@endsection
