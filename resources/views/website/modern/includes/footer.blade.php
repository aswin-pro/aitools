@php
// Settings
use App\Models\Setting;
use App\Models\Page;

$setting = Setting::where('status', 1)->first();
$pages = Page::get();
$socialLinks = Page::where('theme_id', '330599619570398')->where('slug', 'social-links')->where('status', 1)->get();
@endphp

<section class="py-12 lg:px-12 overflow-hidden">
    <div class="container px-4 mx-auto">
      <div class="flex flex-wrap justify-center -m-8 mb-12">
        <div class="w-full md:w-1/2 lg:w-4/12 p-8">
          <div class="md:max-w-xs">
            <img class="image-shadow mb-3 h-14" src="{{ asset($setting->site_logo) }}" alt="{{ config('app.name') }}">
          </div>
        </div>
        <div class="w-full md:w-1/2 lg:w-2/12 p-8">
          <h3 class="mb-6 text-lg text-white font-medium">{{ __('About Company') }}</h3>
          <ul>
            {{-- About us --}}
            @if($pages[19]->slug == 'about' && $pages[19]->status == 1)
            <li class="mb-2.5"><a class="inline-block text-lg font-medium text-gray-300 hover:text-white transition duration-300" href="{{ route('web.about') }}">{{ __('About Us') }}</a></li>
            @endif
            {{-- Blogs --}}
            <li class="mb-2.5"><a class="inline-block text-lg font-medium text-gray-300 hover:text-white transition duration-300" href="{{ route('web.blogs') }}">{{ __('Blog') }}</a></li>
            {{-- Contact --}}
            @if($pages[21]->slug == 'contact' && $pages[21]->status == 1)
            <li class="mb-2.5"><a class="inline-block text-lg font-medium text-gray-300 hover:text-white transition duration-300" href="{{ route('web.contact') }}">{{ __('Help') }}</a></li>
            @endif
          </ul>
        </div>
        <div class="w-full md:w-1/2 lg:w-2/12 p-8">
          <h3 class="mb-6 text-lg text-white font-medium">{{ __('Useful Links') }}</h3>
          <ul>
            {{-- FAQs --}}
            @if($pages[22]->slug == 'faq' && $pages[22]->status == 1)
            <li class="mb-2.5"><a class="inline-block text-lg font-medium text-gray-300 hover:text-white transition duration-300" href="{{ route('web.faq') }}">{{ __('FAQs') }}</a></li>
            @endif
            {{-- Privacy Policy --}}
            @if($pages[23]->slug == 'privacy-policy' && $pages[23]->status == 1)
            <li class="mb-2.5"><a class="inline-block text-lg font-medium text-gray-300 hover:text-white transition duration-300" href="{{ route('web.privacy') }}">{{ __('Privacy Policy') }}</a></li>
            @endif
            {{-- Refund Policy --}}
            @if($pages[24]->slug == 'refund-policy' && $pages[24]->status == 1)
            <li class="mb-2.5"><a class="inline-block text-lg font-medium text-gray-300 hover:text-white transition duration-300" href="{{ route('web.refund') }}">{{ __('Refund Policy') }}</a></li>
            @endif
            {{-- Terms and Conditions --}}
            @if($pages[25]->slug == 'terms-and-conditions' && $pages[25]->status == 1)
            <li class="mb-2.5"><a class="inline-block text-lg font-medium text-gray-300 hover:text-white transition duration-300" href="{{ route('web.terms') }}">{{ __('Terms and conditions') }}</a></li>
            @endif
          </ul>
        </div>
        {{-- Social Links --}}
        @if (!empty($socialLinks[0]->body))
            @foreach (preg_split('/(<[^>]*>)/', $socialLinks[0]->body, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) as $part)
                @if (strpos($part, '<') === 0)
                    {!! __($part) !!}
                @else
                    {{ __($part) }}
                @endif
            @endforeach
        @endif
      </div>
      <div class="flex flex-wrap justify-between -m-2">
        <div class="w-auto p-2">
          <p class="inline-block text-sm font-medium text-white text-opacity-60">© {{ now()->year }} {{ __(config('app.name')) }}. {{ __('All rights reserved.') }}</p>
        </div>
        <div class="w-auto p-2">
          <div class="flex flex-wrap items-center -m-2 sm:-m-7">
            {{-- Terms and Conditions --}}
            @if($pages[25]->slug == 'terms-and-conditions' && $pages[25]->status == 1)
            <div class="w-auto p-2 sm:p-7"><a class="inline-block text-sm text-white text-opacity-60 hover:text-opacity-100 font-medium transition duration-300" href="{{ route('web.terms') }}">{{ __('Terms and conditions') }}</a></div>
            @endif
            {{-- Privacy Policy --}}
            @if($pages[23]->slug == 'privacy-policy' && $pages[23]->status == 1)
            <div class="w-auto p-2 sm:p-7"><a class="inline-block text-sm text-white text-opacity-60 hover:text-opacity-100 font-medium transition duration-300" href="{{ route('web.privacy') }}">{{ __('Privacy Policy') }}</a></div>
            @endif
          </div>
        </div>
      </div>
    </div>
</section>