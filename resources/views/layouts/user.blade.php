
<!doctype html>
<html lang="en">

<head>
   <!-- Google Tag Manager -->
   <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
   new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
   j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
   'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
   })(window,document,'script','dataLayer','GTM-W3L9899');</script>
   <!-- End Google Tag Manager -->
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Showcase Hub</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/platform/css/font-awesome.css">
    <link rel="stylesheet" href="/platform/css/bootstrap.min.css">
    <link rel="stylesheet" href="/platform/css/custom.css?v=@php echo date('Y-m-d-h-i-s');@endphp">
    <link rel="stylesheet" href="/platform/css/bootstrap-tagsinput.css">
    <link rel="stylesheet" href="/platform/css/bootstrap-select.min.css">
    
    <link rel="stylesheet" href="/platform/css/addons.css">
    <link rel="stylesheet" href="/platform/css/newcss.css?v=@php echo date('Y-m-d-h-i-s');@endphp">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,700&display=swap" rel="stylesheet">
    <style type="text/css">
        .alert.alert-success {
            background-color: #e7133e;
            border-color: #e7133e;
            color: #fff;
        }
        .alert.alert-success p{
            margin-bottom: 0;
        }
    </style>
    @yield('css')
</head>

<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W3L9899"
   height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
   <!-- End Google Tag Manager (noscript) -->
   
    <section class="hub_content-wrapper {{ strpos(url()->current(), '/my-reviews') !== false ? 'fix-header-review' : '' }} {{ strpos(url()->current(), '/video') !== false ? 'fix-header-video' : '' }}">
        <header class="header">
            <div class="container-fluid">
                <div class="header-content">
                    <div class="logo header-logo">
                        <a href="#"><img src="/platform/img/logo.png" alt="" class="img-fluid"></a>
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
                                @if(Auth::user()->avatar !='')
                                        @if(Str::startsWith(Auth::user()->avatar,'http'))
                                           <div class="coach-image rounded ml-3" style="background-image:url('{{ Auth::user()->avatar }}');width:42px;height:42px;background-position: center center;background-repeat: no-repeat;background-size: cover;"></div>
                                        @else
                                            <div class="coach-image rounded ml-3" style="background-image:url('@if( ! Str::startsWith(Auth::user()->avatar,'/'))/@endif{{ Auth::user()->avatar }}');width:42px;height:42px;background-position: center center;background-repeat: no-repeat;background-size: cover;"></div>
                                        @endif
                                    @else
                                        <div class="coach-image rounded ml-3" style="background-image:url('/images/default_avatar_new.png');width:42px;height:42px;background-position: center center;background-repeat: no-repeat;background-size: cover;"></div>
                                        
                                    @endif
                                
                                </a>
                            </li>
                            <li>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="setting"><i class="fa fa-cog"></i></span></a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ url('change-password') }}">Change Password</a>
                                    <div class="faq">
                                        <a href="#" class="dropdown-item"> Help</a>
                                        <a href="https://www.showcasehub.com/faqs.html" class="dropdown-item" target="_blank"><span> FAQs</span></a>
                                        <a href="https://www.showcasehub.com/terms.html" class="dropdown-item" target="_blank"><span> Terms & Conditions</span></a>
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
                <div class="logo">
                    <a href="#"><img src="/platform/img/logo.png" alt="" class="img-fluid"></a>
                </div>
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
                    <a href="https://itunes.apple.com/us/app/showcasehub-dance/id1418444744?ls=1&mt=8" class="app-icon" target="_blank"><img src="/platform/img/app-download.png" class="img-fluid"></a>
                    <ul class="bottom-menus">
                        <li><a href="https://www.showcasehub.com/about-us.html" target="_blank">About Us</a></li>
                        <li><a href="https://www.showcasehub.com/support.html" target="_blank">Support</a></li>
                        <li><a href="https://www.showcasehub.com/legal.html" target="_blank">Legal</a></li>
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
    <script src="/platform/js/jquery.min.js"></script>
    <script src="/platform/js/popper.min.js"></script>
    <script src="/platform/js/bootstrap.min.js"></script>
    <script src="/platform/js/bootstrap-tagsinput.min.js"></script>
    <script src="/platform/js/bootstrap-select.min.js"></script>
    <script src="/platform/js/bootstrap-datepicker.min.js"></script>
    <script src="/platform/js/fontawesome-all.js"></script>
    
    <script src="/platform/js/custom.js?v={{ date('Y-m-d-h-i-s') }}"></script>
    @yield('js')
</body>

</html>