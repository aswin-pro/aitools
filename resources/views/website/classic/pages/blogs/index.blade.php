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
@endsection

{{-- Topbar --}}
@include('website.classic.includes.topbar')

{{-- Blogs --}}
<section class="lg:py-24 py-12 md:pb-32 bg-white"
    style="background-image: url({{ asset('images/web/elements/pattern-white.svg') }}); background-repeat: no-repeat; background-position: center top;">
    <div class="container px-2 mx-auto">
        <div class="flex flex-wrap items-center lg:mb-12 mb-8">
            <div class="w-full mb-8 md:mb-0">
                <span
                    class="inline-block py-px px-2 mb-4 text-sm leading-5 text-yellow-500 bg-yellow-100 font-medium uppercase rounded-full shadow-sm">{{ __('Blogs') }}</span>
                <h3 class="mb-4 text-3xl md:text-4xl leading-tight text-darkCoolGray-900 font-bold tracking-tighter">
                    {{ __('Unlock the Future of Content Creation with AI Tools') }}</h3>
                <p class="text-md md:text-md text-coolGray-500 font-medium">
                    {{ __('Unleash the Power of Artificial Intelligence: Craft Exceptional Blogs, Articles, and Social Media Posts Effortlessly.') }}
                </p>
            </div>
        </div>
        {{-- Blogs --}}
        @if (count($blogs) > 0)
            <div class="grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-2 mb-8">
                @foreach ($blogs as $blog)
                    <div class="p-3 m-1 mb-8 shadow-md">
                        <a class="block mb-2 overflow-hidden rounded-md" href="{{ route('web.view.blog', $blog->slug) }}">
                            <img class="w-100 h-60 object-cover mb-3" src="{{ asset($blog->cover_image) }}" alt="{{ $blog->heading }}">
                            <p class="mb-3 text-coolGray-500 font-medium">{{ $blog->user->name }} •
                                {{ Carbon\Carbon::parse($blog->created_at)->format('d M Y') }}</p>
                            <h2
                                class="inline-block mb-3 text-2xl leading-tight text-coolGray-800 hover:text-coolGray-900 font-bold hover:underline">
                                {{ $blog->heading }}</h2>
                            <p
                                class="inline-block mb-3 py-1 px-3 text-xs leading-5 text-yellow-500 hover:text-yellow-600 font-medium uppercase bg-yellow-100 hover:bg-yellow-200 rounded-full shadow-sm">
                                {{ $blog->blogCategory->blog_category_title }}</p>
                            <p class="text-base md:text-md text-coolGray-400 font-small">
                                {{ $blog->short_description }}</p>
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            {{ $blogs->links('vendor.pagination.blog') }}
        @else
            <div class="flex flex-wrap">
                <div class="w-full">
                    <h3
                        class="mb-4 text-3xl md:text-4xl leading-tight text-darkCoolGray-900 font-bold tracking-tighter">
                        {{ __('No blog posts found!') }}</h3>
                </div>
            </div>
        @endif
    </div>
</section>

{{-- Footer --}}
@include('website.classic.includes.footer')
@endsection
