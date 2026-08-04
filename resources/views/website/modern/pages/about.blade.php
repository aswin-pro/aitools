@extends('layouts.modern')

{{-- Custom JS --}}
@section('custom-css')

{{-- AdSense status --}}
@if ($setting->adsense_code != "DISABLE")
@if ($setting->adsense_code != "")
{{-- AdSense code --}}
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ $setting->adsense_code }}"
    crossorigin="anonymous"></script>
@endif
@endif
@endsection

@php
// Settings
use App\Models\Config;
use App\Models\Page;

$config = Config::get();
$page = Page::where('theme_id', '330599619570398')->where('slug', 'about')->where('status', 1)->get();
@endphp

@section('content')
{{-- Topbar --}}
@include('website.modern.includes.topbar')

{{-- Start home page sections --}}
@if (!empty($page[0]->body))
    @foreach (preg_split('/(<[^>]*>)/', $page[0]->body, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) as $part)
        @if (strpos($part, '<') === 0)
            {!! __($part) !!}
        @else
            {{ __($part) }}
        @endif
    @endforeach
@endif

{{-- Start call action section --}}
@include('website.modern.includes.call-action')
{{-- End call action section --}}

{{-- Footer --}}
@include('website.modern.includes.footer')
@endsection