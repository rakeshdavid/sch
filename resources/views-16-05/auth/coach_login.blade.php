@extends('layouts.auth.main')
@section('title')
    Login
@endsection
@section('meta')
    <meta name="description" content="Showcasehub, login">
@endsection
@section('css')
    <style type="text/css">
        [data-palette="palette-4"] h1 {
            font-size: 32px;
        }
        [data-palette="palette-4"] .form-group.floating-labels input[type="text"],
        [data-palette="palette-4"] .form-group.floating-labels input[type="password"],
        [data-palette="palette-4"] .form-group.floating-labels input[type="email"] {
            color: #d9d9d9 !important;
        }
        [data-palette="palette-4"] a {
            color: #f5f5f5!important;
            text-decoration: underline!important;
        }
        [data-palette="palette-4"] a:hover,
        [data-palette="palette-4"] a:focus,
        [data-palette="palette-4"] a:active {
            color: #d9d9d9 !important;
            text-decoration: none!important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="login-page text-center animated fadeIn delay-2000">
                    <h1 style="color: #f5f5f5 !important;">Coach login page</h1>
                    <div class="row">
                        <div class="col-md-offset-2 col-md-8">
                            <img style="width: 40%" src="/images/logo-black.png">
                        </div>
                    </div>
                    <div class="row m-20">
                        <div class="col-xs-offset-2 col-xs-8 col-sm-offset-3 col-sm-6 col-md-offset-3 col-md-6 col-lg-offset-4 col-lg-4">
                            <form class="form-bg-cr" name="form" novalidate class="form" role="form" method="POST" action="{{ url('/login') }}"
                                autocomplete="off">
                                {!! csrf_field() !!}
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group floating-labels{{ $errors->has('email') ? ' has-error' :
                                         old('email') ? ' has-focus' : ''}}">
                                            <label for="email">Email</label>
                                            <input id="email" autocomplete="off" type="email" name="email" value="{{ old('email') }}">
                                            <p class="error-block">{{ $errors->has('email') ? $errors->first('email') : '' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
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

                                <p style="margin-bottom: 25px;" class="text-md">
                                    <a style="color: #f5f5f5 !important;" href="{{url('/password/reset')}}">Reset password?</a>
                                </p>
                                <p style="color: #f5f5f5 !important;margin-bottom: 0;" class="text-md">Want to be a coach? Contact us
                                    <a style="color: #f5f5f5 !important;" href="mailto:support@showcasehub.com">
                                        support@showcasehub.com
                                    </a>
                                </p>

                            </form>
                        </div>
                    </div>
                    <p class="copyright text-sm">&copy; Copyright {{ date("Y") }}</p>

                    <div class="col-xs-offset-2 col-xs-4 col-sm-offset-8 col-sm-4 col-md-offset-10 col-md-2 col-lg-offset-10 col-lg-2">
                        <p class="text-sm form-bg-cr"><a href="{{ env('USER_PLATFORM_LINK') }}" style="color: #f5f5f5 !important;">Are you a dancer?</a></p>
                    </div>
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