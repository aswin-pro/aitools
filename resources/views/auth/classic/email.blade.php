{{-- Forget Password --}}
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
                        <h3 class="mb-4 text-2xl md:text-3xl font-bold">{{ __('Reset Password') }}</h3>
                    </div>

                    {{-- Success --}}
                    @if (session('status'))
                    <div class="alert flex flex-row items-center bg-{{ $config[11]->config_value }}-200 p-5 rounded border-b-2 border-{{ $config[11]->config_value }}-300 mb-3"
                        data-aos="fade-up" data-aos-delay="100">
                        <div class="alert-content ml-4">
                            <div class="alert-description text-sm text-{{ $config[11]->config_value }}-600">
                                {{ session('status') }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" data-aos="fade-up" data-aos-delay="100">
                        @csrf
                        {{-- Email --}}
                        <div class="mb-6">
                            <label class="block mb-2 text-coolGray-800 font-medium" for="email">{{ __('Email*') }}</label>
                            <input
                                class="appearance-none block w-full p-3 leading-5 text-coolGray-900 border border-coolGray-200 rounded-lg shadow-md placeholder-coolGray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 @error('email') is-invalid @enderror"
                                type="email" id="email" name="email" value="{{ old('email') }}" required
                                autocomplete="email" autofocus placeholder="{{ __('dev@domain.com') }}">

                            @error('email')
                            <span class="invalid-feedback mt-1" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        {{-- Recaptcha --}}
                        @if ($settings['recaptcha_configuration']['RECAPTCHA_ENABLE'] == 'on')
                        <div class="mb-3 w-full lg:w-1/2 px-2 mt-8">
                            {!! htmlFormSnippet() !!}
                        </div>
                        @endif

                        <button type="submit"
                            class="inline-block py-3 px-7 mb-6 lg:mb-0 lg:mr-3 w-full lg:full py-2 px-6 leading-loose bg-{{ $config[11]->config_value }}-500 hover:bg-{{ $config[11]->config_value }}-700 text-white font-semibold rounded-l-xl rounded-t-xl transition duration-200 text-center">{{
                            __('Send Password Reset Link') }}</button>

                        {{-- Back to Home --}}
                        <a class="inline-block py-3 px-7 mb-6 lg:mb-0 lg:mr-3 w-full lg:full py-2 px-6 leading-loose bg-gray-900 hover:bg-gray-700 text-white font-semibold rounded-l-xl rounded-t-xl transition duration-200 text-center mt-3"
                            href="{{ route('web.index') }}">{{ __('Go back to Homepage') }}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>