@extends('layouts.modern')

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
@include('website.modern.includes.topbar')

<section class="relative pt-20 pb-8 lg:pb-12 overflow-hidden">
    <img class="absolute top-0 left-0" src="{{ asset('themes/modern/assets/images/blog-content/shadow.png') }}"
        alt="">
    <div class="container px-4 mx-auto">
        <div class="relative z-10 py-16 bg-green-500 rounded-t-5xl">
            <div class="md:max-w-md px-4 text-center mx-auto">
                <h2 class="mb-4 text-3xl text-black tracking-3xl">{{ $blogDetails->heading }}
                </h2>
                <p class="mb-6 text-black font-medium">{{ $blogDetails->short_description }}</p>
                <div class="flex flex-wrap justify-center items-center -m-2">
                    <div class="w-auto p-2">
                        <img src="{{ asset($blogDetails->user->profile_image ? $blogDetails->user->profile_image : $setting->site_logo) }}"
                            alt="{{ $blogDetails->heading }}">
                    </div>
                    <div class="w-auto p-2">
                        <p class="text-sm text-black font-medium tracking-tighter">
                            {{ $blogDetails->blogCategory->blog_category_title }}</p>
                    </div>
                    <div class="w-auto p-2">
                        <svg width="3" height="3" viewbox="0 0 3 3" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M2.492 3H0.574V0.83H2.492V3Z" fill="white"></path>
                        </svg>
                    </div>
                    <div class="w-auto p-2">
                        <p class="text-sm text-black font-medium tracking-tighter">{{ $blogDetails->user->name }}</p>
                    </div>
                    <div class="w-auto p-2">
                        <svg width="3" height="3" viewbox="0 0 3 3" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M2.492 3H0.574V0.83H2.492V3Z" fill="white"></path>
                        </svg>
                    </div>
                    <div class="w-auto p-2">
                        <p class="text-sm text-black font-medium tracking-tighter">
                            {{ Carbon\Carbon::parse($blogDetails->created_at)->format('d M Y h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <img class="hidden md:block absolute top-24 right-0"
            src="{{ asset('themes/modern/assets/images/blog-content/lines.png') }}" alt="">
        <div class="relative px-16">
            <div class="absolute top-0 left-0 w-full h-1/2 bg-green-500 rounded-b-5xl"></div>
            <img class="relative z-10 mx-auto border border-black border-opacity-30 rounded-5xl"
                src="{{ asset($blogDetails->cover_image) }}" alt="{{ $blogDetails->heading }}">
        </div>
        <div class="{{ count($recentBlogs) > 0 ? 'border-b border-blueGray-900' : '' }}">
            <div class="max-w-3xl pt-16 mx-auto">
                <div class="">
                    {!! $blogDetails->long_description !!}
                </div>

                <!-- Tags Heading -->
                <div class="my-8">
                    <h2 class="text-xl font-semibold mb-2">{{ __('Related Tags:') }}</h2>

                    {{-- Tags --}}
                    <div class="flex flex-wrap -m-2">
                        @php
                            // Tags separated
                            $tags = explode(',', $blogDetails->tags);
                            $tags = collect($tags)->all();
                        @endphp

                        @foreach ($tags as $tag)
                            <div class="w-auto p-2">
                                <span
                                    class="flex items-center px-3 text-white cursor-pointer hover:text-black font-medium tracking-tighter hover:bg-green-400 border-2 border-white focus:border-green-400 focus:border-opacity-40 hover:border-green-400 focus:ring-4 focus:ring-green-400 focus:ring-opacity-40 rounded-full transition duration-300">{{ strtoupper($tag) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Share --}}
                <div class="pt-8 pb-16">
                    <h2 class="text-xl font-semibold mb-4">{{ __('Share This Blog Post') }}</h2>
                    <div class="flex flex-wrap -m-2">
                        <div class="w-auto p-2">
                            <!-- Facebook Share Button -->
                            <a href="{{ route('sharetofacebook', $blogDetails->slug) }}" target="_blank"
                                class="flex items-center justify-center p-3 text-white hover:text-black font-medium tracking-tighter bg-green-900 hover:bg-green-700 border-2 border-white focus:border-green-400 focus:border-opacity-40 hover:border-green-400 focus:ring-4 focus:ring-green-400 focus:ring-opacity-40 rounded-full transition duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3" />
                                </svg>
                            </a>
                        </div>

                        <div class="w-auto p-2">
                            <!-- Twitter Share Button -->
                            <a href="{{ route('sharetotwitter', $blogDetails->slug) }}" target="_blank"
                                class="flex items-center justify-center p-3 text-white hover:text-black font-medium tracking-tighter bg-green-900 hover:bg-green-700 border-2 border-white focus:border-green-400 focus:border-opacity-40 hover:border-green-400 focus:ring-4 focus:ring-green-400 focus:ring-opacity-40 rounded-full transition duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 4l11.733 16h4.267l-11.733 -16z" />
                                    <path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772" />
                                </svg>
                            </a>
                        </div>

                        <div class="w-auto p-2">
                            <!-- LinkedIn Share Button -->
                            <a href="{{ route('sharetolinkedin', $blogDetails->slug) }}" target="_blank"
                                class="flex items-center justify-center p-3 text-white hover:text-black font-medium tracking-tighter bg-green-900 hover:bg-green-700 border-2 border-white focus:border-green-400 focus:border-opacity-40 hover:border-green-400 focus:ring-4 focus:ring-green-400 focus:ring-opacity-40 rounded-full transition duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                                    <path d="M8 11l0 5" />
                                    <path d="M8 8l0 .01" />
                                    <path d="M12 16l0 -5" />
                                    <path d="M16 16v-3a2 2 0 0 0 -4 0" />
                                </svg>
                            </a>
                        </div>
                        
                        <div class="w-auto p-2">
                            <!-- Instagram Share Button -->
                            <a href="{{ route('sharetoinstagram', $blogDetails->slug) }}" target="_blank"
                                class="flex items-center justify-center p-3 text-white hover:text-black font-medium tracking-tighter bg-green-900 hover:bg-green-700 border-2 border-white focus:border-green-400 focus:border-opacity-40 hover:border-green-400 focus:ring-4 focus:ring-green-400 focus:ring-opacity-40 rounded-full transition duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M4 4m0 4a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z" />
                                    <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                    <path d="M16.5 7.5l0 .01" />
                                </svg>
                            </a>
                        </div>

                        <div class="w-auto p-2">
                            <!-- WhatsApp Share Button -->
                            <a href="{{ route('sharetowhatsapp', $blogDetails->slug) }}" target="_blank"
                                class="flex items-center justify-center p-3 text-white hover:text-black font-medium tracking-tighter bg-green-900 hover:bg-green-700 border-2 border-white focus:border-green-400 focus:border-opacity-40 hover:border-green-400 focus:ring-4 focus:ring-green-400 focus:ring-opacity-40 rounded-full transition duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" />
                                    <path
                                        d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent blogs --}}
        @if (count($recentBlogs) > 0)
        <div class="pt-16">
            <h2 class="mb-16 text-5xl lg:text-7xl xl:text-8xl text-white tracking-5xl lg:tracking-7xl xl:tracking-8xl">{{ __('Recent Blogs') }}</h2>
            <div class="flex flex-wrap -m-4">
                @foreach ($recentBlogs as $blog)
                <a href="{{ route('web.view.blog', $blog->slug) }}">
                    <div class="w-full md:w-1/2 lg:w-1/3 p-4">
                        <div class="mb-8 overflow-hidden rounded-3xl">
                            <img class="w-full transform hover:scale-125 transition duration-1000" src="{{ asset($blog->cover_image) }}" alt="{{ $blog->heading }}">
                        </div>
                        <div class="flex flex-wrap items-center -m-2 mb-4">
                            <div class="w-auto p-2">
                                <span class="text-sm text-white font-medium tracking-tighter"{{ $blog->blogCategory->blog_category_title }}</span>
                            </div>
                            <div class="w-auto p-2">
                                <svg width="3" height="3" viewbox="0 0 3 3" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2.492 2.5H0.574V0.33H2.492V2.5Z" fill="white"></path>
                                </svg>
                            </div>
                            <div class="w-auto p-2">
                                <span class="text-sm text-white font-medium tracking-tighter">{{ Carbon\Carbon::parse($blog->created_at)->format('d M Y') }}</span>
                            </div>
                        </div>
                        <a class="group block" href="{{ route('web.view.blog', $blog->slug) }}">
                            <h3 class="mb-4 text-3xl text-white tracking-3xl hover:underline">{{ $blog->heading }}</h3>
                        </a>
                        <p class="mb-6 text-white text-opacity-60">{{ $blog->short_description }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

{{-- Footer --}}
@include('website.modern.includes.footer')
@endsection
