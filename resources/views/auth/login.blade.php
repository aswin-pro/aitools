@php
    // Settings
    use App\Models\Setting;
    use App\Models\Config;

    $setting = Setting::where('status', 1)->first();
    $config = Config::get();

    // Selected theme
    $theme = 'classic';
    $section = 'auth.classic.login';
    $topbar = 'website.classic.includes.topbar';
    $footer = 'website.classic.includes.footer';
    if ($config[48]->config_value == '330599619570398') {
        $theme = 'modern';
        $section = 'auth.modern.login';
        $topbar = 'website.modern.includes.topbar';
        $footer = 'website.modern.includes.footer';
    }
    if ($config[48]->config_value == '317109101703740') {
        $theme = 'modern-orange';
        $section = 'auth.modern-orange.login';
        $topbar = 'website.modern-orange.includes.topbar';
        $footer = 'website.modern-orange.includes.footer';
    }
@endphp

@extends('layouts.' . $theme)

@section('content')

{{-- Topbar --}}
@include($topbar)

{{-- Login section --}}
@include($section)

{{-- Footer --}}
@include($footer)

{{-- Show / Hide Password --}}
@section('custom-js')
<script>
    function showPassword() {
    "use strict";
    var temp = document.getElementById("password");
    var icon = document.getElementById("icon");
    if (temp.type === "password") {
        temp.type = "text";
        icon.src = "{{ asset('themes/modern-orange/assets/images/sign-up/icon-close-eye.svg') }}";
    } else {
        temp.type = "password";
        icon.src = "{{ asset('themes/modern-orange/assets/images/sign-up/icon-eye.svg') }}";
    }
}
</script>
@endsection
@endsection