@extends('layouts.modern-orange')

{{-- Custom JS --}}
@section('custom-css')
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
$page = Page::where('theme_id', '317109101703740')->where('slug', 'tools')->where('status', 1)->get();
@endphp

@section('content')
{{-- Topbar --}}
@include('website.modern-orange.includes.topbar')

{{-- Start home page sections --}}
<section class="relative pb-24 md:pb-20 lg:pt-10 overflow-hidden">
    <img class="absolute bottom-0 left-0" src="{{ asset('themes/modern-orange/assets/images/features/start-left-bottom.png') }}" alt="">
    <img class="absolute bottom-1/2 right-0 transform translate-y-1/2" src="{{ asset('themes/modern-orange/assets/images/features/blue-light-right.png') }}" alt="">
    <div class="relative container px-4 mx-auto">
      <div class="max-w-7xl mx-auto">
        
        @if (!empty($page[0]->body))
          @foreach (preg_split('/(<[^>]*>)/', $page[0]->body, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) as $part)
              @if (strpos($part, '<') === 0)
                  {!! __($part) !!}
              @else
                  {{ __($part) }}
              @endif
          @endforeach
        @endif

        <div class="flex flex-wrap -mx-4">
          {{-- Tools --}}
          @foreach ($templates as $tool)
          <div class="w-full lg:w-1/3 my-6 lg:mb-0 transition duration-500 ease-in-out transform hover:-translate-y-1 hover:scale-110">
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
    </div>
</section>

{{-- Start call action section --}}
@include('website.modern-orange.includes.call-action')
{{-- End call action section --}}

{{-- Footer --}}
@include('website.modern-orange.includes.footer')
@endsection
