<section class="relative overflow-hidden">
    <div class="hidden xl:block absolute top-0 left-0 h-full w-full max-w-2xl xl:max-w-3xl 2xl:max-w-4xl">
        <img class="block h-full w-full object-cover"
            src="{{ asset('themes/modern-orange/assets/images/sign-up/image-ai.png') }}" alt="">
    </div>
    <div class="container px-4 mx-auto">
        <div class="max-w-7xl py-10 xl:py-20 mx-auto">
            <div class="max-w-md mx-auto xl:mr-10 2xl:mr-0">
                <h3 class="font-heading text-4xl text-gray-900 font-semibold mb-4">{{ __('Confirm your password') }}</h3>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    {{-- Password --}}
                    <div class="mb-4">
                        <label class="block mb-1.5 text-sm text-gray-900 font-semibold"
                            for="password">{{ __('Password') }} <span class="text-red-800">*</span></label>
                        <input
                            class="w-full py-3 px-4 text-sm text-gray-900 placeholder-gray-400 border border-gray-200 focus:border-purple-500 focus:outline-purple rounded-lg"
                            type="password" name="password" id="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="{{ __('Password') }}" required autocomplete="new-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    {{-- Confirm password --}}
                    <div class="mb-4">
                        <label class="block mb-1.5 text-sm text-gray-900 font-semibold"
                            for="password-confirm">{{ __('Confirm Password') }} <span
                                class="text-red-800">*</span></label>
                        <input
                            class="w-full py-3 px-4 text-sm text-gray-900 placeholder-gray-400 border border-gray-200 focus:border-purple-500 focus:outline-purple rounded-lg"
                            type="password" class="form-control" placeholder="{{ __('Confirm password') }}"
                            name="password_confirmation" id="password-confirm" required autocomplete="new-password">
                    </div>

                    {{-- Google Recaptcha : v2 Checkbox --}}
                    @if ($settings['recaptcha_configuration']['RECAPTCHA_ENABLE'] == 'on')
                        <div class="mb-4">
                            {!! htmlFormSnippet() !!}
                        </div>
                    @endif

                    <div>
                        <button
                            class="relative group block w-full mb-6 py-3 px-5 text-center text-sm font-semibold text-orange-50 bg-orange-900 rounded-full overflow-hidden  mt-5"
                            type="submit">
                            <div
                                class="absolute top-0 right-full w-full h-full bg-gray-900 transform group-hover:translate-x-full group-hover:scale-102 transition duration-500">
                            </div>
                            <span class="relative"> {{ __('Confirm Password') }}</span>
                        </button>

                        {{-- Forget password --}}
                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div class="xl:hidden lg:inline hidden">
        <img class="block h-full w-full object-cover object-top"
            src="{{ asset('themes/modern-orange/assets/images/sign-up/image-ai.png') }}" alt="">
    </div>
</section>
