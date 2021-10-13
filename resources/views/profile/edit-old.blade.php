@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="/assets/css/select2.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/5.3.4/sweetalert2.min.css">
    <link rel="stylesheet" href="/assets/marino/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css"/>
    <link type="text/css" rel="stylesheet" href="/assets/js/toastr/toastr.min.css">
@endsection

@section('content')

    @include('profile.header')

    <div class="row m-b-20">
        <div class="col-xs-12 col-lg-10">
            <div class="bs-nav-tabs nav-tabs-warning">
                <ul class="nav nav-tabs nav-animated-border-from-left">
                    <li class="nav-item">
                        <a class="nav-link" href="/profile">View Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/profile">Edit Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/profile/account_settings">Account settings</a>
                    </li>
                </ul>
                <div class="tab-content">

                    <div role="tabpanel" class="tab-pane in active" id="nav-tabs-0-3">
                        <h4 class="m-b-20">My Profile</h4>
                        <div class="p-t-20">
                            <form action="/profile/{{ $user->id }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label" for="first_name">First name</label>
                                    <div class="col-sm-9">
                                        <input type="text" maxlength="254" class="form-control" name="first_name" autocomplete="off"
                                               value="{{ $user->first_name }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label" for="last_name">Last name</label>
                                    <div class="col-sm-9">
                                        <input type="text" maxlength="254" class="form-control" name="last_name" autocomplete="off"
                                               value="{{ $user->last_name }}">
                                    </div>
                                </div>
                                @if($user->isCoach())
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Title</label>
                                        <div class="col-sm-9">
                                            <input type="text" maxlength="254" class="form-control" name="title" autocomplete="off"
                                                   value="{{ $user->title }}">
                                        </div>
                                    </div>
                                @else
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Birth date</label>
                                        <div class="col-sm-9">
                                            <input type="text" maxlength="254" class="form-control" name="birthday"
                                                   id="birthday" value="{{$user->birthday}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label" for="gender">Gender</label>
                                        <div class="col-sm-9 m-t-5">
                                            <label class="c-input c-radio">
                                                <input type="radio" name="gender" id="inlineRadio1" value="famale" @if($user->gender == "famale") {{"checked"}} @endif >
                                                <span class="c-indicator c-indicator-warning"></span>
                                                Female
                                            </label>
                                            <label class="c-input c-radio">
                                                <input type="radio" name="gender" id="inlineRadio2" value="male"  @if($user->gender == "male") {{"checked"}} @endif >
                                                <span class="c-indicator c-indicator-warning"></span>
                                                Male
                                            </label>
                                        </div>
                                    </div>
                                @endif

                                {{--<div class="form-group row">
                                    <label class="col-sm-3 form-control-label">Activity type</label>
                                    <div class="col-sm-9 m-t-5">
                                        <select class="form-control" name="activity_type" id="activity_type" autocomplete="off">
                                            <option></option>
                                            @foreach($activity_types as $activity_type)
                                                <option value="{{ $activity_type->id }}" {{ $user->activity_types->first() ?
                                                $user->activity_types->first()->id == $activity_type->id ? 'selected' : '' : ''}}>
                                                    {{ $activity_type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>--}}

                                    <select class="form-control" name="activity_type" style="display: none"> {{--crunch--}}
                                            <option value="1" selected>
                                                Dance
                                            </option>
                                    </select>

                                @php
                                    $user_act = $user->activity_genres;
                                @endphp

                                @if(!$user->isAdmin())
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label" for="genres">Genre/s</label>
                                        <div class="col-sm-9 m-t-5" id="genres_container">

                                            {{-- //TODO:: delete after test --}}
                                            {{--@foreach($activity_genres as $activity_genre)
                                                <label class="c-input c-checkbox">
                                                    <input type="checkbox" name="genres[]" autocomplete="off" value="{{ $activity_genre->id }}"
                                                            {{ $user_act->where('id', $activity_genre->id)->first() ? 'checked' : ''}}>
                                                    <span class="c-indicator c-indicator-warning"></span>
                                                    <span class="c-input-text"> {{ $activity_genre->name }} </span>
                                                </label>
                                            @endforeach--}}

                                            <select class="form-control" name="genres[]" id="activity_type_genres"
                                                autocomplete="off" multiple="multiple">
                                                @foreach($activity_genres as $activity_genre)
                                                    <option value="{{ $activity_genre->id }}"
                                                        {{ $user_act->where('id', $activity_genre->id)->first() ? 'selected' : ''}}>{{ $activity_genre->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                @endif
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">City</label>
                                    <div class="col-sm-9">
                                        <input type="text" maxlength="254" class="form-control" name="location" autocomplete="off"
                                               value="{{ $user->location }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">State</label>
                                    <div class="col-sm-9">
                                        <input type="text" maxlength="254" class="form-control" name="location_state" autocomplete="off"
                                               value="{{ $user->location_state }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label" for="avatar">Upload Profile Photo </label>
                                    <div class="col-sm-9">
                                        <input type="file" name="avatar" accept="image/x-png,image/gif,image/jpeg">(jpg, png, gif)
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">{{ $user->isCoach() ? 'Overview' : 'About me' }}</label>
                                    <div class="col-sm-9">
                                        @php
                                            $review = $user->isCoach() ? $user->about : strip_tags($user->about);
                                            if($user->isCoach()) {
                                                $review = str_replace("<p>", "", $review);
                                                $review = str_replace("</p>", "\n", $review);
                                            }
                                        @endphp
                                        <textarea class="form-control" name="overview" rows="5">{!! $review !!}</textarea>
                                    </div>
                                </div>
                                @if($user->isCoach())
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Certifications</label>
                                        <div class="col-sm-9">
                                            <input type="text" maxlength="254" class="form-control" name="certifications" autocomplete="off"
                                                   value="{{ $user->certifications }}" data-toggle="tooltip" data-placement="top" title=""
                                                   data-original-title='For separate Certifications use ";"'>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Teaching Positions</label>
                                        <div class="col-sm-9">
                                            <input type="text" maxlength="254" class="form-control" name="teaching_positions" autocomplete="off"
                                                   value="{{ $user->teaching_positions }}" data-toggle="tooltip" data-placement="top" title=""
                                                   data-original-title='For separate Teaching Positions use ";"'>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Performance Credits</label>
                                        <div class="col-sm-9">
                                            <input type="text" maxlength="254" class="form-control" name="performance_credits" autocomplete="off"
                                                   value="{{ $user->performance_credits }}" data-toggle="tooltip" data-placement="top" title=""
                                                   data-original-title='For separate Performance Credits use ";"'>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">{{ $user->isCoach() ? 'Gives feedback to'
                                    : 'Performance Level/s' }}</label>
                                    <div class="col-sm-9 m-t-5">
                                        @php
                                            $user_performance_level = $user->performance_levels;
                                        @endphp
                                        @if($user->isCoach())
                                            @foreach($performance_levels as $performance_level)
                                                <label class="c-input {{ $user->isCoach() ? 'c-checkbox' : 'c-radio' }}">
                                                    <input type="{{ $user->isCoach() ? 'checkbox' : 'radio' }}" name="level[]"
                                                           value="{{ $performance_level->id }}"
                                                        autocomplete="off" {{ $user_performance_level->all() ?
                                                        $user_performance_level->where('id', $performance_level->id)->first()
                                                            ? 'checked' : '' : '' }}>
                                                    <span class="c-indicator c-indicator-warning"></span>
                                                    {{ $performance_level->name }}
                                                </label>
                                            @endforeach
                                        @else
                                           <select class="form-control" name="level[]" id="performance_level_select" autocomplete="off">
                                                @foreach($performance_levels as $performance_level)
                                                    <option value="{{$performance_level->id}}" {{ $user_performance_level->all() ?
                                                        $user_performance_level->where('id', $performance_level->id)->first()
                                                            ? 'selected' : '' : '' }}>
                                                        {{ $performance_level->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                </div>
                                @if($user->isCoach())
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Upload Gallery Images</label>
                                        <div class="col-sm-9 m-t-5">
                                            <div id="upload_container">
                                                <button id="pick_files" type="button" class="btn btn-primary m-r-5"
                                                        data-toggle="tooltip" data-placement="top" title=""
                                                        data-original-title="Maximum allowed file size: {{ env("PHOTO_MAX_UPLOAD_SIZE", 2) }}Mb">
                                                    <i class="btn-icon fa fa-folder-open"></i>Select photos
                                                </button>
                                                <button id="upload_files" type="button" class="btn btn-success m-r-5">
                                                    <i class="btn-icon fa fa-upload"></i>Upload photos
                                                </button>
                                            </div>
                                            <div id="file_list" class="m-t-10">Your browser doesn't have HTML5 support.</div>
                                            <input type="hidden" name="gallery_photos" id="gallery_photos" autocomplete="off">
                                            <input type="hidden" name="deleted_gallery_items" id="deleted_gallery_items"
                                                   autocomplete="off">
                                            <div class="m-t-10">
                                                @if($user->gallery->count() > 0)
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <label>Your current gallery:</label>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="row">
                                                    @foreach($user->gallery as $gallery_item)
                                                        @if ($gallery_item->type == 'image')
                                                            <div class="col-xs-12 col-md-6 m-t-10 gallery-item-container m-l-0 m-r-0">
                                                                <img class="w-100 img-thumbnail" src="{{ $gallery_item->type
                                                                     == 'image' ? '/gallery/'.$gallery_item->path : 'http://img.youtube.com/vi/'
                                                                     . $gallery_item->path . '/1.jpg' }}">
                                                                <a href="{{ $gallery_item->type == 'image' ? '/gallery/'.$gallery_item->path :
                                                                   'https://www.youtube.com/watch?v='.$gallery_item->path }}" target="_blank">
                                                                    <u style="text-transform: capitalize;">{{ $gallery_item->type }}</u>
                                                                </a>
                                                                <span data-type="{{ $gallery_item->type }}" style="cursor:pointer;"
                                                                      class="delete-gallery-item label label-danger-outline label-lg"
                                                                      data-id="{{ $gallery_item->id }}">
                                                                    delete
                                                                </span>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row @foreach(range(0, 10) as $index) @if($errors->has('documents.' . $index))has-danger @break @endif @endforeach">
                                        <label class="col-sm-3 form-control-label">Upload PDF Documents</label>
                                        <div class="col-sm-9 m-t-5">
                                            <div class="input-group m-b-10">
                                                <label class="input-group-btn">
                                                    <span class="btn btn-primary">
                                                    Upload files
                                                        <input type="file" name="documents[]" multiple="multiple" accept="application/pdf" id="document_upload" style="display: none;">
                                                    </span>
                                                </label>
                                                <input type="text" class="form-control @foreach(range(0, 10) as $index) @if($errors->has('documents.' . $index))form-control-danger @break @endif @endforeach" readonly="">
                                            </div>
                                            @foreach(range(0, 10) as $index)
                                                @if($errors->has('documents.' . $index))
                                                    <small class="text-danger">{{ $errors->first('documents.' . $index) }}</small>
                                                    @break
                                                @endif
                                            @endforeach
                                        </div>
                                        @if (count($coachDocuments))
                                            <input type="hidden" name="deleted_coach_documents" id="deleted_coach_documents" autocomplete="off">
                                            <label class="col-sm-3 form-control-label">PDF Documents List</label>
                                            <div class="col-sm-9 m-t-5">
                                                <ul>
                                                    @foreach($coachDocuments as $document)
                                                        <li class="coach-document-container m-b-30">
                                                            <u><a href="{{ asset(env('UPLOADS_FOLDER') . '/' . env('COACHES_DOCUMENTS_FOLDER') . '/' . $document['document_name']) }}" target="_blank">
                                                                    {{ $document['document_name'] }}
                                                                </a></u>
                                                            {{ \File::mimeType($userDocunentFolder . '/' . $document['document_name']) }}
                                                            <span data-type="{{ \File::mimeType($userDocunentFolder . '/' . $document['document_name']) }}" style="cursor:pointer;"
                                                                  class="delete-coach-document label label-danger-outline label-lg m-b-10"
                                                                  data-id="{{ $document['id'] }}">delete</span>

                                                            <embed src="{{ asset(env('UPLOADS_FOLDER') . '/' . env('COACHES_DOCUMENTS_FOLDER') . '/' . $document['document_name']) }}"
                                                                   style="display:block;" width="500" height="375"
                                                                   type="{{ \File::mimeType($userDocunentFolder . '/' . $document['document_name']) }}">
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label m-t-5">Upload Performance Video</label>
                                        <div class="col-sm-9 m-t-5">
                                            <input type="text" class="form-control m-b-15" name="video" autocomplete="off"
                                                   placeholder="Youtube video url">
                                            <div id="upload_container_video">
                                                <button id="pick_file_video" type="button" class="btn btn-primary m-r-5">
                                                    <i class="btn-icon fa fa-folder-open"></i>Select video
                                                </button>
                                                <button id="upload_file_video" type="button" class="btn btn-success m-r-5">
                                                    <i class="btn-icon fa fa-upload"></i>Upload video
                                                </button>
                                                <button style="display: none;" id="stop_uploading_video" type="button" class="btn btn-danger m-r-5">
                                                    <i class="btn-icon fa fa-stop"></i>Stop uploading
                                                </button>
                                            </div>
                                            <div id="file_list_video" class="m-t-10">Your browser doesn't have HTML5 support.</div>
                                            <div class="row" id="progress" style="display: none">
                                                <div class="col-xs-12 col-lg-8">
                                                    <progress class="progress-xs progress progress-danger" value="0" max="100"
                                                              id="uploading_progress"></progress>
                                                </div>
                                            </div>
                                            <input type="hidden" name="gallery_video" id="gallery_video" autocomplete="off">
                                            <div class="row">
                                                @foreach($user->gallery as $gallery_item)
                                                    @if ($gallery_item->type == 'video')
                                                        <div class="col-xs-12 col-md-6 m-t-10 gallery-item-container m-l-0 m-r-0">
                                                            <img class="w-100 img-thumbnail" src="{{ $gallery_item->type
                                                                     == 'image' ? '/gallery/'.$gallery_item->path : 'http://img.youtube.com/vi/'
                                                                     . $gallery_item->path . '/1.jpg' }}">
                                                            <a href="{{ $gallery_item->type == 'image' ? '/gallery/'.$gallery_item->path :
                                                                   'https://www.youtube.com/watch?v='.$gallery_item->path }}" target="_blank">
                                                                <u style="text-transform: capitalize;">{{ $gallery_item->type }}</u>
                                                            </a>
                                                            <span data-type="{{ $gallery_item->type }}" style="cursor:pointer;"
                                                                  class="delete-gallery-item label label-danger-outline label-lg"
                                                                  data-id="{{ $gallery_item->id }}">
                                                                    delete
                                                                </span>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <h4 class="m-b-20">Contact</h4>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">Email</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="contact_email" autocomplete="off"
                                               value="{{ $user->contact_email }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">Phone</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="phone" autocomplete="off"
                                               value="{{ $user->phone }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">Website</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="wevsites" autocomplete="off"
                                               value="{{ $user->wevsites }}">
                                    </div>
                                </div>

                                {{--@if(!$user->isCoach())--}}
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label" for="social_links">Facebook link</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="facebook_link" value="{{ $user->facebook_link }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label" for="social_links">Instagram link</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="instagram_link" value="{{ $user->instagram_link }}">
                                        </div>
                                    </div>
                                {{--@endif--}}

                                @if($user->isCoach())
                                <div class="form-group row">
                                        <label class="col-sm-3 form-control-label" for="other_site_spec">Other Blog sites</label>
                                        <div class="col-sm-9">
                                            <input autocomplete="off" type="text" class="form-control" name="other_site_spec" value="{{ $user->other_site_spec }}">
                                        </div>
                                    </div>
                                @endif

                                @if($user->isCoach())
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label" for="coachs_site">Links to Coach's site</label>
                                        <div class="col-sm-9">
                                            <input autocomplete="off" type="text" class="form-control" name="coachs_site" value="{{ $user->coachs_site }}">
                                        </div>
                                    </div>
                                @endif

                                @if($user->isCoach())
                                    <h4 class="m-b-20">Vacation time</h4>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Start date</label>
                                        <div class="col-sm-9">
                                            <input id="vacation_start" type="text" class="form-control" name="vacation_start" autocomplete="off"
                                                   value="{{ ($user->vacation_start != '0000-00-00') ? $user->vacation_start : '' }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">End Date</label>
                                        <div class="col-sm-9">
                                            <input id="vacation_end" type="text" class="form-control" name="vacation_end" autocomplete="off"
                                                   value="{{ ($user->vacation_end != '0000-00-00') ? $user->vacation_end : '' }}">
                                        </div>
                                    </div>
                                @endif

                                @if($user->isCoach())
                                    <h4 class="m-b-20">Pricing</h4>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Summary package</label>
                                        <div class="col-sm-9">
                                            <input id="vacation_start" type="text" class="form-control" name="price_summary" autocomplete="off"
                                                   value="{{ ($user->price_summary != 0) ? $user->price_summary : '' }}" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Detailed package</label>
                                        <div class="col-sm-9">
                                            <input id="vacation_end" type="text" class="form-control" name="price_detailed" autocomplete="off"
                                                   value="{{ ($user->price_detailed != 0) ? $user->price_detailed : '' }}" required>
                                        </div>
                                    </div>
                                @endif

                            {{--@if($user->isCoach())
                            <h4 class="m-b-20">Review Pricing & Terms</h4>
                            @endif--}}
                            <!--                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label" for="proposal">Companies</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="companies" value="{{ $user->companies }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label" for="proposal">Position</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="position" value="{{ $user->position }}">
                                </div>
                            </div>-->
                            <!--                    <div class="form-group">
                                <label for="proposal">Role</label><br/>
                                <label class="radio-inline">
                                    <input type="radio" name="role" id="inlineRadio1" value="1" @if($user->role == 1) {{"checked"}} @endif > User
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="role" id="inlineRadio2" value="2"  @if($user->role == 2) {{"checked"}} @endif > Coach
                                </label>
                            </div>-->
                            <!-- <div class="form-group">
                                    <label for="exampleInputFile">Avatar</label>
                                    <input type="file" name="avatar" id="exampleInputFile">
                                </div>-->
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="row">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" class="btn btn-warning m-r-10 m-b-10">
                                            <i class="btn-icon fa fa-check"></i>Save
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/sweetalert2/5.3.4/sweetalert2.min.js"></script>
    <script src="/assets/js/select2.min.js"></script>
    <script src="/vendor/jildertmiedema/laravel-plupload/js/plupload.full.min.js"></script>
    <script src="/assets/marino/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="/assets/js/toastr/toastr.min.js"></script>
    <script type="text/javascript">
        successMsg = "{{ session()->has('success') ? session('success') : '' }}";
        var user_genres = {{ $user_act->pluck('id') }};
        var gallery_size = {{ $user->gallery()->count() }};

        if(successMsg){
            swal(successMsg);
        }

        $(function () {
            if($('#document_upload').length) {
                // We can attach the `fileselect` event to all file inputs on the page
                $(document).on('change', $('#document_upload'), function () {
                    var input = $('#document_upload'),
                        numFiles = input.get(0).files ? input.get(0).files.length : 1,
                        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                    input.trigger('fileselect', [numFiles, label]);
                });
                // We can watch for our custom `fileselect` event like this
                $(document).ready(function () {
                    $('#document_upload').on('fileselect', function (event, numFiles, label) {
                        var input = $('#document_upload').parents('.input-group').find(':text'),
                            log = numFiles > 1 ? numFiles + ' files selected' : label;
                        if (input.length) {
                            input.val(log);
                        } else {
                            if (log) alert(log);
                        }
                    });
                });
            }
        });

        $(".delete-coach-document").on("click", function() {
            var that = $(this);
            swal({
                title: 'Are you sure?',
                text: "Do you really want to delete this " + that.attr("data-type") + "?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then(function() {
                //preventNavigation("You have not saved the form. Any changes will be lost if you leave this page.");
                var deleted_val = $("#deleted_coach_documents").val();
                deleted_val += (deleted_val.length ? "," : "") + that.attr("data-id");
                $("#deleted_coach_documents").val(deleted_val);
                that.parent(".coach-document-container").remove();
                gallery_size--;
            }, function(dismiss) {});
        });

        function str_random(length) {
            if(!length)
                length = 5;
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            for(var i=0; i < length; i++)
                text += possible.charAt(Math.floor(Math.random() * possible.length));
            return text;
        }

        function preventNavigation(message) {
            var confirmOnPageExit = function (e) {
                e = e || window.event;
                if(e) {
                    e.returnValue = message;
                }
                return message;
            };
            window.onbeforeunload = confirmOnPageExit;
        }

        function genresArrayToTemplate(data) {
            if(!data) return '';
            var template = '';
            for(var i=0; i < data.length; i++) {
                var checked = user_genres.indexOf(data[i].id) !== -1 ? 'checked' : '';
                template += '<label class="c-input c-checkbox">\
                             <input type="checkbox" name="genres[]" value="' + data[i].id + '"\
                             autocomplete="off" ' + checked + '>\
                             <span class="c-indicator c-indicator-warning"></span>\
                             <span class="c-input-text">' + data[i].name + '</span>\
                             </label>';
            }
            return template;
        }
        function genresArrayToTemplateList(data) {
            if(!data) return '';
            var template = '';
            for(var i=0; i < data.length; i++) {
                var checked = user_genres.indexOf(data[i].id) !== -1 ? 'selected' : '';
                template += '<option  value="' + data[i].id + '" ' + checked + ' >' + data[i].name + '</option>';
            }
            return template;
        }

        $(function() {
            $('[data-toggle="tooltip"]').tooltip();

            $("#birthday").datepicker({
                endDate: '0d'
            });

            $(".form-horizontal :input").on('change',function(e) {
                if(e.target.id !='vacation_start' &&  e.target.id !='vacation_end' ){
                    preventNavigation("You have not saved the form. Any changes will be lost if you leave this page.");
                }

            });
            $("form").on("submit", function(e) {
                window.onbeforeunload = null;
            });

            $("#performance_level_select").select2({
                placeholder: "Performance level"
            });

            $("#activity_type_genres").select2({
                placeholder: "Select genre/s"
            });

            $("#activity_type").select2({
                placeholder: "Select type"
            }).on("change", function(e) {
                $.ajax({
                    type: "POST",
                    url: "{{ url('/ajax/get-genres') }}",
                    data: {"activity": e.currentTarget.value, "_token": "{{ csrf_token() }}"},
                    dataType: "json",
                    success: function(result) {
                        if(result.length > 0) {
                            //$("#genres_container").html(genresArrayToTemplateList(result));
                            $("#activity_type_genres").html(genresArrayToTemplateList(result));

                        } else {
                            //$("#genres_container").html("");
                            $("#activity_type_genres").html("");
                        }
                    }
                });
            });

            //crunch
            $.ajax({
                type: "POST",
                url: "{{ url('/ajax/get-genres') }}",
                data: {"activity": 1, "_token": "{{ csrf_token() }}"},
                dataType: "json",
                success: function(result) {
                    if(result.length > 0) {
                        //$("#genres_container").html(genresArrayToTemplateList(result));
                        $("#activity_type_genres").html(genresArrayToTemplateList(result));

                    } else {
                        //$("#genres_container").html("");
                        $("#activity_type_genres").html("");
                    }
                }
            });

            delImg = function deleteImage(obj) {
                console.log(obj.getAttribute('data-file'));
                upload.removeFile(obj.getAttribute('data-file'));
                var current_wrapper = document.getElementById(obj.getAttribute('data-file'));
                current_wrapper.parentElement.removeChild(current_wrapper);
            };

            function uploader() {
                var uploader = new plupload.Uploader({
                    runtimes: 'html5,html4',
                    browse_button: document.getElementById('pick_files'),
                    container: document.getElementById('upload_container'),
                    max_retries: 1,
                    max_files_count: {{ env('GALLERY_MAX_ITEMS_COUNT', 3) }},
                    prevent_duplicates: true,
                    unique_names: true,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    multi_selection: true,
                    url : "{{ route('profile.gallery.save-file') }}",
                    filters : {
                        max_file_size : '{{env('PHOTO_MAX_UPLOAD_SIZE', 2)}}mb',
                        mime_types: [
                            {title : "Image files", extensions : "png,jpg,jpeg"}
                        ]
                    },
                    init: {
                        PostInit: function() {
                            document.getElementById('file_list').innerHTML = '';
                            document.getElementById('upload_files').onclick = function() {
                                uploader.start();
                                return false;
                            };
                        },
                        FilesAdded: function(up, files) {
                            if((up.files.length + gallery_size) > up.settings.max_files_count) {
                                $.notify("Upload error: " + "Maximum images count: " + up.settings.max_files_count +
                                    ". " + "In gallery " + gallery_size + " file(s) already!", {autoHideDelay: 10000});
                                for(var i in files) {
                                    up.removeFile(files[i]);
                                }
                            } else {
                                plupload.each(files, function(file) {
                                    document.getElementById('file_list').innerHTML += '<div id="' + file.id + '">'
                                            + file.name + ' (' + plupload.formatSize(file.size) + ') <b>' +
                                            '<span onclick="delImg(this)" data-file="' + file.id + '" class="m-l-5 label ' +
                                            'label-warning-outline label-lg" style="cursor:pointer;">' +
                                            'cancel</span></b> </div>';
                                });
                            }
                        },
                        UploadProgress: function(up, file) {
                            document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>'
                                    + file.percent + "%</span>";
                        },
                        BeforeUpload: function (uploader, file) {
                            $("#pick_files").attr({disabled: "disabled"});
                            $("#upload_container :input[type='file']").attr({disabled: "disabled"});
                            //preventNavigation("You have not saved the form. Any changes will be lost if you leave this page.");
                        },
                        Error: function(up, err) {
                            $.notify("Upload error: " + err.message, "error");
                        },
                        FileUploaded: function (uploader, file, result) {
                            var response = $.parseJSON(result.response);
                            if (response.error) {
                                swal(response.error);
                                uploader.removeFile(file);
                                $('#file_list').empty();
                                $("#upload_container :input").removeAttr("disabled");
                            } else {
                                var gallery_photos_val = $("#gallery_photos").val();
                                var new_val = gallery_photos_val.length ? gallery_photos_val + "::" + response.result.file
                                    : response.result.file;
                                $("#gallery_photos").val(new_val);
                                document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span ' +
                                    'class="m-r-10 label label-pill label-success">success</span>';
                                if ((uploader.total.uploaded + 1) == uploader.files.length) {
                                    $("#pick_files").removeAttr("disabled");
                                    $("#upload_container :input[type='file']").removeAttr("disabled");
                                }
                            }
                        }
                    }
                });
                return uploader;
            }
            var upload = uploader();
            upload.init();

            function uploader_video() {
                var uploader_video = new plupload.Uploader({
                    runtimes: 'html5,html4',
                    browse_button: document.getElementById('pick_file_video'),
                    container: document.getElementById('upload_container_video'),
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
                            document.getElementById('file_list_video').innerHTML = '';
                            document.getElementById('upload_file_video').onclick = function() {
                                uploader_video.start();
                                return false;
                            };
                        },
                        FilesAdded: function(up, files) {
                            while (up.files.length > 1) {
                                up.removeFile(up.files[0]);
                            }
                            var file = files[0];
                            var new_name = str_random() + "-" + file.name;
                            up.settings.multipart_params = {name: new_name, Filename: new_name};
                            document.getElementById('file_list_video').innerHTML = '<div id="' + files[0].id + '" style="display:\
                                    inline;">' + files[0].name + ' (' + plupload.formatSize(files[0].size) + ')</div>';
                        },
                        UploadProgress: function(up, file) {
                            $("#uploading_progress").val(file.percent);
                        },
                        BeforeUpload: function (uploader_video, file) {
                            //preventNavigation("You have not saved the form. Any changes will be lost if you leave this page.");
                            $("#progress").show();
                            $("#stop_uploading_video").show();
                            $("#pick_file_video").attr({disabled: "disabled"});
                            $("#upload_container_video :input[type='file']").attr({disabled: "disabled"});
                        },
                        Error: function(up, err) {
                            $.notify("Upload error: " + err.message, "error");
                        },
                        FileUploaded: function (uploader_video, file, result) {
                            var response = $.parseJSON(result.response);
                            $("#gallery_video").val(response.result.file);
                            $("#file_list_video").append('<span class="m-r-10 label label-pill label-success">success</span>');
                            $("#progress").hide();
                            $("#stop_uploading_video").hide();
                        }
                    }
                });
                return uploader_video;
            }


            var upload_video = uploader_video();
            //upload.init();
            $("#stop_uploading_video").on("click", function () {
                $(this).hide();
                upload_video.removeFile(upload_video.files[0]);
                $('#file_list_video').html('');
                $("#uploading_progress").val(0);
                $("#progress").hide();
                $(".moxie-shim").remove();
                $("#pick_files").removeAttr("disabled");
                upload_video.destroy();
                upload_video = uploader();
                upload_video.init();
            });


            var upload_video = uploader_video();
            upload_video.init();

            $(".delete-gallery-item").on("click", function() {
                var that = $(this);
                swal({
                    title: 'Are you sure?',
                    text: "Do you really want to delete this " + that.attr("data-type") + "?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                }).then(function() {
                    //preventNavigation("You have not saved the form. Any changes will be lost if you leave this page.");
                    var deleted_val = $("#deleted_gallery_items").val();
                    deleted_val += (deleted_val.length ? "," : "") + that.attr("data-id");
                    $("#deleted_gallery_items").val(deleted_val);
                    that.parent(".gallery-item-container").remove();
                    gallery_size--;
                }, function(dismiss) {});
            });

        });

        $(document).ready(function () {
            $('#vacation_start').datepicker({
                format: 'yyyy-mm-dd',
            }).on('changeDate', function (e) {
                $('#vacation_end').datepicker('setStartDate', e.date);
            });

            $('#vacation_end').datepicker({
                format: 'yyyy-mm-dd',
            }).on('changeDate', function (e) {
                $('#vacation_start').datepicker('setEndDate', e.date);
            });
        });


        @if ( count( $errors ) > 0)
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
        toastr.error( 'The Avatar must be an image.' , "Error");
        @endif

    </script>
@endsection