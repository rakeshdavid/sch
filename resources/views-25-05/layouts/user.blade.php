
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Showcase Hub</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/assets/css/font-awesome.css">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/custom.css">
    <link rel="stylesheet" href="/assets/css/bootstrap-tagsinput.css">
    <link rel="stylesheet" href="/assets/css/bootstrap-select.min.CSS">
    <link rel="stylesheet" href="/assets/css/addons.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,700&display=swap" rel="stylesheet">
    @yield('css')
</head>

<body>

    <section class="hub_content-wrapper">
        <header class="header">
            <div class="container-fluid">
                <div class="header-content">
                    <div class="logo">
                        <a href="#"><img src="/assets/img/logo.png" alt="" class="img-fluid"></a>
                    </div>
                   
                            <div id="nav-icon3">
                              <span></span>
                              <span></span>
                              <span></span>
                              <span></span>
                            </div>
                       
                    <div class="hub-admin">
                        <ul class="user-dropdown">
                            <li class="user-avatar">
                                <a href="/profile"><span>{{Auth::user()->first_name}}</span>
                                @if(Auth::user()->avatar)
                                    <img src="{{Auth::user()->avatar}}" style="width: 42px;border-radius: 50%;margin-left: 10px;">
                                @endif
                                </a>
                            </li>
                            <li>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="setting"><i class="fa fa-cog"></i></span></a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ url('change-password') }}">Change Password</a>
                                    <div class="faq">
                                        <a href="#" class="dropdown-item"> Help</a>
                                        <a href="#" class="dropdown-item"><span> FAQs</span></a>
                                        <a href="#" class="dropdown-item"><span> Terms & Conditions</span></a>
                                    </div>
                                    <a class="dropdown-item" href="/auth/logout">Log Out</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
        <div class="hub-sidebar">
            <div class="hub_nav_menu">
                <a href="{{ url('upload-new-video') }}" class="btn btn-danger upload-video">UPLOAD A NEW VIDEO</a>
                <ul class="navbar-nav">
                    <li class="nav-item {{ Request::segment(1) =='video' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('video') }}">My Videos</a>
                    </li>
                    <li class="nav-item {{ Request::segment(1) =='my-reviews' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('my-reviews') }}">My Reviews</a>
                    </li>
                    <li class="nav-item {{ Request::segment(1) =='coaches' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('coaches') }}">Coaches</a>
                    </li>
                    <li class="nav-item {{ Request::segment(1) =='auditions' ? 'active' : '' }} {{ Request::segment(1) =='filter-auditions' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('auditions') }}">Auditions</a>
                    </li>
                    <li class="nav-item {{ Request::segment(1) =='challenges' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('challenges') }}">Challenges</a>
                    </li>
                </ul>
                <div class="bottom-menu-wrap">
                    <a href="#" class="app-icon"><img src="/assets/img/app-download.png" class="img-fluid"></a>
                    <ul class="bottom-menus">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Support</a></li>
                        <li><a href="#">Legal</a></li>
                    </ul>
                    <span class="version">Version 1.1</span>
                </div>
            </div>
        </div>
          @yield('content')
        <!-- <div class="main-content">
            <div class="container-fluid">
              
            </div>
        </div> -->
    </section>
    @yield('modal-right')

 
    <!-- Optional JavaScript -->
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/popper.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/bootstrap-tagsinput.min.js"></script>
    <script src="/assets/js/bootstrap-select.min.js"></script>
    <script src="/assets/js/bootstrap-datepicker.min.js"></script>
    <script src="/assets/js/fontawesome-all.js"></script>
    <script src="/assets/js/custom.js"></script>
    @yield('js')
</body>

</html>