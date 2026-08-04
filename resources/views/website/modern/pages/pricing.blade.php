@extends('layouts.modern')

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
    $page = Page::where('theme_id', '330599619570398')->where('slug', 'pricing')->where('status', 1)->get();
@endphp

@section('content')
    {{-- Topbar --}}
    @include('website.modern.includes.topbar')

    {{-- Start home page sections --}}
    <section class="py-24 overflow-hidden">
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

            <div class="flex flex-wrap -m-4">
                {{-- Plans --}}
                @foreach ($plans as $plan)
                <div class="w-full md:w-1/2 lg:w-1/3 p-4">
                    <div class="relative px-8 pt-12 pb-12 h-full bg-gradient-radial-dark border-2 border-gray-900 border-opacity-30 overflow-hidden rounded-5xl">
                    <h2 class="mb-2 text-4xl text-white font-medium">{{ __($plan->name) }}</h2>
                    <p class="mb-6 text-gray-300">{{ __($plan->description) }}</p>
                    <p class="mb-4 text-white font-medium text-5xl">
                        @if ($plan->price == 0)
                        <span>{{ __('Free') }}</span>
                        @endif
                        <span>{{ $plan->price == 0 ? '' : currency($plan->price) }}</span>
                        <span class="text-base font-medium text-gray-300">/
                        @if ($plan->price != 0 && $plan->validity == 9999)
                            {{ __('Forever') }}</span>
                        @endif
                        @if ($plan->validity == 31)
                        {{ __('Per Month') }}</span>
                        @endif
                        @if ($plan->validity == 366)
                        {{ __('Per Year') }}</span>
                        @endif
                        @if ($plan->validity > 1 && $plan->validity != 31 && $plan->validity != 366 &&
                        $plan->validity != 9999)
                        {{ 'Per'.' '.$plan->validity.' '.__('Days') }}</span>
                        @endif
                        </span>
                    </p>
                    <p class="mb-6 text-xs text-gray-300 font-light uppercase">{{ __('What\'s includes')}}</p>
                    <ul class="mb-10">
                        {{-- Templates --}}
                        <li class="flex items-center mb-3">
                        <div class="flex items-center justify-center w-5 h-5 mr-4 border border-green-400 rounded-full">
                            <img src="{{ asset('themes/modern/assets/images/modals/check.svg') }}" alt="">
                        </div>
                        <p class="text-white">{{ $plan->template_counts }} {{ __('Templates') }}</p>
                        </li>
                        {{-- AI Words --}}
                        <li class="flex items-center mb-3">
                        <div class="flex items-center justify-center w-5 h-5 mr-4 border border-green-400 rounded-full">
                            <img class="w-2" src="{{ asset('themes/modern/assets/images/modals/check.svg') }}" alt="">
                        </div>
                        <p class="text-white">{{ $plan->max_words == 9999 ? __('Unlimited') : $plan->max_words }} {{ __('AI Words') }}</p>
                        </li>
                        {{-- AI Images --}}
                        <li class="flex items-center mb-3">
                        <div class="flex items-center justify-center w-5 h-5 mr-4 border border-green-400 rounded-full">
                            <img class="w-2" src="{{ asset('themes/modern/assets/images/modals/check.svg') }}" alt="">
                        </div>
                        <p class="text-white">{{ $plan->max_images == 9999 ? __('Unlimited') : $plan->max_images }} {{ __('AI Images') }}</p>
                        </li>
                        {{-- AI Speech to Text --}}
                        <li class="flex items-center mb-3">
                        <div class="flex items-center justify-center w-5 h-5 mr-4 border {{ $plan->ai_speech_to_text == 1 ? "border-green-400" : "border-gray-400" }} rounded-full">
                            <img class="w-2" src="{{ asset($plan->ai_speech_to_text == 1 ? "themes/modern/assets/images/modals/check.svg" : "themes/modern/assets/images/modals/close-icon.png") }}" alt="">
                        </div>
                        <p class="text-white">{{ __('AI Speech to Text') }}</p>
                        </li>
                        {{-- AI Text to Speech --}}
                        <li class="flex items-center mb-3">
                        <div class="flex items-center justify-center w-5 h-5 mr-4 border {{ $plan->ai_text_to_speech == 1 ? "border-green-400" : "border-gray-400" }} rounded-full">
                            <img class="w-2" src="{{ asset($plan->ai_text_to_speech == 1 ? "themes/modern/assets/images/modals/check.svg" : "themes/modern/assets/images/modals/close-icon.png") }}" alt="">
                        </div>
                        <p class="text-white">{{ __('AI Text to Speech') }}</p>
                        </li>
                        {{-- AI Code --}}
                        <li class="flex items-center mb-3">
                        <div class="flex items-center justify-center w-5 h-5 mr-4 border {{ $plan->ai_code == 1 ? "border-green-400" : "border-gray-400" }} rounded-full">
                            <img class="w-2" src="{{ asset($plan->ai_code == 1 ? "themes/modern/assets/images/modals/check.svg" : "themes/modern/assets/images/modals/close-icon.png") }}" alt="">
                        </div>
                        <p class="text-white">{{ __('AI Code') }}</p>
                        </li>
                        {{-- AI Chat Assistant --}}
                        <li class="flex items-center mb-3">
                            <div class="flex items-center justify-center w-5 h-5 mr-4 border {{ $plan->ai_code == 1 ? "border-green-400" : "border-gray-400" }} rounded-full">
                            <img class="w-2" src="{{ asset($plan->ai_chatgenius == 1 ? "themes/modern/assets/images/modals/check.svg" : "themes/modern/assets/images/modals/close-icon.png") }}" alt="">
                            </div>
                            <p class="text-white">{{ __('AI Chat Assistant') }}</p>
                        </li>
                        {{-- AI File Analyzer --}}
                        <li class="flex items-center mb-3">
                            <div class="flex items-center justify-center w-5 h-5 mr-4 border {{ $plan->ai_code == 1 ? "border-green-400" : "border-gray-400" }} rounded-full">
                            <img class="w-2" src="{{ asset($plan->ai_docsassist == 1 ? "themes/modern/assets/images/modals/check.svg" : "themes/modern/assets/images/modals/close-icon.png") }}" alt="">
                            </div>
                            <p class="text-white">{{ __('AI File Analyzer') }}</p>
                        </li>
                        {{-- AI Web Chat --}}
                        <li class="flex items-center mb-3">
                            <div class="flex items-center justify-center w-5 h-5 mr-4 border {{ $plan->ai_code == 1 ? "border-green-400" : "border-gray-400" }} rounded-full">
                            <img class="w-2" src="{{ asset($plan->ai_webchat == 1 ? "themes/modern/assets/images/modals/check.svg" : "themes/modern/assets/images/modals/close-icon.png") }}" alt="">
                            </div>
                            <p class="text-white">{{ __('AI Web Chat') }}</p>
                        </li>
                        {{-- Multiple Languages --}}
                        <li class="flex items-center mb-3">
                        <div class="flex items-center justify-center w-5 h-5 mr-4 border border-green-400 rounded-full">
                            <img class="w-2" src="themes/modern/assets/images/modals/check.svg" alt="">
                        </div>
                        <p class="text-white">{{ __('Multiple Languages') }}</p>
                        </li>
                        {{-- Rich Editor --}}
                        <li class="flex items-center mb-3">
                        <div class="flex items-center justify-center w-5 h-5 mr-4 border border-green-400 rounded-full">
                            <img class="w-2" src="themes/modern/assets/images/modals/check.svg" alt="">
                        </div>
                        <p class="text-white">{{ __('Rich Editor') }}</p>
                        </li>
                        {{-- Additional Tools --}}
                        <li class="flex items-center mb-3">
                        <div class="flex items-center justify-center w-5 h-5 mr-4 border {{ $plan->additional_tools == 1 ? "border-green-400" : "border-gray-400" }} rounded-full">
                            <img class="w-2" src="{{ asset($plan->additional_tools == 1 ? "themes/modern/assets/images/modals/check.svg" : "themes/modern/assets/images/modals/close-icon.png") }}" alt="">
                        </div>
                        <p class="text-white">{{ __('Additional Tools') }}</p>
                        </li>
                        {{-- Support --}}
                        <li class="flex items-center mb-3">
                        <div class="flex items-center justify-center w-5 h-5 mr-4 border {{ $plan->support == 1 ? "border-green-400" : "border-gray-400" }} rounded-full">
                            <img class="w-2" src="{{ asset($plan->support == 1 ? "themes/modern/assets/images/modals/check.svg" : "themes/modern/assets/images/modals/close-icon.png") }}" alt="">
                        </div>
                        <p class="text-white">{{ __('Support (Email)') }}</p>
                        </li>
                    </ul>
                    <a class="relative z-10 block px-14 py-4 text-center font-medium tracking-2xl border-2 border-green-400 bg-green-400 hover:bg-green-500 text-black focus:ring-4 focus:ring-green-500 focus:ring-opacity-40 rounded-full transition duration-300" href="{{ route('register') }}">{{ __('Start now') }}</a>
                    <img class="absolute bottom-0 right-0" src="{{ asset('themes/modern/assets/images/pricing/shadow.svg') }}" alt="">
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Start call action section --}}
    @include('website.modern.includes.call-action')
    {{-- End call action section --}}

    {{-- Footer --}}
    @include('website.modern.includes.footer')
@endsection
