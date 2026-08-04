@php
    // Settings
    use App\Models\Setting;
    use App\Models\Config;

    $setting = Setting::where('status', 1)->first();
    $config = Config::get();

    // Selected theme
    $theme = 'classic';
    $section = 'auth.classic.verify';
    $topbar = 'website.classic.includes.topbar';
    $footer = 'website.classic.includes.footer';
    if ($config[48]->config_value == '330599619570398') {
        $theme = 'modern';
        $section = 'auth.modern.verify';
        $topbar = 'website.modern.includes.topbar';
        $footer = 'website.modern.includes.footer';
    }
    if ($config[48]->config_value == '317109101703740') {
        $theme = 'modern-orange';
        $section = 'auth.modern-orange.verify';
        $topbar = 'website.modern-orange.includes.topbar';
        $footer = 'website.modern-orange.includes.footer';
    }
@endphp

@extends('layouts.' . $theme)

@section('content')

{{-- Topbar --}}
@include($topbar)

{{-- Verify section --}}
@include($section)

{{-- Footer --}}
@include($footer)

@endsection