@extends('layouts.auth.main')
@section('title')
    Password reset
@endsection
@section('meta')
    <meta name="description" content="Showcasehub,reset password">
@endsection
@section('css')
    <style type="text/css">
        [data-palette="palette-4"] .form-group.floating-labels input[type="text"], [data-palette="palette-4"]
        .form-group.floating-labels input[type="password"], [data-palette="palette-4"]
        .form-group.floating-labels input[type="email"] {
            color: #d9d9d9 !important; /* inputs color fix */
        }
    </style>
@endsection

@section('content')
    <div class="fullsize-background-image-1">
        <img src="/assets/marino/assets/backgrounds/bg2.png" />
    </div>
    <div class="container-fluid m-t-50">
        <div class="row">
            <div class="col-xs-12">
                <div class="login-page text-center animated fadeIn delay-2000">
                    <div class="row">
                        <div class="col-xs-offset-2 col-xs-8 col-sm-offset-3 col-sm-6 col-md-offset-3 col-md-6 col-lg-offset-4 col-lg-4">
                            <form name="form" novalidate class="form-bg-cr" role="form" method="POST" action="{{ url('/password/email') }}">
                                <h1 class="m-t-0">Reset password</h1>
                                <h4>Please enter your email address</h4>
                                {{ csrf_field() }}
                                <div class="row">
                                    @if (session('status'))
                                        <div class="alert alert-success-outline animated fadeIn" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span> <span class="sr-only">Close</span>
                                            </button> <strong>Success!</strong> {{ session('status') }}
                                        </div>
                                    @endif
                                    <div class="col-xs-12">
                                        <div class="form-group floating-labels{{ $errors->has('email') ? ' has-error' : ''}}">
                                            <label for="email">Email</label>
                                            <input id="email" style="color: #d9d9d9 !important;" autocomplete="off"
                                                   type="email" name="email" value="{{ old('email') }}">
                                            <p class="error-block"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row buttons">
                                    <div class="col-xs-12 col-md-6">
                                        <input type="submit" class="btn-login btn btn-lg btn-info btn-block m-b-20" value="Reset">
                                    </div>
                                    <div class="col-xs-12 col-md-6"> <a href="{{ url('/login') }}" class="btn btn-lg btn-danger btn-block m-b-20">Login</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <p class="copyright text-sm">&copy; Copyright {{ date('Y') }}</p>
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