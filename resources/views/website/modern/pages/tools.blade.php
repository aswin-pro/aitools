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
$page = Page::where('theme_id', '330599619570398')->where('slug', 'tools')->where('status', 1)->get();
@endphp

@section('content')
{{-- Topbar --}}
@include('website.modern.includes.topbar')

<section class="pt-24 lg:px-12 overflow-hidden">
    <div class="container px-4 mx-auto">
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
    </div>
    <div class="flex flex-wrap -m-4 mb-10 px-4">
        {{-- Tools --}}
        @foreach ($templates as $tool)
        <div class="w-full md:w-1/2 lg:w-1/3 p-4">
            <a href="{{ route('user.new.ai.content', $tool->unique_slug) }}">
                <div
                    class="relative h-full py-8 px-8 bg-gradient-radial-dark overflow-hidden border border-gray-900 border-opacity-30 rounded-5xl">
                    <div class="relative z-10">
                        <h3 class="mb-8 text-3xl text-white tracking-3xl">{{ __($tool->name) }}</h3>
                        <p class="text-gray-300">{{ __($tool->description) }}</p>
                    </div>
                    <img class="absolute bottom-0 right-0" src="{{ asset('themes/modern/assets/images/team/shadow.png') }}"
                        alt="" />
                </div>
            </a>
        </div>
        @endforeach
    </div>
</section>

{{-- Start call action section --}}
@include('website.modern.includes.call-action')
{{-- End call action section --}}

{{-- Footer --}}
@include('website.modern.includes.footer')
@endsection