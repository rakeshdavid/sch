@extends('layouts.auth.main')
@section('title')
    Login
@endsection
@section('meta')
    <meta name="description" content="Showcasehub, login">
@endsection
@section('css')
    <style type="text/css">
        [data-palette="palette-4"] .form-group.floating-labels input[type="text"],
        [data-palette="palette-4"] .form-group.floating-labels input[type="password"],
        [data-palette="palette-4"] .form-group.floating-labels input[type="email"] {
            color: #d9d9d9 !important;
        }
        [data-palette="palette-4"] a:hover,
        [data-palette="palette-4"] a:focus,
        [data-palette="palette-4"] a:active {
            color: #d9d9d9 !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="login-page text-center animated fadeIn delay-2000 m-t-50">

                    <div class="row">
                        <div class="form-bg-cr col-xs-offset-2 col-xs-8 col-sm-offset-3 col-sm-6 col-md-offset-3 col-md-6 col-lg-offset-4 col-lg-4">
                            <h1 class="m-t-20">Login as admin</h1>
                            <h4>Please enter your email address and password to login</h4>
                            <form class="" name="form" novalidate class="form" role="form" method="POST" action="{{ url('/login') }}"
                                  autocomplete="off">
                                {!! csrf_field() !!}
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group floating-labels">
                                            <label for="email">Email</label>
                                            <input id="email" autocomplete="off" type="email" name="email" value="{{ old('email') }}">
                                            <p class="error-block">{{ $errors->has('email') ? $errors->first('email') : '' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row m-b-20">
                                    <div class="col-xs-12">
                                        <div class="form-group floating-labels">
                                            <label for="password">Password</label>
                                            <input id="password" autocomplete="off" type="password" name="password">
                                            <p class="error-block"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row buttons">
                                    <div class="col-xs-12 col-md-12">
                                        <input type="submit" class="btn-login btn btn-lg btn-info btn-block" value="Login">
                                    </div>
                                </div>
                            </form>
                            <p class="text-md m-t-0 m-b-10">
                                <a style="color: #f5f5f5 !important;" href="{{url('/password/reset')}}"><u>Reset password?</u></a>
                            </p>
                        </div>
                    </div>
                    <p class="copyright text-sm">&copy; Copyright {{ date("Y") }}</p>
                </div>
            </div>
        </div>
    </div>
    <style>
        .container-fluid {
            position: relative;
            z-index: 2;
            margin: 0 auto;
            max-width: 1280px;
            text-align: center;
        }
        .video {
            position: fixed;
            top: 50%; left: 50%;
            z-index: 1;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            transform: translate(-50%, -50%);
        }
        html [data-palette="palette-4"][data-layout="fullsize-background-image"] {
            background: #fff !important;
        }

        .form-bg-cr{
            background-color: #666267;
            opacity: 0.8!Important;
            padding: 15px;
            border-radius: 5px;
        }

    </style>
    <video id="my-video" class="video" autoplay muted loop>
        <source src="/videos/loopn_{{rand(1, 4)}}.mp4" type="video/mp4">
    </video>
@endsection

@section('js')
    <script type="text/javascript">
        (function() {
            'use strict';
            $(function() {
                var config = $.localStorage.get('config');
                $('body').attr('data-layout', 'fullsize-background-image');
                $('body').attr('data-palette', config.theme);
                $('body').attr('data-direction', config.direction);
            });
        })();
    </script>
@endsection