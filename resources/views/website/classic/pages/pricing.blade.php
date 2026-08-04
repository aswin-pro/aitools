@extends('layouts.classic')

@section('content')

{{-- Custom JS --}}
@section('custom-css')
<link rel="stylesheet" href="{{ asset('css/pricing.css') }}">
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
@include('website.classic.includes.topbar')

@php
use App\Models\Page;
$page = Page::where('slug', 'pricing')->where('status', 1)->get();
@endphp

{{-- Pricing --}}
<section class="pt-24 bg-white"
    style="background-image: url('{{ asset('images/web/elements/pattern-white.svg') }}'); background-position: center;">
    <div class="container px-4 mx-auto">
        {{-- Heading --}}
        @if (!empty($page[0]->body))
            @foreach (preg_split('/(<[^>]*>)/', $page[0]->body, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) as $part)
                @if (strpos($part, '<') === 0)
                    {!! __($part) !!}
                @else
                    {{ __($part) }}
                @endif
            @endforeach
        @endif

        {{-- Pricing --}}
        <div class="flex flex-wrap -mx-4 mb-16">
            {{-- all plans --}}
            @foreach ($plans as $plan)
            <div class="w-full md:w-1/2 lg:w-1/3 p-4" data-aos="fade-up" data-aos-delay="100">
                <div
                    class="flex flex-col pt-8 pb-8 bg-gray-50 rounded-md shadow-md hover:scale-105 transition duration-500">
                    <div class="px-8 pb-8">
                        <h3 class="mb-6 text-lg md:text-xl text-gray-800 font-medium">{{ __($plan->name) }}</h3>
                        <div class="mb-6">
                            @if ($plan->price == 0)
                            <span
                                class="text-6xl md:text-7xl font-semibold {{ $plan->recommended == 1 ? 'text-gray-900' : '' }}">{{
                                __('Free') }}</span>
                            @endif
                            <span class="text-6xl md:text-7xl text-gray-900 font-semibold">{{ $plan->price == 0 ?
                                '' :
                                currency($plan->price) }}</span>
                            <span class="inline-block ml-1 text-gray-500 font-semibold">
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
                            @endif</span>
                        </div>
                        <p class="mb-6 text-gray-400 font-medium">{{ __($plan->description) }}</p>
                        <a class="inline-block mb-3 lg:mb-0 lg:mr-3 w-full lg:full py-2 px-6 leading-loose bg-{{ $config[11]->config_value }}-500 hover:bg-{{ $config[11]->config_value }}-600 text-white font-semibold rounded-l-xl rounded-t-xl transition duration-200 text-center"
                            href="{{ route('register') }}">{{ __('Get Started') }}</a>
                    </div>
                    <div class="border-b border-gray-100"></div>
                    <ul class="self-start px-8 pt-8">
                        {{-- Templates --}}
                        <li class="flex items-center mb-3 text-gray-500 font-medium">
                            <img class="mr-3" src="{{ asset('images/web/elements/checkbox-green.svg') }}">
                            <span>{{ $plan->template_counts }} {{ __('Templates') }}</span>
                            {{-- Info --}}
                            <a onclick="openModal('main-modal'), planTemplates({{ $plan->templates }})"
                                class="cursor-pointer bg-grey-500 hover:bg-grey text-grey-100 font-bold py-1 px-1 rounded inline-flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-info-circle-filled" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path
                                        d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-5 7.66h-1l-.117 .007a1 1 0 0 0 .117 1.993v3l.007 .117a1 1 0 0 0 .993 .883h1l.117 -.007a1 1 0 0 0 .883 -.993l-.007 -.117a1 1 0 0 0 -.876 -.876l-.117 -.007v-3l-.007 -.117a1 1 0 0 0 -.993 -.883zm.01 -4l-.127 .007a1 1 0 0 0 .117 1.993l.127 -.007a1 1 0 0 0 -.117 -1.993z"
                                        fill="currentColor" stroke-width="0"></path>
                                </svg>
                            </a>
                        </li>

                        {{-- AI Words --}}
                        <li class="flex items-center mb-3 text-gray-500 font-medium">
                            <img class="mr-3" src="{{ asset('images/web/elements/checkbox-green.svg') }}">
                            <span>{{ $plan->max_words == 9999 ? __('Unlimited') : $plan->max_words }} {{ __('AI Words') }}</span>
                        </li>

                        {{-- AI Images --}}
                        <li class="flex items-center mb-3 text-gray-500 font-medium">
                            <img class="mr-3" src="{{ asset('images/web/elements/checkbox-green.svg') }}">
                            <span>{{ $plan->max_images == 9999 ? __('Unlimited') : $plan->max_images }} {{ __('AI Images') }}</span>
                        </li>

                        {{-- AI Speech to Text --}}
                        <li class="flex items-center mb-3 text-gray-500 font-medium">
                            @if ($plan->ai_speech_to_text == 1)
                            <img class="mr-3" src="{{ asset('images/web/elements/checkbox-green.svg') }}">
                            @else
                            <img class="mr-3" src="{{ asset('images/web/elements/icons8-cancel-1.svg') }}">
                            @endif
                            <span>{{ __('AI Speech to Text') }}</span>
                        </li>

                        {{-- AI Text to Speech --}}
                        <li class="flex items-center mb-3 text-gray-500 font-medium">
                            @if ($plan->ai_text_to_speech == 1)
                            <img class="mr-3" src="{{ asset('images/web/elements/checkbox-green.svg') }}">
                            @else
                            <img class="mr-3" src="{{ asset('images/web/elements/icons8-cancel-1.svg') }}">
                            @endif
                            <span>{{ __('AI Text to Speech') }}</span>
                        </li>

                        {{-- AI Code --}}
                        <li class="flex items-center mb-3 text-gray-500 font-medium">
                            @if ($plan->ai_code == 1)
                            <img class="mr-3" src="{{ asset('images/web/elements/checkbox-green.svg') }}">
                            @else
                            <img class="mr-3" src="{{ asset('images/web/elements/icons8-cancel-1.svg') }}">
                            @endif
                            <span>{{ __('AI Code') }}</span>
                        </li>

                        {{-- AI Chat Assistant --}}
                        <li class="flex items-center mb-3 text-gray-500 font-medium">
                            @if ($plan->ai_chatgenius == 1)
                            <img class="mr-3" src="{{ asset('images/web/elements/checkbox-green.svg') }}">
                            @else
                            <img class="mr-3" src="{{ asset('images/web/elements/icons8-cancel-1.svg') }}">
                            @endif
                            <span>{{ __('AI Chat Assistant') }}</span>
                        </li>

                        {{-- AI File Analyzer --}}
                        <li class="flex items-center mb-3 text-gray-500 font-medium">
                            @if ($plan->ai_docsassist == 1)
                            <img class="mr-3" src="{{ asset('images/web/elements/checkbox-green.svg') }}">
                            @else
                            <img class="mr-3" src="{{ asset('images/web/elements/icons8-cancel-1.svg') }}">
                            @endif
                            <span>{{ __('AI File Analyzer') }}</span>
                        </li>

                        {{-- AI Web Chat --}}
                        <li class="flex items-center mb-3 text-gray-500 font-medium">
                            @if ($plan->ai_webchat == 1)
                            <img class="mr-3" src="{{ asset('images/web/elements/checkbox-green.svg') }}">
                            @else
                            <img class="mr-3" src="{{ asset('images/web/elements/icons8-cancel-1.svg') }}">
                            @endif
                            <span>{{ __('AI Web Chat') }}</span>
                        </li>

                        {{-- Multiple AI Languages --}}
                        <li class="flex items-center mb-3 text-gray-500 font-medium">
                            <img class="mr-3" src="{{ asset('images/web/elements/checkbox-green.svg') }}">
                            <span>{{ __('Multiple Languages') }}</span>
                        </li>

                        {{-- Rich Editor --}}
                        <li class="flex items-center mb-3 text-gray-500 font-medium">
                            <img class="mr-3" src="{{ asset('images/web/elements/checkbox-green.svg') }}">
                            <span>{{ __('Rich Editor') }}</span>
                        </li>

                        {{-- Additional Tools --}}
                        <li class="flex items-center mb-3 text-gray-500 font-medium">
                            @if ($plan->additional_tools == 1)
                            <img class="mr-3" src="{{ asset('images/web/elements/checkbox-green.svg') }}">
                            @else
                            <img class="mr-3" src="{{ asset('images/web/elements/icons8-cancel-1.svg') }}">
                            @endif
                            <span>{{ __('Additional Tools') }}</span>
                        </li>

                        {{-- Support --}}
                        <li class="flex items-center mb-3 text-gray-500 font-medium">
                            @if ($plan->support == 1)
                            <img class="mr-3" src="{{ asset('images/web/elements/checkbox-green.svg') }}">
                            @else
                            <img class="mr-3" src="{{ asset('images/web/elements/icons8-cancel-1.svg') }}">
                            @endif
                            <span>{{ __('Support (Email)') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Quick call --}}
        @if (!empty($page[1]->body))
            @foreach (preg_split('/(<[^>]*>)/', $page[1]->body, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) as $part)
                @if (strpos($part, '<') === 0)
                    {!! __($part) !!}
                @else
                    {{ __($part) }}
                @endif
            @endforeach
        @endif
    </div>
    <div class="h-64 bg-gray-50"></div>
</section>

{{-- Info Modal --}}
<div class="main-modal fixed w-full inset-0 z-50 overflow-hidden flex justify-center items-center animated fadeIn faster hidden"
    style="background: rgba(0,0,0,.7);">
    <div class="border border-{{ $config[11]->config_value }}-500 shadow-lg modal-container bg-white lg:w-4/12 md:w-full mx-auto rounded-xl shadow-lg z-50 overflow-y-auto"
        style="height: 600px;">
        <div class="modal-content py-4 text-left px-6">
            <!--Title-->
            <header class="flex items-center justify-between p-2">
                <h2 class="text-2xl font-bold text-gray-500">{{ __('Available Templates') }}</h2>
                <div class="modal-close cursor-pointer z-50" onclick="modalClose('main-modal')">
                    <svg class="fill-current text-gray-500" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                        viewBox="0 0 18 18">
                        <path
                            d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                        </path>
                    </svg>
                </div>
            </header>
            <!--Body-->
            <main class="p-2" id="planAvailableTemplates">

            </main>
        </div>
    </div>
</div>

{{-- Footer --}}
@include('website.classic.includes.footer')
 
{{-- Custom JS --}}
@section('custom-js')
<script src="{{ asset('js/pricing.js') }}"></script>
<script>
function planTemplates(templates) {
    var availableTemplates = "";
    $.each( templates, function( i, item ) {
        if (item == 1) {
            replaceText = i.replace("_", " ").replace("_", " ");
            availableTemplates += '<p class="flex items-center mb-3 text-gray-700 font-medium"><img class="mr-3" src="{{ asset("images/web/elements/checkbox-green.svg") }}">'+replaceText.toUpperCase()+'</p>';
        }
    });
    $("#planAvailableTemplates").html(availableTemplates);
}
</script>
@endsection
@endsection