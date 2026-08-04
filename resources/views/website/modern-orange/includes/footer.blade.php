@php
// Settings
use App\Models\Setting;
use App\Models\Page;

$setting = Setting::where('status', 1)->first();
$pages = Page::get();
@endphp

<section class="py-20 bg-gray-900">
    <div class="container px-4 mx-auto">
        <div class="max-w-7xl mx-auto">
            <div class="pb-8 mb-15 border-b border-gray-700 items-center">
                <div class="flex flex-wrap -mx-4 items-center">
                    <div class="w-full lg:w-1/2 px-4 mb-4 lg:mb-0">
                        <a class="inline-block" href="{{ route('web.index') }}">
                            <img class="image-shadow h-15" src="{{ asset($setting->site_logo) }}" alt="{{ config('app.name') }}" />
                        </a>
                    </div>

                    {{-- Contact --}}
                    @if($pages[30]->slug == 'contact' && $pages[30]->status == 1)
                    <div class="w-full lg:w-1/2 px-4">
                        <div class="sm:flex -mb-4 items-center lg:justify-end">
                            <span class="inline-block text-white mb-4 mr-8">{{ __('Questions or interested in our services? Reach out!') }}</span>
                            <a class="relative group inline-block w-full sm:w-auto py-3 px-5 mb-4 text-center text-sm font-semibold text-orange-50 hover:text-orange-900 bg-orange-900 rounded-md overflow-hidden transition duration-300"
                                href="{{ route('web.contact') }}">
                                <div
                                    class="absolute top-0 right-full w-full h-full bg-white transform group-hover:translate-x-full group-hover:scale-105 transition duration-500">
                                </div>
                                <span class="relative">{{ __('Contact Us') }}</span>
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="flex flex-wrap -mb-5 lg:-mx-6">
                {{-- Blogs --}}
                <div class="lg:px-6 md:px-6 px-2 mb-5">
                    <a class="inline-block text-gray-300 hover:text-gray-200" href="{{ route('web.blogs') }}">{{ __('Blogs') }}</a>
                </div>

                {{-- FAQs --}}
                @if($pages[31]->slug == 'faq' && $pages[30]->status == 1)
                <div class="lg:px-6 md:px-6 px-2 mb-5">
                    <a class="inline-block text-gray-300 hover:text-gray-200" href="{{ route('web.faq') }}">{{ __('FAQs') }}</a>
                </div>
                @endif

                {{-- Privacy policy --}}
                @if($pages[32]->slug == 'privacy-policy' && $pages[32]->status == 1)
                <div class="lg:px-6 md:px-6 px-2 mb-5">
                    <a class="inline-block text-gray-300 hover:text-gray-200"
                        href="{{ route('web.privacy') }}">{{ __('Privacy Policy') }}</a>
                </div>
                @endif

                {{-- Refund policy --}}
                @if($pages[33]->slug == 'refund-policy' && $pages[33]->status == 1)
                <div class="lg:px-6 md:px-6 px-2 mb-5">
                    <a class="inline-block text-gray-300 hover:text-gray-200" href="{{ route('web.refund') }}">{{ __('Refund Policy') }}</a>
                </div>
                @endif

                {{-- Terms and conditions --}}
                @if($pages[34]->slug == 'terms-and-conditions' && $pages[34]->status == 1)
                <div class="lg:px-6 md:px-6 px-2 mb-5">
                    <a class="inline-block text-gray-300 hover:text-gray-200"
                        href="{{ route('web.terms') }}">{{ __('Terms and Conditions') }}</a>
                </div>
                @endif
            </div>
            <div class="mt-15 text-center">
                <span class="text-gray-500">© {{ now()->year }} {{ config('app.name') }}. {{ __('All rights reserved.') }}</span>
            </div>
        </div>
    </div>
</section>
