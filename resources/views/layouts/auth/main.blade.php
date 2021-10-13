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
    <link rel="stylesheet" href="/assets/marino/styles/components/bootstrap.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.1/animate.min.css"/>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css"/>
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/1.0.0/css/flag-icon.min.css" rel="stylesheet"
          type="text/css"/>
    <link rel="stylesheet" href="/assets/marino/styles/main.css">
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

@yield('content')

<!-- global scripts -->
<script src="/assets/marino/bower_components/jquery/dist/jquery.js"></script>
<script src="/assets/marino/bower_components/tether/dist/js/tether.js"></script>
<script src="/assets/marino/bower_components/bootstrap/dist/js/bootstrap.js"></script>
<script src="/assets/marino/bower_components/PACE/pace.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.0.0/lodash.min.js"></script>
<script src="/assets/marino/bower_components/jquery-storage-api/jquery.storageapi.min.js"></script>
<script src="/assets/marino/bower_components/wow/dist/wow.min.js"></script>
<script src="/assets/marino/scripts/functions.js"></script>
<script src="/assets/marino/scripts/colors.js"></script>
<script src="/assets/marino/scripts/main.js"></script>
<script src="/assets/marino/scripts/components/floating-labels.js"></script>
@yield('js')
</body>
</html>
