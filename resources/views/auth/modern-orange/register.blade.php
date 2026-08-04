<section class="relative overflow-hidden">
    <div class="hidden xl:block absolute top-0 left-0 h-full w-full max-w-2xl xl:max-w-3xl 2xl:max-w-4xl">
        <img class="block h-full w-full object-cover"
            src="{{ asset('themes/modern-orange/assets/images/sign-up/image-ai.png') }}" alt="">
    </div>
    <div class="container px-4 mx-auto">
        <div class="max-w-7xl py-10 xl:py-20 mx-auto">
            <div class="max-w-md mx-auto xl:mr-10 2xl:mr-0">
                <h3 class="font-heading text-4xl text-gray-900 font-semibold mb-4">{{ __('Sign up to your account') }}</h3>
                <p class="text-lg text-gray-500 mb-6">{{ __('Greetings on your return! We kindly request you to enter your details.') }}</p>
                
                {{-- Sign in With Google --}}
                @if (env('GOOGLE_ENABLE') == 'on')
                <div class="flex mb-6 items-center -mx-2">
                    <a class="flex w-full mr-6 p-3 items-center justify-center rounded-full border border-gray-200 hover:border-gray-400"
                        href="{{ route('login.google') }}">
                        <div class="mr-4 inline-block">
                            <img src="{{ asset('themes/modern-orange/assets/images/sign-up/icon-small-google.svg') }}"
                                alt="">
                        </div>
                        <span class="text-sm text-gray-900 font-medium">{{ __('Sign up with Google') }}</span> 
                    </a>
                </div>

                <div class="flex mb-6 items-center">
                    <div class="w-full h-px bg-gray-300"></div>
                    <span class="mx-4 text-sm font-semibold text-gray-500">{{ __('Or') }}</span>
                    <div class="w-full h-px bg-gray-300"></div>
                </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    {{-- Name --}}
                    <div class="mb-4">
                        <label class="block mb-1.5 text-sm text-gray-900 font-semibold"
                            for="name">{{ __('Name') }} <span class="text-red-800">*</span></label>
                        <input
                            class="w-full py-3 px-4 text-sm text-gray-900 placeholder-gray-400 border border-gray-200 focus:border-purple-500 focus:outline-purple rounded-lg"
                            type="name" id="name" class="form-control @error('name') is-invalid @enderror" name="name"
                            value="{{ old('name') }}" placeholder="{{ __('Enter your name') }}" required autocomplete="name" autofocus>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    {{-- Email address --}}
                    <div class="mb-4">
                        <label class="block mb-1.5 text-sm text-gray-900 font-semibold"
                            for="email">{{ __('Email Address') }} <span class="text-red-800">*</span></label>
                        <input
                            class="w-full py-3 px-4 text-sm text-gray-900 placeholder-gray-400 border border-gray-200 focus:border-purple-500 focus:outline-purple rounded-lg"
                            type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email') }}" placeholder="{{ __('Enter your email address') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-4">
                        <div class="flex mb-1.5 items-center justify-between">
                            <label class="block text-sm text-gray-900 font-semibold" for="password">{{ __('Password') }} <span class="text-red-800">*</span></label>
                        </div>
                        <div class="relative">
                            <input
                                class="w-full py-3 px-4 text-sm text-gray-900 placeholder-gray-400 border border-gray-200 focus:border-purple-500 focus:outline-purple rounded-lg"
                                type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password"
                                placeholder="{{ __('Password') }}" required autocomplete="new-password">
                                
                            @error('password')
                            <p class="invalid-feedback text-left px-2 my-1" role="alert">
                                <strong>{{ $message }}</strong>
                            </p>
                            @enderror
                                
                            {{-- Show password --}}
                            <button type="button" class="absolute top-1/2 right-0 mr-3 transform -translate-y-1/2 inline-block hover:scale-110 transition duration-100" onclick="showPassword()">
                                <img id="icon" src="{{ asset('themes/modern-orange/assets/images/sign-up/icon-eye.svg') }}" alt="">
                            </button>
                        </div>
                    </div>

                    {{-- Confirm password --}}
                    <div class="mb-4">
                        <div class="flex mb-1.5 items-center justify-between">
                            <label class="block text-sm text-gray-900 font-semibold" for="password_confirmation">{{ __('Confirm Password') }} <span class="text-red-800">*</span></label>
                        </div>
                        <div class="relative">
                            <input
                                class="w-full py-3 px-4 text-sm text-gray-900 placeholder-gray-400 border border-gray-200 focus:border-purple-500 focus:outline-purple rounded-lg"
                                type="password" class="form-control" name="password_confirmation" id="password-confirm" placeholder="{{ __('Confirm password') }}" required
                                autocomplete="new-password">
                                
                            {{-- Show password --}}
                            <button type="button" class="absolute top-1/2 right-0 mr-3 transform -translate-y-1/2 inline-block hover:scale-110 transition duration-100" onclick="showConfirmPassword()">
                                <img id="confirm-icon" src="{{ asset('themes/modern-orange/assets/images/sign-up/icon-eye.svg') }}" alt="">
                            </button>
                        </div>
                    </div>

                    {{-- Recaptcha --}}
                    @if ($settings['recaptcha_configuration']['RECAPTCHA_ENABLE'] == 'on')
                    <div class="mb-4">
                        {!! htmlFormSnippet() !!} 
                    </div>
                    @endif

                    <button
                        class="relative group block w-full mb-6 py-3 px-5 text-center text-sm font-semibold text-orange-50 bg-orange-900 rounded-full overflow-hidden  mt-5"
                        type="submit">
                        <div
                            class="absolute top-0 right-full w-full h-full bg-gray-900 transform group-hover:translate-x-full group-hover:scale-102 transition duration-500">
                        </div>
                        <span class="relative"> {{ __('Register') }}</span>
                    </button>
                </form>
                <span class="text-xs font-semibold text-gray-900">
                    <span>{{ __('Already have an account?') }}</span>
                    <a class="inline-block ml-1 text-orange-900 hover:text-orange-700"
                        href="{{ route('login') }}">{{ __('Sign In') }}</a>
                </span>
            </div>
        </div>
    </div>
    <div class="xl:hidden lg:inline hidden">
        <img class="block h-full w-full object-cover object-top"
            src="{{ asset('themes/modern-orange/assets/images/sign-up/image-ai.png') }}" alt="">
    </div>
</section>
