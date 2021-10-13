@extends('layouts.app')
@section('css')
    <link type="text/css" rel="stylesheet" href="/assets/js/sweetalert/sweet-alert.css">
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
                        <a class="nav-link" href="/profile/{{ $user->id }}/edit">Edit Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/profile/account_settings">Account settings</a>
                    </li>
                </ul>
                <div class="tab-content">
                    @if(session()->has('success_password'))
                        <div class="alert alert-success-outline animated fadeIn m-t-20" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">×</span> <span class="sr-only">Close</span></button>
                            <strong>Success!</strong> {{ session()->get('success_password') }}
                        </div>
                    @endif
                    <div role="tabpanel" class="tab-pane in active" id="nav-tabs-0-3">
                        <div class="p-t-20">

                            @if($user->isCoach())
                                {{--<h4 class="m-b-20">Payment Details</h4>--}}
                                <h4 class="m-b-20">Stripe</h4>
                                @if( isset($user->stripe_connection->id) )
                                    <div class="p-b-20">
                                        <h3 class="f-s-15 uppercase color-danger m-t-15">Successfully added!</h3>
                                        <p>
                                            You can disconnect from Showcase platform by going to Stripe control panel.
                                            <br>
                                            Disconnecting from the platform may take several minutes.
                                            <br>
                                            <a target="_blank" href="https://dashboard.stripe.com/account/applications"><strong>Stripe control panel</strong></a>
                                        </p>
                                    </div>
                                @else
                                    <div class="p-b-20">
                                        <a class="btn btn-danger" href="https://connect.stripe.com/oauth/authorize?response_type=code&client_id={{env('STRIPE_CLIENT_ID')}}&scope=read_write">
                                            Connect to showcase-hub stripe platform
                                        </a>
                                    </div>
                                @endif
                            @endif

                                <form action="/profile/account_settings/new_password" method="POST"
                                      class="form-horizontal">
                                    <h4 class="m-b-20">Password/Security Options</h4>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">
                                            Change password
                                        </label>
                                        <div class="col-sm-9">
                                            @if($errors->has('password_old'))
                                                <small class="text-danger">{{ $errors->first('password_old') }}</small>
                                            @endif
                                            <input type="password" placeholder="Old password" class="form-control m-b-20"
                                                   name="password_old" autocomplete="off">
                                            @if($errors->has('password'))
                                                <small class="text-danger">{{ $errors->first('password') }}</small>
                                            @endif
                                            <input type="password" maxlength="254" class="form-control m-b-20"
                                                   name="password" autocomplete="off" placeholder="New password">
                                            <input type="password" maxlength="254" class="form-control m-b-20"
                                                   name="password_confirmation" autocomplete="off"
                                                   placeholder="Password confirmation">
                                        </div>
                                    </div>
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
    <input type="hidden" id="success_connection" data-value="{{Session::get('success_connection')}}">
    <input type="hidden" id="error_connection" data-value="{{Session::get('error_connection')}}">
@endsection
@section('js')
    <script type="text/javascript" src="/assets/js/toastr/toastr.min.js"></script>
    <script type="text/javascript" src="/assets/js/sweetalert/sweet-alert.min.js"></script>
    <script>
        $(document).ready(function () {
            var success_payment = $('#success_connection').attr('data-value');
            var error_connection = $('#error_connection').attr('data-value');
            if(success_payment){
                swal("Success!", "Successfully added!", "success");
            }
            if(error_connection){
                swal("Error!!", "Oops, something happened. Try later.", "error");
            }
        });

    </script>
@endsection
