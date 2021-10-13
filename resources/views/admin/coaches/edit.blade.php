@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="/assets/css/select2-v4.0.4.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/5.3.4/sweetalert2.min.css">
    <link type="text/css" rel="stylesheet" href="/assets/js/datatables/datatables.min.css">
@endsection
@section('content')
    <div class="row m-b-20">
        <div class="col-xs-12">
            <h3> Edit coach profile </h3>
            <h4 class="m-b-20 m-t-20">Profile Data</h4>
            <form action="{{ route('admin.coaches.update', ['id' => $coach['id']]) }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group row {{ $errors->has('first_name') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="first_name">First name*</label>
                    <div class="col-sm-9">
                        <input type="text" maxlength="254" class="form-control {{ $errors->has('first_name') ? ' form-control-danger' : '' }}" name="first_name" autocomplete="off"
                               value="{{ !empty(old('first_name')) ? old('first_name') : $coach['first_name'] }}">
                        @if($errors->has('first_name'))
                            <small class="text-danger">{{ $errors->first('first_name') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('last_name') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="last_name">Last name*</label>
                    <div class="col-sm-9">
                        <input type="text" maxlength="254" class="form-control {{ $errors->has('last_name') ? ' form-control-danger' : '' }}" name="last_name" autocomplete="off"
                               value="{{ !empty(old('last_name')) ? old('last_name') : $coach['last_name'] }}">
                        @if($errors->has('last_name'))
                            <small class="text-danger">{{ $errors->first('last_name') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('title') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="title">Title</label>
                    <div class="col-sm-9">
                        <input type="text" maxlength="254" class="form-control {{ $errors->has('title') ? ' form-control-danger' : '' }}" name="title" autocomplete="off"
                               value="{{ !empty(old('title')) ? old('title') : $coach['title'] }}">
                        @if($errors->has('title'))
                            <small class="text-danger">{{ $errors->first('title') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('email') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="email">Email*</label>
                    <div class="col-sm-9">
                        <input type="email" maxlength="254" class="form-control {{ $errors->has('email') ? ' form-control-danger' : '' }}" name="email" autocomplete="off"
                               value="{{ !empty(old('email')) ? old('email') : $coach['email'] }}">
                        @if($errors->has('email'))
                            <small class="text-danger">{{ $errors->first('email') }}</small>
                        @endif
                    </div>
                </div>

                {{--<div class="form-group row">
                    <label class="col-sm-3 form-control-label" for="title">Activity type/s</label>
                    <div class="col-sm-9">
                        <select class="form-control" name="activity_type[]" id="activity_type" autocomplete="off" multiple="multiple">
                            @if (count($activity_types))
                                @foreach($activity_types as $activity_type)
                                    <option value="{{ $activity_type['id'] }}">
                                        {{ $activity_type['name']}}
                                    </option>
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
                    $user_act = $user->activity_genres;
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
                               value="{{ !empty(old('location')) ? old('location') : $coach['location'] }}">
                        @if($errors->has('location'))
                            <small class="text-danger">{{ $errors->first('location') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('location_state') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="location_state">State</label>
                    <div class="col-sm-9">
                        <input type="text" maxlength="254" class="form-control {{ $errors->has('location_state') ? ' form-control-danger' : '' }}" name="location_state" autocomplete="off"
                               value="{{ !empty(old('location_state')) ? old('location_state') : $coach['location_state'] }}">
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
                    <label class="col-sm-3 form-control-label" for="contact_email">Overview</label>
                    <div class="col-sm-9">
                        @php
                            $review = strip_tags($coach['about']);
                            $review = str_replace("<p>", "", $review);
                            $review = str_replace("</p>", "\n", $review);
                        @endphp
                        <textarea type="text" class="form-control {{ $errors->has('overview') ? ' form-control-danger' : '' }}"
                                  rows="5" name="overview" autocomplete="off">{!! !empty (old('overview')) ? old('overview') : $review !!}</textarea>
                        @if($errors->has('overview'))
                            <small class="text-danger">{{ $errors->first('overview') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('certifications') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="location_state">Certifications</label>
                    <div class="col-sm-9">
                        <input type="text" maxlength="254" class="form-control {{ $errors->has('certifications') ? ' form-control-danger' : '' }}"
                               name="certifications" autocomplete="off" value="{{ !empty (old('certifications')) ? old('certifications') : $coach['certifications'] }}"
                               data-toggle="tooltip" data-placement="top" data-original-title="For separate Certifications use &quot;;&quot;">
                        @if($errors->has('certifications'))
                            <small class="text-danger">{{ $errors->first('certifications') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('teaching_positions') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="location_state">Teaching Positions</label>
                    <div class="col-sm-9">
                        <input type="text" maxlength="254" class="form-control {{ $errors->has('teaching_positions') ? ' form-control-danger' : '' }}"
                               name="teaching_positions" autocomplete="off" checked data-toggle="tooltip" data-placement="top"
                               data-original-title="For separate Teaching Positions use &quot;;&quot;"
                               value="{{ !empty(old('teaching_positions')) ? old('teaching_positions') : $coach['teaching_positions'] }}">
                        @if($errors->has('teaching_positions'))
                            <small class="text-danger">{{ $errors->first('teaching_positions') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('performance_credits') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="location_state">Performance Credits</label>
                    <div class="col-sm-9">
                        <input type="text" maxlength="254" class="form-control {{ $errors->has('performance_credits') ? ' form-control-danger' : '' }}"
                               name="performance_credits" autocomplete="off" data-toggle="tooltip" data-placement="top"
                               data-original-title="For separate Performance Credits use &quot;;&quot;"
                               value="{{ !empty(old('performance_credits')) ? old('performance_credits') : $coach['performance_credits'] }}">
                        @if($errors->has('performance_credits'))
                            <small class="text-danger">{{ $errors->first('performance_credits') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 form-control-label">Gives feedback to</label>
                    <div class="col-sm-9 m-t-5">
                        @foreach($perfomance_levels as $level)
                            <label class="c-input c-checkbox">
                                <input type="checkbox" name="performance_levels[{{ $level['id'] }}]"
                                       @if(isset(old('performance_levels')[$level['id']]))
                                       checked="checked"
                                       @elseif($coachPerformanceLevels)
                                       @php
                                           $coach_performance_level = $coach_levl->performance_levels->where('id', $level['id'])->first();
                                       @endphp
                                       {{ $coach_levl->all() ? $coach_performance_level ? 'checked="checked"' : '' : '' }}
                                       @endif
                                       value="{{ $level['id'] }}">
                                <span class="c-indicator c-indicator-warning"></span>
                                {{ $level['name'] }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-group row @foreach(range(0, 10) as $index) @if($errors->has('gallery_photos.' . $index))has-danger @break @endif @endforeach">
                    <label class="col-sm-3 form-control-label">Upload Gallery Images</label>
                    <div class="col-sm-9 m-t-5">
                        <div class="input-group m-b-10">
                            <label class="input-group-btn">
                                    <span class="btn btn-primary">
                                    Upload photos
                                        <input type="file" name="gallery_photos[]" multiple="multiple"
                                               accept="image/jpeg,image/png,image/gif" id="gallery_photos" style="display: none;">
                                    </span>
                            </label>
                            <input type="text" class="form-control  @foreach(range(0, 10) as $index) @if($errors->has('gallery_photos.' . $index))form-control-danger @break @endif @endforeach" readonly="">
                        </div>
                        @foreach(range(0, 10) as $index)
                            @if($errors->has('gallery_photos.' . $index))
                                <small class="text-danger">{{ $errors->first('gallery_photos.' . $index) }}</small>
                                @break
                            @endif
                        @endforeach
                        @if($coach_levl->gallery->count() > 0)
                            <input type="hidden" name="deleted_gallery_items" id="deleted_gallery_items"
                                   autocomplete="off">
                            <div class="m-t-10">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <label>Your current gallery:</label>
                                    </div>
                                </div>
                                <div class="row">
                                    @foreach($coach_levl->gallery as $gallery_item)
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
                                                  data-id="{{ $gallery_item->id }}">delete</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
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

                <div class="form-group row @foreach(range(0, 10) as $index) @if($errors->has('video') || $errors->has('gallery_video.' . $index))has-danger @break @endif @endforeach">
                    <label class="col-sm-3 form-control-label">Upload Gallery Video</label>
                    <div class="col-sm-9 m-t-5">
                        <input type="text" class="form-control m-b-15 {{ $errors->has('video') ? ' form-control-danger' : '' }}"
                               name="video" autocomplete="off" placeholder="Youtube video url" value="{{ old('video', '') }}">
                        @if($errors->has('video'))
                            <small class="text-danger m-b-20">{{ $errors->first('video') }}</small>
                        @endif
                        <div id="upload_container_video">
                            <div class="input-group m-b-10">
                                <label class="input-group-btn">
                                    <span class="btn btn-primary">
                                    Upload video
                                        <input type="file" name="gallery_video" accept="video/avi,video/mpeg4,video/wmv,video/mp4"
                                               id="gallery_video" style="display: none;">
                                    </span>
                                </label>
                                <input type="text" class="form-control  @if($errors->has('gallery_video'))form-control-danger @endif " readonly="">
                            </div>
                        </div>
                        @if($errors->has('gallery_video'))
                            <small class="text-danger">{{ $errors->first('gallery_video') }}</small>
                        @endif
                    </div>
                </div>

                <h4 class="m-b-20">Contact</h4>

                <div class="form-group row {{ $errors->has('contact_email') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="contact_email">Contact email</label>
                    <div class="col-sm-9">
                        <input type="email" maxlength="254" class="form-control {{ $errors->has('contact_email') ? ' form-control-danger' : '' }}" name="contact_email" autocomplete="off"
                               value="{{ !empty(old('contact_email')) ? old('contact_email') : $coach['contact_email'] }}">
                        @if($errors->has('contact_email'))
                            <small class="text-danger">{{ $errors->first('contact_email') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('phone') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label">Phone</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="phone" autocomplete="off"
                               value="{{ !empty(old('phone')) ? old('phone') : $coach['phone'] }}">
                        @if($errors->has('phone'))
                            <small class="text-danger">{{ $errors->first('phone') }}</small>
                        @endif
                    </div>
                </div>
                <div class="form-group row {{ $errors->has('wevsites') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label">Website</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control {{ $errors->has('wevsites') ? ' form-control-danger' : '' }}"
                               name="wevsites" autocomplete="off" value="{{ !empty(old('wevsites')) ? old('wevsites') : $coach['wevsites'] }}">
                        @if($errors->has('wevsites'))
                            <small class="text-danger">{{ $errors->first('wevsites') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('facebook_link') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="facebook_link">Facebook link</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control {{ $errors->has('facebook_link') ? ' form-control-danger' : '' }}"
                               name="facebook_link" value="{{ !empty(old('facebook_link')) ? old('facebook_link') : $coach['facebook_link'] }}">
                        @if($errors->has('facebook_link'))
                            <small class="text-danger">{{ $errors->first('facebook_link') }}</small>
                        @endif
                    </div>
                </div>
                <div class="form-group row {{ $errors->has('instagram_link') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="instagram_link">Instagram link</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control {{ $errors->has('instagram_link') ? ' form-control-danger' : '' }}"
                               name="instagram_link" value="{{ !empty(old('instagram_link')) ? old('instagram_link') : $coach['instagram_link'] }}">
                        @if($errors->has('instagram_link'))
                            <small class="text-danger">{{ $errors->first('instagram_link') }}</small>
                        @endif
                    </div>
                </div>
                <div class="form-group row {{ $errors->has('other_site_spec') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="other_site_spec">Other Blog sites</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control {{ $errors->has('other_site_spec') ? ' form-control-danger' : '' }}"
                               name="other_site_spec" value="{{ !empty(old('other_site_spec')) ? old('other_site_spec') : $coach['other_site_spec'] }}" autocomplete="off">
                        @if($errors->has('other_site_spec'))
                            <small class="text-danger">{{ $errors->first('other_site_spec') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('coachs_site') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="coachs_site">Links to Coach's site</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control {{ $errors->has('coachs_site') ? ' form-control-danger' : '' }}"
                               name="coachs_site" value="{{ !empty(old('coachs_site')) ? old('coachs_site') : $coach['coachs_site'] }}" autocomplete="off">
                        @if($errors->has('coachs_site'))
                            <small class="text-danger">{{ $errors->first('coachs_site') }}</small>
                        @endif
                    </div>
                </div>

                <h4 class="m-b-20">Vacation time</h4>
                <div class="form-group row {{ $errors->has('vacation_start') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label">Start date</label>
                    <div class="col-sm-9">
                        <input id="vacation_start" type="text" class="form-control {{ $errors->has('vacation_start') ? ' form-control-danger' : '' }}"
                               name="vacation_start" autocomplete="off"
                               value="@if(!empty(old('vacation_start'))){{ old('vacation_start') }}@elseif($coach['vacation_start'] != '0000-00-00'){{ $coach['vacation_start'] }}@endif">
                        @if($errors->has('vacation_start'))
                            <small class="text-danger">{{ $errors->first('vacation_start') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('vacation_end') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label">End Date</label>
                    <div class="col-sm-9">
                        <input id="vacation_end" type="text" class="form-control {{ $errors->has('vacation_end') ? ' form-control-danger' : '' }}"
                               name="vacation_end" autocomplete="off"
                               value="@if(!empty(old('vacation_end'))){{ old('vacation_end') }}@elseif($coach['vacation_end'] != '0000-00-00'){{ $coach['vacation_end'] }}@endif">
                        @if($errors->has('vacation_end'))
                            <small class="text-danger">{{ $errors->first('vacation_end') }}</small>
                        @endif
                    </div>
                </div>

                <h4 class="m-b-20">Pricing</h4>
                <div class="form-group row {{ $errors->has('price_summary') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label">Summary package</label>
                    <div class="col-sm-9">
                        <input id="vacation_start" type="text" class="form-control {{ $errors->has('price_summary') ? ' form-control-danger' : '' }}"
                               name="price_summary" autocomplete="off"
                               value="{{ !empty(old('price_summary')) ? old('price_summary') : $coach['price_summary'] }}">
                        @if($errors->has('price_summary'))
                            <small class="text-danger">{{ $errors->first('price_summary') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('price_detailed') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label">Detailed package</label>
                    <div class="col-sm-9">
                        <input id="vacation_end" type="text" class="form-control {{ $errors->has('price_detailed') ? ' form-control-danger' : '' }}"
                               name="price_detailed" autocomplete="off"
                               value="{{ !empty(old('price_detailed')) ? old('price_detailed') : $coach['price_detailed'] }}">
                        @if($errors->has('price_detailed'))
                            <small class="text-danger">{{ $errors->first('price_detailed') }}</small>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-warning m-r-10 m-b-10">
                            <input type="hidden" name="id" value="{{ $coach['id'] }}">
                            <i class="btn-icon fa fa-check"></i>Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/sweetalert2/5.3.4/sweetalert2.min.js"></script>
    <script src="/assets/js/select2-v4.0.4.full.min.js"></script>
    <script src="/vendor/jildertmiedema/laravel-plupload/js/plupload.full.min.js"></script>
    <script type="text/javascript">
        var successMsg = "{{ session()->has('success') ? session('success') : '' }}";
        var gallery_size = {{ $coach_levl->gallery()->count() }};
        var user_genres = {{ $activity_types[0]['id'] }};

        function genresArrayToTemplateList(data) {
            if(!data) return '';
            var template = '';
            for(var i=0; i < data.length; i++) {
                var checked = user_genres !== -1 ? 'selected' : '';
                template += '<option  value="' + data[i].id + '" ' + checked + ' >' + data[i].name + '</option>';
            }
            return template;
        }

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

        var popups = [
            $('#document_upload'),
            $('#avatar_upload'),
            $('#gallery_photos'),
            $('#gallery_video')
        ];

        $.each(popups, function (key,val) {
            $(function () {
                // We can attach the `fileselect` event to all file inputs on the page
                $(document).on('change', val, function () {
                    var input = val,
                        numFiles = input.get(0).files ? input.get(0).files.length : 1,
                        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                    input.trigger('fileselect', [numFiles, label]);
                });
                // We can watch for our custom `fileselect` event like this
                $(document).ready(function () {
                    $(val).on('fileselect', function (event, numFiles, label) {
                        var input = val.parents('.input-group').find(':text'),
                            log = numFiles > 1 ? numFiles + ' files selected' : label;
                        if (input.length) {
                            input.val(log);
                        } else {
                            if (log) alert(log);
                        }
                    });
                });
            });
        });

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

        $('[data-toggle="tooltip"]').tooltip();
    </script>
@endsection
