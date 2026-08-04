{{-- 429 --}}
<section class="py-24 lg:py-40">
    <div class="container px-4 mx-auto">
        <div class="max-w-sm md:max-w-xl lg:max-w-6xl mx-auto">
            <div class="flex flex-wrap -mx-4">
                <div class="w-full px-4">
                    <div>
                        <span class="block text-5xl font-bold text-gray-300 mb-6">{{ __('429') }}</span>
                        <h3 class="text-4xl font-bold mb-6">
                            {{ __('Page Too Many Requests') }}
                        </h3>
                        <a class="inline-block px-8 py-4 tracking-tighter bg-green-400 hover:bg-green-500 text-black font-medium focus:ring-4 focus:ring-green-500 focus:ring-opacity-40 rounded-full transition duration-300"
                            href="{{ route('web.index') }}">{{ __('Go back to Homepage') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
