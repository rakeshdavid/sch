@extends('layouts.auth.main')
@section('title')
Create account
@endsection
@section('meta')
    <meta name="description" content="Showcasehub,Create account">
@endsection
@section('css')
    <style type="text/css">
        [data-palette="palette-4"] .form-group.floating-labels input[type="text"], [data-palette="palette-4"]
        .form-group.floating-labels input[type="password"], [data-palette="palette-4"]
        .form-group.floating-labels input[type="email"] {
            color: #d9d9d9 !important; /* inputs color fix */
        }
        a.agree-links {
            color:#FFF !important;
            text-decoration:underline !important;
        }
        a.agree-links:hover {
            color:#FFF !important;
            text-decoration: none!important;
        }
    </style>
@endsection

@section('content')
{{--<div class="fullsize-background-image-1">
    <img src="/assets/marino/assets/backgrounds/bg11.png"/>
</div>--}}
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">

            <div class="create-account-page text-center animated fadeIn delay-2000">

                <h1>
                    Create account
                </h1>
                <h4>
                    Please fill all fields correctly to create your account
                </h4>

                <div class="row">
                    <div class="col-xs-offset-2 col-xs-8 col-sm-offset-3 col-sm-6 col-md-offset-3 col-md-6 col-lg-offset-4 col-lg-4">
                        <form class="form-bg-cr" name="form" novalidate role="form" method="POST" action="{{ url('/register') }}">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-xs-12 col-xl-6">
                                    <div class="form-group floating-labels{{old('first_name') ? ' has-focus' : ''}}
                                    {{$errors->has('first_name') ? ' has-error' : '' }}">
                                        <label for="first_name">First Name</label>
                                        <input id="first_name" autocomplete="off" type="text"  class="white-input"
                                               name="first_name" value="{{ old('first_name') }}">
                                        <p class="error-block"></p>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-xl-6">
                                    <div class="form-group floating-labels{{old('last_name') ? ' has-focus' : ''}}
                                    {{$errors->has('last_name') ? ' has-error' : '' }}">
                                        <label for="last_name">Last Name</label>
                                        <input id="last_name" autocomplete="off" type="text" name="last_name"
                                               value="{{ old('last_name') }}">
                                        <p class="error-block"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-xl-6">
                                    <div class="form-group floating-labels{{old('phone') ? ' has-focus' : ''}}
                                    {{$errors->has('phone') ? ' has-error' : '' }}">
                                        <label for="phone">Phone</label>
                                        <input id="phone" autocomplete="off" type="text" name="phone"
                                               value="{{ old('phone') }}">
                                        <p class="error-block"></p>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-xl-6">
                                    <div class="form-group floating-labels{{old('email') ? ' has-focus' : ''}}
                                    {{$errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email">Email</label>
                                        <input id="email" autocomplete="off" type="email" name="email"
                                               value="{{ old('email') }}">
                                        <p class="error-block"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-xl-6">
                                    <div class="form-group floating-labels{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <label for="password">Password</label>
                                        <input id="password" autocomplete="off" type="password" name="password">
                                        <p class="error-block"></p>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-xl-6">
                                    <div class="form-group floating-labels{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                        <label for="password_confirmation">Confirm Password</label>
                                        <input id="password_confirmation" autocomplete="off" type="password"
                                               name="password_confirmation">
                                        <p class="error-block"></p>
                                    </div>
                                </div>
                            </div>

                            <br/>

                            <p class="text-center">By clicking on create account, you agree to our
                                <a class="agree-links" href="http://www.showcasehub.com/terms.html" target="_blank">terms of service</a> and
                                that you have read our <a class="agree-links" href="http://www.showcasehub.com/terms.html" target="_blank">privacy policy</a>,
                                including our <a class="agree-links" href="http://www.showcasehub.com/terms.html" target="_blank">cookie use policy</a></p>

                            <fieldset class="form-group">
                                <label class="c-input c-checkbox" style="color:#f5f5f5 !important">
                                    <input type="checkbox" id="terms_accept" data-error="You should really check this" required="" autocomplete="off">
                                    <span class="c-indicator c-indicator-warning"></span> Accept terms and conditions </label>
                                <div id="terms_accept_error" class="help-block with-errors" style="color: #d9534f !important; visibility: hidden">You must accept terms and conditions!</div>
                            </fieldset>


                            <div class="row buttons">
                                <div class="col-xs-12">
                                    <input id="submit_create_account" type="submit" class="btn btn-lg btn-info btn-block no-border"
                                           value="Create account">
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

    $(document).ready(function () {
        $('#submit_create_account').on('click', function (e) {
            var checked = $('#terms_accept').prop("checked");
            if(!checked){
                $('#terms_accept_error').css('visibility', 'visible');
                e.preventDefault();
            } else {
                $('#terms_accept_error').css('visibility', 'hidden');
            }
            $('#terms_accept').on('click', function () {
                var checked = $(this).prop("checked");
                if(checked){
                    $('#terms_accept_error').css('visibility', 'hidden');
                }
            });
        });
    });

    (function () {
        'use strict';

        $(function () {
            var config = $.localStorage.get('config');
            $('body').attr('data-layout', 'fullsize-background-image');
            $('body').attr('data-palette', config.theme);
            $('body').attr('data-direction', config.direction);
        });
    })();
</script>
@endsection
