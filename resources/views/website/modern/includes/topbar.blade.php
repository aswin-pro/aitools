@php
    // Settings
    use App\Models\Setting;
    use App\Models\Page;

    $setting = Setting::where('status', 1)->first();
    $pages = Page::get();
@endphp

<section class="relative lg:px-12 overflow-hidden">
    <div class="container px-4 mx-auto">
        <div class="flex items-center justify-between pt-5 pb-2.5 -m-2">
            <div class="w-auto p-2">
                <div class="flex flex-wrap items-center">
                    <div class="w-auto">
                        <a class="relative z-10 inline-block" href="{{ route('web.index') }}">
                            <img class="image-shadow h-10" src="{{ asset($setting->site_logo) }}" alt="{{ config('app.name') }}">
                        </a>
                    </div>
                </div>
            </div>
            <div class="w-auto p-2">
                <div class="flex flex-wrap items-center">
                    <div class="w-auto hidden lg:block">
                        <ul class="flex items-center mr-12">
                            {{-- Home --}}
                            <li
                                class="mr-12 text-white font-medium hover:text-opacity-90 tracking-tighter {{ request()->is('/') ? 'text-green-400' : '' }}">
                                <a href="{{ route('web.index') }}">{{ __('Home') }}</a></li>

                            {{-- Tools --}}
                            @if ($pages[17]->slug == 'tools' && $pages[17]->status == 1)
                                <li
                                    class="mr-12 text-white font-medium hover:text-opacity-90 tracking-tighter {{ request()->is('tools') ? 'text-green-400' : '' }}">
                                    <a href="{{ route('web.tools') }}">{{ __('Tools') }}</a></li>
                            @endif

                            {{-- Features --}}
                            @if ($pages[18]->slug == 'features' && $pages[18]->status == 1)
                                <li
                                    class="mr-12 text-white font-medium hover:text-opacity-90 tracking-tighter {{ request()->is('features') ? 'text-green-400' : '' }}">
                                    <a href="{{ route('web.features') }}">{{ __('Features') }}</a></li>
                            @endif

                            {{-- About us --}}
                            @if ($pages[19]->slug == 'about' && $pages[19]->status == 1)
                                <li
                                    class="mr-12 text-white font-medium hover:text-opacity-90 tracking-tighter {{ request()->is('about') ? 'text-green-400' : '' }}">
                                    <a href="{{ route('web.about') }}">{{ __('About Us') }}</a></li>
                            @endif

                            {{-- Pricing --}}
                            @if ($pages[20]->slug == 'pricing' && $pages[20]->status == 1)
                                <li
                                    class="mr-12 text-white font-medium hover:text-opacity-90 tracking-tighter {{ request()->is('pricing') ? 'text-green-400' : '' }}">
                                    <a href="{{ route('web.pricing') }}">{{ __('Pricing') }}</a></li>
                            @endif

                            {{-- Contact us --}}
                            @if ($pages[21]->slug == 'contact' && $pages[21]->status == 1)
                                <li
                                    class="text-white font-medium hover:text-opacity-90 tracking-tighter {{ request()->is('contact') ? 'text-green-400' : '' }}">
                                    <a href="{{ route('web.contact') }}">{{ __('Contact Us') }}</a></li>
                            @endif

                            <!-- Custom Pages -->
                            @if ($pages)
                                @foreach ($pages as $page)
                                    @if (
                                        $page->slug != 'home' &&
                                            $page->slug != 'hero' &&
                                            $page->slug != 'newsletter' &&
                                            $page->slug != 'pricing-home' &&
                                            $page->slug != 'social-links' &&
                                            $page->slug != 'tools' &&
                                            $page->slug != 'features' &&
                                            $page->slug != 'about' &&
                                            $page->slug != 'contact' &&
                                            $page->slug != 'faq' &&
                                            $page->slug != 'pricing' &&
                                            $page->slug != 'privacy-policy' &&
                                            $page->slug != 'refund-policy' &&
                                            $page->slug != 'terms-and-conditions' &&
                                            $page->status == 1)
                                        <li
                                            class="{{ $loop->last ? 'mx-12' : '' }} text-white font-medium hover:text-opacity-90 tracking-tighter {{ request()->is('p/' . $page->slug) ? 'text-green-400' : '' }}">
                                            <a
                                                href="{{ route('web.custom.page', $page->slug) }}">{{ __($page->title) }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            @endif
                        </ul>
                    </div>
                    <div class="w-auto hidden lg:block">
                        <div class="inline-block">
                            {{-- Languages --}}
                            @if(count(config('app.languages')) > 1)
                                <div class="inline-block mx-5">
                                    <select class="w-full py-3 px-4 text-sm text-gray-900 font-medium placeholder-gray-400 border border-gray-200 focus:border-purple-500 focus:outline-purple rounded-full appearance-none" name="field-name" onchange="window.location.href = this.value;">
                                        @foreach(config('app.languages') as $langLocale => $langName)
                                            <option value="{{ url()->current() }}?change_language={{ $langLocale }}" {{ app()->getLocale() == $langLocale ? 'selected' : '' }}>{{ strtoupper($langName) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            {{-- Login & Register --}}
                            @guest
                                @if (Route::has('login'))
                                    <a class="inline-block px-8 py-2 text-white hover:text-black tracking-tighter hover:bg-green-400 border-2 border-white focus:border-green-400 focus:border-opacity-40 hover:border-green-400 focus:ring-4 focus:ring-green-400 focus:ring-opacity-40 rounded-full transition duration-300"
                                        href="{{ route('login') }}">{{ __('Sign In') }}</a>
                                @endif
                                @if (Route::has('register'))
                                    <a class="inline-block px-8 py-2 text-black hover:text-white hover:text-black tracking-tighter bg-green-400 hover:bg-black border-2 border-white focus:border-green-400 focus:border-opacity-40 hover:border-green-400 focus:ring-4 focus:ring-green-400 focus:ring-opacity-40 rounded-full transition duration-300"
                                        href="{{ route('register') }}">{{ __('Sign Up') }}</a>
                                @endif
                            @else
                                <a class="inline-block px-8 py-2 text-white hover:text-black tracking-tighter hover:bg-green-400 border-2 border-white focus:border-green-400 focus:border-opacity-40 hover:border-green-400 focus:ring-4 focus:ring-green-400 focus:ring-opacity-40 rounded-full transition duration-300"
                                    href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</a>
                            @endguest
                        </div>
                    </div>
                    <div class="w-auto lg:hidden">
                        <a class="relative z-10 inline-block" href="#">
                            <svg class="navbar-burger text-green-500" width="51" height="51" viewbox="0 0 56 56"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="56" height="56" rx="28" fill="currentColor"></rect>
                                <path d="M37 32H19M37 24H19" stroke="black" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hidden navbar-menu fixed top-0 left-0 bottom-0 w-4/6 sm:max-w-xs z-50">
        <div class="navbar-backdrop fixed inset-0 bg-black opacity-80"></div>
        <nav class="relative z-10 px-9 pt-8 h-full bg-black overflow-y-auto">
            <div class="flex flex-wrap justify-between h-full">
                <div class="w-full">
                    <div class="flex items-center justify-between -m-2">
                        <div class="w-auto p-2">
                            <a class="inline-block" href="{{ route('web.index') }}">
                                <img class="image-shadow" src="{{ asset($setting->site_logo) }}"
                                    alt="{{ config('app.name') }}">
                            </a>
                        </div>
                        <div class="w-auto p-2">
                            <a class="navbar-burger inline-block text-white" href="#">
                                <svg width="24" height="24" viewbox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 18L18 6M6 6L18 18" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col justify-center py-16 w-full">
                    <ul>
                        {{-- Home --}}
                        <li class="mb-8 text-white font-medium hover:text-opacity-90 tracking-tighter"><a
                                href="{{ route('web.index') }}">{{ __('Home') }}</a></li>

                        {{-- Tools --}}
                        @if ($pages[17]->slug == 'tools' && $pages[17]->status == 1)
                            <li class="mb-8 text-white font-medium hover:text-opacity-90 tracking-tighter"><a
                                    href="{{ route('web.tools') }}">{{ __('Tools') }}</a></li>
                        @endif

                        {{-- Features --}}
                        @if ($pages[18]->slug == 'features' && $pages[18]->status == 1)
                            <li class="mb-8 text-white font-medium hover:text-opacity-90 tracking-tighter"><a
                                    href="{{ route('web.features') }}">{{ __('Features') }}</a></li>
                        @endif

                        {{-- About --}}
                        @if ($pages[19]->slug == 'about' && $pages[19]->status == 1)
                            <li class="mb-8 text-white font-medium hover:text-opacity-90 tracking-tighter"><a
                                    href="{{ route('web.about') }}">{{ __('About Us') }}</a></li>
                        @endif

                        {{-- Pricing --}}
                        @if ($pages[20]->slug == 'pricing' && $pages[20]->status == 1)
                            <li class="mb-8 text-white font-medium hover:text-opacity-90 tracking-tighter"><a
                                    href="{{ route('web.pricing') }}">{{ __('Pricing') }}</a></li>
                        @endif

                        {{-- Contact --}}
                        @if ($pages[21]->slug == 'contact' && $pages[21]->status == 1)
                            <li class="text-white font-medium hover:text-opacity-90 tracking-tighter"><a
                                    href="{{ route('web.contact') }}">{{ __('Contact Us') }}</a></li>
                        @endif
                    </ul>
                </div>
                <div class="flex flex-col justify-end w-full pb-8">
                    {{-- Login & Register --}}
                    @guest
                        @if (Route::has('login'))
                            <a class="inline-block px-8 py-2 mb-3 text-white hover:text-black tracking-tighter hover:bg-green-400 border-2 border-white focus:border-green-400 focus:border-opacity-40 hover:border-green-400 focus:ring-4 focus:ring-green-400 focus:ring-opacity-40 rounded-full transition duration-300"
                                href="{{ route('login') }}">{{ __('Sign In') }}</a>
                        @endif
                        @if (Route::has('register'))
                            <a class="inline-block px-8 py-2 text-black hover:text-white hover:text-black tracking-tighter bg-green-400 hover:bg-black border-2 border-white focus:border-green-400 focus:border-opacity-40 hover:border-green-400 focus:ring-4 focus:ring-green-400 focus:ring-opacity-40 rounded-full transition duration-300"
                                href="{{ route('register') }}">{{ __('Sign Up') }}</a>
                        @endif
                    @else
                        <a class="inline-block px-8 py-2 text-center text-white hover:text-black tracking-tighter hover:bg-green-400 border-2 border-white focus:border-green-400 focus:border-opacity-40 hover:border-green-400 focus:ring-4 focus:ring-green-400 focus:ring-opacity-40 rounded-full transition duration-300"
                            href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</a>
                    @endguest

                    {{-- Languages --}}
                    @if(count(config('app.languages')) > 1)
                        <div class="inline-block mt-3">
                            <select class="w-full py-3 px-4 text-sm text-gray-900 font-medium placeholder-gray-400 border border-gray-200 focus:border-purple-500 focus:outline-purple rounded-full appearance-none" name="field-name" onchange="window.location.href = this.value;">
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

    {{-- Hero section --}}
    @if (Request::is('/'))
        @include('website.modern.includes.banner')
    @endif
</section>
