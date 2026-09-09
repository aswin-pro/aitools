<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ App::isLocale('ar') || App::isLocale('ur') || App::isLocale('he') ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    @if (isset($setting))
        <!-- Favicon -->
        <link rel="icon" href="{{ asset($setting->favicon) }}" sizes="96x96" type="image/png" />
    @endif


    <!-- CSS files -->
    <!-- CSS files -->
    @if (App::isLocale('ar') || App::isLocale('ur') || App::isLocale('he'))
        <link href="{{ asset('css/tabler.rtl.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('css/tabler-vendors.rtl.min.css') }}" rel="stylesheet" />
    @else
        <link href="{{ asset('css/tabler.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('css/tabler-vendors.min.css') }}" rel="stylesheet" />
    @endif

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/preloader.min.css') }}">

    {{-- Custom CSS --}}
    @yield('custom-css')
</head>

<body class="antialiased" data-bs-theme="light">
    {{-- Preloader --}}
    <div id="nativecode-loader">
        <div class="nativecode-loading"></div>
    </div>

    <div id="wrapper" class="page">
        {{-- Page Content --}}
        @yield('content')
    </div>

    <!-- Scripts -->
    <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/tabler.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/main.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/clipboard.min.js') }}"></script>
    {{-- Custom JS --}}
    @yield('custom-js')
    <script>
        // Wait for the window to load
        window.onload = function() {
            "use strict";

            // Get the preloader element
            var preloader = document.getElementById('nativecode-loader');

            // Add the fade-out class to start the fade-out effect
            setTimeout(function() {
                preloader.classList.add('fade-out');

                setTimeout(function() {
                    preloader.style.display = 'none'; 
                    document.querySelector('.page').style.display = 'flex'; 
                }, 7000); 
            }, 100);
        };
    </script>
</body>

</html>
