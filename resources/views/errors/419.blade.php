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
    $section = 'errors.classic.419';
    $topbar = 'website.classic.includes.topbar';
    $footer = 'website.classic.includes.footer';
    if ($config[48]->config_value == '330599619570398') {
        $theme = 'modern';
        $section = 'errors.modern.419';
        $topbar = 'website.modern.includes.topbar';
        $footer = 'website.modern.includes.footer';
    }
@endphp

@extends('layouts.' . $theme, ['title' => '419'. ' - ' . str_replace("Home -", "", $page[0]->page_title)])

@section('content')

{{-- Topbar --}}
@include($topbar)

{{-- 401 --}}
@include($section)

{{-- Footer --}}
@include($footer)

@endsection