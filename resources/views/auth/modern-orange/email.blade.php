<section class="relative overflow-hidden">
    <div class="hidden xl:block absolute top-0 left-0 h-full w-full max-w-2xl xl:max-w-3xl 2xl:max-w-4xl">
        <img class="block h-full w-full object-cover"
            src="{{ asset('themes/modern-orange/assets/images/sign-up/image-ai.png') }}" alt="">
    </div>
    <div class="container px-4 mx-auto">
        <div class="max-w-7xl py-10 xl:py-20 mx-auto">
            <div class="max-w-md mx-auto xl:mr-10 2xl:mr-0">
                <h3 class="font-heading text-4xl text-gray-900 font-semibold mb-4">{{ __('Forget your password') }}</h3>

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

                <form action="{{ route('password.email') }}" method="POST">
                    @csrf

                    {{-- Email address --}}
                    <div class="mb-4">
                        <label class="block mb-1.5 text-sm text-gray-900 font-semibold"
                            for="email">{{ __('Email Address') }} <span class="text-red-800">*</span></label>
                        <input
                            class="w-full py-3 px-4 text-sm text-gray-900 placeholder-gray-400 border border-gray-200 focus:border-purple-500 focus:outline-purple rounded-lg"
                            type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            placeholder="{{ __('Enter your email address') }}" value="{{ old('email') }}" required
                            autocomplete="email" autofocus>
                            
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    {{-- Recaptcha --}}
                    @if ($settings['recaptcha_configuration']['RECAPTCHA_ENABLE'] == 'on')
                        <div class="mb-4">
                            {!! htmlFormSnippet() !!}
                        </div>
                    @endif

                    <button
                        class="relative group block w-full mb-6 py-3 px-5 text-center text-sm font-semibold text-orange-50 bg-orange-900 rounded-full overflow-hidden"
                        type="submit">
                        <div
                            class="absolute top-0 right-full w-full h-full bg-gray-900 transform group-hover:translate-x-full group-hover:scale-102 transition duration-500">
                        </div>
                        <span class="relative">{{ __('Send Password Reset Link') }}</span>
                    </button>

                </form>
            </div>
        </div>
    </div>
    <div class="xl:hidden lg:inline hidden">
        <img class="block h-full w-full object-cover object-top"
            src="{{ asset('themes/modern-orange/assets/images/sign-up/image-ai.png') }}" alt="">
    </div>
</section>
