@extends('layouts.app')

@section('content')

    @include('profile.header')

    <div class="row m-b-20">
        <div class="col-xs-12 col-lg-8">
            <div class="bs-nav-tabs nav-tabs-warning">
                <ul class="nav nav-tabs nav-animated-border-from-left">
                    <li class="nav-item">
                        <a class="nav-link active">View Profile</a>
                    </li>
                    @if(Auth::user()->id == $user->id)
                        <li class="nav-item">
                            <a class="nav-link" href="/profile/{{Auth::user()->id}}/edit">Edit Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/profile/account_settings">Account settings</a>
                        </li>
                    @endif
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane in active" id="nav-tabs-0-1">
                        <div class="row zoom m-b-20">
                            <div class="col-xs-12">
                                <div class="m-b-40" style="width: 728px">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            First name: {{$user->first_name}}
                                        </li>
                                        <li class="list-group-item">
                                            Last name: {{$user->last_name}}
                                        </li>

                                        <li class="list-group-item">
                                            Activities:  {{(count($user->activity_types)) ? implode(", ", $user->activity_types->lists("name")->all()) : ''}}
                                        </li>
                                        <li class="list-group-item">
                                            @php
                                                $activity_genres = "";
                                                if($user->activity_genres->count() > 0) {
                                                    $activity_genres = implode(", ", $user->activity_genres->lists("name")->all());
                                                }
                                            @endphp
                                            Genres: {{ $activity_genres }}
                                        </li>
                                        <li class="list-group-item">
                                            @php
                                                $performance_levels = "";
                                                if($user->performance_levels->count() > 0) {
                                                    $performance_levels = implode(", ", $user->performance_levels->lists("name")->all());
                                                }
                                            @endphp
                                            Level/s: {{ $performance_levels }}
                                        </li>
                                        <li class="list-group-item">
                                            Gender: @if($user->gender == "famale") Female @elseif($user->gender == "male") Male  @endif
                                        </li>
                                        <li class="list-group-item">
                                            Birth date: {{$user->birthday}}
                                        </li>
                                        {{--<li class="list-group-item">
                                            Languages: {{$user->languages}}
                                        </li>--}}
                                        <li class="list-group-item">
                                            Lives in: {{$user->location}}, {{$user->location_state}}
                                        </li>
                                        <li class="list-group-item">
                                            About me:  {{ strip_tags($user->about) }}
                                        </li>
                                        <li class="list-group-item">
                                            Role: @if($user->role == 1) {{'User'}} @elseif ($user->role == 2) {{'Coach'}} @elseif ($user->role == 3) {{ 'Administrator' }}@endif
                                        </li>
                                        <li class="list-group-item">
                                            Email: {{$user->email}}
                                        </li>
                                        <li class="list-group-item">
                                            Phone:  {{$user->phone}}
                                        </li>
                                        <li class="list-group-item">
                                            Website:  {{$user->wevsites}}
                                        </li>
                                        <li class="list-group-item">
                                            Social links:  {{$user->social_links}}
                                        </li>
                                    </ul>
                                </div>
                            </div>
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
@section('js')
    <script type="text/javascript">
        $(window).on('load',function(){
            @if(session()->has('site_password'))
               $('#myModal').modal('show');
            @endif
        });
    </script>
@endsection
@endsection