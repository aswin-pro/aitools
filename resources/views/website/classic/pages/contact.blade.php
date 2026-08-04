@extends('layouts.classic')

@section('content')

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

{{-- Topbar --}}
@include('website.classic.includes.topbar')

@php
use App\Models\Page;
use App\Models\Setting;

$page = Page::where('slug', 'contact')->where('status', 1)->get();
$settings = Setting::where('status', 1)->first();

// Recaptcha Configuration
$recaptcha_configuration = [
    'RECAPTCHA_ENABLE' => env('RECAPTCHA_ENABLE', 'off'),
    'RECAPTCHA_SITE_KEY' => env('RECAPTCHA_SITE_KEY', ''),
    'RECAPTCHA_SECRET_KEY' => env('RECAPTCHA_SECRET_KEY', ''),
    'RECAPTCHA_SKIP_IP' => env('RECAPTCHA_SKIP_IP', '[]'),
];

$settings['recaptcha_configuration'] = $recaptcha_configuration;
@endphp

{{-- Contact --}}
<section class="py-20 bg-white"
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

        {{-- Contact details --}}
        <div class="flex flex-wrap -mx-4">
            {{-- Details --}}
            @if (!empty($page[1]->body))
                @foreach (preg_split('/(<[^>]*>)/', $page[1]->body, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) as $part)
                    @if (strpos($part, '<') === 0)
                        {!! __($part) !!}
                    @else
                        {{ __($part) }}
                    @endif
                @endforeach
            @endif

            {{-- Form --}}
            <div class="w-full lg:w-1/2 px-5">
                <div class="px-5 py-5 md:p-4 bg-gray-50 rounded-md" data-aos="fade-up" data-aos-delay="150">
                    <form action="{{route('send-email')}}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Success --}}
                        @if(Session::has("success"))
                        <div
                            class="alert flex flex-row items-center bg-{{ $config[11]->config_value }}-200 p-5 rounded border-b-2 border-{{ $config[11]->config_value }}-300 mb-3">
                            <div class="alert-content ml-4">
                                <div class="alert-description text-sm text-{{ $config[11]->config_value }}-500">
                                    {{Session::get('success')}}
                                </div>
                            </div>
                        </div>

                        {{-- Failed --}}
                        @elseif(Session::has("failed"))
                        <div
                            class="alert flex flex-row items-center bg-red-200 p-5 rounded border-b-2 border-red-300 mb-3">
                            <div class="alert-content ml-4">
                                <div class="alert-description text-sm text-red-500">
                                    {{Session::get('failed')}}
                                </div>
                            </div>
                        </div>

                        {{-- Error --}}
                        @elseif(Session::has("error"))
                        <div
                            class="alert flex flex-row items-center bg-red-200 p-5 rounded border-b-2 border-red-300 mb-3">
                            <div class="alert-content ml-4">
                                <div class="alert-description text-sm text-red-500">
                                    {{Session::get('error')}}
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Name --}}
                        <div class="mb-6">
                            <label class="block mb-2 text-gray-800 font-medium leading-6" for="">{{ __('Name')
                                }}</label>
                            <input
                                class="block w-full py-2 px-3 appearance-none border border-gray-200 rounded-lg shadow-md text-gray-500 leading-6 focus:outline-none focus:ring-2 focus:ring-{{ $config[11]->config_value }}-500 focus:ring-opacity-50"
                                type="text" name="emailName" minlength="3" placeholder="{{ __('Full name') }}" required>
                        </div>

                        {{-- Email --}}
                        <div class="mb-6">
                            <label class="block mb-2 text-gray-800 font-medium leading-6" for="">{{ __('Email')
                                }}</label>
                            <input
                                class="block w-full py-2 px-3 appearance-none border border-gray-200 rounded-lg shadow-md text-gray-500 leading-6 focus:outline-none focus:ring-2 focus:ring-{{ $config[11]->config_value }}-500 focus:ring-opacity-50"
                                type="email" name="emailRecipient" placeholder="{{ __('Email Address') }}" required>
                        </div>

                        {{-- Message --}}
                        <div class="mb-6">
                            <label class="block mb-2 text-gray-800 font-medium leading-6" for="">{{ __('Message')
                                }}</label>
                            <textarea
                                class="block h-32 md:h-52 w-full py-2 px-3 appearance-none border border-gray-200 rounded-lg shadow-md text-gray-500 leading-6 focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-{{ $config[11]->config_value }}-500 focus:ring-opacity-50 placeholder-gray-200 resize-none"
                                type="text" minlength="50" name="emailBody" placeholder="{{ __('Enter your message') }}"
                                required></textarea>
                        </div>

                        {{-- Google Recaptcha : v2 Checkbox --}}
                        @if ($settings['recaptcha_configuration']['RECAPTCHA_ENABLE'] == 'on')
                        <div class="mb-6">
                            {!! htmlFormSnippet() !!}
                        </div>
                        @endif

                        <button
                            class="inline-block mb-3 lg:mb-0 lg:mr-3 w-full lg:full py-2 px-6 leading-loose bg-{{ $config[11]->config_value }}-500 hover:bg-{{ $config[11]->config_value }}-600 text-white font-semibold rounded-l-xl rounded-t-xl transition duration-200 text-center">{{
                            __('Send') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Footer --}}
@include('website.classic.includes.footer')
@endsection