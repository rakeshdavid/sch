@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="/assets/css/select2-v4.0.4.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/5.3.4/sweetalert2.min.css">
    <link type="text/css" rel="stylesheet" href="/assets/js/datatables/datatables.min.css">
@endsection
@section('content')
    <div class="row m-b-20">
        <div class="col-xs-12">
            <h3>Update user profile</h3>
            <h4 class="m-b-20 m-t-20">Profile Data</h4>
            <form action="{{ route('admin.users.update', ['id' => $user['id']]) }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group row {{ $errors->has('first_name') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="first_name">First name*</label>
                    <div class="col-sm-9">
                        <input type="text" maxlength="254" class="form-control {{ $errors->has('first_name') ? ' form-control-danger' : '' }}" name="first_name" autocomplete="off"
                               value="{{ !empty(old('first_name')) ? old('first_name') : $user['first_name'] }}">
                        @if($errors->has('first_name'))
                            <small class="text-danger">{{ $errors->first('first_name') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('last_name') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="last_name">Last name*</label>
                    <div class="col-sm-9">
                        <input type="text" maxlength="254" class="form-control {{ $errors->has('last_name') ? ' form-control-danger' : '' }}" name="last_name" autocomplete="off"
                               value="{{ !empty(old('last_name')) ? old('last_name') : $user['last_name'] }}">
                        @if($errors->has('last_name'))
                            <small class="text-danger">{{ $errors->first('last_name') }}</small>
                        @endif
                    </div>
                </div>
                <div class="form-group row {{ $errors->has('email') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="email">Email*</label>
                    <div class="col-sm-9">
                        <input type="email" maxlength="254" class="form-control {{ $errors->has('email') ? ' form-control-danger' : '' }}" name="email" autocomplete="off"
                               value="{{ !empty(old('email')) ? old('email') : $user['email'] }}">
                        @if($errors->has('email'))
                            <small class="text-danger">{{ $errors->first('email') }}</small>
                        @endif
                    </div>
                </div>
                <div class="form-group row {{ $errors->has('birthday') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label">Birth date</label>
                    <div class="col-sm-9">
                        <input type="text" maxlength="254" class="form-control" name="birthday" id="birthday"
                               value="{{ !empty(old('birthday')) ? old('birthday') : $user['birthday'] }}">
                        @if($errors->has('birthday'))
                            <small class="text-danger">{{ $errors->first('birthday') }}</small>
                        @endif
                    </div>
                </div>
                <div class="form-group row {{ $errors->has('gender') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="gender">Gender</label>
                    <div class="col-sm-9 m-t-5">
                        <label class="c-input c-radio">
                            <input type="radio" name="gender" id="inlineRadio1" value="famale"
                                   @if($user['gender'] == 'famale') checked @endif >
                            <span class="c-indicator c-indicator-warning"></span>
                            Female
                        </label>
                        <label class="c-input c-radio">
                            <input type="radio" name="gender" id="inlineRadio2" value="male"
                                   @if($user['gender'] == 'male') checked @endif >
                            <span class="c-indicator c-indicator-warning"></span>
                            Male
                        </label>
                        @if($errors->has('gender'))
                            <small class="text-danger">{{ $errors->first('gender') }}</small>
                        @endif
                    </div>
                </div>

                {{--<div class="form-group row">
                    <label class="col-sm-3 form-control-label" for="title">Activity type/s</label>
                    <div class="col-sm-9">
                        <select class="form-control" name="activity_type[]" id="activity_type" autocomplete="off" multiple="multiple">
                            @if (count($activity_types))
                                @foreach($activity_types as $activity_type)
                                    @foreach($userActivityTypes as $userActivityType)
                                        <option value="{{ $activity_type['id'] }}"
                                                {{ ($userActivityType['activity_type_id'] == $activity_type['id']) ? 'selected' : '' }}>
                                            {{ $activity_type['name']}}
                                        </option>
                                    @endforeach
                                @endforeach
                            @endif
                        </select>
                        @if($errors->has('activity_type'))
                            <small class="text-danger">{{ $errors->first('activity_type') }}</small>
                        @endif
                    </div>
                </div>--}}
                <select class="form-control" name="activity_type" style="display: none"> {{--crunch--}}
                    <option value="1" selected>
                        Dance
                    </option>
                </select>

                @php
                    $user_act = $user_crunch->activity_genres;
                @endphp
                <div class="form-group row">
                    <label class="col-sm-3 form-control-label" for="title">Genre/s</label>
                    <div class="col-sm-9">
                        <select class="form-control" name="genres[]" id="activity_type_genres"
                                autocomplete="off" multiple="multiple">
                            @foreach($activity_genres as $activity_genre)
                                <option value="{{ $activity_genre->id }}"
                                        {{ $user_act->where('id', $activity_genre->id)->first() ? 'selected' : ''}}>{{ $activity_genre->name }}
                                </option>
                            @endforeach
                        </select>
                        @if($errors->has('genres'))
                            <small class="text-danger">{{ $errors->first('genres') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('location') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="location">City</label>
                    <div class="col-sm-9">
                        <input type="text" maxlength="254" class="form-control {{ $errors->has('location') ? ' form-control-danger' : '' }}" name="location" autocomplete="off"
                               value="{{ !empty(old('location')) ? old('location') : $user['location'] }}">
                        @if($errors->has('location'))
                            <small class="text-danger">{{ $errors->first('location') }}</small>
                        @endif
                    </div>
                </div>
                <div class="form-group row {{ $errors->has('location_state') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="location_state">State</label>
                    <div class="col-sm-9">
                        <input type="text" maxlength="254" class="form-control {{ $errors->has('location_state') ? ' form-control-danger' : '' }}" name="location_state" autocomplete="off"
                               value="{{ !empty(old('location_state')) ? old('location_state') : $user['location_state'] }}">
                        @if($errors->has('location_state'))
                            <small class="text-danger">{{ $errors->first('location_state') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group row @if($errors->has('profile_photo'))has-danger @endif">
                    <label class="col-sm-3 form-control-label">Upload Profile Photo</label>
                    <div class="col-sm-9 m-t-5">
                        <div class="input-group m-b-10">
                            <label class="input-group-btn">
                                <span class="btn btn-primary">
                                Upload file (jpg, png, gif)
                                    <input type="file" name="profile_photo" accept="image/jpeg,image/png,image/gif"
                                           id="avatar_upload" style="display: none;">
                                </span>
                            </label>
                            <input type="text" class="form-control @if($errors->has('profile_photo'))form-control-danger @endif" readonly="">
                        </div>
                        @if($errors->has('profile_photo'))
                            <small class="text-danger">{{ $errors->first('profile_photo') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('overview') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="contact_email">About user</label>
                    <div class="col-sm-9">
                        @php
                            $review = strip_tags($user['about']);
                            $review = str_replace("<p>", "", $review);
                            $review = str_replace("</p>", "\n", $review);
                        @endphp
                        <textarea type="text" maxlength="254" class="form-control {{ $errors->has('overview') ? ' form-control-danger' : '' }}"
                                  rows="5" name="overview" autocomplete="off">{!! !empty (old('overview')) ? old('overview') : $review !!}</textarea>
                        @if($errors->has('overview'))
                            <small class="text-danger">{{ $errors->first('overview') }}</small>
                        @endif
                    </div>
                </div>

                @if (count($performance_levels))
                    <div class="form-group row">
                        <label class="col-sm-3 form-control-label">Performance Level/s</label>
                        <div class="col-sm-9 m-t-5">
                            <select class="form-control" name="performance_levels[]" id="performance_levels" autocomplete="off">
                                <option value=""></option>
                                @foreach($performance_levels as $level)
                                    <option value="{{ $level['id'] }}"
                                        {{ ($userPerformanceLevels['performance_level_id'] == $level['id']) ? 'selected' : '' }}>
                                        {{ $level['name']}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                <h4 class="m-b-20">Contact</h4>
                <div class="form-group row {{ $errors->has('contact_email') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="contact_email">Contact email</label>
                    <div class="col-sm-9">
                        <input type="email" maxlength="254" class="form-control {{ $errors->has('contact_email') ? ' form-control-danger' : '' }}" name="contact_email" autocomplete="off"
                               value="{{ !empty(old('contact_email')) ? old('contact_email') : $user['contact_email'] }}">
                        @if($errors->has('contact_email'))
                            <small class="text-danger">{{ $errors->first('contact_email') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('phone') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label">Phone</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="phone" autocomplete="off"
                               value="{{ !empty(old('phone')) ? old('phone') : $user['phone'] }}">
                        @if($errors->has('phone'))
                            <small class="text-danger">{{ $errors->first('phone') }}</small>
                        @endif
                    </div>
                </div>
                <div class="form-group row {{ $errors->has('wevsites') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label">Website</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control {{ $errors->has('wevsites') ? ' form-control-danger' : '' }}"
                               name="wevsites" autocomplete="off" value="{{ !empty(old('wevsites')) ? old('wevsites') : $user['wevsites'] }}">
                        @if($errors->has('wevsites'))
                            <small class="text-danger">{{ $errors->first('wevsites') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('social_links') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="social_links">Social links</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control {{ $errors->has('social_links') ? ' form-control-danger' : '' }}"
                               name="social_links" value="{{ !empty(old('social_links')) ? old('social_links') : $user['social_links'] }}">
                        @if($errors->has('social_links'))
                            <small class="text-danger">{{ $errors->first('social_links') }}</small>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-warning m-r-10 m-b-10">
                            <i class="btn-icon fa fa-check"></i>Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/js/select2-v4.0.4.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/sweetalert2/5.3.4/sweetalert2.min.js"></script>
    <script src="/vendor/jildertmiedema/laravel-plupload/js/plupload.full.min.js"></script>
    <script type="text/javascript">
        var successMsg = "{{ session()->has('success') ? session('success') : '' }}";
        var user_genres = {{ $activity_types[0]['id'] }};

        $(document).ready(function() {
            if(successMsg){
                swal(successMsg);
            }

            /*$('#activity_type').val([{{ old('activity_type') ? old('activity_type') : '' }}]).select2({
                placeholder: "Select activity type/s",
                multiple: true
            }).on("change", function () {
                $.ajax({
                    url: '{{ route('get-genres') }}',
                    type: 'POST',
                    data: {activityTypesIds: $(this).val()},
                    dataType: 'JSON',
                    success: function (res) {
                        //var $genres = result;
                        $('#activity_type_genres').select2('destroy').empty().select2({
                            placeholder: "Select genre/s",
                            multiple: true,
                            data: res,
                            processResults: function (result) {
                                return {
                                    results: $.map(result, function(obj) {
                                        return { id: obj.id, text: obj.name };
                                    })
                                };
                            }
                        });
                    },
                    error: function (z,x,v) {

                    }
                });
            });
            */
            $('#activity_type_genres').select2({
                placeholder: "Select genre/s",
                multiple: true
            }).change(function () {
                //crunch
                $.ajax({
                    url: '{{ route('get-genres') }}',
                    type: 'POST',
                    data: {activityTypesIds: user_genres},
                    dataType: 'JSON',
                    success: function (res) {
                        //var $genres = result;
                        $('#activity_type_genres').select2({
                            placeholder: "Select genre/s",
                            multiple: true,
                            data: res,
                            processResults: function (result) {
                                return {
                                    results: $.map(result, function(obj) {
                                        return { id: obj.id, text: obj.name };
                                    })
                                };
                            }
                        });
                    },
                    error: function (z,x,v) {
                    }
                });
            });

            $('#performance_levels').select2({
                placeholder: "Select Performance Level"
            });

            /*//crunch
            $.ajax({
                url: '{{ route('get-genres') }}',
                type: 'POST',
                data: {activityTypesIds: user_genres},
                dataType: 'JSON',
                success: function (res) {
                    //var $genres = result;
                    $('#activity_type_genres').select2({
                        placeholder: "Select genre/s",
                        multiple: true,
                        data: res,
                        processResults: function (result) {
                            return {
                                results: $.map(result, function(obj) {
                                    return { id: obj.id, text: obj.name };
                                })
                            };
                        }
                    });
                },
                error: function (z,x,v) {
                }
            });*/
        });

        $(function () {
            // We can attach the `fileselect` event to all file inputs on the page
            $(document).on('change', $('#avatar_upload'), function () {
                var input = $('#avatar_upload'),
                    numFiles = input.get(0).files ? input.get(0).files.length : 1,
                    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                input.trigger('fileselect', [numFiles, label]);
            });
            // We can watch for our custom `fileselect` event like this
            $(document).ready(function () {
                $('#avatar_upload').on('fileselect', function (event, numFiles, label) {
                    var input = $('#avatar_upload').parents('.input-group').find(':text'),
                        log = numFiles > 1 ? numFiles + ' files selected' : label;
                    if (input.length) {
                        input.val(log);
                    } else {
                        if (log) alert(log);
                    }
                });
            });
        });

        $(document).ready(function () {
            $('#birthday').datepicker({
                format: 'yyyy-mm-dd'
            }).on('changeDate', function (e) {
                $('#birthday').datepicker('setStartDate', '');
            });
        });

        $('[data-toggle="tooltip"]').tooltip();
    </script>
@endsection
