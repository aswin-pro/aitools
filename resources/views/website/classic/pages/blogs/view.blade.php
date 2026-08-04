@extends('layouts.classic')

@section('content')
    {{-- Custom JS --}}
@section('custom-css')
    {{-- AdSense status --}}
    @if ($setting->adsense_code != 'DISABLE')
        @if ($setting->adsense_code != '')
            {{-- AdSense code --}}
            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ $setting->adsense_code }}"
                crossorigin="anonymous"></script>
        @endif
    @endif

    <style>
        .max-w-full h1, h2, h3, h4, h5, h6 {
             margin: 1rem 0;
             font-weight: bold;
        }
        .max-w-full blockquote, dd, dl, figure, hr, p, pre {
             margin: 1rem 0 2rem 0;
        }
        .max-w-full ul {
            list-style: disc;
            margin: 0 6px;
            padding: 0 10px;
        }
        .max-w-full ol {
            list-style: auto;
            margin: 0 6px;
            padding: 0 10px;
        }
        .max-w-full li {
            margin-bottom: 1rem;
        }
    </style>
@endsection

{{-- Topbar --}}
@include('website.classic.includes.topbar')

{{-- View blog post --}}
<section class="lg:py-24 py-12 md:pb-32 bg-white"
    style="background-image: url({{ asset('images/web/elements/pattern-white.svg') }}); background-repeat: no-repeat; background-position: center top;">
    <div class="container px-4 mx-auto">
        <div class="md:max-w-2xl mx-auto mb-12 text-center">
            <div
                class="inline-block py-1 px-3 mb-6 text-xs leading-5 text-yellow-500 font-medium uppercase bg-yellow-100 rounded-full shadow-sm">
                {{ $blogDetails->blogCategory->blog_category_title }}</div>
            <h2 class="mb-4 text-3xl md:text-5xl leading-tight text-darkCoolGray-900 font-bold tracking-tighter">
                {{ $blogDetails->heading }}</h2>
            <p class="mb-10 text-md md:text-md font-small text-coolGray-500">{{ $blogDetails->short_description }}</p>
            <div class="flex items-center justify-center text-left -mx-2">
                <div class="flex items-center justify-center h-16 w-16 rounded-full bg-gray-200 text-white">
                    <img src="{{ asset($blogDetails->user->profile_image ? $blogDetails->user->profile_image : $setting->site_logo) }}"
                        alt="{{ $blogDetails->user->name }}">
                </div>
                <div class="w-auto px-2">
                    <h4 class="text-base md:text-lg font-bold text-coolGray-800">{{ $blogDetails->user->name }}</h4>
                    <p class="text-base md:text-lg text-coolGray-500">
                        {{ Carbon\Carbon::parse($blogDetails->created_at)->format('d M Y h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>
    <img class="w-auto h-auto mb-10 mx-auto" src="{{ asset($blogDetails->cover_image) }}" alt="{{ $blogDetails->heading }}">
    <div class="container px-4 mx-auto">
        <div class="md:max-w-3xl mx-auto">
            {!! $blogDetails->long_description !!}

            <!-- Tags Heading -->
            <div class="my-8">
                <h2 class="text-xl font-semibold mb-2">{{ __('Related Tags:') }}</h2>

                {{-- Tags --}}
                <div class="flex space-x-2">
                    @php
                    // Tags separated
                    $tags = explode(',', $blogDetails->tags);
                    $tags = collect($tags)->all();
                    @endphp

                    @foreach ($tags as $tag)
                        <span class="bg-yellow-500 text-white py-1 px-2 rounded-lg text-xs font-medium">{{ strtoupper($tag) }}</span>
                    @endforeach
                </div>
            </div>

            {{-- Share This Post --}}
            <div class="my-8">
                <h2 class="text-xl font-semibold mb-4">{{ __('Share This Blog Post') }}</h2>
                <div class="flex space-x-4">
                    <!-- Facebook Share Button -->
                    <a href="{{ route('sharetofacebook', $blogDetails->slug) }}" target="_blank" class="flex items-center px-2 py-2 bg-blue-700 text-white rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3" /></svg>
                    </a>

                    <!-- Twitter Share Button -->
                    <a href="{{ route('sharetotwitter', $blogDetails->slug) }}" target="_blank" class="flex items-center px-2 py-2 bg-gray-800 text-white rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4l11.733 16h4.267l-11.733 -16z" /><path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772" /></svg>
                    </a>

                    <!-- LinkedIn Share Button -->
                    <a href="{{ route('sharetolinkedin', $blogDetails->slug) }}" target="_blank" class="flex items-center px-2 py-2 bg-blue-600 text-white rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M8 11l0 5" /><path d="M8 8l0 .01" /><path d="M12 16l0 -5" /><path d="M16 16v-3a2 2 0 0 0 -4 0" /></svg>
                    </a>
                
                    <!-- Instagram Share Button -->
                    <a href="{{ route('sharetoinstagram', $blogDetails->slug) }}" target="_blank" class="flex items-center px-2 py-2 bg-pink-600 text-white rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 4a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z" /><path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M16.5 7.5l0 .01" /></svg>
                    </a>
                
                    <!-- WhatsApp Share Button -->
                    <a href="{{ route('sharetowhatsapp', $blogDetails->slug) }}" target="_blank" class="flex items-center px-2 py-2 bg-green-600 text-white rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" /><path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" /></svg>
                    </a>
                </div>
            </div>         
        </div>
    </div>
</section>

{{-- Footer --}}
@include('website.classic.includes.footer')
@endsection
