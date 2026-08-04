{{-- Login --}}
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
                        <h3 class="mb-4 text-2xl md:text-3xl font-bold">{{ __('Sign in to your account') }}</h3>
                    </div>
                    <form method="POST" action="{{ route('login') }}" data-aos="fade-up" data-aos-delay="100">
                        @csrf

                        {{-- Email --}}
                        <div class="mb-6">
                            <label class="block mb-2 text-gray-800 font-medium" for="email">{{ __('Email') }}</label>
                            <input
                                class="appearance-none block w-full p-3 leading-5 text-gray-900 border border-gray-200 rounded-lg shadow-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 @error('email') is-invalid @enderror"
                                type="email" name="email" id="email" value="{{ old('email') }}" required
                                autocomplete="email" autofocus placeholder="{{ __('Email') }}">

                            @error('email')
                            <span class="invalid-feedback mt-1" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-2">
                            <label class="block mb-2 text-gray-800 font-medium" for="password">{{ __('Password')
                                }}</label>
                            <input
                                class="appearance-none block w-full p-3 leading-5 text-gray-900 border border-gray-200 rounded-lg shadow-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 @error('password') is-invalid @enderror"
                                type="password" name="password" id="password" required autocomplete="current-password"
                                placeholder="{{ __('************') }}">
                        </div>
                        {{-- Show password --}}
                        <div class="mb-4">
                            <a class="ml-7 text-xs text-gray-800 font-medium float-right" title="Show password"
                                data-bs-toggle="tooltip" onclick="showPassword()">{{ __('Show / Hide Password')}}</a>
                        </div>

                        {{-- Recaptcha --}}
                        @if ($settings['recaptcha_configuration']['RECAPTCHA_ENABLE'] == 'on')
                        <div class="mb-3 w-full lg:w-1/2 px-2 mt-8">
                            {!! htmlFormSnippet() !!} 
                        </div>
                        @endif

                        {{-- Forget password --}}
                        <div
                            class="flex flex-wrap items-center {{(App::isLocale('ar') || App::isLocale('ur') || App::isLocale('he') || App::isLocale('fa') ? 'rtl-justify-between' : 'justify-between')}} mb-6">
                            @if (Route::has('password.request'))
                            <div class="w-full md:w-auto mt-1"><a
                                    class="inline-block text-xs font-medium text-blue-500 hover:text-blue-600"
                                    href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a></div>
                            @endif
                        </div>

                        <button type="submit"
                            class="inline-block py-3 px-7 mb-3 lg:mr-3 w-full lg:full py-2 px-6 leading-loose bg-{{ $config[11]->config_value }}-500 hover:bg-{{ $config[11]->config_value }}-700 text-white font-semibold rounded-l-xl rounded-t-xl transition duration-200 text-center">{{
                            __('Sign In') }}</button>

                        {{-- Sign in With Google --}}
                        @if (env('GOOGLE_ENABLE') == 'on')
                        <a class="inline-block py-3 px-7 mb-3 lg:mr-3 w-full lg:full py-2 px-6 leading-loose bg-gray-900 hover:bg-gray-700 text-white font-semibold rounded-l-xl rounded-t-xl transition duration-200 text-center"
                            href="{{ route('login.google') }}">
                            <span>{{ __('Sign in with Google') }}</span>
                        </a>
                        @endif

                        @if(Route::has('register'))
                        <p class="text-xs font-medium m-2 text-center">{{ __('Don’t have an account?') }}</p>

                        {{-- Register --}}
                        <p class="text-center">
                            <a class="inline-block py-3 px-7 mb-6 lg:mb-0 lg:mr-3 w-full lg:full py-2 px-6 leading-loose bg-gray-900 hover:bg-gray-700 text-white font-semibold rounded-l-xl rounded-t-xl transition duration-200 text-center"
                                href="{{ route('register') }}">{{ __('Sign Up') }}</a>
                        </p>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>