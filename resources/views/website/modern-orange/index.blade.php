@extends('layouts.modern-orange')

{{-- Custom JS --}}
@section('custom-css')
<!-- lightbox -->
<link rel="stylesheet" href="{{ asset('css/spotlight.min.css') }}">
<script src="{{ asset('js/spotlight.min.js') }}"></script>
<style>
.tool {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>

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
$page = Page::where('theme_id', '317109101703740')->where('slug', 'home')->where('status', 1)->get();
$featureSection = Page::where('theme_id', '317109101703740')->where('slug', 'features')->where('status', 1)->get();
$pricing = Page::where('theme_id', '317109101703740')->where('slug', 'pricing-home')->where('status', 1)->get();
@endphp

@section('content')
    {{-- Start Header --}}
    @include('website.modern-orange.includes.topbar')
    {{-- End Header --}}

    {{-- Start Feature Section --}}
    @if (!empty($featureSection[0]->body))
        @foreach (preg_split('/(<[^>]*>)/', $featureSection[0]->body, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) as $part)
            @if (strpos($part, '<') === 0)
                {!! __($part) !!}
            @else
                {{ __($part) }}
            @endif
        @endforeach
    @endif
    {{-- End Feature Section --}}

    {{-- Start Tools Section --}}
    <section class="relative pt-20 pb-24 md:pb-20 lg:pt-32 overflow-hidden">
        <img class="absolute bottom-0 left-0" src="{{ asset('themes/modern-orange/assets/images/features/start-left-bottom.png') }}" alt="">
        <img class="absolute bottom-1/2 right-0 transform translate-y-1/2" src="{{ asset('themes/modern-orange/assets/images/features/blue-light-right.png') }}" alt="">
        <div class="relative container px-4 mx-auto">
          <div class="max-w-7xl mx-auto">
            <div class="flex flex-wrap items-center -mx-4 mb-24">
              <div class="w-full lg:w-2/3 px-4 mb-16 lg:mb-0">
                <div class="max-w-lg lg:max-w-2xl mx-auto lg:mx-0">
                  <h1 class="font-heading text-5xl xs:text-6xl md:text-7xl font-bold text-gray-900 mb-8">
                    <span class="animated-gradient-text">{{ __('Dynamic') }}</span>
                    <span class="font-serif italic">{{ __('Content Creation') }}</span>
                  </h1>
                  <p class="max-w-xl text-2xl md:text-3xl font-semibold text-gray-400">{{ __("Leverage OpenAI's dynamic content creation. Our advanced algorithms generate diverse, compelling content, ensuring your messaging stays fresh and engaging.") }}</p>
                </div>
              </div>
              <div class="w-full lg:w-1/3 px-4">
                <img class="w-full max-w-lg mx-auto lg:mr-0" src="{{ asset('themes/modern-orange/assets/images/features/violet-image.png') }}" alt="">
              </div>
            </div>
            <div class="flex flex-wrap -mx-4">
              {{-- Tools --}}
              @foreach ($tools as $tool)
              <div class="w-full lg:w-1/3 my-12 lg:mb-0 transition duration-500 ease-in-out transform hover:-translate-y-1 hover:scale-110">
                <a href="{{ route('user.new.ai.content', $tool->unique_slug) }}">
                  <div class="max-w-xs mx-auto pb-10 lg:pb-5 text-center border-b border-gray-100 lg:border-b-0 shadow-xl bg-gray-100 rounded-3xl p-5">
                    <div class="flex mx-auto mb-5 items-center justify-center w-15 h-15 rounded-full bg-orange-400">
                      <img src="{{ asset('themes/modern-orange/assets/images/features/icon-point.svg') }}" alt="">
                    </div>
                    <h4 class="text-2xl text-gray-900 font-semibold mb-3">{{ __($tool->name) }}</h4>
                    <p class="text-gray-500 tool">{{ __($tool->description) }}</p>
                  </div>
                </a>
              </div>
              @endforeach
            </div>
          </div>
          <div class="w-full px-4 my-12 lg:mb-0 lg:border-r border-gray-100 text-center">
            <a class="relative group inline-block py-4 px-6 text-white font-semibold bg-orange-900 rounded-full overflow-hidden" href="{{ route('user.new.ai.templates') }}">
              <div class="absolute top-0 right-full w-full h-full bg-gray-900 transform group-hover:translate-x-full group-hover:scale-102 transition duration-500"></div>
              <div class="relative flex items-center justify-center">
                <span class="mr-4">{{ __('See more tools') }}</span>
                <span>
                  <svg width="8" height="12" viewbox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.83 5.28999L2.59 1.04999C2.49704 0.956266 2.38644 0.881872 2.26458 0.831103C2.14272 0.780334 2.01202 0.754196 1.88 0.754196C1.74799 0.754196 1.61729 0.780334 1.49543 0.831103C1.37357 0.881872 1.26297 0.956266 1.17 1.04999C0.983753 1.23736 0.879211 1.49081 0.879211 1.75499C0.879211 2.01918 0.983753 2.27263 1.17 2.45999L4.71 5.99999L1.17 9.53999C0.983753 9.72736 0.879211 9.98081 0.879211 10.245C0.879211 10.5092 0.983753 10.7626 1.17 10.95C1.26344 11.0427 1.37426 11.116 1.4961 11.1658C1.61794 11.2155 1.7484 11.2408 1.88 11.24C2.01161 11.2408 2.14207 11.2155 2.26391 11.1658C2.38575 11.116 2.49656 11.0427 2.59 10.95L6.83 6.70999C6.92373 6.61703 6.99813 6.50643 7.04889 6.38457C7.09966 6.26271 7.1258 6.13201 7.1258 5.99999C7.1258 5.86798 7.09966 5.73728 7.04889 5.61542C6.99813 5.49356 6.92373 5.38296 6.83 5.28999Z" fill="#FAFBFC"></path>
                  </svg>
                </span>
              </div>
            </a>
          </div>
        </div>
      </section>
    {{-- End Tools Section --}}

    {{-- Start Images Section --}}
    <section class="relative py-20 md:py-32 overflow-hidden bg-gray-100">
        <div class="container px-4 mx-auto">
          <div class="max-w-7xl mx-auto">
            <div class="max-w-2xl mx-auto mb-18 text-center">
              <h1 class="font-heading text-5xl xs:text-6xl md:text-7xl font-bold text-gray-900 mb-8">
                <span>{{ __('Visual') }}</span>
                <span class="font-serif italic">{{ __('Creativity Unleashed') }}</span>
              </h1>
              <p class="text-lg text-gray-500">{{ __('Our image generator employs advanced algorithms, pushing the boundaries of digital imagery.' )}}</p>
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
                        <h5 class="text-xl font-bold capitalize">{{ __($image->name) }}</h5>
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
                        <h5 class="text-xl font-bold capitalize">{{ __("Generated by OpenAI") }}</h5>
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
            <a class="relative group inline-block py-4 px-6 text-white font-semibold bg-orange-900 rounded-full overflow-hidden" href="{{ route('user.new.ai.image') }}">
              <div class="absolute top-0 right-full w-full h-full bg-gray-900 transform group-hover:translate-x-full group-hover:scale-102 transition duration-500"></div>
              <div class="relative flex items-center justify-center">
                <span class="mr-4">{{ __('Generate your image') }}</span>
                <span>
                  <svg width="8" height="12" viewbox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.83 5.28999L2.59 1.04999C2.49704 0.956266 2.38644 0.881872 2.26458 0.831103C2.14272 0.780334 2.01202 0.754196 1.88 0.754196C1.74799 0.754196 1.61729 0.780334 1.49543 0.831103C1.37357 0.881872 1.26297 0.956266 1.17 1.04999C0.983753 1.23736 0.879211 1.49081 0.879211 1.75499C0.879211 2.01918 0.983753 2.27263 1.17 2.45999L4.71 5.99999L1.17 9.53999C0.983753 9.72736 0.879211 9.98081 0.879211 10.245C0.879211 10.5092 0.983753 10.7626 1.17 10.95C1.26344 11.0427 1.37426 11.116 1.4961 11.1658C1.61794 11.2155 1.7484 11.2408 1.88 11.24C2.01161 11.2408 2.14207 11.2155 2.26391 11.1658C2.38575 11.116 2.49656 11.0427 2.59 10.95L6.83 6.70999C6.92373 6.61703 6.99813 6.50643 7.04889 6.38457C7.09966 6.26271 7.1258 6.13201 7.1258 5.99999C7.1258 5.86798 7.09966 5.73728 7.04889 5.61542C6.99813 5.49356 6.92373 5.38296 6.83 5.28999Z" fill="#FAFBFC"></path>
                  </svg>
                </span>
              </div>
            </a>
          </div>
        </div>
    </section>
    {{-- End Images Section --}}


    {{-- Start Our Plans Section --}}
    <section class="relative py-20 overflow-hidden">
        <img class="absolute top-0 right-0 -mr-32 md:-mr-0"
            src="{{ asset('themes/modern-orange/assets/images/pricing/circle-star.png') }}" alt="" />
        <div class="relative container px-4 mx-auto">
            <div class="max-w-2xl lg:max-w-5xl mx-auto mb-18 text-center">
                <span
                    class="inline-block py-1 px-3 mb-4 text-xs font-semibold bg-orange-900 text-orange-100 rounded-full">{{ __('OUR PLANS') }}</span>
                <h1 class="font-heading text-4xl sm:text-6xl lg:text-7xl font-bold text-gray-900 mb-10">
                    <span>{{ __('Choose the') }}</span>
                    <span class="font-serif italic">{{ __('perfect plan') }}</span>
                    <span>{{ __('for your needs') }}</span>
                </h1>
            </div>
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-wrap -mx-4">
                    {{-- Plans --}}
                    @foreach ($plans as $plan)
                    <div class="w-full lg:w-1/3 px-4 my-8 lg:mb-0">
                        <div
                            class="max-w-sm lg:max-w-none mx-auto pt-10 px-10 pb-8 bg-white border border-gray-100 rounded-3xl transition duration-500 ease-in-out transform hover:-translate-y-1 hover:scale-110">
                            <div class="text-center mb-6">
                                <h5 class="text-3xl font-semibold text-gray-900 mb-3">
                                    {{ __($plan->name) }}
                                </h5>
                                <p class="mb-6 text-gray-600">{{ __($plan->description) }}</p>
                                {{-- Price --}}
                                @if ($plan->price == 0)
                                <span class="block text-5xl font-bold text-gray-900 mb-3">{{ __('Free') }}</span>
                                @endif
                                
                                <span class="block text-5xl font-bold text-gray-900 mb-3">{{ $plan->price == 0 ? '' : currency($plan->price) }}</span>
                                
                                <span class="block text-gray-500 font-medium mb-6">/
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
                                <a class="inline-block w-full py-4 px-6 text-center text-orange-900 border border-gray-200 hover:border-orange-900 font-semibold rounded-full transition duration-200"
                                    href="{{ route('register') }}">{{ __('Start now')}}</a>
                            </div>
                            <ul>
                                <li class="flex mb-4 items-center">
                                    <img src="{{ asset('themes/modern-orange/assets/images/pricing/green-check.svg') }}"
                                        alt="" /><span class="ml-2 text-gray-900 font-medium">{{ $plan->template_counts }} {{ __('Templates') }}</span>
                                </li>
                                <li class="flex mb-4 items-center">
                                    <img src="{{ asset('themes/modern-orange/assets/images/pricing/green-check.svg') }}"
                                        alt="" /><span class="ml-2 text-gray-900 font-medium">{{ $plan->max_words == 9999 ? __('Unlimited') : $plan->max_words }} {{ __('AI Words') }}</span>
                                </li>
                                <li class="flex mb-4 items-center">
                                    <img src="{{ asset('themes/modern-orange/assets/images/pricing/green-check.svg') }}"
                                        alt="" /><span class="ml-2 text-gray-900 font-medium">{{ $plan->max_images == 9999 ? __('Unlimited') : $plan->max_images }} {{ __('AI Images') }}</span>
                                </li>
                                <li class="flex mb-4 items-center">
                                    <img src="{{ asset($plan->ai_speech_to_text == 1 ? 'themes/modern-orange/assets/images/pricing/green-check.svg' : 'themes/modern-orange/assets/images/pricing/check-circle-grey.svg') }}"
                                        alt="" /><span class="ml-2 font-medium {{ $plan->ai_speech_to_text == 1 ? 'text-gray-900' : 'text-gray-500 line-through' }}">{{ __('AI Speech to Text') }}</span>
                                </li>
                                <li class="flex mb-4 items-center">
                                    <img src="{{ asset($plan->ai_text_to_speech == 1 ? 'themes/modern-orange/assets/images/pricing/green-check.svg' : 'themes/modern-orange/assets/images/pricing/check-circle-grey.svg') }}"
                                        alt="" /><span class="ml-2 font-medium {{ $plan->ai_text_to_speech == 1 ? 'text-gray-900' : 'text-gray-500 line-through' }}">{{ __('AI Text to Speech') }}</span>
                                </li>
                                <li class="flex mb-4 items-center">
                                    <img src="{{ asset($plan->ai_code == 1 ? 'themes/modern-orange/assets/images/pricing/green-check.svg' : 'themes/modern-orange/assets/images/pricing/check-circle-grey.svg') }}"
                                        alt="" /><span
                                        class="ml-2 font-medium {{ $plan->ai_code == 1 ? 'text-gray-900' : 'text-gray-500 line-through' }}">{{ __('AI Code') }}</span>
                                </li>
                                <li class="flex mb-4 items-center">
                                    <img src="{{ asset($plan->ai_chatgenius == 1 ? 'themes/modern-orange/assets/images/pricing/green-check.svg' : 'themes/modern-orange/assets/images/pricing/check-circle-grey.svg') }}"
                                        alt="" /><span
                                        class="ml-2 font-medium {{ $plan->ai_chatgenius == 1 ? 'text-gray-900' : 'text-gray-500 line-through' }}">{{ __('AI Chat Assistant') }}</span>
                                </li>
                                <li class="flex mb-4 items-center">
                                  <img src="{{ asset($plan->ai_docsassist == 1 ? 'themes/modern-orange/assets/images/pricing/green-check.svg' : 'themes/modern-orange/assets/images/pricing/check-circle-grey.svg') }}"
                                      alt="" /><span
                                      class="ml-2 font-medium {{ $plan->ai_docsassist == 1 ? 'text-gray-900' : 'text-gray-500 line-through' }}">{{ __('AI File Analyzer') }}</span>
                                </li>
                                <li class="flex mb-4 items-center">
                                  <img src="{{ asset($plan->ai_webchat == 1 ? 'themes/modern-orange/assets/images/pricing/green-check.svg' : 'themes/modern-orange/assets/images/pricing/check-circle-grey.svg') }}"
                                      alt="" /><span
                                      class="ml-2 font-medium {{ $plan->ai_webchat == 1 ? 'text-gray-900' : 'text-gray-500 line-through' }}">{{ __('AI Web Chat') }}</span>
                                </li>
                                <li class="flex mb-4 items-center">
                                    <img src="{{ asset('themes/modern-orange/assets/images/pricing/green-check.svg') }}"
                                        alt="" /><span
                                        class="ml-2 font-medium text-gray-900">{{ __('Multiple Languages') }}</span>
                                </li>
                                <li class="flex mb-4 items-center">
                                    <img src="{{ asset('themes/modern-orange/assets/images/pricing/green-check.svg') }}"
                                        alt="" /><span
                                        class="ml-2 font-medium text-gray-900">{{ __('Rich Editor') }}</span>
                                </li>
                                <li class="flex mb-4 items-center">
                                    <img src="{{ asset($plan->additional_tools == 1 ? 'themes/modern-orange/assets/images/pricing/green-check.svg' : 'themes/modern-orange/assets/images/pricing/check-circle-grey.svg') }}"
                                        alt="" /><span
                                        class="ml-2 font-medium {{ $plan->additional_tools == 1 ? 'text-gray-900' : 'text-gray-500 line-through' }}">{{ __('Additional Tools') }}</span>
                                </li>
                                <li class="flex items-center">
                                    <img src="{{ asset($plan->support == 1 ? 'themes/modern-orange/assets/images/pricing/green-check.svg' : 'themes/modern-orange/assets/images/pricing/check-circle-grey.svg') }}"
                                        alt="" /><span
                                        class="ml-2 font-medium {{ $plan->support == 1 ? 'text-gray-900' : 'text-gray-500 line-through' }}">{{ __('Support (Email)') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    {{-- End Our plans Section --}}

    {{-- Start  Newsletter --}}
    @include('website.modern-orange.includes.call-action')
    {{-- End newsletter --}}

    {{-- Start  Footer --}}
    @include('website.modern-orange.includes.footer')
    {{-- End  Footerr --}}
@endsection
