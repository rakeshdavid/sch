<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Showcase</title>

        <!-- Bootstrap -->
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    @if(Auth::user())
                        <ul class="nav navbar-nav">
                          <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                              <li><a href="/video/create">Add video</a></li>
                              <li><a href="/search">Search video</a></li>
                              <li role="separator" class="divider"></li>
                              <li><a href="/profile">My profile</a></li>
                              <li><a href="/profile/{{Auth::user()->id}}/edit">Edit profile</a></li>
                              <li><a href="/video">My videos</a></li>
                              <li><a href="/proposal">My reviews</a></li>
                              <li role="separator" class="divider"></li>
                              <li><a href="#">One more separated link</a></li>
                            </ul>
                          </li>
                        </ul>
                    @endif
                    <ul class="nav navbar-nav navbar-right">
                        @if(!Auth::user())
                            <li><a class="btn btn-info" href="/auth/facebook" role="button">Facebook login</a></li>
                        @else
                            <p class="navbar-text">{{Auth::user()->first_name}} {{Auth::user()->last_name}}</p>
                            <p class="navbar-text">{{Auth::user()->email}}</p>
                            <li><a class="btn btn-info" href="/auth/logout" role="button">Logout</a></li>
                        @endif
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>

        @yield('content')
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="{{ asset('/assets/js/bootstrap.min.js') }}"></script>
</html>