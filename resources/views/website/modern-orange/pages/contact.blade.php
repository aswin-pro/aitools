@extends('layouts.modern-orange')

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

@php
    // Settings
    use App\Models\Config;
    use App\Models\Page;

    $config = Config::get();
    $page = Page::where('theme_id', '317109101703740')->where('slug', 'contact')->where('status', 1)->get();
@endphp

@section('content')
    {{-- Topbar --}}
    @include('website.modern-orange.includes.topbar')

    {{-- Start home page sections --}}
    <section class="relative py-20 md:py-32 overflow-hidden">
        <img class="absolute top-0 left-0" src="themes/modern-orange/assets/images/contact/light-left-blue.png" alt="">
        <img class="absolute bottom-0 right-0 -mb-20" src="themes/modern-orange/assets/images/contact/light-orange-right.png"
            alt="">
        <div class="relative container px-4 mx-auto">
            <div class="max-w-7xl mx-auto">
                <div class="max-w-2xl text-center mx-auto mb-20">
                    <span
                        class="inline-block py-1 px-3 mb-4 text-xs font-semibold text-orange-900 bg-orange-50 rounded-full">{{ __('READY TO SUPPORT US') }}</span>
                    <h1 class="font-heading text-5xl xs:text-6xl font-bold text-gray-900 mb-4">
                        <span class="animated-gradient-text">{{ __('Let’s stay') }}</span>
                        <span class="font-serif italic">{{ __('connected') }}</span>
                    </h1>
                    <p class="text-xl text-gray-500 font-semibold">{{ __('We help people to grow their business using saturn ui library with professional and powerfull solution.') }}</p>
                </div>
                <div class="xs:max-w-sm lg:max-w-none mx-auto">
                    <div class="flex flex-wrap items-center -mx-4 mb-18">
                        <div class="w-full lg:w-1/3 px-4 mb-12 lg:mb-0">
                            <div class="flex items-center lg:justify-center">
                                <div
                                    class="flex flex-shrink-0 mr-5 sm:mr-8 items-center justify-center p-1 w-16 sm:w-20 h-16 sm:h-20 rounded-full bg-blue-200">
                                    <img src="themes/modern-orange/assets/images/contact/icon-phone.svg" alt="">
                                </div>
                                <div>
                                    <span class="text-lg text-gray-500">{{ __('Phone') }}</span>
                                    <span class="block text-lg font-semibold text-gray-900">+1 891 4937</span>
                                </div>
                            </div>
                        </div>
                        <div class="w-full lg:w-1/3 px-4 mb-12 lg:mb-0">
                            <div class="flex items-center lg:justify-center">
                                <div
                                    class="flex flex-shrink-0 mr-5 sm:mr-8 items-center justify-center p-1 w-16 sm:w-20 h-16 sm:h-20 rounded-full bg-yellow-200">
                                    <img src="themes/modern-orange/assets/images/contact/icon-email.svg" alt="">
                                </div>
                                <div>
                                    <span class="text-lg text-gray-500">{{ __('Email') }}</span>
                                    <span class="block text-lg font-semibold text-gray-900">hello@yourdomain.com</span>
                                </div>
                            </div>
                        </div>
                        <div class="w-full lg:w-1/3 px-4">
                            <div class="flex items-center lg:justify-center">
                                <div
                                    class="flex flex-shrink-0 mr-5 sm:mr-8 items-center justify-center p-1 w-16 sm:w-20 h-16 sm:h-20 rounded-full bg-green-200">
                                    <img class="h-8" src="themes/modern-orange/assets/images/contact/icon-location.svg"
                                        alt="">
                                </div>
                                <div>
                                    <span class="text-lg text-gray-500">{{ __('Office') }}</span>
                                    <span class="block text-lg font-semibold text-gray-900">213, New York</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rounded-4xl overflow-hidden">
                    <iframe class="w-full" id="gmap_canvas" style="height: 601px;"
                        src="your-google-map-iframe-src-code" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                </div>
            </div>
        </div>
    </section>

    {{-- Start call action section --}}
    @include('website.modern-orange.includes.call-action')
    {{-- End call action section --}}

    {{-- Footer --}}
    @include('website.modern-orange.includes.footer')
@endsection
