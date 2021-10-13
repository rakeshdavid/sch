<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    @yield('meta')
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="favicon.ico">
    <!--[if IE]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- global stylesheets --> 
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/platform/css/font-awesome.css">
    <link rel="stylesheet" href="/platform/css/bootstrap.min.css">
    <link rel="stylesheet" href="/platform/css/custom.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,700&display=swap" rel="stylesheet">
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
<body>

@yield('content')

<!-- Optional JavaScript -->
<script src="/platform/js/jquery.min.js"></script>
<script src="/platform/js/popper.min.js"></script>
<script src="/platform/js/bootstrap.min.js"></script>
<script src="/platform/js/fontawesome-all.js"></script>
<script src="/platform/js/custom.js"></script>
@yield('js')
</body>
</html>
