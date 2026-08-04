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
$page = Page::where('theme_id', '330599619570398')->where('slug', 'home')->where('status', 1)->get();
$pricing = Page::where('theme_id', '330599619570398')->where('slug', 'pricing-home')->where('status', 1)->get();
@endphp

@section('content')
{{-- Topbar --}}
@include('website.modern.includes.topbar')

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

{{-- Tools --}}
<section class="relative py-24 lg:px-12 overflow-hidden">
  <div class="container px-4 mx-auto">
    <div class="text-center">
      <span
        class="inline-block mb-4 text-sm text-green-400 font-medium tracking-tighter"
        >{{ __('Tools') }}</span
      >
      <h2
        class="mb-6 text-7xl lg:text-7xl text-white tracking-8xl md:max-w-md mx-auto"
      >
      {{ __('Dynamic Content Creation') }}
      </h2>
      <p class="mb-20 text-gray-300 md:max-w-md mx-auto">
        {{ __('Leverage OpenAI\'s dynamic content creation. Our advanced algorithms generate diverse, compelling content, ensuring your messaging stays fresh and engaging.') }}
      </p>
    </div>
    <div class="flex flex-wrap -m-3">
      {{-- Tools --}}
      @foreach ($tools as $tool)
      <div class="w-full md:w-1/2 lg:w-1/3 p-3">
        <div class="flex flex-wrap -m-3">
          <div class="w-full p-3">
            <a href="{{ route('user.new.ai.content', $tool->unique_slug) }}">
              <div class="px-6 py-8 border border-gray-800 rounded-5xl">
                <div class="flex flex-wrap items-center -m-3 mb-3">
                  <div class="w-auto p-3">
                    <h2 class="text-2xl text-white">{{ __($tool->name) }}</h2>
                  </div>
                </div>
                <p class="mb-4 text-white">
                  {{ __($tool->description) }}
                </p>
              </div>
            </a>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  <div
    class="absolute bottom-0 left-0 z-10 py-16 lg:py-2 flex items-center justify-center w-full bg-gradient-radial-darker3"
  >
    <a
      class="inline-block px-8 py-4 font-medium tracking-tighter bg-green-400 hover:bg-green-500 text-black focus:ring-4 focus:ring-green-500 focus:ring-opacity-40 rounded-full transition duration-300"
      href="{{ route('user.new.ai.templates') }}"
      >{{ __('See more tools') }}</a
    >
  </div>
</section>

{{-- Start Images Section --}}
<section class="relative py-20 md:py-32 lg:px-12 overflow-hidden">
  <div class="container px-4 mx-auto">
    <div class="max-w-7xl mx-auto">
      <div class="max-w-2xl mx-auto mb-18 text-center">
        <h1 class="font-heading text-5xl xs:text-6xl md:text-7xl font-bold mb-8">
          <span>{{ __('Visual') }}</span>
          <span class="font-serif italic">{{ __('Creativity Unleashed') }}</span>
        </h1>
        <p class="text-lg mb-16">{{ __('Our image generator employs advanced algorithms, pushing the boundaries of digital imagery.' )}}</p>
      </div>
      <div class="flex flex-wrap -mx-4 -mb-8">
        {{-- Images --}}
        @if (count($images) > 0)
          @foreach ($images as $image)
          <div class="w-full md:w-1/2 lg:w-1/3 xl:w-1/4 px-4 mb-8">
            <a class="spotlight" href="{{ asset(json_decode($image->result)[0]) }}">
              <div class="max-w-xs md:max-w-none mx-auto h-full pt-3 px-3 pb-5 bg-white transition duration-500 ease-in-out transform hover:-translate-y-1 hover:scale-110">
                <img class="block w-full mb-4 h-64 object-cover" src="{{ asset(json_decode($image->result)[0]) }}" alt="">
                <div class="text-center">
                  <h5 class="text-xl font-bold text-gray-800 capitalize">{{ __($image->name) }}</h5>
                  <span class="text-sm font-bold text-gray-400 capitalize">{{ __($image->type) }}</span>
                </div>
              </div>
            </a>
          </div>
          @endforeach 
        @else
          {{-- Sample images --}}
          @for ($i = 1; $i <= 4; $i++)
          <div class="w-full md:w-1/2 lg:w-1/3 xl:w-1/4 px-4 mb-8">
            <a class="spotlight" href="{{ asset('images/generate-images/ai/0000'.$i.'.png') }}">
              <div class="max-w-xs md:max-w-none mx-auto h-full pt-3 px-3 pb-5 bg-white transition duration-500 ease-in-out transform hover:-translate-y-1 hover:scale-110">
                <img class="block w-full mb-4 h-64 object-cover" src="{{ asset('images/generate-images/ai/0000'.$i.'.png') }}" alt="">
                <div class="text-center">
                  <h5 class="text-xl font-bold text-gray-800 capitalize">{{ __("Generated by OpenAI") }}</h5>
                  <span class="text-sm font-bold text-gray-400 capitalize">{{ __("Concept Art") }}</span>
                </div>
              </div>
            </a>
          </div>
          @endfor
        @endif
      </div>
    </div>
    <div class="w-full px-4 my-12 lg:mb-0 lg:border-r border-gray-100 text-center">
      <a class="inline-block px-8 py-4 font-medium tracking-tighter bg-green-400 hover:bg-green-500 text-black focus:ring-4 focus:ring-green-500 focus:ring-opacity-40 rounded-full transition duration-300" href="{{ route('user.new.ai.image') }}">
        <div class="absolute top-0 right-full w-full h-full bg-gray-900 transform group-hover:translate-x-full group-hover:scale-102 transition duration-500"></div>
        <div class="relative flex items-center justify-center">
          <span class="mr-4">{{ __('Generate your image') }}</span>
          <span>
            <svg width="8" height="12" viewbox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M6.83 5.28999L2.59 1.04999C2.49704 0.956266 2.38644 0.881872 2.26458 0.831103C2.14272 0.780334 2.01202 0.754196 1.88 0.754196C1.74799 0.754196 1.61729 0.780334 1.49543 0.831103C1.37357 0.881872 1.26297 0.956266 1.17 1.04999C0.983753 1.23736 0.879211 1.49081 0.879211 1.75499C0.879211 2.01918 0.983753 2.27263 1.17 2.45999L4.71 5.99999L1.17 9.53999C0.983753 9.72736 0.879211 9.98081 0.879211 10.245C0.879211 10.5092 0.983753 10.7626 1.17 10.95C1.26344 11.0427 1.37426 11.116 1.4961 11.1658C1.61794 11.2155 1.7484 11.2408 1.88 11.24C2.01161 11.2408 2.14207 11.2155 2.26391 11.1658C2.38575 11.116 2.49656 11.0427 2.59 10.95L6.83 6.70999C6.92373 6.61703 6.99813 6.50643 7.04889 6.38457C7.09966 6.26271 7.1258 6.13201 7.1258 5.99999C7.1258 5.86798 7.09966 5.73728 7.04889 5.61542C6.99813 5.49356 6.92373 5.38296 6.83 5.28999Z" fill="#000000"></path>
            </svg>
          </span>
        </div>
      </a>
    </div>
  </div>
</section>
{{-- End Images Section --}}
            
{{-- Pricing --}}
<section class="pb-24 lg:px-12 overflow-hidden">
  <div class="container px-4 mx-auto">
    {{-- Pricing --}}
    @if (!empty($pricing[0]->body))
        @foreach (preg_split('/(<[^>]*>)/', $pricing[0]->body, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) as $part)
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
{{-- End home page sections --}}

{{-- Start call action section --}}
@include('website.modern.includes.call-action')
{{-- End call action section --}}

{{-- Footer --}}
@include('website.modern.includes.footer')

{{-- Custom JS --}}
@section('custom-js')
<script src="{{ asset('js/smooth-scroll.js')}}"></script>
@endsection
@endsection