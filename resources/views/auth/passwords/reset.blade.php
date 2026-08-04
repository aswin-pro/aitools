@php
    // Settings
    use App\Models\Setting;
    use App\Models\Config;

    $settings = Setting::where('status', 1)->first();
    $config = Config::get();

    // Google recaptcha
    $recaptcha_configuration = [
        'RECAPTCHA_ENABLE' => env('RECAPTCHA_ENABLE', 'off'),
        'RECAPTCHA_SITE_KEY' => env('RECAPTCHA_SITE_KEY', ''),
        'RECAPTCHA_SECRET_KEY' => env('RECAPTCHA_SECRET_KEY', '')
    ];

    $settings['recaptcha_configuration'] = $recaptcha_configuration;


    // Selected theme
    $theme = 'classic';
    $section = 'auth.classic.reset';
    $topbar = 'website.classic.includes.topbar';
    $footer = 'website.classic.includes.footer';
    if ($config[48]->config_value == '330599619570398') {
        $theme = 'modern';
        $section = 'auth.modern.reset';
        $topbar = 'website.modern.includes.topbar';
        $footer = 'website.modern.includes.footer';
    }
    if ($config[48]->config_value == '317109101703740') {
        $theme = 'modern-orange';
        $section = 'auth.modern-orange.reset';
        $topbar = 'website.modern-orange.includes.topbar';
        $footer = 'website.modern-orange.includes.footer';
    }
@endphp

@extends('layouts.' . $theme)

@section('content')

{{-- Custom CSS --}}
@section('custom-css')
<style type="text/css">
    a:hover {
        cursor: pointer;
    }
</style>
@endsection

{{-- Topbar --}}
@include($topbar)

@include($section)

{{-- Footer --}}
@include($footer)

{{-- Show / Hide Password --}}
@section('custom-js')
<script>
    function showPassword() {
        "use strict";
        var temp = document.getElementById("password");
        if (temp.type === "password") {
            temp.type = "text";
        } else {
            temp.type = "password";
        }
    }

    function showConfirmPassword() {
        "use strict";
        var temp = document.getElementById("password-confirm");
        if (temp.type === "password") {
            temp.type = "text";
        } else {
            temp.type = "password";
        }
    }
</script>
@endsection
@endsection