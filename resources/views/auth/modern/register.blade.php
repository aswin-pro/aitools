<section class="overflow-hidden">
    <div class="flex flex-wrap -m-8">
        <div class="w-full md:w-1/2 p-8 hidden lg:inline">
            <div class="flex flex-wrap items-center justify-center h-full py-16 px-4">
                <div class="w-full mb-8">
                    <img class="mx-auto rounded-5xl" src="{{ asset('themes/modern/assets/images/sign-in/stats.png') }}">
                </div>
                <div class="w-full"></div>
            </div>
        </div>
        <div class="w-full md:w-1/2 p-8">
            <div class="px-4 pt-12 md:pt-28 md:pb-40 max-w-lg mx-auto">
                <div class="text-center mx-auto">
                    <h3 class="mb-4 text-5xl text-white tracking-5xl">{{ __('Let`s Get Started') }}</h3>
                    <p class="mb-10 text-gray-300">{{ __('Now create your account!') }}</p>


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
                                    <span class="text-sm text-white font-medium">{{ __('Sign up with Google') }}</span>
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="flex flex-wrap items-center mb-10">
                        <div class="flex-1 bg-gray-900">
                            <div class="h-px"></div>
                        </div>
                        <div class="px-5 text-xs text-gray-300 font-medium">
                            {{ __(env('GOOGLE_ENABLE') == 'on' ? 'or sign up with email' : 'Sign up with email') }}
                        </div>
                        <div class="flex-1 bg-gray-900">
                            <div class="h-px"></div>
                        </div>
                    </div>

                    {{-- Register form --}}
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- Name --}}
                        <div class="mb-4 border border-gray-900 focus-within:border-white overflow-hidden rounded-3xl">
                            <input
                                class="pl-6 pr-16 py-4 text-gray-300 w-full placeholder-gray-300 outline-none bg-transparent"
                                type="text" id="name" placeholder="{{ __('Name') }}" name="name" value="{{ old('name') }}" autocomplete="name" autofocus required>
                        </div>

                        {{-- Name error --}}
                        @error('name')
                        <p class="invalid-feedback text-left px-2 my-1" role="alert">
                            <strong>{{ $message }}</strong>
                        </p>
                        @enderror

                        {{-- Email --}}
                        <div class="mb-4 border border-gray-900 focus-within:border-white overflow-hidden rounded-3xl">
                            <input
                                class="pl-6 pr-16 py-4 text-gray-300 w-full placeholder-gray-300 outline-none bg-transparent"
                                type="email" id="email" name="email" value="{{ old('email') }}"
                                autocomplete="email" placeholder="{{ __('Email') }}" required>
                        </div>

                        {{-- Email error --}}
                        @error('email')
                        <p class="invalid-feedback text-left px-2 my-1" role="alert">
                            <strong>{{ $message }}</strong>
                        </p>
                        @enderror

                        {{-- Password --}}
                        <div
                            class="mb-1 relative border border-gray-900 focus-within:border-white overflow-hidden rounded-3xl">
                            <input
                                class="pl-6 pr-16 py-4 text-gray-300 w-full placeholder-gray-50 outline-none bg-transparent"
                                type="password" name="password" id="password" required autocomplete="new-password"
                                placeholder="{{ __('************') }}" required>
                        </div>

                        {{-- Show password --}}
                        <div class="mb-3 relative overflow-hidden rounded-3xl">
                            <a class="text-xs text-gray-300 font-medium float-right cursor-pointer" title="Show password"
                                data-bs-toggle="tooltip" onclick="showPassword()">{{ __('Show / Hide Password')}}</a>
                        </div>

                        {{-- Password error --}}
                        @error('password')
                        <p class="invalid-feedback text-left px-2 my-1" role="alert">
                            <strong>{{ $message }}</strong>
                        </p>
                        @enderror

                        {{-- Confirm Password --}}
                        <div
                            class="mb-1 relative border border-gray-900 focus-within:border-white overflow-hidden rounded-3xl">
                            <input
                                class="pl-6 pr-16 py-4 text-gray-300 w-full placeholder-gray-50 outline-none bg-transparent"
                                type="password" name="password_confirmation" id="password-confirm" autocomplete="new-password" placeholder="{{ __('************') }}" required>
                        </div>

                        {{-- Show password --}}
                        <div class="mb-4 relative overflow-hidden rounded-3xl">
                            <a class="text-xs text-gray-300 font-medium float-right cursor-pointer" title="Show password"
                                data-bs-toggle="tooltip" onclick="showConfirmPassword()">{{ __('Show / Hide Password')}}</a>
                        </div>

                        {{-- Google Recaptcha : v2 Checkbox --}}
                        @if ($settings['recaptcha_configuration']['RECAPTCHA_ENABLE'] == 'on')
                        <div
                            class="mb-4 {{(App::isLocale('ar') || App::isLocale('ur') || App::isLocale('he') || App::isLocale('fa') ? 'recaptcha' : '')}}">
                            {!! htmlFormSnippet() !!}
                        </div>
                        @endif

                        <button type="submit"
                            class="block mb-6 px-14 py-4 text-center font-medium tracking-2xl border-2 border-green-400 bg-green-400 hover:bg-green-500 text-black focus:ring-4 focus:ring-green-500 focus:ring-opacity-40 rounded-full transition duration-300">{{ __('Sign up') }}</button>
                    </form>

                    {{-- Login --}}
                    @if(Route::has('login'))
                    <p class="text-gray-300">
                        <span>{{ __('Already have an account?') }}</span>
                        <a class="underline" href="{{ route('login') }}">{{ __('Sign In') }}</a>
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
