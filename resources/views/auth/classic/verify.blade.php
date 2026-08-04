{{-- Verify --}}
<section class="relative pt-16 pb-0 md:py-22">
    <div class="container px-4 mx-auto mb-16"> 
        <div class="w-full">
            <div class="max-w-sm mx-auto">
                <div class="mb-6 text-center" data-aos="fade-up">
                    <a class="inline-block mb-6" href="{{ route('web.index') }}">
                        <img class="h-16" src="{{ asset($setting->site_logo) }}" alt="{{ config('app.name') }}">
                    </a>
                    <h3 class="mb-4 text-2xl md:text-3xl font-bold">{{ __('Verify Your Email Address') }}</h3>
                </div>

                {{-- Resent --}}
                @if (session('resent'))
                <div class="alert flex flex-row items-center bg-{{ $config[11]->config_value }}-200 p-5 rounded border-b-2 border-{{ $config[11]->config_value }}-300 mb-3"
                    data-aos="fade-up" data-aos-delay="100">
                    <div
                        class="alert-icon flex items-center bg-{{ $config[11]->config_value }}-100 border-2 border-{{ $config[11]->config_value }}-500 justify-center h-10 w-10 flex-shrink-0 rounded-full">
                        <span class="text-{{ $config[11]->config_value }}-500">
                            <svg fill="currentColor" viewBox="0 0 20 20" class="h-6 w-6">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                    </div>
                    <div class="alert-content ml-4">
                        <div class="alert-description text-sm text-{{ $config[11]->config_value }}-600">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    </div>
                </div>
                @endif

                {{-- Information --}}
                <div class="alert flex flex-row items-center bg-red-200 p-5 rounded border-b-2 border-red-300 mb-3"
                    data-aos="fade-up" data-aos-delay="100">
                    <div
                        class="alert-icon flex items-center bg-red-100 border-2 border-red-500 justify-center h-10 w-10 flex-shrink-0 rounded-full">
                        <span class="text-red-500">
                            <svg fill="currentColor" viewBox="0 0 20 20" class="h-6 w-6">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                    </div>
                    <div class="alert-content ml-4">
                        <div class="alert-description text-sm text-red-600">
                            {{ __('Before proceeding, please check your email for a verification link.') }}
                            {{ __('If you did not receive the email') }},
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('verification.resend') }}" data-aos="fade-up" data-aos-delay="100">
                    @csrf

                    <button type="submit"
                        class="inline-block py-3 px-7 mb-6 lg:mb-0 lg:mr-3 w-full lg:full py-2 px-6 leading-loose bg-{{ $config[11]->config_value }}-500 hover:bg-{{ $config[11]->config_value }}-700 text-white font-semibold rounded-l-xl rounded-t-xl transition duration-200 text-center">{{
                        __('click here to request another') }}</button>
                </form>

                {{-- Back to Home --}}
                <a class="inline-block py-3 px-7 mb-6 lg:mb-0 lg:mr-3 w-full lg:full py-2 px-6 leading-loose bg-gray-900 hover:bg-gray-700 text-white font-semibold rounded-l-xl rounded-t-xl transition duration-200 text-center mt-3"
                    href="{{ route('login') }}">{{ __('Go back to Login') }}</a>
            </div>
        </div>
    </div>
</section>