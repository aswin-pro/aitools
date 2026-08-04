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
    $section = 'auth.classic.email';
    $topbar = 'website.classic.includes.topbar';
    $footer = 'website.classic.includes.footer';
    if ($config[48]->config_value == '330599619570398') {
        $theme = 'modern';
        $section = 'auth.modern.email';
        $topbar = 'website.modern.includes.topbar';
        $footer = 'website.modern.includes.footer';
    }
    if ($config[48]->config_value == '317109101703740') {
        $theme = 'modern-orange';
        $section = 'auth.modern-orange.email';
        $topbar = 'website.modern-orange.includes.topbar';
        $footer = 'website.modern-orange.includes.footer';
    }
@endphp

@extends('layouts.' . $theme)

@section('content')

{{-- Topbar --}}
@include($topbar)

@include($section)

{{-- Footer --}}
@include($footer)
@endsection