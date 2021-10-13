<!doctype html> 
<html lang="en"> 
    <head> 
        <meta charset="utf-8"> 
        <title>Showcase</title> 
        <meta name="description" content="Showcase"> 
        <meta name="viewport" content="width=device-width,initial-scale=1"> 
        <link rel="shortcut icon" href="/favicon.ico">
        <!--[if IE]>

        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>

        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

        <![endif]-->
        <!-- global stylesheets --> 
        <link rel="stylesheet" href="/assets/marino/styles/components/bootstrap.css"> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css"> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.1/animate.min.css"> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/1.0.0/css/flag-icon.min.css" rel="stylesheet" type="text/css"> 
        <link rel="stylesheet" href="/assets/marino/styles/main.css"> 
        <style>
              .container-fluid {
                position: relative;
                top: 10%;
                z-index: 2;
                margin: 0 auto;
                max-width: 720px;
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
        </style>
    </head> 
    <body data-layout="empty-layout" data-palette="palette-0" data-direction="none"> 
        <div class="container-fluid"> 
            <div class="row"> 
                <div class="col-xs-12"> 
                    <div class="login-page text-center animated fadeIn delay-2000"> 
                        <!--<h1> Account login </h1>--> 
                        <div class="row"> 
                            <div class="col-md-offset-2 col-md-8"> 
                                <img style="width: 150px" src="/images/logo-black.png">
                            </div>
                        </div>
                        @if($roleNameBySubDomain == App\Models\User::getUserRoleName())
                            <div class="row">
                                <div class="col-md-offset-4 col-md-8 m-t-20">
                                    <div class="row">
                                        <div class="col-xs-12 col-md-6 m-b-10">
                                            <a href="/register" class="btn btn-success m-r-10 m-b-10 centered">Create Account</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-offset-4 col-md-8 m-t-20">
                                <div class="row">
                                    <div class="col-xs-12 col-md-6 m-b-10">
                                        <a href="/login" class="btn btn-primary m-r-10 m-b-10 centered">Login</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($roleNameBySubDomain == App\Models\User::getUserRoleName())
                            <div class="row">
                                <div class="col-md-offset-4 col-md-8">
                                    <div class="row">
                                        <div class="col-xs-12 col-md-6">
                                            <a href="/auth/facebook" class="btn btn-facebook m-r-5 centered">
                                                <i class="btn-icon fa fa-facebook"></i> Login with Facebook
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <p class="copyright text-sm m-t-20">&copy; Copyright {{ date("Y") }}</p>
                    </div>
                </div>
            </div>
        </div>
        <video id="my-video" class="video" autoplay muted loop>
            <source src="/videos/loopn_{{rand(1, 4)}}.mp4" type="video/mp4">
        </video>
    </body>
     <!-- global scripts --> 
    <script src="/assets/marino/bower_components/jquery/dist/jquery.js"></script> 
    <script src="/assets/marino/bower_components/tether/dist/js/tether.js"></script> 
    <script src="/assets/marino/bower_components/bootstrap/dist/js/bootstrap.js"></script> 
    <script src="/assets/marino/bower_components/PACE/pace.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.0.0/lodash.min.js"></script> 
    <script src="/assets/marino/scripts/components/jquery-fullscreen/jquery.fullscreen-min.js"></script> 
    <script src="/assets/marino/bower_components/jquery-storage-api/jquery.storageapi.min.js"></script> 
    <script src="/assets/marino/bower_components/wow/dist/wow.min.js"></script> 
    <script src="/assets/marino/scripts/functions.js"></script> 
    <script src="/assets/marino/scripts/colors.js"></script> 
    <script src="/assets/marino/scripts/left-sidebar.js"></script> 
    <script src="/assets/marino/scripts/navbar.js"></script> 
    <script src="/assets/marino/scripts/horizontal-navigation-1.js"></script> 
    <script src="/assets/marino/scripts/horizontal-navigation-2.js"></script> 
    <script src="/assets/marino/scripts/horizontal-navigation-3.js"></script> 
    <script src="/assets/marino/scripts/main.js"></script> 
    <script src="/assets/marino/scripts/components/floating-labels.js"></script> 
    <script src="/assets/marino/scripts/pages-login.js"></script>
</html>