@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="/assets/js/rateit/rateit.css">
    <link type="text/css" rel="stylesheet" href="/assets/js/toastr/toastr.min.css">
    <link type="text/css" rel="stylesheet" href="/assets/js/sweetalert/sweet-alert.css">
@endsection
@section('content')
    <div class="container">
        <h1 class="f-s-48 color-danger text-center f-w-500 m-b-15 text-uppercase">
            <a href="/profile/{{ $video->user->id }}" target="_blank" style="color: #d9534f !important">
                {{ $video->user->first_name . ' ' . $video->user->last_name }}
            </a>
        </h1>
        <div class="row m-b-20">
            <div class="col-xs-12">
                <h3 class="text-center m-b-0">
                    <span class="f-s-18 text-uppercase f-w-500 text-gray">Level:</span>
                    <span class="f-s-18">{{ $video->performance_level->name }}</span>
                </h3>
            </div>
        </div>
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger" style="background-color: #EF0025">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div style="display: none;">
            <span id="video_id" data-video-id="{{$video->id}}">{{$video->name}}</span>
            <span id="user_id" data-user-id="{{$video->user_id}}">{{$video->user_first_name}}  {{$video->user_last_name}}</span>
        </div>
        <form action="{{url('review/audition-rating')}}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <input type="hidden" name="video_id" value="{{$video->id}}">
             <input type="hidden" name="owner_id" value="{{$video->user_id}}">
            <div id="rating_blk">
                <div class="row">
                    <div class="col-xs-12 form-group">
                        <label class="f-w-400 f-s-18" for="performance_level_placement">Performance level placement</label>
                        <select name="performance_level_placement" id="performance_level_placement" autocomplete="off" class="form-control c-select">
                            @foreach($performance_levels as $performance_level)
                                <option value="{{ $performance_level->id }}" {{ $performance_level->id == $video->performance_level->id
                                    ? 'selected' : '' }}>{{ $performance_level->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                            <p class="text-center text-danger f-w-500">SCORE</p>
                            <div class="col-xs-12 col-md-4 m-b-15">
                                <div class="row">
                                    <div class="col-xs-12 col-md-8">
                                        <span>Performance Quality</span>
                                        <span class="rateit-value"></span>
                                        <div class="rateit pull-right bigstars" data-rateit-span-id="1" data-rateit-name="pq-rating" data-rateit-min="0" data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0" data-rateit-value="" ></div>
                                        <input type="hidden" name="pq-rating" value="" /> 
                                        <fieldset>
                                            <small class="m-b-0">Comment</small>
                                            <input class="form-control comment" type="text" name="performance-quality" autocomplete="off" value="" />
                                        </fieldset>
                                    </div>
                                    <div class="col-xs-12 col-md-4">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-4 m-b-15">
                                <div class="row">
                                    <div class="col-xs-12 col-md-8 col-md-offset-2">
                                        <span>Technical Ability</span>
                                        <span class="rateit-value"></span>
                                        <div class="rateit pull-right bigstars" data-rateit-span-id="1" data-rateit-name="ta-rating" data-rateit-min="0" data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0" data-rateit-value="" ></div>
                                        <input type="hidden" name="ta-rating" value="" />
                                        <fieldset>
                                            <small class="m-b-0">Comment</small>
                                            <input class="form-control comment" type="text" name="technical-ability" autocomplete="off" value="{{old('footwork-comment')}}" />
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-4 m-b-15">
                                <div class="row">
                                    <div class="col-xs-12 col-md-4">
                                    </div>
                                    <div class="col-xs-12 col-md-8">
                                        <span>Energy and Style</span>
                                        <span class="rateit-value"></span>
                                        <div class="rateit pull-right bigstars" data-rateit-span-id="1" data-rateit-name="es-rating" data-rateit-min="0" data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0" data-rateit-value="" ></div>
                                        <input type="hidden" name="es-rating" value="" />
                                        <fieldset>
                                            <small class="m-b-0">Comment</small>
                                            <input class="form-control comment" type="text" name="energy-and-style" autocomplete="off" value="" />
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-4 m-b-15">
                                <div class="row">
                                    <div class="col-xs-12 col-md-8">
                                        <span>Storytelling</span>
                                        <span class="rateit-value"></span>
                                        <div class="rateit pull-right bigstars" data-rateit-span-id="1" data-rateit-name="storytelling-rating" data-rateit-min="0" data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0" data-rateit-value="" ></div>
                                        <input type="hidden" name="storytelling-rating" value="" />
                                        <fieldset>
                                            <small class="m-b-0">Comment</small>
                                            <input class="form-control comment" type="text" name="storytelling" autocomplete="off" value="" />
                                        </fieldset>
                                    </div>
                                    <div class="col-xs-12 col-md-4">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-4 m-b-15">
                                <div class="row">
                                    <div class="col-xs-12 col-md-8 col-md-offset-2">
                                        <span>Look and Appearance</span>
                                        <span class="rateit-value"></span>
                                        <div class="rateit pull-right bigstars" data-rateit-span-id="1" data-rateit-name="la-rating" data-rateit-min="0" data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0" data-rateit-value="" ></div>
                                        <input type="hidden" name="la-rating" value="" />
                                        
                                        <fieldset>
                                            <small class="m-b-0">Comment</small>
                                            <input class="form-control comment" type="text" name="look-and-appearance" autocomplete="off" value="" />
                                        </fieldset>
                                    </div>
                                </div>
                            </div>


                           
                            <div class="col-xs-12"><hr></div>
                        </div>

                </div>

                <hr>@if($video->questions->count())
                    <span class="f-w-400 f-s-18">Answer for 3 {{ $video->user->first_name }} questions:</span>
                    <div class="row">
                        @foreach($video->questions as $question)
                            <div class="col-xs-4">
                                <div class="form-group" style="overflow-x: hidden">
                                    <label for="answer{{ $question->id }}">{{ $question->question }}</label>
                                    <textarea name="answer[{{ $question->id }}]" id="answer{{ $question->id }}" rows="4" class="form-control answer" data-question="{{ $question->id }}" placeholder="Your answer"></textarea>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                <hr>
                <div class="row">
                    <div class="col-xs-12">
                        <fieldset class="form-group m-b-20">
                            <label>Notes</label>
                            <textarea name="review_message" class="form-control" id="review_message" rows="5"></textarea>
                        </fieldset>
                    </div>
                    <!-- <div class="col-xs-12">
                        <fieldset class="form-group m-b-20">
                            <label class="m-b-0">Additional Tips</label>
                            <small style="display: block">Resources (websites, books, videos), practice tips etc </small>
                            <textarea name="additional_tips" class="form-control" id="additional_tips" rows="4"></textarea>
                        </fieldset>
                    </div> -->
                    <input type="hidden" value="0" id="review_id">
                </div>

            </div>

            <div class="row">
                <div style="text-align: center;" class="col-xs-12">
                    <a class="btn btn-warning m-r-10 m-b-10" href="{{url('review/create/' . $video->id)}}">
                        <i class="btn-icon fa fa-arrow-left"></i>Back To Video
                    </a>
                    <button type="submit" class="btn btn-success m-r-10 m-b-10" >
                        <i class="btn-icon fa fa-check"></i>Save Review
                    </button>
                    
                </div>
            </div>
        </form>

    </div>
@endsection
@section('js')
    <script type="text/javascript" src="/assets/js/sweetalert/sweet-alert.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".rateit").bind('rated', function (event, value) {
            var rate = $(this);
            /*insert star rating value*/
            rate.closest('.row').find('.rateit-value').html(value.toFixed(1));
            if(value === null){
                value = 0;
            }
            rate.attr("data-rateit-value", value);
            var name = rate.attr("data-rateit-name");
            //ratings[name] = value;
            // console.log(value);
            // console.log(name);
            $('input[name="'+name+'"]').val(value);
//            $('#rate_' + rate.attr("data-rateit-span-id")).text(value);
        });
        })

    </script>
    <script src="/assets/js/rateit/jquery.rateit.js"></script>
    <script type="text/javascript" src="/assets/js/toastr/toastr.min.js"></script>
@endsection
