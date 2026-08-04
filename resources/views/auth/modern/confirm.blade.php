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
            <div class="px-4 pt-12 md:pt-40 md:pb-40 max-w-lg mx-auto">
                <div class="text-center mx-auto">
                    <h3 class="mb-4 text-5xl text-white tracking-5xl">{{ __('Confirm Password') }}</h3>

                    {{-- Information --}}
                    <div class="alert flex flex-row items-center bg-red-200 p-5 rounded border-b-2 border-red-300 mb-3" data-aos="fade-up" data-aos-delay="100">
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
                                {{ __('Please confirm your password before continuing.') }}
                            </div>
                        </div>
                    </div>

                    {{-- Reset password form --}}
                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        {{-- Password --}}
                        <div
                            class="mb-1 relative border border-gray-900 focus-within:border-white overflow-hidden rounded-3xl">
                            <input
                                class="pl-6 pr-16 py-4 text-gray-300 w-full placeholder-gray-50 outline-none bg-transparent"
                                type="password" name="password" id="password" required autocomplete="current-password"
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

                        {{-- Google Recaptcha : v2 Checkbox --}}
                        @if ($settings['recaptcha_configuration']['RECAPTCHA_ENABLE'] == 'on')
                        <div
                            class="mb-4 {{(App::isLocale('ar') || App::isLocale('ur') || App::isLocale('he') || App::isLocale('fa') ? 'recaptcha' : '')}}">
                            {!! htmlFormSnippet() !!}
                        </div>
                        @endif

                        <button type="submit" class="w-full block my-10 px-14 py-4 text-center font-medium tracking-2xl border-2 border-green-400 bg-green-400 hover:bg-green-500 text-black focus:ring-4 focus:ring-green-500 focus:ring-opacity-40 rounded-full transition duration-300">{{ __('Confirm Password') }}</button>
                    </form>

                    {{-- Forget password --}}
                    @if (Route::has('password.request'))
                        <a class="mb-10 inline-block text-sm text-gray-300 underline" href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
                    @endif

                    {{-- Back to home --}}
                    <a class="mb-10 inline-block text-sm text-gray-300 underline" href="{{ route('web.index') }}">{{ __('Go back to Homepage') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>