<section class="relative overflow-hidden">
    <div class="hidden xl:block absolute top-0 left-0 h-full w-full max-w-2xl xl:max-w-3xl 2xl:max-w-4xl">
        <img class="block h-full w-full object-cover"
            src="{{ asset('themes/modern-orange/assets/images/sign-up/image-ai.png') }}" alt="">
    </div>
    <div class="container px-4 mx-auto">
        <div class="max-w-7xl py-10 xl:py-20 mx-auto">
            <div class="max-w-md mx-auto xl:mr-10 2xl:mr-0">
                <h3 class="font-heading text-4xl text-gray-900 font-semibold mb-4">{{ __('Verify your email address') }}
                </h3>

                <div>
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif
                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }},

                    <form method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button
                            class="relative group block w-full mb-6 py-3 px-5 text-center text-sm font-semibold text-orange-50 bg-orange-900 rounded-full overflow-hidden  mt-5"
                            type="submit">
                            <div
                                class="absolute top-0 right-full w-full h-full bg-gray-900 transform group-hover:translate-x-full group-hover:scale-102 transition duration-500">
                            </div>
                            <span class="relative"> {{ __('click here to request another') }}</span>
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="xl:hidden lg:inline hidden">
        <img class="block h-full w-full object-cover object-top" src="{{ asset('themes/modern-orange/assets/images/sign-up/image-ai.png') }}" alt="">
    </div>
</section>
