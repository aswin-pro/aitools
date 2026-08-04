@extends('layouts.classic')

@section('content')

{{-- Custom CSS --}}
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
use App\Models\Page;
$page = Page::where('slug', 'tools')->where('status', 1)->get();
@endphp


{{-- Topbar --}}
@include('website.classic.includes.topbar')

{{-- Tools --}}
<section class="py-24 md:pb-32 bg-white"
    style="background-image: url('flex-ui-assets/elements/pattern-white.svg'); background-position: center;">
    <div class="container px-4 mx-auto">
        @if (!empty($page[0]->body))
            @foreach (preg_split('/(<[^>]*>)/', $page[0]->body, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) as $part)
                @if (strpos($part, '<') === 0)
                    {!! __($part) !!}
                @else
                    {{ __($part) }}
                @endif
            @endforeach
        @endif
        <div class="flex flex-wrap -mx-4" data-aos="fade-up" data-aos-delay="100">
            {{-- Tools --}}
            @foreach ($templates as $template)
            <div class="w-full md:w-1/2 lg:w-1/3 px-4 py-2">
                <div class="grid grid-cols-1 h-full p-8 text-center hover:bg-white rounded-md shadow-md hover:shadow-xl transition duration-200">
                    <div
                        class="inline-flex h-16 w-16 mb-6 mx-auto items-center justify-center text-white bg-{{ $config[11]->config_value }}-500 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-black icon-tabler icon-tabler-article" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M3 4m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z"></path>
                            <path d="M7 8h10"></path>
                            <path d="M7 12h10"></path>
                            <path d="M7 16h10"></path>
                         </svg>
                    </div>
                    <h3 class="mb-4 text-xl md:text-2xl leading-tight font-bold">{{ __($template->name) }}</h3>
                    <p class="text-coolGray-500 m-4 font-medium">{{ __($template->description) }}</p>
                    <div class="w-full">
                        <div class="w-full md:w-auto"><a
                                class="block rounded py-2 px-12 m-auto w-full text-base md:text-lg leading-4 text-{{ $config[11]->config_value }}-50 font-medium text-center bg-{{ $config[11]->config_value }}-500 hover:bg-{{ $config[11]->config_value }}-600 focus:ring-2 focus:ring-{{ $config[11]->config_value }}-500 focus:ring-opacity-50 border border-{{ $config[11]->config_value }}-500 rounded-md shadow-sm"
                                href="{{ route('user.new.ai.content', $template->unique_slug) }}">{{ __('Try') }}</a></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Footer --}}
@include('website.classic.includes.footer')
@endsection