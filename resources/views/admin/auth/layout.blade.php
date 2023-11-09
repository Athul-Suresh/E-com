<!doctype html>
<html class="h-100" lang="en">

<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin - Login')</title>
    <meta content="" name="description" />
    <meta content="" name="author" />



    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/images/favicon.png')}}">
    <link href="{{asset('assets/css/core.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">


    @yield('styles')

</head>

<body  class="h-100">

@yield('auth-content')



<script src="{{asset('assets/vendor/global/global.min.js')}}"></script>
<script src="{{asset('assets/js/quixnav-init.js')}}"></script>
<script src="{{asset('assets/js/custom.min.js')}}"></script>
@yield('scripts')
</body>

</html>
