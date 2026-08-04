@extends('layouts.modern-orange')

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
@include('website.modern-orange.includes.topbar')

{{-- Start Blog Section --}}
<section class="relative py-20 overflow-hidden">
    <img class="hidden sm:block absolute bottom-0 left-0 -mb-24" src="{{ asset('themes/modern-orange/assets/images/blog/blue-light-left.png') }}" alt="">
    <img class="absolute top-0 left-1/2 transform -translate-x-1/2" src="{{ asset('themes/modern-orange/assets/images/blog/orange-light-center.png') }}" alt="">
    <div class="relative container px-4 mx-auto">
        <div class="max-w-xs md:max-w-2xl xl:max-w-7xl mx-auto">
            <div class="flex flex-wrap -mx-4 mb-18 items-end">
                <div class="w-full lg:w-2/3 xl:w-1/2 px-4 mb-14 lg:mb-0">
                    <div>
                        <span
                            class="inline-block py-1 px-3 mb-4 text-xs font-semibold text-orange-900 bg-orange-50 rounded-full">{{ __('OUR BLOG') }}</span>
                        <h1 class="font-heading text-5xl xs:text-6xl md:text-7xl font-bold">
                            <span class="animated-gradient-text">{{ __('News') }} &amp;</span>
                            <span class="font-serif italic">{{ __('Articles') }}</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap -mx-4 -mb-12">
                {{-- Blogs --}}
                @if (count($blogs) > 0)
                @foreach ($blogs as $blog)
                <div class="w-full md:w-1/2 xl:w-1/3 px-4 mb-12 border-r border-gray-100">
                    <a class="block px-4 group" href="{{ route('web.view.blog', $blog->slug) }}">
                        <img class="block w-full h-50 mb-4" src="{{ asset($blog->cover_image) }}" alt="{{ $blog->heading }}">
                        <div class="flex inline">
                            <span class="block text-gray-500 mb-2">{{ Carbon\Carbon::parse($blog->created_at)->format('d M Y') }}</span>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900 group-hover:text-orange-900 mb-4">{{ $blog->heading }}</h4>
                        <p class="text-gray-500">{{ $blog->short_description }}</p>
                    </a>
                </div>
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
    </div>
</section>
{{-- End Blog Section --}}

{{-- Start call action section --}}
@include('website.modern-orange.includes.call-action')
{{-- End call action section --}}

{{-- Footer --}}
@include('website.modern-orange.includes.footer')
@endsection
