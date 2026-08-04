@extends('layouts.modern-orange')

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
@include('website.modern-orange.includes.topbar')

{{-- About us --}}
<section class="pt-24 bg-white" style="background-image: url({{ asset('images/web/elements/pattern-white.svg') }}); background-position: center;">
    <div class="container px-4 pb-20 mx-auto" data-aos="fade-up">
        {{-- Page content --}}
        @if (!empty($page->body))
            @foreach (preg_split("/(<[^>]*>)/", $page->body, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) as $part)
                @if (strpos($part, '<') === 0)
                    {!! __($part) !!}
                @else
                    {{ __($part) }}
                @endif
            @endforeach
        @endif
    </div>
</section>

{{-- Footer --}}
@include('website.modern-orange.includes.footer')
@endsection