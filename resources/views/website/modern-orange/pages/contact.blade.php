@extends('layouts.modern-orange')

@section('custom-css')
    @if ($setting->adsense_code != 'DISABLE')
        @if ($setting->adsense_code != '')
            <script
                async
                src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ $setting->adsense_code }}"
                crossorigin="anonymous">
            </script>
        @endif
    @endif
@endsection

@php
    use App\Models\Config;
    use App\Models\Page;

    $config = Config::get();

    $page = Page::where('theme_id', '317109101703740')
        ->where('slug', 'contact')
        ->where('status', 1)
        ->first();
@endphp

@section('content')

    {{-- Topbar --}}
    @include('website.modern-orange.includes.topbar')

    {{-- GrapesJS Page Content --}}
    @if ($page && !empty($page->body))
        {!! $page->body !!}
    @endif

    {{-- Call Action --}}
    @include('website.modern-orange.includes.call-action')

    {{-- Footer --}}
    @include('website.modern-orange.includes.footer')

@endsection