<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Showcase Hub</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/platform/css/font-awesome.css">
    <link rel="stylesheet" href="/platform/css/bootstrap.min.css">
    <link rel="stylesheet" href="/platform/css/custom.css">
    <link rel="stylesheet" href="/platform/css/dropzone.css">
    <link rel="stylesheet" href="/platform/css/newcss.css?v=@php echo date('Y-m-d-h-i-s');@endphp">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,700&display=swap" rel="stylesheet">
    @yield('css')
</head>

<body>

    @yield('content')


 
    <!-- Optional JavaScript -->
    <script src="/platform/js/jquery.min.js"></script>
    <script src="/platform/js/popper.min.js"></script>
    <script src="/platform/js/bootstrap.min.js"></script>
    <script src="/platform/js/fontawesome-all.js"></script>
    <script src="/platform/js/dropzone.min.js"></script>
    <script src="/platform/js/custom.js"></script>
    @yield('js')
</body>

</html>