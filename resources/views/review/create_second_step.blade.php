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
        <div style="display: none;">
            <span id="video_id" data-video-id="{{$video->id}}">{{$video->name}}</span>
            <span id="user_id" data-user-id="{{$video->user_id}}">{{$video->user_first_name}}  {{$video->user_last_name}}</span>
        </div>
        <div id="rating_blk">
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label class="f-w-400 f-s-18" for="performance_level_placement">Performance level placement</label>
                    <select id="performance_level_placement" autocomplete="off" class="form-control c-select">
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
                    <p class="text-center text-danger f-w-500">TECHNIQUE SCORE</p>
                    <div class="col-xs-12 col-md-4 m-b-15">
                        <div class="row">
                            <div class="col-xs-12 col-md-8">
                                <span>Timing</span>
                                <span class="rateit-value"></span>
                                <div class="rateit pull-right" data-rateit-span-id="1" data-rateit-name="timing" data-rateit-min="0"
                                     data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0"
                                     data-rateit-value="{{$video->review ? $video->review->timing : ''}}"></div>
                                <fieldset>
                                    <small class="m-b-0">Comment</small>
                                    <input class="form-control comment" type="text" name="timing_comment" autocomplete="off"
                                           @if(old('timing_comment'))
                                           value="{{ old('timing_comment') }}"
                                                   @else
                                           value="{{ $video->review ? $video->review->timing_comment : '' }}"
                                                   @endif
                                            >
                                </fieldset>
                            </div>
                            <div class="col-xs-12 col-md-4">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4 m-b-15">
                        <div class="row">
                            <div class="col-xs-12 col-md-8 col-md-offset-2">
                                <span>Footwork</span>
                                <span class="rateit-value"></span>
                                <div class="rateit pull-right" data-rateit-span-id="2" data-rateit-name="footwork" data-rateit-min="0"
                                     data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0"
                                     data-rateit-value="{{$video->review ? $video->review->footwork : ''}}"></div>
                                <fieldset>
                                    <small class="m-b-0">Comment</small>
                                    <input class="form-control comment" type="text" name="footwork_comment" autocomplete="off"
                                           @if(old('footwork_comment'))
                                           value="{{ old('footwork_comment') }}"
                                           @else
                                           value="{{ $video->review ? $video->review->footwork_comment : '' }}"
                                            @endif
                                    >
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4 m-b-15">
                        <div class="row">
                            <div class="col-xs-12 col-md-4">
                            </div>
                            <div class="col-xs-12 col-md-8">
                                <span>Alignment</span>
                                <span class="rateit-value"></span>
                                <div class="rateit pull-right" data-rateit-span-id="3" data-rateit-name="alingment" data-rateit-min="0"
                                     data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0"
                                     data-rateit-value="{{$video->review ? $video->review->alingment : ''}}"></div>
                                <fieldset>
                                    <small class="m-b-0">Comment</small>
                                    <input class="form-control comment" type="text" name="alingment_comment" autocomplete="off"
                                           @if(old('alingment_comment'))
                                           value="{{ old('alingment_comment') }}"
                                           @else
                                           value="{{ $video->review ? $video->review->alingment_comment : '' }}"
                                            @endif
                                    >
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4 m-b-15">
                        <div class="row">
                            <div class="col-xs-12 col-md-8">
                                <span>Balance</span>
                                <span class="rateit-value"></span>
                                <div class="rateit pull-right" data-rateit-span-id="4" data-rateit-name="balance" data-rateit-min="0"
                                     data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0"
                                     data-rateit-value="{{$video->review ? $video->review->balance : ''}}"></div>
                                <fieldset>
                                    <small class="m-b-0">Comment</small>
                                    <input class="form-control comment" type="text" name="balance_comment" autocomplete="off"
                                           @if(old('balance_comment'))
                                           value="{{ old('balance_comment') }}"
                                           @else
                                           value="{{ $video->review ? $video->review->balance_comment : '' }}"
                                            @endif
                                    >
                                </fieldset>
                            </div>
                            <div class="col-xs-12 col-md-4">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4 m-b-15">
                        <div class="row">
                            <div class="col-xs-12 col-md-8 col-md-offset-2">
                                <span>Focus</span>
                                <span class="rateit-value"></span>
                                <div class="rateit pull-right" data-rateit-span-id="5" data-rateit-name="focus" data-rateit-min="0"
                                     data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0"
                                     data-rateit-value="{{$video->review ? $video->review->focus : ''}}"></div>
                                <fieldset>
                                    <small class="m-b-0">Comment</small>
                                    <input class="form-control comment" type="text" name="focus_comment" autocomplete="off"
                                           @if(old('focus_comment'))
                                           value="{{ old('focus_comment') }}"
                                           @else
                                           value="{{ $video->review ? $video->review->focus_comment : '' }}"
                                            @endif
                                    >
                                </fieldset>
                            </div>
                        </div>
                    </div>


                    <div class="col-xs-12 col-md-4 m-b-15">
                        <div class="row">
                            <div class="col-xs-12 col-md-4">
                            </div>
                            <div class="col-xs-12 col-md-8">
                                <span>Precision</span>
                                <span class="rateit-value"></span>
                                <div class="rateit pull-right"  data-rateit-span-id="6" data-rateit-name="precision" data-rateit-min="0"
                                     data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0"
                                     data-rateit-value="{{$video->review ? $video->review->precision : ''}}"></div>
                                <fieldset>
                                    <small class="m-b-0">Comment</small>
                                    <input class="form-control comment" type="text" name="precision_comment" autocomplete="off"
                                           @if(old('precision_comment'))
                                           value="{{ old('precision_comment') }}"
                                           @else
                                           value="{{ $video->review ? $video->review->precision_comment : '' }}"
                                            @endif
                                    >
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12"><hr></div>
                </div>

                <div class="col-md-12">
                    <p class="text-center text-danger f-w-500">EXPRESSION</p>
                    <div class="col-xs-12 col-md-3 m-b-15">
                        <div class="row">
                            <div class="col-xs-12 col-md-11">
                                <span>Energy</span>
                                <span class="rateit-value"></span>
                                <div class="rateit pull-right" data-rateit-span-id="7" data-rateit-name="energy" data-rateit-min="0"
                                     data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0"
                                     data-rateit-value="{{$video->review ? $video->review->energy : ''}}"></div>
                                <fieldset>
                                    <small class="m-b-0">Comment</small>
                                    <input class="form-control comment" type="text" name="energy_comment" autocomplete="off"
                                           @if(old('energy_comment'))
                                           value="{{ old('energy_comment') }}"
                                           @else
                                           value="{{ $video->review ? $video->review->energy_comment : '' }}"
                                            @endif
                                    >
                                </fieldset>
                            </div>
                            <div class="col-xs-0 col-md-1"></div>
                        </div>

                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="row">
                            <div class="col-xs-12 col-md-6 m-b-15">
                                <div class="row">
                                    <div class="col-xs-12 col-md-11" style="margin-left: 3%;">
                                        <span>Style</span>
                                        <span class="rateit-value"></span>
                                        <div class="rateit pull-right" data-rateit-span-id="8" data-rateit-name="style" data-rateit-min="0"
                                             data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0"
                                             data-rateit-value="{{$video->review ? $video->review->style : ''}}"></div>
                                        <fieldset>
                                            <small class="m-b-0">Comment</small>
                                            <input class="form-control comment" type="text" name="style_comment" autocomplete="off"
                                                   @if(old('style_comment'))
                                                   value="{{ old('style_comment') }}"
                                                   @else
                                                   value="{{ $video->review ? $video->review->style_comment : '' }}"
                                                    @endif
                                            >
                                        </fieldset>
                                    </div>
                                    <div class="col-xs-0 col-md-1"></div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6 m-b-15">
                                <div class="row">
                                    <div class="col-xs-12 col-md-11 col-md-offset-1" style="margin-right: 3%;">
                                        <span>Creativity</span>
                                        <span class="rateit-value"></span>
                                        <div class="rateit pull-right" data-rateit-span-id="9" data-rateit-name="creativity" data-rateit-min="0"
                                             data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0"
                                             data-rateit-value="{{$video->review ? $video->review->creativity : ''}}"></div>
                                        <fieldset>
                                            <small class="m-b-0">Comment</small>
                                            <input class="form-control comment" type="text" name="creativity_comment" autocomplete="off"
                                                   @if(old('creativity_comment'))
                                                   value="{{ old('creativity_comment') }}"
                                                   @else
                                                   value="{{ $video->review ? $video->review->creativity_comment : '' }}"
                                                    @endif
                                            >
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-3 m-b-15">
                        <div class="row">

                            <div class="col-xs-12 col-md-11 col-md-offset-1">
                                <span>Interpretation</span>
                                <span class="rateit-value"></span>
                                <div class="rateit pull-right" data-rateit-span-id="10" data-rateit-name="interpretation" data-rateit-min="0"
                                     data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0"
                                     data-rateit-value="{{$video->review ? $video->review->interpretation : ''}}"></div>
                                <fieldset>
                                    <small class="m-b-0">Comment</small>
                                    <input class="form-control comment" type="text" name="interpretation_comment" autocomplete="off"
                                           @if(old('interpretation_comment'))
                                           value="{{ old('interpretation_comment') }}"
                                           @else
                                           value="{{ $video->review ? $video->review->interpretation_comment : '' }}"
                                            @endif
                                    >
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12"><hr></div>
                </div>

                <div class="col-md-12">
                    <p class="text-center text-danger f-w-500">CHOREOGRAPHY</p>
                    <div class="col-xs-12 col-md-6 m-b-15">
                        <div class="row">
                            <div class="col-xs-12 col-md-6 col-md-offset-4">
                                <div class="row">
                                    <div class="col-xs-12 col-md-11 col-md-offset-1">
                                        <span>Formation</span>
                                        <span class="rateit-value"></span>
                                        <div class="rateit pull-right" data-rateit-span-id="11" data-rateit-name="formation" data-rateit-min="0"
                                             data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0"
                                             data-rateit-value="{{$video->review ? $video->review->formation : ''}}"></div>
                                        <fieldset>
                                            <small class="m-b-0">Comment</small>
                                            <input class="form-control comment" type="text" name="formation_comment" autocomplete="off"
                                                   @if(old('formation_comment'))
                                                   value="{{ old('formation_comment') }}"
                                                   @else
                                                   value="{{ $video->review ? $video->review->formation_comment : '' }}"
                                                    @endif
                                            >
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 m-b-15">
                        <div class="row">
                            <div class="col-xs-0 col-md-2"></div>
                            <div class="col-xs-12 col-md-6">
                                <div class="row">
                                    <div class="col-xs-12 col-md-11">
                                        <span>Artistry</span>
                                        <span class="rateit-value"></span>
                                        <div class="rateit pull-right" data-rateit-span-id="12" data-rateit-name="artisty" data-rateit-min="0"
                                             data-rateit-max="5" data-rateit-step="1" data-rateit-resetable="0"
                                             data-rateit-value="{{$video->review ? $video->review->artisty : ''}}"></div>
                                        <fieldset>
                                            <small class="m-b-0">Comment</small>
                                            <input class="form-control comment" type="text" name="artisty_comment" autocomplete="off"
                                                   @if(old('artisty_comment'))
                                                   value="{{ old('artisty_comment') }}"
                                                   @else
                                                   value="{{ $video->review ? $video->review->artisty_comment : '' }}"
                                                    @endif
                                            >
                                        </fieldset>
                                    </div>
                                    <div class="col-xs-12 col-md-1">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-0 col-md-4"></div>
                        </div>
                    </div>

                </div>
            </div>

            <hr>@if($video->questions->count())
                <span class="f-w-400 f-s-18">Answer for 3 {{ $video->user->first_name }} questions:</span>
                <div class="row">
                    @foreach($video->questions as $question)
                        <div class="col-xs-4">
                            <div class="form-group" style="overflow-x: hidden">
                                <label for="answer{{ $question->id }}">{{ $question->question }}</label>
                                <textarea name="answer[{{ $question->id }}]" id="answer{{ $question->id }}" rows="4"
                                          class="form-control answer" data-question="{{ $question->id }}"
                                          placeholder="Your answer"></textarea>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            <hr>
            <div class="row">
                <div class="col-xs-12">
                    <fieldset class="form-group m-b-20">
                        <label>Summary of Performance Video</label>
                        <textarea class="form-control" id="review_message" rows="5"></textarea>
                    </fieldset>
                </div>
                <div class="col-xs-12">
                    <fieldset class="form-group m-b-20">
                        <label class="m-b-0">Additional Tips</label>
                        <small style="display: block">Resources (websites, books, videos), practice tips etc </small>
                        <textarea class="form-control" id="additional_tips" rows="4"></textarea>
                    </fieldset>
                </div>
                <input type="hidden" value="0" id="review_id">
            </div>

        </div>

        <div class="row">
            <div style="text-align: center;" class="col-xs-12">
                <a class="btn btn-warning m-r-10 m-b-10" href="{{url('review/create/' . $video->id)}}">
                    <i class="btn-icon fa fa-arrow-left"></i>Back To Video
                </a>
                <button class="btn btn-success m-r-10 m-b-10" id="save_review_rating_btn">
                    <i class="btn-icon fa fa-check"></i>Save Review
                </button>
            </div>
        </div>


    </div>
@endsection
@section('js')
    <script type="text/javascript" src="/assets/js/sweetalert/sweet-alert.min.js"></script>
    <script type="text/javascript">
        var ratings = {
            artisty: '{{($video->review && !empty($video->review->artisty)) ? $video->review->artisty : ''}}',
            formation: '{{($video->review && !empty($video->review->formation)) ? $video->review->formation : ''}}',
            interpretation: '{{($video->review && !empty($video->review->interpretation)) ? $video->review->interpretation : ''}}',
            creativity: '{{($video->review && !empty($video->review->creativity)) ? $video->review->creativity : ''}}',
            style: '{{($video->review && !empty($video->review->style)) ? $video->review->style : ''}}',
            energy: '{{($video->review && !empty($video->review->energy)) ? $video->review->energy : ''}}',
            precision: '{{($video->review && !empty($video->review->precision)) ? $video->review->precision : ''}}',
            timing: '{{($video->review && !empty($video->review->timing)) ? $video->review->timing : ''}}',
            footwork: '{{($video->review && !empty($video->review->footwork)) ? $video->review->footwork : ''}}',
            alingment: '{{($video->review && !empty($video->review->alingment)) ? $video->review->alingment : ''}}',
            balance: '{{($video->review && !empty($video->review->balance)) ? $video->review->balance : ''}}',
            focus: '{{($video->review && !empty($video->review->focus)) ? $video->review->focus : ''}}',
        };
        $(".rateit").bind('rated', function (event, value) {
            var rate = $(this);
            /*insert star rating value*/
            rate.closest('.row').find('.rateit-value').html(value.toFixed(1));
            if(value === null){
                value = '';
            }
            rate.attr("data-rateit-value", value);
            var name = rate.attr("data-rateit-name");
            ratings[name] = value;
//            $('#rate_' + rate.attr("data-rateit-span-id")).text(value);
        });


        function saveRewEvent() {

            $("#save_review_rating_btn").click(function(){
                $("#save_review_rating_btn").hide();
                $(".comment").each(function() {
                    var that = $(this);
                    ratings[that.attr("name")] = that.val();
                });
                ratings.message= $("#review_message").val();
                ratings.additional_tips= $("#additional_tips").val();
                var review_id = $("#review_id").val();
                var answers = {};
                $(".answer").each(function() {
                    answers[$(this).attr("data-question")] = $(this).val();
                });
                var video_id = $("#video_id").attr('data-video-id');
                var performance_level_placement = $("#performance_level_placement").val();
                var owner_id = document.getElementById('user_id').getAttribute('data-user-id');

                $.ajax({
                    type: "POST",
                    url: "/review/save-ratings",
                    data: {ratings : ratings, review_id : review_id, answers: answers, video_id: video_id,
                        performance_level_placement: performance_level_placement, owner_id: owner_id},
                    beforeSend: function () {

                        $("#save_review_rating_btn").off('click');

                    },
                    success: function(data){
                        if(data.status == 'val_error'){ //field validation
                           $("#save_review_rating_btn").show();
                            $.each(data.errors, function(i, val){
                                toastr.options = {
                                    "closeButton": true,
                                    "positionClass": "toast-bottom-left",
                                    "onclick": null,
                                    "showDuration": "1000",
                                    "hideDuration": "1000",
                                    "timeOut": "5000",
                                    "extendedTimeOut": "1000",
                                    "showEasing": "swing",
                                    "hideEasing": "linear",
                                    "showMethod": "fadeIn",
                                    "hideMethod": "fadeOut"
                                };
                                toastr.error(val, "Error");
                            });
                        } else {
                            if( data.status == "success"){
                                swal("Success!", "Review successfully added", "success");
                                setTimeout(window.location.href = '/review/show-my/' + video_id, 2000);
                            }
                        }
                        saveRewEvent();
                    },
                    error: function () {
                        swal("Error!", "Oops something happens.", "error");
                        location.reload();
                        saveRewEvent();
                    },
                    dataType: "json"
                });
            });

        }; saveRewEvent();


    </script>
    <script src="/assets/js/rateit/jquery.rateit.js"></script>
    <script type="text/javascript" src="/assets/js/toastr/toastr.min.js"></script>
@endsection
