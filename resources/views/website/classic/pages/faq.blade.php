@extends('layouts.classic')

@section('content')

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

{{-- Topbar --}}
@include('website.classic.includes.topbar')

@php
use App\Models\Page;
$page = Page::where('slug', 'faq')->where('status', 1)->get();
@endphp

{{-- FAQs --}}
@if (!empty($page[0]->body))
    @foreach (preg_split('/(<[^>]*>)/', $page[0]->body, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) as $part)
        @if (strpos($part, '<') === 0)
            {!! __($part) !!}
        @else
            {{ __($part) }}
        @endif
    @endforeach
@endif

{{-- Footer --}}
@include('website.classic.includes.footer')
@endsection