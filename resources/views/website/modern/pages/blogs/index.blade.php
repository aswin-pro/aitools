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
@endsection

{{-- Topbar --}}
@include('website.modern.includes.topbar')

{{-- Start blogs --}}
<section class="py-24 overflow-hidden">
    <div class="container px-4 mx-auto">
        <div class="mb-20 md:max-w-2xl text-center mx-auto">
            <h2 class="text-7xl lg:text-8xl text-white tracking-7xl lg:tracking-8xl animated-gradient-text">
                {{ __('Blogs') }}</h2>
        </div>
        <div class="flex flex-wrap -m-4">
            {{-- Blogs --}}
            @if (count($blogs) > 0)
                @foreach ($blogs as $blog)
                <a href="{{ route('web.view.blog', $blog->slug) }}">
                    <div class="w-full md:w-1/2 lg:w-1/3 p-4">
                        <div class="mb-8 overflow-hidden rounded-3xl">
                            <img class="w-full transform hover:scale-125 transition duration-1000"
                                src="{{ asset($blog->cover_image) }}" alt="{{ $blog->heading }}">
                        </div>
                        <div class="flex flex-wrap items-center -m-2 mb-4">
                            <div class="w-auto p-2">
                                <span
                                    class="text-sm text-white font-medium tracking-tighter">{{ $blog->user->name }}</span>
                            </div>
                            <div class="w-auto p-2">
                                <svg width="3" height="3" viewbox="0 0 3 3" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2.492 2.5H0.574V0.33H2.492V2.5Z" fill="white"></path>
                                </svg>
                            </div>
                            <div class="w-auto p-2">
                                <span
                                    class="text-sm text-white font-medium tracking-tighter">{{ Carbon\Carbon::parse($blog->created_at)->format('d M Y') }}</span>
                            </div>
                        </div>
                        <a class="group block" href="{{ route('web.view.blog', $blog->slug) }}">
                            <h3 class="mb-4 text-3xl text-white tracking-3xl hover:underline">{{ $blog->heading }}</h3>
                        </a>
                        <p class="mb-6 text-white text-opacity-60">{{ $blog->short_description }}</p>
                        <div class="flex flex-wrap -m-1.5">
                            <div class="w-auto p-1.5">
                                <div
                                    class="py-3.5 px-6 text-sm text-white hover:text-black font-medium tracking-tighter bg-blueGray-900 bg-opacity-30 hover:bg-green-400 rounded-full transition duration 300">
                                    {{ $blog->blogCategory->blog_category_title }}</div>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach

                {{-- Pagination --}}
                {{ $blogs->links('vendor.pagination.blog') }}
            @else
                <div class="w-full p-4">
                    <h3
                        class="mb-4 text-3xl md:text-4xl leading-tight text-darkCoolGray-900 font-bold tracking-tighter">
                        {{ __('No blog posts found!') }}</h3>
                </div>
            @endif
        </div>
    </div>
</section>
{{-- End blogs --}}

{{-- Footer --}}
@include('website.modern.includes.footer')
@endsection
