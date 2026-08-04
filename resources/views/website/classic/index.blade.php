@extends('layouts.classic')

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

@section('content')
{{-- Topbar --}}
@include('website.classic.includes.topbar')

{{-- Your custom codes here --}}


{{-- Upcoming Cookie Feature --}}
{{-- @include('website.classic.includes.cookie') --}}

{{-- Footer --}}
@include('website.classic.includes.footer')

{{-- Custom JS --}}
@section('custom-js')
<script src="{{ asset('js/smooth-scroll.js')}}"></script>
@endsection
@endsection