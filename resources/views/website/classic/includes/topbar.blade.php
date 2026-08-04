{{-- Custom CSS --}}
@section('custom-css')
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@endsection

@php
// Settings
use App\Models\Setting;
use App\Models\Page;
$setting = Setting::where('status', 1)->first();
$pages = Page::get();
@endphp

{{-- Website menu --}}
<section class="overflow">
    <nav class="relative px-6 py-6 flex justify-between items-center bg-white">
        <a class="text-3xl font-bold leading-none" href="{{ route('web.index') }}">
            <img class="h-10" src="{{ asset($setting->site_logo) }}" alt="{{ config('app.name') }}" width="auto">
        </a>
        <div class="lg:hidden">
            <button class="navbar-burger flex items-center text-gray-400 p-3">
                <svg class="block h-4 w-4 fill-current" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <title>{{ __('Mobile menu') }}</title>
                    <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
                </svg>
            </button>
        </div>

        <ul
            class="hidden absolute top-1/2 left-1/2 transform -translate-y-1/2 -translate-x-1/2 lg:flex lg:mx-auto lg:flex lg:items-center lg:w-auto lg:space-x-2">

            {{-- Menus --}}
            <li><a class="text-sm text-gray-900 hover:text-gray-500 {{ request()->is('/') ? 'font-bold' : '' }} ml-4"
                    href="{{ route('web.index') }}">{{
                    __('Home') }}</a></li>
            <li class="text-gray-300">
                <svg class="w-4 h-4 current-fill" xmlns="http://www.w3.org/2000/svg" fill="none" viewbox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                    </path>
                </svg>
            </li>

            {{-- Tools --}}
            @if($pages[2]->slug == 'tools' && $pages[2]->status == 1)
            <li><a class="text-sm text-gray-900 hover:text-gray-500 {{ request()->is('tools') ? 'font-bold' : '' }} ml-4"
                    href="{{ route('web.tools') }}">{{
                    __('Tools') }}</a></li>
            <li class="text-gray-300">
                <svg class="w-4 h-4 current-fill" xmlns="http://www.w3.org/2000/svg" fill="none" viewbox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                    </path>
                </svg>
            </li>
            @endif

            {{-- Features --}}
            @if($pages[1]->slug == 'features' && $pages[1]->status == 1)
            <li><a class="text-sm text-gray-900 hover:text-gray-500 {{ request()->is('features') ? 'font-bold' : '' }} ml-4"
                    href="{{ route('web.features') }}">{{
                    __('Features') }}</a></li>
            <li class="text-gray-300">
                <svg class="w-4 h-4 current-fill" xmlns="http://www.w3.org/2000/svg" fill="none" viewbox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                    </path>
                </svg>
            </li>
            @endif

            {{-- About --}}
            @if($pages[3]->slug == 'about' && $pages[3]->status == 1)
            <li><a class="text-sm text-gray-900 hover:text-gray-500 {{ request()->is('about') ? 'font-bold' : '' }} ml-4"
                    href="{{ route('web.about') }}">{{
                    __('About') }}</a></li>
            <li class="text-gray-300">
                <svg class="w-4 h-4 current-fill" xmlns="http://www.w3.org/2000/svg" fill="none" viewbox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                    </path>
                </svg>
            </li>
            @endif

            {{-- Pricing --}}
            @if($pages[4]->slug == 'pricing' && $pages[4]->status == 1)
            <li><a class="text-sm text-gray-900 hover:text-gray-500 {{ request()->is('pricing') ? 'font-bold' : '' }}"
                    href="{{ route('web.pricing') }}">{{
                    __('Pricing') }}</a></li>
            <li class="text-gray-300">
                <svg class="w-4 h-4 current-fill" xmlns="http://www.w3.org/2000/svg" fill="none" viewbox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                    </path>
                </svg>
            </li>
            @endif

            {{-- Contact --}}
            @if($pages[7]->slug == 'contact' && $pages[7]->status == 1)
            <li><a class="text-sm text-gray-900 hover:text-gray-500 {{ request()->is('contact') ? 'font-bold' : '' }}"
                    href="{{ route('web.contact') }}">{{ __('Contact')
                    }}</a></li>
            @endif

            <!-- Custom Pages -->
            @if($pages)
            @foreach($pages as $page)
            @if($page->slug != 'home' && $page->slug != 'hero' && $page->slug != 'newsletter' && $page->slug != 'pricing-home' && $page->slug != 'social-links' && $page->slug != 'tools' && $page->slug != 'features' && $page->slug != 'about'
            && $page->slug !=
            'contact' && $page->slug != 'faq' && $page->slug != 'pricing' &&
            $page->slug != 'privacy-policy' && $page->slug != 'refund-policy' &&
            $page->slug != 'terms-and-conditions' && $page->status == 1)
            <li class="text-gray-300">
                <svg class="w-4 h-4 current-fill" xmlns="http://www.w3.org/2000/svg" fill="none" viewbox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                    </path>
                </svg>
            </li>
            <li><a class="text-sm text-gray-900 hover:text-gray-500 {{ request()->is('p/'.$page->slug) ? 'font-bold' : '' }}"
                    href="{{ route('web.custom.page', $page->slug) }}">{{ __($page->title) }}</a></li>
            @endif
            @endforeach
            @endif
        </ul>

        <div class="hidden lg:inline-block">
            {{-- Languages --}}
            @if(count(config('app.languages')) > 1)
                <div class="lg:inline-block mx-5">
                    <select class="w-full py-3 px-4 text-sm text-gray-900 font-medium placeholder-gray-400 border border-gray-200 focus:border-purple-500 focus:outline-purple rounded-lg appearance-none" name="field-name" onchange="window.location.href = this.value;">
                        @foreach(config('app.languages') as $langLocale => $langName)
                            <option value="{{ url()->current() }}?change_language={{ $langLocale }}" {{ app()->getLocale() == $langLocale ? 'selected' : '' }}>{{ strtoupper($langName) }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- Login & Register --}}
            @guest
            @if (Route::has('login'))
            <a class="hidden lg:inline-block py-2 px-6 bg-gray-900 hover:bg-gray-500 text-sm text-white font-bold rounded-l-xl rounded-t-xl transition duration-200"
                href="{{ route('login') }}">{{ __('Sign In') }}</a>
            @endif
            @if (Route::has('register'))
            <a class="hidden lg:inline-block py-2 px-6 bg-{{ $config[11]->config_value }}-500 hover:bg-{{ $config[11]->config_value }}-600 text-sm text-white font-bold rounded-l-xl rounded-t-xl transition duration-200"
                href="{{ route('register') }}">{{ __('Sign Up') }}</a>
            @endif

            @else
            <a class="hidden lg:inline-block py-2 px-6 bg-{{ $config[11]->config_value }}-500 hover:bg-{{ $config[11]->config_value }}-600 text-sm text-white font-bold rounded-l-xl rounded-t-xl transition duration-200"
                href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</a>
            @endguest
        </div>
    </nav>

    {{-- Banner --}}
    @php
    if(request()->is('/')) { @endphp
    @include('website.classic.includes.banner')
    @php } @endphp

    <div class="hidden navbar-menu fixed top-0 left-0 bottom-0 w-5/6 max-w-sm z-50">
        <div class="navbar-backdrop fixed inset-0 bg-gray-800 opacity-25"></div>
        <nav class="relative flex flex-col py-6 px-6 h-full w-full bg-white border-r overflow-y-auto">
            <div class="flex items-center mb-8">
                <a class="mr-auto text-3xl font-bold leading-none" href="{{ route('web.index') }}">
                    <img class="h-10" src="{{ asset($setting->site_logo) }}" alt="{{ config('app.name') }}"
                        width="auto">
                </a>
                <button class="navbar-close">
                    <svg class="h-6 w-6 text-gray-400 cursor-pointer hover:text-gray-500"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewbox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div>
                {{-- Menu --}}
                <ul>
                    {{-- Home --}}
                    <li class="mb-1"><a
                            class="block p-4 text-sm font-semibold text-gray-900 hover:bg-{{ $config[11]->config_value }}-50 hover:text-{{ $config[11]->config_value }}-500 rounded {{ request()->is('/') ? 'font-bold' : '' }}"
                            href="{{ route('web.index') }}">{{ __('Home') }}</a></li>
                    @if($pages[2]->slug == 'tools' && $pages[2]->status == 1)
                    {{-- Tools --}}
                    <li class="mb-1"><a
                            class="block p-4 text-sm font-semibold text-gray-900 hover:bg-{{ $config[11]->config_value }}-50 hover:text-{{ $config[11]->config_value }}-500 rounded {{ request()->is('tools') ? 'font-bold' : '' }}"
                            href="{{ route('web.tools') }}">{{ __('Tools') }}</a></li>
                    @endif
                    @if($pages[1]->slug == 'features' && $pages[1]->status == 1)
                    {{-- Features --}}
                    <li class="mb-1"><a
                            class="block p-4 text-sm font-semibold text-gray-900 hover:bg-{{ $config[11]->config_value }}-50 hover:text-{{ $config[11]->config_value }}-500 rounded {{ request()->is('features') ? 'font-bold' : '' }}"
                            href="{{ route('web.features') }}">{{ __('Features') }}</a></li>
                    @endif
                    {{-- About --}}
                    @if($pages[3]->slug == 'about' && $pages[3]->status == 1)
                    <li class="mb-1"><a
                            class="block p-4 text-sm font-semibold text-gray-900 hover:bg-{{ $config[11]->config_value }}-50 hover:text-{{ $config[11]->config_value }}-500 rounded {{ request()->is('about') ? 'font-bold' : '' }}"
                            href="{{ route('web.about') }}">{{ __('About Us') }}</a></li>
                    @endif
                    {{-- Pricing --}}
                    @if($pages[4]->slug == 'pricing' && $pages[4]->status == 1)
                    <li class="mb-1"><a
                            class="block p-4 text-sm font-semibold text-gray-900 hover:bg-{{ $config[11]->config_value }}-50 hover:text-{{ $config[11]->config_value }}-500 rounded {{ request()->is('pricing') ? 'font-bold' : '' }}"
                            href="{{ route('web.pricing') }}">{{ __('Pricing') }}</a></li>
                    @endif
                    {{-- Contact --}}
                    @if($pages[7]->slug == 'contact' && $pages[7]->status == 1)
                    <li class="mb-1"><a
                            class="block p-4 text-sm font-semibold text-gray-900 hover:bg-{{ $config[11]->config_value }}-50 hover:text-{{ $config[11]->config_value }}-500 rounded {{ request()->is('contact') ? 'font-bold' : '' }}"
                            href="{{ route('web.contact') }}">{{ __('Contact') }}</a></li>
                    @endif

                </ul>
            </div>
            <div class="mt-auto">
                <div class="pt-6">
                    {{-- Login & Register --}}
                    @guest
                    @if (Route::has('login'))
                    <a class="block px-4 py-3 mb-3 leading-loose text-xs text-center font-semibold leading-none text-white bg-gray-900 hover:bg-gray-500 rounded-l-xl rounded-t-xl"
                        href="{{ route('login') }}">{{ __('Sign In') }}</a>
                    @endif
                    @if (Route::has('register'))
                    <a class="block px-4 py-3 mb-2 leading-loose text-xs text-center text-white font-semibold bg-{{ $config[11]->config_value }}-500 hover:bg-{{ $config[11]->config_value }}-600 rounded-l-xl rounded-t-xl"
                        href="{{ route('register') }}">{{ __('Sign Up') }}</a>
                    @endif
                    @else
                    <a class="block px-4 py-3 mb-2 leading-loose text-xs text-center text-white font-semibold bg-{{ $config[11]->config_value }}-500 hover:bg-{{ $config[11]->config_value }}-600 rounded-l-xl rounded-t-xl"
                        href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</a>
                    @endguest

                    {{-- Languages --}}
                    @if(count(config('app.languages')) > 1)
                        <div class="relative">
                            <select class="w-full py-3 px-4 text-sm text-gray-900 font-medium placeholder-gray-400 border border-gray-200 focus:border-purple-500 focus:outline-purple rounded-lg appearance-none" name="field-name" onchange="window.location.href = this.value;">
                                @foreach(config('app.languages') as $langLocale => $langName)
                                    <option value="{{ url()->current() }}?change_language={{ $langLocale }}" {{ app()->getLocale() == $langLocale ? 'selected' : '' }}>{{ strtoupper($langName) }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
                <p class="my-4 text-xs text-center text-gray-900">
                    <span>© {{ date('Y') }} {{ __('All rights reserved') }}.</span>
                </p>
                <div class="text-center"> 
                    <a class="inline-block px-1" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-facebook"
                            width="24" height="24" viewbox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3"></path>
                        </svg>
                    </a>
                    <a class="inline-block px-1" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-twitter"
                            width="24" height="24" viewbox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path
                                d="M22 4.01c-1 .49 -1.98 .689 -3 .99c-1.121 -1.265 -2.783 -1.335 -4.38 -.737s-2.643 2.06 -2.62 3.737v1c-3.245 .083 -6.135 -1.395 -8 -4c0 0 -4.182 7.433 4 11c-1.872 1.247 -3.739 2.088 -6 2c3.308 1.803 6.913 2.423 10.034 1.517c3.58 -1.04 6.522 -3.723 7.651 -7.742a13.84 13.84 0 0 0 .497 -3.753c-.002 -.249 1.51 -2.772 1.818 -4.013z">
                            </path>
                        </svg>
                    </a>
                    <a class="inline-block px-1" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-instagram"
                            width="24" height="24" viewbox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <rect x="4" y="4" width="16" height="16" rx="4"></rect>
                            <circle cx="12" cy="12" r="3"></circle>
                            <line x1="16.5" y1="7.5" x2="16.5" y2="7.501"></line>
                        </svg>
                    </a>
                </div>
            </div>
        </nav>
    </div>
</section>