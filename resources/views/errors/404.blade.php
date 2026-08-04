@php
    // Settings
    use App\Models\Setting;
    use App\Models\Config;
    use App\Models\Page;

    $setting = Setting::where('status', 1)->first();
    $page = Page::where('slug', 'home')->where('status', 1)->get();
    $config = Config::get();

    // Selected theme
    $theme = 'classic';
    $section = 'errors.classic.404';
    $topbar = 'website.classic.includes.topbar';
    $footer = 'website.classic.includes.footer';
    if ($config[48]->config_value == '330599619570398') {
        $theme = 'modern';
        $section = 'errors.modern.404';
        $topbar = 'website.modern.includes.topbar';
        $footer = 'website.modern.includes.footer';
    }
    if ($config[48]->config_value == '317109101703740') {
        $theme = 'modern-orange';
        $section = 'errors.modern-orange.404';
        $topbar = 'website.modern-orange.includes.topbar';
        $footer = 'website.modern-orange.includes.footer';
    }
@endphp

@extends('layouts.' . $theme, ['title' => '404'. ' - ' . str_replace("Home -", "", $page[0]->page_title)])

@section('content')

{{-- Topbar --}}
@include($topbar)

{{-- 401 --}}
@include($section)

{{-- Footer --}}
@include($footer)

@endsection