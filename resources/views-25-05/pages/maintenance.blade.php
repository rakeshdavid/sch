<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Showcase</title>
        <link rel="stylesheet" href="{{url('/')}}/assets/marino/styles/components/bootstrap.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.1/animate.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
        <link rel="stylesheet" href="{{url('/')}}/assets/marino/styles/main.css">
    </head>
    <body style="background: url('/images/maintenance.png') no-repeat center center fixed;background-size: cover;">
        <div class="container-fluid">
            <div class="maintenance-logo" style="width: 100%; color: #fff; font-family: 'Montserrat', sans-serif; text-align: center;">
                <a href="/" style=" display: block; float: left;">
                    <img class="main-logo-img" src="/images/logo-black.png" width="90" height="102" style="margin: 10px 0 0 50px;">
                </a>
                <span style="font-size: 70px;">Wait... something went wrong.</span>

            </div>
            <div class="maintenance-bottom" style="width: 100%; color: #fff; text-align: center;position: absolute; bottom: 50px;">
                <span style="font-size: 32px;">
                    @if ($platform == 'coach')
                    Somebody get me my cape, i'm on it - Buddy
                    @elseif ($platform == 'user')
                    Somebody get me my cape, i'm on it - Buddy
                    @else
                    {{ $platform }} is under maintenance.
                    @endif
                </span>
                <br />
                <span style="font-size: 20px;">PS - He's great with sniffing out problems</span>
            </div>
        </div>
    </body>
</html>