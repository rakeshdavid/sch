@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="/assets/marino/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css"/>
    <style>
        #overlay {
            background: #262932;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            opacity: .2;
            display: none;
        }
    </style>
@endsection

@section('content')

    <div class="container">
        <div class="row">

            <h1 class="m-b-40">Make your request to the coach</h1>

            <div class="col-md-12">
                <h3 class="f-s-22 text-danger f-w-400 m-b-15">
                    <span class="text-uppercase f-s-24">STEP 1:</span> Add Performance Video
                </h3>
                {{--@if(count($errors) > 0)
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif--}}

                @if(session('status'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{session('status')}}
                    </div>
                @endif
                <form action="{{ url('video') }}" method="POST" id="submit-request" enctype="multipart/form-data" class="form-horizontal">

                    <div class="form-group row">
                        <label class="col-sm-2 form-control-label" for="name">Name<span class="req-star"> *</span></label>
                        <div class="col-sm-10">
                            <input {!!(count($errors->get('name'))>0)?'style="border-color:#db534f;"':''!!} type="text" class="form-control"
                               name="name" placeholder="Name" maxlength="254"
                               value="{{old('name', '')}}" required>
                                @if(count($errors->get('name'))>0)
                                    @foreach($errors->get('name') as $err)
                                        <small class="text-danger">{{$err}}</small>
                                    @endforeach
                                @endif
                        </div>

                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 form-control-label" for="description">Description<span class="req-star"> *</span></label>
                        <div class="col-sm-10">
                            <textarea {!!(count($errors->get('description'))>0)?'style="border-color:#db534f;"':''!!}  class="form-control" rows="3" name="description" placeholder="Description" required minlength="6">{{old('description', '')}}</textarea>
                            @if(count($errors->get('description'))>0)
                                @foreach($errors->get('description') as $err)
                                    <small class="text-danger">{{$err}}</small>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    {{--<div class="form-group row">--}}
                        {{--<label class="col-sm-2 form-control-label" for="url">Youtube URL<span class="req-star"> *</span></label>--}}
                        {{--<div class="col-sm-10">--}}
                            {{--<input {!!(count($errors->get('url'))>0)?'style="border-color:#db534f;"':''!!}--}}
                               {{--type="text" class="form-control" name="url" placeholder="Youtube URL" maxlength="254"--}}
                               {{--value="{{old('url', '')}}" data-toggle="tooltip" data-placement="top" title=""--}}
                               {{--data-original-title="Paste video Url from Youtube or upload video">--}}
                                {{--@if(count($errors->get('url'))>0)--}}
                                    {{--@foreach($errors->get('url') as $err)--}}
                                        {{--<small class="text-danger">{{$err}}</small>--}}
                                    {{--@endforeach--}}
                                {{--@endif--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="form-group row">--}}
                        {{--<label class="col-sm-2 form-control-label" for="description">or<span class="req-star"></span></label>--}}
                        {{--<div class="col-sm-10">--}}

                        {{--</div>--}}
                    {{--</div>--}}

                    <div class="form-group row">
                        <label class="col-sm-2 form-control-label" for="exampleInputFile">Upload file</label>
                        <div class="col-sm-10">
                            <div id="upload_container">
                                <button id="pick_file" type="button" class="btn btn-primary m-r-5">
                                    <i class="btn-icon fa fa-folder-open"></i>Select file
                                </button>
                                {{--<button id="upload_file" type="button" class="btn btn-success m-r-5">--}}
                                    {{--<i class="btn-icon fa fa-upload"></i>Upload file--}}
                                {{--</button>--}}
                                <button style="display: none;" id="stop_uploading" type="button" class="btn btn-danger m-r-5">
                                    <i class="btn-icon fa fa-stop"></i>Stop uploading
                                </button>
                            </div>
                            <div id="file_list" class="m-t-10">Your browser doesn't have HTML5 support.</div>
                            <div class="row" id="progress" style="display: none">
                                <div class="col-xs-12 col-lg-6">
                                    <progress class="progress-xs progress progress-danger" value="0" max="100"
                                              id="uploading_progress"></progress>
                                </div>
                            </div>
                            @if(count($errors->get('file_name'))>0)
                                @foreach($errors->get('file_name') as $err)
                                    <small class="text-danger">{{$err}}</small>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-10">
                            <p>
                                The maximum video size is 300 mb. Valid formats avi, mpeg4, wmv, mp4, mov.
                            </p>
                        </div>
                    </div>

                    @if($detailed_package)
                        <div class="row">
                            <div class="col-md-12"><hr></div>
                            <div class="col-xs-12">
                                <h3 class="f-s-22 text-danger f-w-400 m-b-15">
                                    <span class="text-uppercase f-s-24">STEP 2:</span> Ask {{ $coach->first_name }} 3 Specific Questions
                                </h3>
                                @foreach($questions_labels as $number => $question_label)
                                    <div class="form-group col-xs-12 col-md-4">
                                        <fieldset>
                                            <label for="{{ $question_label }}-q">{{ $question_label }} Question</label>
                                            <input type="text" class="form-control" name="question[{{ $number }}]" maxlength="254"
                                                   placeholder="{{ $question_label }} Question" autocomplete="off"
                                                   id="{{ $question_label }}-q">
                                        </fieldset>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <hr>
                    <h3 class="f-s-22 text-danger f-w-400 m-b-15">
                        <span class="text-uppercase f-s-24">STEP {{ $detailed_package ? '3' : '2' }}:</span> Complete Profile Questions
                    </h3>
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <fieldset class="form-group">
                                <label>Name</label>
                                <div class="row">
                                    <div class="col-xs-12 col-md-6">
                                        <input type="text" data-name="first_name" class="form-control editable" maxlength="254"
                                               placeholder="First Name" value="{{ old('first_name') ? old('first_name') :
                                                $user->first_name }}" autocomplete="off">
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <input type="text" data-name="last_name" class="form-control editable" maxlength="254"
                                               placeholder="Last Name" value="{{ old('last_name') ? old('last_name') :
                                                $user->last_name }}" autocomplete="off">
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="form-group">
                                <label>Contact Email</label>
                                    <input type="text" data-name="contact_email" class="form-control editable" maxlength="254"
                                           placeholder="Contact Email" value="{{ old('contact_email') ? old('contact_email') :
                                                $user->contact_email }}" autocomplete="off">
                            </fieldset>
                            <fieldset class="form-group">
                                <label>Location</label>
                                    <input type="text" data-name="location" class="form-control editable" maxlength="254"
                                           placeholder="Location" value="{{ old('location') ? old('location') :
                                                $user->location }}" autocomplete="off">
                            </fieldset>
                           {{-- <fieldset class="form-group">
                                <label>Birthday</label>
                                    <input type="text" data-name="birthday" class="form-control editable" maxlength="254"
                                           placeholder="Birthday" value="{{ old('birthday') ? old('birthday') :
                                                $user->birthday }}" autocomplete="off" id="birthday">
                            </fieldset>--}}


                            {{--<fieldset class="form-group c-inputs-stacked">
                                <label>Genre/s<span class="req-star"> *</span></label><br>
                                --}}{{--@php--}}{{--
                                --}}{{--$current_type_id = 0;--}}{{--
                                --}}{{--@endphp--}}{{--
                                @foreach($coach->activity_genres as $genre)
                                    --}}{{--@if($genre->activity_type_id != $current_type_id)--}}{{--
                                    --}}{{--<label>{{($current_type_id != 0 ? '; ' : '') .--}}{{--
                                    --}}{{--$coach->activity_types->where('id', $genre->activity_type_id)->first()->name}}: </label>--}}{{--
                                    --}}{{--@php--}}{{--
                                    --}}{{--$current_type_id = $genre->activity_type_id;--}}{{--
                                    --}}{{--@endphp--}}{{--
                                    --}}{{--@endif--}}{{--
                                    <label class="c-input c-checkbox">
                                        <input type="checkbox" name="genres[]" value="{{ $genre->id }}" autocomplete="off">
                                        <span class="c-indicator c-indicator-warning"></span>
                                        {{ $genre->name }}
                                    </label>
                                @endforeach--}}

                                <div class="help-block with-errors" >
                                    @if(count($errors->get('genres'))>0)
                                        @foreach($errors->get('genres') as $err)
                                            <small class="text-danger">{{$err}}</small>
                                        @endforeach
                                    @endif
                                </div>

                            </fieldset>


                            <fieldset class="form-group c-inputs-stacked">
                                <label>Performance Level<span class="req-star"> *</span></label><br>
                                @foreach($performance_levels as $performance_level)
                                    <label class="c-input c-radio">
                                        <input type="radio" name="level" {{ old('level', 0) == $performance_level->id ? 'checked' : '' }}
                                        value="{{ $performance_level->id }}" autocomplete="off" required>
                                        <span class="c-indicator c-indicator-warning"></span>
                                        {{ $performance_level->name }}
                                    </label>
                                @endforeach
                                <div class="help-block with-errors" >
                                    @if(count($errors->get('level'))>0)
                                        @foreach($errors->get('level') as $err)
                                            <small class="text-danger">{{$err}}</small>
                                        @endforeach
                                    @endif
                                </div>
                            </fieldset>
                            <fieldset class="form-group">
                                <label>{{ ( isset($coach->activity_types->first()->name))? $coach->activity_types->first()->name:"" }} Experience<span class="req-star"> *</span></label>
                                <div class="row">
                                    <div class="col-xs-12 col-md-6">
                                        <input {!!(count($errors->get('activity_experience'))>0)?'style="border-color:#db534f;"':''!!}
                                               type="text" name="activity_experience" class="form-control" maxlength="3"
                                               placeholder="Years" value="{{ old('activity_experience') }}" autocomplete="off" required>
                                        @if(count($errors->get('activity_experience'))>0)
                                            @foreach($errors->get('activity_experience') as $err)
                                                <small class="text-danger">{{$err}}</small>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="form-group">
                                <label>Tell Us About Yourself...</label>
                                <textarea data-name="about" rows="5" class="form-control editable"
                                          autocomplete="off">{{ old('about') ? old('about') : strip_tags($user->about) }}</textarea>
                            </fieldset>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <fieldset class="form-group m-b-15">
                                <label {!!(count($errors->get('seeking_auditions'))>0)?'style="border-bottom: 1px solid #db534f;color:#db534f;"':''!!} >Are You Seeking Auditions?</label>
                                <div class="c-inputs-stacked">
                                    <label class="c-input c-radio">
                                        <input name="seeking_auditions" value="yes" autocomplete="off" type="radio"
                                                {{ old('seeking_auditions') == 'yes' ? 'checked' : '' }}>
                                        <span class="c-indicator c-indicator-warning"></span>
                                        Yes
                                    </label>
                                    <label class="c-input c-radio">
                                        <input name="seeking_auditions" value="maybe" autocomplete="off" type="radio"
                                                {{ old('seeking_auditions') == 'maybe' ? 'checked' : '' }}>
                                        <span class="c-indicator c-indicator-warning"></span>
                                        Maybe
                                    </label>
                                    {{--<label class="c-input c-radio">
                                        <input name="seeking_auditions" value="not_yet" autocomplete="off" type="radio"
                                                {{ old('seeking_auditions') == 'not_yet' ? 'checked' : '' }}>
                                        <span class="c-indicator c-indicator-warning"></span>
                                        Not Yet
                                    </label>--}}
                                    <label class="c-input c-radio">
                                        <input name="seeking_auditions" type="radio" autocomplete="off" value="no"
                                                {{ !$errors->all() ? 'checked' : (old('seeking_auditions') == 'no' ? 'checked' : '') }}>
                                        <span class="c-indicator c-indicator-warning"></span>
                                        No
                                    </label>
                                </div>
                                <div class="help-block with-errors" >
                                    @if(count($errors->get('seeking_auditions'))>0)
                                        @foreach($errors->get('seeking_auditions') as $err)
                                            <small class="text-danger">{{$err}}</small>
                                        @endforeach
                                    @endif
                                </div>
                            </fieldset>
                            <fieldset class="form-group">
                                <label>How Did You Hear About This Site?</label>
                                <div class="c-inputs-stacked m-b-15">
                                    <label class="c-input c-radio">
                                        <input name="site_target" value="internet_search" autocomplete="off" type="radio"
                                                {{old('site_target') == 'internet_search' ? 'checked' : $user->site_target
                                                == 'internet_search' ? 'checked' : !$errors->all() ? 'checked' : ''}}
                                                class="site_radio">
                                        <span class="c-indicator c-indicator-warning"></span>
                                        Internet Search
                                    </label>
                                    <label class="c-input c-radio">
                                        <input name="site_target" value="advertisement" autocomplete="off" type="radio"
                                                {{ old('site_target') == 'advertisement' ? 'checked' :
                                                $user->site_target == 'advertisement' ? 'checked' : '' }} class="site_radio">
                                        <span class="c-indicator c-indicator-warning"></span>
                                        Advertisement
                                    </label>
                                    <label class="c-input c-radio">
                                        <input name="site_target" value="friend" autocomplete="off" type="radio"
                                                {{ old('site_target') == 'friend' ? 'checked' :
                                                 $user->site_target == 'friend' ? 'checked' : ''}} class="site_radio">
                                        <span class="c-indicator c-indicator-warning"></span>
                                        Friend
                                    </label>
                                    <label class="c-input c-radio">
                                        <input id="other_sell" name="site_target" type="radio" autocomplete="off" value="other"
                                                {{ old('site_target') == 'other' ? 'checked' :
                                                 $user->site_target == 'other' ? 'checked' : ''}} class="site_radio">
                                        <span class="c-indicator c-indicator-warning"></span>
                                        Other
                                    </label>
                                </div>

                               <div class="row" id="other_site_spec" style="{{ empty($user->other_site_spec) ?
                                    (old('site_target') == 'other' ? '' : 'display: none;') : '' }}">
                                    <div class="col-xs-12 col-md-8">
                                        <label>If Other Please Specify</label>
                                        <input {!!(count($errors->get('other_site_spec'))>0)?'style="border-color:#db534f;"':''!!}
                                              name="other_site_spec" type="text" data-name="other_site_spec" autocomplete="off"
                                               value="{{ old('other_site_spec') ? old('other_site_spec') : $user->other_site_spec }}"
                                               maxlength="254" class="form-control editable">
                                        @if(count($errors->get('other_site_spec'))>0)
                                            @foreach($errors->get('other_site_spec') as $err)
                                                <small class="text-danger">{{$err}}</small>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                            </fieldset>
                        </div>
                    </div>

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="file_name" value="" id="file_name_ipt" autocomplete="off">
                    <input type="hidden" name="coach" value="{{ $coach->id }}" autocomplete="off">
                    <input type="hidden" name="package_type" value="{{ $detailed_package ? 'detailed' : 'summary' }}"
                           autocomplete="off">
                    <div class="row">
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-warning m-r-10 m-b-10">
                                <i class="btn-icon fa fa-floppy-o"></i>Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="overlay"></div>
@endsection

@section("js")

    <script src="/assets/marino/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="/vendor/jildertmiedema/laravel-plupload/js/plupload.full.min.js"
        type="text/javascript"></script>
    <script type="text/javascript">

        function str_random(length) {
            if(!length)
                length = 5;
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            for(var i=0; i < length; i++)
                text += possible.charAt(Math.floor(Math.random() * possible.length));
            return text;
        }

        $( document ).ready(function() {
            var video_submitted = false;
            var videofile = null;
            $('#submit-request').on('submit', function (e) {
                video_submitted = true;
                $('#overlay').fadeIn();
            });
            window.onbeforeunload = function() {
                if(!video_submitted){
                    $.ajax({
                        url: "{{ route('review.fallback-videofile') }}",
                        type: 'POST',
                        data: {"videofile":videofile}
                    });
                }
            };

            /*$('#submit-request').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ url('video') }}",
                    type: 'POST',
                    data: $(this).serialize(),
                    beforeSend: function () {
                        
                    },
                    success: function () {
                        
                    }
                });
            });*/

            var other_sell = $('#other_sell');
            if( other_sell.is(':checked') ){
                $("#other_site_spec").fadeIn();
            }


            $("#birthday").datepicker({
                endDate: '0d'
            }).on("change", function () {
                var that = $(this);
                if(that.attr("data-name")) {
                    that.attr({name: that.attr("data-name")});
                    that.removeAttr("data-name");
                }
            });

            $(".site_radio").on("change", function() {
                if($(this).val() == "other") {
                    $("#other_site_spec").fadeIn();
                } else {
                    $("#other_site_spec").fadeOut();
                    //$("#other_site_spec>div>input").val("").attr({"data-name": $(this).attr("name")}).removeAttr("name");
                }
            });

            $(".editable").on("input", function() {
                var that = $(this);
                if(that.attr("data-name")) {
                    that.attr({name: that.attr("data-name")});
                    that.removeAttr("data-name");
                }
            });

            function uploader() {
                var uploader = new plupload.Uploader({
                    runtimes: 'html5,html4',
                    browse_button: document.getElementById('pick_file'),
                    container: document.getElementById('upload_container'),
                    max_retries: 5,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    multi_selection: false,
                    url : "/video/save-file",
                    chunk_size: '{{env('VIDEO_UPLOAD_CHUNK_SIZE', 512)}}kb',
                    filters : {
                        max_file_size : '{{env('VIDEO_MAX_UPLOAD_SIZE', 300)}}mb',
                        mime_types: [
                            {title : "Video files", extensions : "avi,mpeg4,wmv,mp4,mov"}
                        ]
                    },
                    init: {
                        PostInit: function() {
                            document.getElementById('file_list').innerHTML = '';
                        },
                        FilesAdded: function(up, files) {
                            while (up.files.length > 1) {
                                up.removeFile(up.files[0]);
                            }
                            var file = files[0];
                            var new_name = str_random() + "-" + file.name;
                            up.settings.multipart_params = {name: new_name, Filename: new_name};
                            document.getElementById('file_list').innerHTML = '<div id="' + files[0].id + '" style="display:\
                                    inline;">' + files[0].name + ' (' + plupload.formatSize(files[0].size) + ')</div>';
                            uploader.start();
                        },
                        UploadProgress: function(up, file) {
                            $("#uploading_progress").val(file.percent);
                        },
                        BeforeUpload: function (uploader, file) {
                            $("#progress").show();
                            $("#stop_uploading").show();
                            $("#pick_file").attr({disabled: "disabled"}).css('cursor', 'not-allowed');
                            $("#upload_container :input[type='file']").attr({disabled: "disabled"});
                        },
                        Error: function(up, err) {
                            $.notify("Upload error: " + err.message, "error");
                            console.log(err);
                        },
                        FileUploaded: function (uploader, file, result) {
                            var response = $.parseJSON(result.response);
                            $("#file_name_ipt").attr("value", response.result.file);
                            videofile = response.result.file;
                            $("#file_list").append('<span class="m-r-10 label label-pill label-success">success</span>');
                            $("#progress").hide();
                            $("#stop_uploading").hide();
                        }
                    }
                });
                return uploader;
            }

            var upload = uploader();
            upload.init();
            $("#stop_uploading").on("click", function() {
                /*upload.stop();
                upload.refresh();*/
                $(this).hide();
                upload.removeFile(upload.files[0]);
                $('#file_list').html('');
                $("#uploading_progress").val(0);
                $("#progress").hide();
                $(".moxie-shim").remove();
                $("#pick_file").removeAttr("disabled").css('cursor', 'pointer');
                upload.destroy();
                upload = uploader();
                upload.init();
            });
            relocate();

            function relocate(){
                var video_id = {{session('created_video_id', 0)}};
                if(video_id){
                    setTimeout(function(){
                        location.href = '/video';
                    }, 2000);
                }

            }
        });
    </script>
@endsection