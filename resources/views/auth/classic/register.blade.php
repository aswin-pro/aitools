{{-- Register --}}
<section class="relative pt-16 pb-0 md:py-22">
    <div class="container px-4 mx-auto mb-16">
        <div class="flex flex-wrap items-center -m-6">
            <div class="w-full md:w-1/2 p-6 lg:block hidden">
                <div class="p-1 mx-auto max-w-max overflow-hidden">
                    <img class="object-cover" src="{{ asset($config[12]->config_value) }}" alt="{{ $config[0]->config_value }}">
                </div>
            </div>
            <div class="w-full md:w-1/2 p-6">
                <div class="md:max-w-md">
                    <div class="mb-6 text-center" data-aos="fade-up">
                        <h3 class="mb-4 text-2xl md:text-3xl font-bold">{{ __('Join our community') }}</h3>
                        <p class="text-lg text-gray-500 font-medium">{{ __('Start your journey with our product') }}</p>
                    </div>
                    <form method="POST" action="{{ route('register') }}" data-aos="fade-up" data-aos-delay="100">
                        @csrf

                        {{-- Name --}}
                        <div class="mb-6">
                            <label class="block mb-2 text-gray-800 font-medium" for="name">{{ __('Name*') }}</label>
                            <input
                                class="appearance-none block w-full p-3 leading-5 text-gray-900 border border-gray-200 rounded-lg shadow-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-{{ $config[11]->config_value }}-500 focus:ring-opacity-50 @error('name') is-invalid @enderror"
                                type="text" id="name" placeholder="{{ __('Name') }}" name="name" value="{{ old('name') }}"
                                required autocomplete="name" autofocus>

                            @error('name')
                            <span class="invalid-feedback mt-1" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-6">
                            <label class="block mb-2 text-gray-800 font-medium" for="email">{{ __('Email*') }}</label>
                            <input
                                class="appearance-none block w-full p-3 leading-5 text-gray-900 border border-gray-200 rounded-lg shadow-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-{{ $config[11]->config_value }}-500 focus:ring-opacity-50 @error('email') is-invalid @enderror"
                                type="email" id="email" name="email" value="{{ old('email') }}" required
                                autocomplete="email" placeholder="{{ __('Email') }}">

                            @error('email')
                            <span class="invalid-feedback mt-1" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-1">
                            <label class="block mb-2 text-gray-800 font-medium" for="password">{{ __('Password*')
                                }}</label>
                            <input
                                class="appearance-none block w-full p-3 leading-5 text-gray-900 border border-gray-200 rounded-lg shadow-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-{{ $config[11]->config_value }}-500 focus:ring-opacity-50 @error('password') is-invalid @enderror"
                                type="password" name="password" id="password" required autocomplete="new-password"
                                placeholder="{{ __('************') }}" required>

                            @error('password')
                            <span class="invalid-feedback mt-1" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-6">
                            <a class="ml-7 text-xs text-gray-800 font-medium float-right cursor-pointer" title="Show password"
                                data-bs-toggle="tooltip" onclick="showPassword()">{{ __('Show / Hide Password')}}</a>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-1">
                            <label class="block mb-2 text-gray-800 font-medium" for="password-confirm">{{ __('Confirm Password*')
                                }}</label>
                            <input
                                class="appearance-none block w-full p-3 leading-5 text-gray-900 border border-gray-200 rounded-lg shadow-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-{{ $config[11]->config_value }}-500 focus:ring-opacity-50 @error('password') is-invalid @enderror"
                                type="password" name="password_confirmation" id="password-confirm" required
                                autocomplete="new-password" placeholder="{{ __('************') }}">
                        </div>
                        <div class="mb-12">
                            <a class="ml-7 text-xs text-gray-800 font-medium float-right cursor-pointer" title="Show password"
                                data-bs-toggle="tooltip" onclick="showConfirmPassword()">{{ __('Show / Hide Password')}}</a>
                        </div>

                        {{-- Google Recaptcha : v2 Checkbox --}}
                        @if ($settings['recaptcha_configuration']['RECAPTCHA_ENABLE'] == 'on')
                        <div
                            class="mb-8 {{(App::isLocale('ar') || App::isLocale('ur') || App::isLocale('he') || App::isLocale('fa') ? 'recaptcha' : '')}}">
                            {!! htmlFormSnippet() !!}
                        </div>
                        @endif

                        <button type="submit"
                            class="inline-block py-3 px-7 mb-6 lg:mb-0 lg:mr-3 w-full lg:full py-2 px-6 leading-loose bg-{{ $config[11]->config_value }}-500 hover:bg-{{ $config[11]->config_value }}-700 text-white font-semibold rounded-l-xl rounded-t-xl transition duration-200 text-center">{{
                            __('Sign Up') }}</button>

                        @if(Route::has('login'))
                        <p class="text-center">
                            <span class="text-xs font-medium">{{ __('Already have an account?') }}</span>
                            <a class="inline-block text-xs font-medium text-{{ $config[11]->config_value }}-500 hover:text-{{ $config[11]->config_value }}-600 hover:underline"
                                href="{{ route('login') }}">{{ __('Sign In') }}</a>
                        </p>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>