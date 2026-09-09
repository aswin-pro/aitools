@extends('layouts.modern-orange')

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
$page = Page::where('theme_id', '317109101703740')->where('slug', 'pricing')->where('status', 1)->get();
@endphp

@section('content')
{{-- Topbar --}}
@include('website.modern-orange.includes.topbar')

{{-- Start Our Plans Section --}}
<section class="relative py-20 overflow-hidden">
  <img class="absolute top-0 right-0 -mr-32 md:-mr-0"
      src="{{ asset('themes/modern-orange/assets/images/pricing/circle-star.png') }}" alt="" />
  <div class="relative container px-4 mx-auto">
        {{-- Start pricing page sections --}}
        @if (!empty($page[0]->body))
            @foreach (preg_split('/(<[^>]*>)/', $page[0]->body, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) as $part)
                @if (strpos($part, '<') === 0)
                    {!! __($part) !!}
                @else
                    {{ __($part) }}
                @endif
            @endforeach
        @endif

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

{{-- Start call action section --}}
@include('website.modern-orange.includes.call-action')
{{-- End call action section --}}

{{-- Footer --}}
@include('website.modern-orange.includes.footer')
@endsection