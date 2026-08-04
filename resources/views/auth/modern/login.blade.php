<section class="overflow-hidden">
    <div class="flex flex-wrap -m-8">
        <div class="w-full md:w-1/2 p-8 hidden lg:inline">
            <div class="flex flex-wrap items-center justify-center h-full py-16 px-4">
                <div class="w-full mb-8">
                    <img class="mx-auto rounded-5xl" src="{{ asset('themes/modern/assets/images/sign-in/stats.png') }}"
                        alt="">
                </div>
                <div class="w-full"></div>
            </div>
        </div>
        <div class="w-full md:w-1/2 p-8">
            <div class="px-4 pt-12 md:pt-28 md:pb-40 max-w-lg mx-auto">
                <div class="text-center mx-auto">
                    <h3 class="mb-4 text-5xl text-white tracking-5xl">{{ __('Sign in to your account') }}</h3>
                    <p class="mb-10 text-gray-300">{{ __('Good to have you back!') }}</p>

                    {{-- Sign in With Google --}}
                    @if (env('GOOGLE_ENABLE') == 'on')
                    <div class="flex flex-wrap -1 mb-7">
                        <div class="w-full p-1">
                            <a class="p-5 flex flex-wrap justify-center bg-gray-900 hover:bg-gray-900 bg-opacity-30 hover:bg-opacity-10 rounded-full transition duration-300"
                                href="{{ route('login.google') }}">
                                <div class="mr-4 inline-block">
                                    <img src="{{ asset('themes/modern/assets/images/sign-in/google.svg') }}"
                                        alt="">
                                </div>
                                <span class="text-sm text-white font-medium">{{ __('Sign in with Google') }}</span>
                            </a>
                        </div>
                    </div>
                    @endif

                    <div class="flex flex-wrap items-center mb-10">
                        <div class="flex-1 bg-gray-900">
                            <div class="h-px"></div>
                        </div>
                        <div class="px-5 text-xs text-gray-300 font-medium">
                            {{ __(env('GOOGLE_ENABLE') == 'on' ? 'or sign in with email' : 'Sign in with email') }}
                        </div>
                        <div class="flex-1 bg-gray-900">
                            <div class="h-px"></div>
                        </div>
                    </div>

                    {{-- Login form --}}
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Email --}}
                        <div class="mb-2 border border-gray-900 focus-within:border-white overflow-hidden rounded-3xl">
                            <input class="pl-6 pr-16 py-4 text-gray-300 w-full placeholder-gray-300 outline-none bg-transparent" type="email" name="email" id="email" value="{{ old('email') }}" autocomplete="email" autofocus placeholder="{{ __('Email') }}" required>
                        </div>

                        {{-- Errors --}}
                        @error('email')
                        <p class="invalid-feedback text-left px-2 my-1" role="alert">
                            <strong>{{ $message }}</strong>
                        </p>
                        @enderror

                        {{-- Password --}}
                        <div class="mb-1 relative border border-gray-900 focus-within:border-white overflow-hidden rounded-3xl">
                            <input class="pl-6 pr-16 py-4 text-gray-300 w-full placeholder-gray-300 outline-none bg-transparent" type="password" name="password" id="password" required autocomplete="current-password" placeholder="{{ __('************') }}">
                        </div>

                        {{-- Show password --}}
                        <div class="mb-4">
                            <a class="ml-7 text-xs text-gray-300 font-medium float-right" title="Show password" data-bs-toggle="tooltip" onclick="showPassword()">{{ __('Show / Hide Password')}}</a>
                        </div>

                        {{-- Recaptcha --}}
                        @if ($settings['recaptcha_configuration']['RECAPTCHA_ENABLE'] == 'on')
                        <div class="mb-3 w-full lg:w-1/2 px-2 mt-8">
                            {!! htmlFormSnippet() !!} 
                        </div>
                        @endif

                        <button type="submit" class="w-full block my-10 px-14 py-4 text-center font-medium tracking-2xl border-2 border-green-400 bg-green-400 hover:bg-green-500 text-black focus:ring-4 focus:ring-green-500 focus:ring-opacity-40 rounded-full transition duration-300">{{ __('Log in') }}</button>
                    </form>

                    {{-- Forget password --}}
                    @if (Route::has('password.request'))
                        <a class="mb-10 inline-block text-sm text-gray-300 underline" href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
                    @endif

                    {{-- Register --}}
                    @if(Route::has('register'))
                    <p class="text-gray-300">
                        <span>{{ __('Don’t have an account?')}}</span>
                        <a class="underline" href="{{ route('register') }}">{{ __('Sign up') }}</a>
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
