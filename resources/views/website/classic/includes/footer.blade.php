@php
// Settings
use App\Models\Setting;
use App\Models\Page;
use App\Models\Blog;

// Queries
$setting = Setting::where('status', 1)->first();
$pages = Page::get();
$blogs = Blog::where('status', 1)->count();
@endphp

<section>
    <div class="skew skew-top mr-for-radius">
        <svg class="h-8 md:h-12 lg:h-20 w-full text-gray-50" viewbox="0 0 10 10" preserveaspectratio="none">
            <polygon fill="currentColor" points="0 0 10 10 0 10"></polygon>
        </svg>
    </div>
    <div class="skew skew-top ml-for-radius">
        <svg class="h-8 md:h-12 lg:h-20 w-full text-gray-50" viewbox="0 0 10 10" preserveaspectratio="none">
            <polygon fill="currentColor" points="0 10 10 0 10 10"></polygon>
        </svg>
    </div>
    <div class="py-20 px-5 bg-gray-50 radius-for-skewed">
        <div class="container mx-auto">
            <div class="pb-12 flex flex-wrap items-center justify-between border-b border-gray-100">
                <div class="w-full lg:w-1/5 mb-4 lg:mb-0 xl:mb-0">
                    <a class="inline-block text-3xl font-bold leading-none" href="{{ url('/') }}">
                        <img class="h-12" src="{{ asset($setting->site_logo) }}" alt="{{ config('app.name') }}"
                            width="auto">
                    </a>
                </div>
                <div class="w-full lg:w-auto">
                    <ul class="flex flex-wrap lg:space-x-0 items-center">
                        {{-- About --}}
                        @if($pages[3]->slug == 'about' && $pages[3]->status == 1)
                        <li class="w-full md:w-auto mb-2 md:mb-0"><a
                                class="lg:text-sm text-gray-900 hover:text-gray-500 {{ request()->is('about') ? 'font-bold' : '' }}"
                                href="{{ route('web.about') }}">{{
                                __('About Us') }}</a></li>
                        <li class="hidden md:block">
                            <svg class="mx-4 w-4 h-4 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewbox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                </path>
                            </svg>
                        </li>
                        @endif

                        {{-- Blogs --}}
                        @if($blogs > 0)
                        <li class="w-full md:w-auto mb-2 md:mb-0"><a
                                class="lg:text-sm text-gray-900 hover:text-gray-500 {{ request()->is('blogs') || request()->is('blog*') ? 'font-bold' : '' }}"
                                href="{{ route('web.blogs') }}">{{
                                __('Blogs') }}</a></li>
                        <li class="hidden md:block">
                            <svg class="mx-4 w-4 h-4 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewbox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                </path>
                            </svg>
                        </li>
                        @endif

                        {{-- Contact --}}
                        @if($pages[7]->slug == 'contact' && $pages[7]->status == 1)
                        <li class="w-full md:w-auto mb-2 md:mb-0"><a
                                class="lg:text-sm text-gray-900 hover:text-gray-500 {{ request()->is('contact') ? 'font-bold' : '' }}"
                                href="{{ route('web.contact') }}">{{
                                __('Help') }}</a></li>
                        <li class="hidden md:block">
                            <svg class="mx-4 w-4 h-4 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewbox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                </path>
                            </svg>
                        </li>
                        @endif

                        {{-- FAQs --}}
                        @if($pages[8]->slug == 'faq' && $pages[8]->status == 1)
                        <li class="w-full md:w-auto mb-2 md:mb-0"><a
                                class="lg:text-sm text-gray-900 hover:text-gray-500 {{ request()->is('faq') ? 'font-bold' : '' }}"
                                href="{{ route('web.faq') }}">{{
                                __('FAQs') }}</a></li>
                        <li class="hidden md:block">
                            <svg class="mx-4 w-4 h-4 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewbox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                </path>
                            </svg>
                        </li>
                        @endif

                        {{-- Privacy Policy --}}
                        @if($pages[9]->slug == 'privacy-policy' && $pages[9]->status == 1)
                        <li class="w-full md:w-auto mb-2 md:mb-0"><a
                                class="lg:text-sm text-gray-900 hover:text-gray-500 {{ request()->is('privacy-policy') ? 'font-bold' : '' }}"
                                href="{{ route('web.privacy') }}">{{
                                __('Privacy Policy') }}</a></li>
                        <li class="hidden md:block">
                            <svg class="mx-4 w-4 h-4 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewbox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                </path>
                            </svg>
                        </li>
                        @endif

                        {{-- Refund Policy --}}
                        @if($pages[10]->slug == 'refund-policy' && $pages[10]->status == 1)
                        <li class="w-full md:w-auto mb-2 md:mb-0"><a
                                class="lg:text-sm text-gray-900 hover:text-gray-500 {{ request()->is('refund-policy') ? 'font-bold' : '' }}"
                                href="{{ route('web.refund') }}">{{
                                __('Refund Policy') }}</a></li>
                        <li class="hidden md:block">
                            <svg class="mx-4 w-4 h-4 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewbox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                </path>
                            </svg>
                        </li>
                        @endif

                        {{-- Terms and Conditions --}}
                        @if($pages[11]->slug == 'terms-and-conditions' && $pages[11]->status == 1)
                        <li class="w-full md:w-auto mb-2 md:mb-0"><a
                                class="lg:text-sm text-gray-900 hover:text-gray-500 {{ request()->is('terms-and-conditions') ? 'font-bold' : '' }}"
                                href="{{ route('web.terms') }}">{{
                                __('Terms and Conditions') }}</a></li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="mt-8 flex flex-wrap justify-between items-center">
                <p class="order-last text-sm text-gray-900">© {{ date("Y") }} {{ config('app.name') }}. {{ __('All rights reserved.') }}</p>
                <div class="mb-4 lg:mb-0 order-first lg:order-last">
                    <a class="inline-block mr-2 p-2 bg-gray-50 hover:bg-gray-100 rounded" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-facebook"
                            width="24" height="24" viewbox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3"></path>
                        </svg>
                    </a>
                    <a class="inline-block mr-2 p-2 bg-gray-50 hover:bg-gray-100 rounded" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-twitter"
                            width="24" height="24" viewbox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path
                                d="M22 4.01c-1 .49 -1.98 .689 -3 .99c-1.121 -1.265 -2.783 -1.335 -4.38 -.737s-2.643 2.06 -2.62 3.737v1c-3.245 .083 -6.135 -1.395 -8 -4c0 0 -4.182 7.433 4 11c-1.872 1.247 -3.739 2.088 -6 2c3.308 1.803 6.913 2.423 10.034 1.517c3.58 -1.04 6.522 -3.723 7.651 -7.742a13.84 13.84 0 0 0 .497 -3.753c-.002 -.249 1.51 -2.772 1.818 -4.013z">
                            </path>
                        </svg>
                    </a>
                    <a class="inline-block mr-2 p-2 bg-gray-50 hover:bg-gray-100 rounded" href="#">
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
        </div>
    </div>
    <div class="skew skew-bottom mr-for-radius">
        <svg class="h-8 md:h-12 lg:h-20 w-full text-gray-50" viewbox="0 0 10 10" preserveaspectratio="none">
            <polygon fill="currentColor" points="0 0 10 0 0 10"></polygon>
        </svg>
    </div>
    <div class="skew skew-bottom ml-for-radius">
        <svg class="h-8 md:h-12 lg:h-20 w-full text-gray-50" viewbox="0 0 10 10" preserveaspectratio="none">
            <polygon fill="currentColor" points="0 0 10 0 10 10"></polygon>
        </svg>
    </div>
</section>