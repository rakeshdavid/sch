@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="/assets/css/select2.min.css">
    <style type="text/css">
        .unblured {
            -webkit-filter: blur(0);
            filter: blur(0);
            transition: .3s ease-in-out;
        }
        .blured {
            -webkit-filter: blur(3px) brightness(0.4);
            filter: blur(1px) brightness(0.4);
            transition: .3s ease-in-out;
        }
        .coach-avatar .select-coach {
            -webkit-filter: blur(0);
            filter: blur(0);
            transform:  translate(-50%, 30%);
            position:absolute;
            top: 30%;
            left: 50%;
            display:none;
        }

        .coach-avatar:hover .select-coach {
            -webkit-filter: blur(0);
            filter: blur(0);
            display: block;
        }

    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <h1 class="m-b-40"> Browse coaches </h1>
            <form class="form-horizontal" action="{{ route('customerActions-searchCoach') }}" method="post">
                {!! csrf_field() !!}

                <fieldset class="form-group m-b-30">

                    {{--crunch--}}
                    {{--<div class="col-xs-5">
                        <h4 class="m-b-15">Select Type</h4>
                        <select name="type" id="activity_type" class="form-control" autocomplete="off">
                            <option></option>
                            @foreach($activity_types as $activity_type)
                                <option value="{{$activity_type->id}}" {{$type_id == $activity_type->id ? 'selected' : ''}}>
                                    {{$activity_type->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xs-1"></div>--}}

                    <div class="col-xs-6 col-md-6 col-lg-6 col-sm-12">
                        <h4 class="m-b-15">Select Level:</h4>
                        <div id="levels_block" class="c-inputs-stacked">
                            @foreach($performance_levels as $performance_level)
                                <label class="c-input c-checkbox">
                                    <input type="checkbox" value="{{$performance_level->id}}" autocomplete="off"
                                        {{ $levels_id ? (in_array($performance_level->id, $levels_id) ? 'checked' : '') : ''}}
                                        name="levels[]">
                                    <span class="c-indicator c-indicator-warning"></span>
                                    <span class="c-input-text">{{$performance_level->name}}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-xs-6" style="{!! count($genres) == 0 ? 'display: none' : '' !!}" id="genres_wrapper">
                        <h4>Select Genre/s:</h4>
                        <div class="c-inputs-stacked" id="genres_block">
                            @foreach($genres as $genre)
                                <label class="c-input c-checkbox">
                                    <input type="checkbox" name="genres[]" value="{{$genre->id}}" autocomplete="off"
                                        {{ in_array($genre->id, $genres_id ? $genres_id : []) ? 'checked' : ''}}>
                                    <span class="c-indicator c-indicator-warning"></span>
                                    <span class="c-input-text"> {{$genre->name}} </span>
                                </label>
                            @endforeach
                        </div>

                    </div>
                </fieldset>

                <div class="col-xs-12">
                    <button class="btn btn-warning" type="submit">
                        <i class="btn-icon fa fa-search"></i>
                        Filter
                    </button>
                    @if($filters)
                        <a href="{{url()->current()}}" class="btn btn-danger m-l-5">
                            <i class="btn-icon fa fa-remove"></i>
                            Clear filters
                        </a>
                    @endif
                </div>
            </form>

        </div>
    </div>
    <hr>
    <div class="row">
        <div class="container">
            @if(count($coaches) == 0)
                <div class="col-xs-12 col-md-6 col-md-offset-3 m-t-10">
                    <h3 class="text-center">Sorry, no results found</h3>
                </div>
            @endif
            @foreach($coaches as $coach)
                <div class="col-xs-12 col-md-4">
                    <div class="row">
                        <div class="col-md-offset-1 col-md-10">
                            <div class="coach-avatar">
                                <div class="coach-photo-wrapper">
                                    <img src="{{ str_replace('picture?type=normal', 'picture?type=large', '../../'.$coach->avatar) }}"
                                         class="m-b-5 center-block" style="width: 250px;height: 320px">
                                </div>
                                <a class="btn btn-warning btn-outline btn-rounded select-coach"
                                   href="{{ url('/profile/'.$coach->id) }}">
                                    Coach profile
                                </a>
                            </div>


                            <div style="margin-right: 13px; margin-left: 13px">
                            <a class="f-s-18 text-danger" href="{{ url('/profile/'.$coach->id) }}" target="_blank"
                               style="color: #D9534F !important;">
                                {{ $coach->first_name }} {{$coach->last_name}}
                            </a>
                            <p class="f-s-18">${{$coach->price_summary ? $coach->price_summary : env('DEFAULT_SUMMARY_PRICE')}}
                                <span style="float: right">${{$coach->price_detailed ? $coach->price_detailed : env('DEFAULT_SUMMARY_DETAILED')}}</span></p>
                            </div>


                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
    @php
        $genres_id_str = !is_null($genres_id) ? implode(',', $genres_id) : null;
        $levels_id_str = !is_null($levels_id) ? implode(',', $levels_id) : null;
    @endphp
    <nav class="container">
        @include('pagination.default', ['paginator' => $coaches->appends(['type' => $type_id, 'genres' => $genres_id_str,
            'levels' => $levels_id_str])])
    </nav>
@endsection

@section('js')
    <script src="/assets/js/select2.min.js"></script>
    <script type="text/javascript">
        function genresArrayToTemplate(data) {
            console.log(data);
            if(!data) return '';
            var template = '';
            for(var i=0; i < data.length; i++) {
                template += '<label class="c-input c-checkbox">\
                             <input type="checkbox" name="genres[]" value="' + data[i].id + '"\
                             autocomplete="off">\
                             <span class="c-indicator c-indicator-warning"></span>\
                             <span class="c-input-text">' + data[i].name + '</span>\
                             </label>';
            }
            return template;
        }

        $(function(){

            $(".coach-photo-wrapper").on("mouseenter", function () {
                $(this).removeClass("unblured").addClass("blured");
            }).on("mouseleave", function () {
                var that = $(this);
                setTimeout(function() {
                    var buttons = that.nextAll("a");
                    if(!that.next(".select-coach").hasClass('hovered') &&
                            !that.next(".select-coach").next(".coach-profile").hasClass('hovered')) {
                        that.removeClass("blured").addClass("unblured");
                    }
                }, 300);
            });

            $(".select-coach").on("mouseenter", function() {
                $(this).addClass("hovered");
            }).on("mouseleave", function() {
                $(this).removeClass("hovered");
            });
            $(".coach-profile").on("mouseenter", function() {
                $(this).addClass("hovered");
            }).on("mouseleave", function() {
                $(this).removeClass("hovered");
            });


            /*$("#activity_type").select2({
                placeholder: "List of types..."
            }).on("change", function(e) {
                $.ajax({
                    type: "POST",
                    url: "{{url('/ajax/get-genres')}}",
                    data: {"activity": e.currentTarget.value, "_token": "{{csrf_token()}}"},
                    dataType: "json",
                    success: function(result) {
                        if(result.length > 0) {
                            $("#genres_wrapper").show();
                            $("#genres_block").html(genresArrayToTemplate(result));
                        } else {
                            $("#genres_wrapper").hide();
                            $("#genres_block").html("");
                        }
                    }
                });
            });*/


            @if(!$filters)
            $(document).ready(function () {
                $.ajax({
                    type: "POST",
                    url: "{{url('/ajax/get-genres')}}",
                    data: {"activity": 1, "_token": "{{csrf_token()}}"},
                    dataType: "json",
                    success: function(result) {
                        if(result.length > 0) {
                            $("#genres_wrapper").show();
                            $("#genres_block").html(genresArrayToTemplate(result));
                        } else {
                            $("#genres_wrapper").hide();
                            $("#genres_block").html("");
                        }
                    }
                });
            });
            @endif


        });
    </script>
@endsection
