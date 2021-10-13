<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Showcase</title>
    <meta name="description" content="Showcase">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="/favicon.ico">
    <!--[if IE]>

    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>

    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]--><!-- global stylesheets -->

    <link rel="stylesheet" href="{{url('/')}}/assets/marino/styles/components/bootstrap.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.1/animate.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">

    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/1.0.0/css/flag-icon.min.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="{{url('/')}}/assets/marino/styles/main.css">
    <link rel="stylesheet" href="{{url('/')}}/assets/marino/bower_components/chartist/dist/chartist.min.css">
    <link rel="stylesheet" href="{{url('/')}}/assets/marino/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css">
    <link rel="stylesheet" href="{{url('/')}}/assets/marino/bower_components/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css">

    <style>
        body[data-layout="collapsed-sidebar"] .sidebar-1 .sidebar-section > a > .main-logo-img {
            width  : 60px;
            margin : 10px;
            content:url('/images/logo-mini.png');
        }

        body[data-layout="default-sidebar"] .sidebar-1 .sidebar-section > a > .main-logo-img {
            width         : 180px;
            margin        : 20px;
            margin-bottom : 0;
        }

        [data-layout="collapsed-sidebar"] .sidebar-1 .section-content > li > a > .title {
            display: block !important;
            font-size: 9px !important;
            position: absolute;
            bottom: 0px;
            left: 0;
            right: 0;
            width: 100%;}

    </style>

    @yield('css')

    @if(request()->getHost() == env('COACH_PLATFORM_DOMAIN'))
        <!-- Begin Inspectlet Embed Code -->
            <script type="text/javascript" id="inspectletjs">
                (function() {
                    window.__insp = window.__insp || [];
                    __insp.push(['wid', 901653424]);
                    var ldinsp = function(){ if(typeof window.__inspld != "undefined") return; window.__inspld = 1; var insp = document.createElement('script'); insp.type = 'text/javascript'; insp.async = true; insp.id = "inspsync"; insp.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cdn.inspectlet.com/inspectlet.js?wid=901653424&r=' + Math.floor(new Date().getTime()/3600000); var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(insp, x); };
                    setTimeout(ldinsp, 0);
                })();
            </script>
            <!-- End Inspectlet Embed Code -->
    @endif

</head>
<body data-layout="empty-layout" data-palette="palette-4" data-direction="none">

<div class="pace pace-inactive">
    <div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
        <div class="pace-progress-inner"></div>
    </div>
    <div class="pace-activity"></div>
</div>
<nav class="navbar navbar-fixed-top navbar-1">
    <!--<a class="navbar-brand" href="/"> <img style="width: 30px" src="/images/main_logo.png"></a>-->
    <ul class="nav navbar-nav pull-left toggle-layout">
        <li class="nav-item"><a class="nav-link" data-click="toggle-layout"> <i class="zmdi zmdi-menu"></i> </a></li>
    </ul>

    @if(Auth::user()->role == 2)
        <ul class="nav navbar-nav pull-left toggle-search">
            <li class="nav-item"><a class="nav-link" data-click="toggle-search"> <i class="zmdi zmdi-search"></i> </a></li>
        </ul>
        <div class="navbar-drawer pull-left hidden-lg-down">
            <form class="form-inline navbar-form" method="GET" action="/myreviews">
                <input class="form-control" type="text" placeholder="Search" name="search" autocomplete="off">
            </form>
        </div>
    @endif
    <ul class="nav navbar-nav pull-right hidden-lg-down navbar-notifications">
        <li class="nav-item">
            <a class="nav-link" data-click="toggle-right-sidebar" id="notification_btn">
                <i class="zmdi zmdi-notifications-active"></i>
                <span class="label label-rounded label-danger label-xs" id="nitification_counter">0</span>
            </a>
        </li>
    </ul>
    <ul class="nav navbar-nav pull-right hidden-lg-down navbar-profile">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle no-after" data-toggle="dropdown"> <img class="img-circle img-fluid profile-image" src="{{Auth::user()->avatar}}"> </a>
            <div class="dropdown-menu dropdown-menu-scale from-right dropdown-menu-right">
                <a class="dropdown-item animated fadeIn" href="/profile"> <i class="zmdi zmdi-settings-square"></i> <span class="dropdown-text">Profile</span> </a>
                <a class="dropdown-item animated fadeIn" href="/auth/logout"> <i class="zmdi zmdi-power"></i> <span class="dropdown-text">Logout</span> </a>
            </div>
        </li>
    </ul>
    <ul class="nav navbar-nav pull-right hidden-lg-down navbar-profile">
        <li class="nav-item"><a class="nav-link" href="/profile">{{Auth::user()->first_name}} {{Auth::user()->last_name}}</a></li>
    </ul>
</nav>
<div class="container-fluid">
    <div class="row">
        <div class="sidebar-placeholder"></div>
        <div class="sidebar-outer-wrapper">
            <div class="sidebar-inner-wrapper">
                <div class="sidebar-1">
                    <div class="sidebar-section">
                        @if(Auth::user()->role == \App\Models\User::getCoachRole())
                            <a href="/myreviews">
                                <img class="main-logo-img" src="/images/logo-white.png">
                            </a>
                        @elseif(Auth::user()->role == App\Models\User::getUserRole())
                            <a href="/video">
                                <img class="main-logo-img" src="/images/logo-white.png">
                            </a>
                        @elseif(Auth::user()->role == \App\Models\User::getAdminRole())
                            <a href="/profile">
                                <img class="main-logo-img" src="/images/logo-white.png">
                            </a>
                        @endif
                    </div>
                    <div class="sidebar-nav">
                        <div class="sidebar-section">
                            <div class="section-title">Account</div>
                            <ul class="list-unstyled section-content">
                                @if(Auth::user()->role == App\Models\User::getUserRole())
                                    <li>
                                        <a class="sideline {{ strpos(url()->current(), '/video') !== false &&
                                        strpos(url()->current(), '/video/') === false ? 'sideline-active' : '' }}"
                                           href="/video">
                                            <i class="zmdi zmdi-collection-video md-icon pull-left"></i>
                                            <span class="title">My Videos</span>
                                        </a>
                                    </li>
                                @endif

                                @php
                                ( isset($flag) ) ? $browse_coach_page = true : $browse_coach_page = false;
                                @endphp
                                @if(Auth::user()->role == App\Models\User::getUserRole())
                                    <li>
                                        <a class="sideline {{ (strpos(url()->current(), '/customer/coach/search') !== false || $browse_coach_page ) ? 'sideline-active' :
                                           (strpos(url()->current(), '/video/create') !== false ? 'sideline-active' : '') }}"
                                           data-id="docs" href="{{ route('customerActions-searchCoach') }}">
                                            <i class="zmdi zmdi-youtube-play md-icon pull-left"></i>
                                            <span class="title ws2">Browse Coaches</span>
                                        </a>
                                    </li>
                                @endif
                                @if(Auth::user()->role == \App\Models\User::getCoachRole())
                                    <li>
                                        <a class="sideline {{ strpos(url()->current(), '/myreviews') !== false ? 'sideline-active' : '' }}"
                                           data-id="docs" href="/myreviews">
                                            <i class="zmdi zmdi-comments md-icon pull-left"></i>
                                            <span class="title">My Reviews</span>
                                        </a>
                                    </li>
                                @endif

                                @if(Auth::user()->role == \App\Models\User::getAdminRole())
                                    <ul class="l1 list-unstyled section-content">
                                        <li>
                                            <a class="sideline {{ Route::currentRouteName() == 'admin.analytics.index' ? 'sideline-active' : '' }}" href="{{ route('admin.analytics.index') }}">
                                                <i class="zmdi zmdi-view-dashboard md-icon pull-left"></i>
                                                <span class="title">Dashboard</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <ul class="l1 list-unstyled section-content">
                                        <li>
                                            <a class="sideline" data-id="tables" data-click="toggle-section">
                                                <i class="pull-right fa fa-caret-down icon-tables"></i>
                                                <i class="fa fa-users md-icon pull-left"></i>
                                                <span class="title">Coaches</span>
                                            </a>
                                            <ul class="list-unstyled section-tables l2 {{ Route::getCurrentRoute()->getPrefix() == '/coaches'  ? 'active' : '' }}">
                                                <li>
                                                    <a class="sideline {{ Route::currentRouteName() == 'admin.coaches.index' ? 'sideline-active' : '' }}" href="{{ route('admin.coaches.index') }}">
                                                        <span class="title">List</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="sideline {{ Route::currentRouteName() == 'admin.coaches.create' ? 'sideline-active' : '' }}" href="{{ route('admin.coaches.create') }}">
                                                        <span class="title">Create new</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="sideline {{ Route::currentRouteName() == 'admin.coaches.invites' ? 'sideline-active' : '' }}" href="{{ route('admin.coaches.invites') }}">
                                                        <span class="title">Invites</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                    <ul class="l1 list-unstyled section-content">
                                        <li>
                                            <a class="sideline" data-id="users" data-click="toggle-section">
                                                <i class="pull-right fa fa-caret-down icon-tables"></i>
                                                <i class="fa fa-users md-icon pull-left"></i>
                                                <span class="title">Users</span>
                                            </a>
                                            <ul class="list-unstyled section-users l2 {{ Route::getCurrentRoute()->getPrefix() == '/users' ? 'active' : '' }}">
                                                <li>
                                                    <a class="sideline {{ Route::currentRouteName() == 'admin.users.index' ? 'sideline-active' : '' }}" href="{{ route('admin.users.index') }}">
                                                        <span class="title">List</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="sideline {{ Route::currentRouteName() == 'admin.users.create' ? 'sideline-active' : '' }}" href="{{ route('admin.users.create') }}">
                                                        <span class="title">Create new</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                    {{--<ul class="l1 list-unstyled section-content">--}}
                                        {{--<li>--}}
                                            {{--<a class="sideline {{ Route::currentRouteName() == 'admin.coaches.index' ? 'sideline-active' : '' }}"--}}
                                               {{--data-id="docs" href="{{ route('admin.coaches.index') }}" data-click="toggle-section">--}}
                                                {{--<i class="pull-right fa fa-caret-down icon-dashboards fa-rotate-180"></i>--}}
                                                {{--<i class="fa fa-users md-icon pull-left"></i>--}}
                                                {{--<span class="title">Coaches</span>--}}
                                            {{--</a>--}}
                                        {{--</li>--}}
                                    {{--</ul>--}}
                                    {{--<li>--}}
                                        {{--<a class="sideline {{ Route::currentRouteName() == 'admin.users.index' ? 'sideline-active' : '' }}"--}}
                                           {{--data-id="docs" href="{{ route('admin.users.index') }}">--}}
                                            {{--<i class="fa fa-users md-icon pull-left"></i>--}}
                                            {{--<span class="title">Users</span>--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                @endif

                                <li>
                                    <a class="sideline {{ (strpos(url()->current(), '/profile') !== false && !$browse_coach_page ) ? 'sideline-active' : '' }}" href="/profile">
                                        <i class="zmdi zmdi-account-circle md-icon pull-left"></i><span class="title">My Profile</span>
                                    </a>
                                </li>
                                    @if(auth()->user()->isAdmin())
                                <li>
                                    <a class="sideline {{ Route::currentRouteName() == 'admin.settings.index' ? 'sideline-active' : '' }}" href="{{ route('admin.settings.index') }}">
                                        <i class="fa fa-cogs md-icon pull-left"></i>
                                        <span class="title">Settings</span>
                                    </a>
                                </li>
                                    @endif
                                <li><a class="sideline" href="/auth/logout"><i class="zmdi zmdi-power md-icon pull-left"></i><span class="title">Logout</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="right-sidebar-outer">
            <div class="right-sidebar-inner">
                <div class="right-sidebar">
                    <div class="bs-nav-tabs nav-tabs-warning justified">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="rtab-left">
                                <h5 id="notification_tittle">No notifications</h5>
                                <div class="timeline-widget-4" id="notification_blk">
                                    <div class="row bg-odd-color" id="notification_tpl" hidden>
                                        <div class="col-xs-12 timeline timeline-warning">
                                            <div class="p-10">
                                                <p class="notification_msg"></p>
                                                <p><a class="review_link" href="#"><span class="label label-warning">Show review</span></a></p>
                                                <p class="text-sm text-muted notification_date"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 main" id="main" style="min-height: 990px;">
            <div class="row m-b-40">
                @yield('content')
            </div>
            <div class="footer">
                <div class="row">
                    <div class="col-xs-12">
                        © {{ date('Y') }}. Showcase.
                        <!--<a href="/" target="_blank">Buy Showcase</a>-->
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
<script src="/assets/marino/bower_components/jquery/dist/jquery.js"></script>
<script src="/assets/marino/bower_components/tether/dist/js/tether.js"></script>
<script src="/assets/marino/bower_components/bootstrap/dist/js/bootstrap.js"></script>
<script src="/assets/marino/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="/assets/marino/bower_components/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js"></script>
<script src="/assets/marino/scripts/forms-pickers.js"></script>
<!--<script src="/assets/marino/bower_components/bootstrap-datetimepicker/dist/js/bootstrap-datetimepicker.min.js"></script>-->
<!--    <script type="text/javascript">
            $(function () {
                $('#datetimepicker1').datetimepicker();
            });
        </script>-->
<script src="/assets/marino/bower_components/PACE/pace.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.0.0/lodash.min.js"></script>
<script src="/assets/marino/bower_components/jquery-storage-api/jquery.storageapi.min.js"></script>
<script src="/assets/marino/bower_components/wow/dist/wow.min.js"></script>
<script src="/assets/marino/scripts/functions.js"></script>
<script src="/assets/marino/scripts/colors.js"></script>
<script src="/assets/marino/scripts/left-sidebar.js"></script>
<script src="/assets/marino/scripts/navbar.js"></script>
<script src="/assets/marino/scripts/horizontal-navigation-1.js"></script>

<script src="/assets/marino/scripts/horizontal-navigation-2.js"></script>
<script src="/assets/js/app.js"></script>
<script src="/assets/marino/scripts/horizontal-navigation-3.js"></script>
<script src="/assets/marino/scripts/main.js"></script>
<script src="/assets/marino/bower_components/notifyjs/dist/notify.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>
<script src="/assets/marino/bower_components/chartist/dist/chartist.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/easy-pie-chart/2.1.6/jquery.easypiechart.min.js"></script>
<script src="/assets/marino/bower_components/d3/d3.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/topojson/1.6.9/topojson.min.js"></script>
<!--<script src="http://bower.batchthemes.com/bower_components/datamaps/dist/datamaps.all.js"></script>-->
<!--<script src="/assets/marino/scripts/dashboards.js"></script>-->
<!--<script src="/assets/marino/scripts/index.js"></script>-->
@yield('js')
</html>