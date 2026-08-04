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
                    <h3 class="mb-4 text-5xl text-white tracking-5xl">{{ __('Reset Password') }}</h3>

                    {{-- Success --}}
                    @if (session('status'))
                    <div class="alert flex flex-row items-center bg-green-800 p-5 rounded mb-3">
                        <div class="alert-content">
                            <div class="alert-description text-sm text-gray-50">
                                {{ session('status') }}
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Reset password form --}}
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        {{-- Email --}}
                        <div class="mb-1 relative border border-gray-900 focus-within:border-white overflow-hidden rounded-3xl">
                            <input class="pl-6 pr-16 py-4 text-gray-300 w-full placeholder-gray-300 outline-none bg-transparent" type="email" id="email" name="email" value="{{ old('email') }}" required
                            autocomplete="email" autofocus placeholder="{{ __('dev@domain.com') }}">
                        </div>

                        {{-- Errors --}}
                        @error('email')
                        <p class="invalid-feedback text-left px-2 my-1" role="alert">
                            <strong>{{ $message }}</strong>
                        </p>
                        @enderror

                        {{-- Recaptcha --}}
                        @if ($settings['recaptcha_configuration']['RECAPTCHA_ENABLE'] == 'on')
                        <div class="mb-3 w-full lg:w-1/2 px-2 mt-8">
                            {!! htmlFormSnippet() !!} 
                        </div>
                        @endif

                        <button type="submit" class="w-full block my-10 px-14 py-4 text-center font-medium tracking-2xl border-2 border-green-400 bg-green-400 hover:bg-green-500 text-black focus:ring-4 focus:ring-green-500 focus:ring-opacity-40 rounded-full transition duration-300">{{ __('Send Password Reset Link') }}</button>
                    </form>

                    {{-- Back to home --}}
                    <a class="mb-10 inline-block text-sm text-gray-300 underline" href="{{ route('web.index') }}">{{ __('Go back to Homepage') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>
