@php
// Settings
use App\Models\Setting;
use App\Models\Page;

$setting = Setting::where('status', 1)->first();
$pages = Page::get();
@endphp

<section class="relative {{ request()->is('/') ? 'pb-20' : '' }} overflow-hidden">
    @if (request()->is('/'))
        <img class="absolute top-0 right-0 w-52 md:w-auto"
          src="{{ asset('themes/modern-orange/assets/images/headers/star-background-header.png') }}" alt="" />
        <img class="absolute top-0 right-0 mt-10 mr-10"
          src="{{ asset('themes/modern-orange/assets/images/headers/light-orange-blue-1.png') }}" alt="" />
        <img class="absolute top-0 right-0 -mr-10 sm:-mr-0 mt-64 md:mt-125 lg:mt-88 xl:mt-112 h-32 md:h-60 lg:h-88 animate-moveHand"
          src="{{ asset('themes/modern-orange/assets/images/headers/robot-hand-header.png') }}" alt="" />
    @endif
    <nav class="relative py-6 mb-6 {{ request()->is('/') ? 'mb-12 md:mb-20' : '' }} bg-transparent">
        <div class="container px-4 mx-auto">
            <div class="flex items-center">
                <a class="inline-block text-lg font-bold" href="{{ route('web.index') }}">
                    <img class="h-10" src="{{ asset($setting->site_logo) }}" alt="{{ config('app.name') }}" width="auto" /></a>
                <div class="lg:hidden ml-auto">
                    <button
                        class="navbar-burger flex w-12 h-12 items-center justify-center bg-gray-100 hover:bg-gray-200 rounded-md transition duration-200">
                        <svg width="24" height="24" viewbox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 12H21" stroke="black" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"></path>
                            <path d="M3 6H21" stroke="black" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"></path>
                            <path d="M3 18H21" stroke="black" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"></path>
                        </svg>
                    </button>
                </div>
                <ul class="hidden lg:flex ml-14 lg:w-auto lg:space-x-12">
                    <li class="group relative">
                        <a class="inline-block text-sm text-gray-900 hover:text-orange-900 font-medium"
                            href="{{ route('web.index') }}">{{ __('Home') }}</a>
                    </li>

                    {{-- Tools --}}
                    @if($pages[27]->slug == 'tools' && $pages[27]->status == 1)
                    <li>
                        <a class="inline-block text-sm text-gray-900 hover:text-orange-900 font-medium"
                            href="{{ route('web.tools') }}">{{ __('Tools') }}</a>
                    </li>
                    @endif

                    {{-- Features --}}
                    @if($pages[28]->slug == 'features' && $pages[28]->status == 1)
                    <li>
                        <a class="inline-block text-sm text-gray-900 hover:text-orange-900 font-medium"
                            href="{{ route('web.features') }}">{{ __('Features') }}</a>
                    </li>
                    @endif

                    {{-- About us --}}
                    @if($pages[29]->slug == 'about' && $pages[29]->status == 1)
                    <li>
                        <a class="inline-block text-sm text-gray-900 hover:text-orange-900 font-medium"
                            href="{{ route('web.about') }}">{{ __('About Us') }}</a>
                    </li>
                    @endif

                    {{-- Pricing --}}
                    @if($pages[35]->slug == 'pricing' && $pages[35]->status == 1)
                    <li>
                        <a class="inline-block text-sm text-gray-900 hover:text-orange-900 font-medium"
                            href="{{ route('web.pricing') }}">{{ __('Pricing') }}</a>
                    </li>
                    @endif

                    {{-- Contact us --}}
                    @if($pages[30]->slug == 'contact' && $pages[30]->status == 1)
                    <li>
                        <a class="inline-block text-sm text-gray-900 hover:text-orange-900 font-medium"
                            href="{{ route('web.contact') }}">{{ __('Contact Us') }}</a>
                    </li>
                    @endif

                    <!-- Custom Pages -->
                    @if($pages)
                        @foreach($pages as $page)
                            @if($page->slug != 'home' && $page->slug != 'hero' && $page->slug != 'newsletter' && $page->slug != 'pricing-home' && $page->slug != 'social-links' && $page->slug != 'tools' && $page->slug != 'features' && $page->slug != 'about' && $page->slug != 'contact' && $page->slug != 'faq' && $page->slug != 'pricing' && $page->slug != 'privacy-policy' && $page->slug != 'refund-policy' && $page->slug != 'terms-and-conditions' && $page->status == 1)
                            <li class="inline-block text-sm text-gray-900 hover:text-orange-900 font-medium">
                                <a href="{{ route('web.custom.page', $page->slug) }}">{{ __($page->title) }}</a>
                            </li>
                            @endif
                        @endforeach
                    @endif
                </ul>
                <div class="hidden lg:block ml-auto">
                    <div class="flex items-center">
                        {{-- Languages --}}
                        @if(count(config('app.languages')) > 1)
                            <div class="relative mx-5">
                                <select class="w-full py-3 px-4 text-sm text-gray-900 font-medium placeholder-gray-400 border border-gray-200 focus:border-purple-500 focus:outline-purple rounded-lg appearance-none" name="field-name" onchange="window.location.href = this.value;">
                                    @foreach(config('app.languages') as $langLocale => $langName)
                                        <option value="{{ url()->current() }}?change_language={{ $langLocale }}" {{ app()->getLocale() == $langLocale ? 'selected' : '' }}>{{ strtoupper($langName) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        {{-- Login --}}
                        @guest
                        @if (Route::has('login'))
                        <a class="inline-block mr-9 text-sm font-semibold text-orange-900 hover:text-gray-900"
                            href="{{ route('login') }}">{{ __('Login') }}</a>
                        @endif

                        {{-- Register --}}
                        @if (Route::has('register'))
                        <a class="relative group inline-block py-3 px-4 text-sm font-semibold text-orange-900 hover:text-white border border-gray-200 rounded-md overflow-hidden transition duration-300"
                            href="{{ route('register') }}">
                            <div
                                class="absolute top-0 right-full w-full h-full bg-orange-900 transform group-hover:translate-x-full group-hover:scale-102 transition duration-500">
                            </div>
                            <span class="relative">{{ __('Create an account') }}</span>
                        </a>
                        @endif
                        @else
                        {{-- Dashboard --}}
                        <a class="relative group inline-block py-3 px-4 text-sm font-semibold text-orange-900 hover:text-white border border-gray-200 rounded-md overflow-hidden transition duration-300"
                            href="{{ route('user.dashboard') }}">
                            <div
                                class="absolute top-0 right-full w-full h-full bg-orange-900 transform group-hover:translate-x-full group-hover:scale-102 transition duration-500">
                            </div>
                            <span class="relative">{{ __('Dashboard') }}</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- Start Hero --}}
    @include('website.modern-orange.includes.banner')
    {{-- End Hero --}}

    <div class="hidden navbar-menu fixed top-0 left-0 bottom-0 w-5/6 max-w-md z-50">
        <div class="navbar-backdrop fixed inset-0 bg-gray-800 opacity-25"></div>
        <nav class="relative flex flex-col py-6 px-10 w-full h-full bg-white border-r overflow-y-auto">
            <div class="flex items-center mb-16">
                <a class="mr-auto text-2xl font-medium leading-none" href="{{ route('web.index') }}">
                    <img class="h-10" src="{{ asset($setting->site_logo) }}" alt="{{ config('app.name') }}" width="auto" /></a>
                <button class="navbar-close">
                    <svg class="h-6 w-6 text-gray-500 cursor-pointer hover:text-gray-500"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewbox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div>
                <ul class="mb-2">
                    {{-- Home --}}
                    <li>
                        <a class="block py-4 text-gray-900 hover:bg-orange-50 rounded-lg"
                            href="{{ route('web.index') }}">{{ __('Home') }}</a>
                    </li>

                    {{-- About --}}
                    @if($pages[29]->slug == 'about' && $pages[29]->status == 1)
                    <li>
                        <a class="block py-4 text-gray-900 hover:bg-orange-50 rounded-lg"
                            href="{{ route('web.about') }}">{{ __('About Us') }}</a>
                    </li>
                    @endif

                    {{-- Features --}}
                    @if($pages[28]->slug == 'features' && $pages[28]->status == 1)
                    <li>
                        <a class="block py-4 text-gray-900 hover:bg-orange-50 rounded-lg"
                            href="{{ route('web.features') }}">{{ __('Features') }}</a>
                    </li>
                    @endif

                    {{-- Tools --}}
                    @if($pages[27]->slug == 'tools' && $pages[27]->status == 1)
                    <li>
                        <a class="block py-4 text-gray-900 hover:bg-orange-50 rounded-lg"
                            href="{{ route('web.tools') }}">{{ __('Tools') }}</a>
                    </li>
                    @endif

                    {{-- Pricing --}}
                    @if($pages[35]->slug == 'pricing' && $pages[35]->status == 1)
                    <li>
                        <a class="block py-4 text-gray-900 hover:bg-orange-50 rounded-lg"
                            href="{{ route('web.pricing') }}">{{ __('Pricing') }}</a>
                    </li>
                    @endif

                    {{-- Contact --}}
                    @if($pages[30]->slug == 'contact' && $pages[30]->status == 1)
                    <li>
                        <a class="block py-4 text-gray-900 hover:bg-orange-50 rounded-lg"
                            href="{{ route('web.contact') }}">{{ __('Contact Us') }}</a>
                    </li>
                    @endif
                </ul>
                <div class="py-3 mb-6 border-t border-b border-gray-200">
                    {{-- Login --}}
                    @guest
                    @if (Route::has('login'))
                    <a class="flex items-center text-sm font-semibold text-orange-900 hover:text-orange-600 py-3"
                        href="{{ route('login') }}">
                        {{ __('Sign In') }}
                    </a>
                    @endif

                    {{-- Register --}}
                    @if (Route::has('register'))
                    <a class="flex items-center text-sm font-semibold text-orange-900 hover:text-orange-600 py-3"
                        href="{{ route('register') }}">
                        {{ __('Create an account') }}
                    </a>
                    @endif
                    @else
                    <a class="flex items-center text-sm font-semibold text-orange-900 hover:text-orange-600 py-3"
                        href="{{ route('user.dashboard') }}">
                        {{ __('Dashboard') }}
                    </a>
                    @endif

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
            </div>
        </nav>
    </div>
</section>
 