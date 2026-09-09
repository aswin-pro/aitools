@extends('user.layouts.app')

@section('content')
    <div class="page-wrapper">
        <!-- Page title -->
        <div class="page-header d-print-none border-bottom pb-3">
            <div class="container d-flex align-items-center justify-content-between gap-2">
                {{-- back button --}}
                <div class="d-flex align-item-center gap-2">
                    <a href="{{ route('dashboard.user.subscriptions.plans') }}" class="border rounded-3 p-2 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-left icon-primary">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M5 12l14 0" />
                            <path d="M5 12l6 6" />
                            <path d="M5 12l6 -6" />
                        </svg>
                    </a>
                    <h2 class="page-title">
                        {{ __('Checkout') }}
                    </h2>
                </div>

                {{-- logo --}}
                <div class="navbar-brand navbar-brand-autodark">
                    <a href="{{ route('dashboard.user.overview') }}">
                        <img src="{{ $settings->site_logo }}" width="200" height="50" alt="{{ $settings->site_name }}"
                            class="navbar-brand-image custom-logo">
                    </a>
                </div>
            </div>
        </div>

        {{-- checkout --}}
        <div class="container mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">{{ $plan_details->name }}</h3>
                        <div class="card col-12">
                            <form action="{{ route('payment.stripe.status', $paymentId) }}" method="post"
                                id="payment-form">
                                @csrf

                                <div class="form-group">
                                    <div class="card-header">
                                        <label for="card-element">
                                            {{ __('Please enter your credit card information') }}
                                        </label>
                                    </div>
                                    <div class="card-body">
                                        <div id="card-element">
                                            <!-- A Stripe Element will be inserted here. -->
                                        </div>
                                        <!-- Used to display form errors. -->
                                        <div id="card-errors" role="alert"></div>
                                        <input type="hidden" name="plan" value="" />
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button id="card-button" class="btn btn-dark" type="submit"
                                        data-secret="{{ $intent }}">
                                        {{ __('Pay Now') }} </button>
                                </div>
                            </form>
                        </div>

                        <br>
                        <a class="mt-2 text-muted text-underline"
                            href="{{ route('payment.stripe.cancel', $paymentId) }}">{{ __('Cancel payment and back to home') }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        @include('user.includes.footer')
    </div>

    {{-- Custom JS --}}
@section('custom-js')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        ! function() {
            "use strict";
            var style = {
                base: {
                    color: '#32325d',
                    lineHeight: '18px',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            };

            const stripe = Stripe('{{ $config[9]->config_value }}', {
                locale: 'en'
            }); // Create a Stripe client.
            const elements = stripe.elements(); // Create an instance of Elements.
            const cardElement = elements.create('card', {
                style: style
            }); // Create an instance of the card Element.
            const cardButton = document.getElementById('card-button');
            const clientSecret = cardButton.dataset.secret;

            cardElement.mount('#card-element'); // Add an instance of the card Element into the `card-element` <div>.

            // Handle real-time validation errors from the card Element.
            cardElement.addEventListener('change', function(event) {
                "use strict";
                var displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });

            // Handle form submission.
            var form = document.getElementById('payment-form');

            form.addEventListener('submit', function(event) {
                "use strict";
                event.preventDefault();

                stripe.handleCardPayment(clientSecret, cardElement, {
                        payment_method_data: {
                            //billing_details: { name: cardHolderName.value }
                        }
                    })
                    .then(function(result) {
                        console.log(result);
                        if (result.error) {
                            // Inform the user if there was an error.
                            var errorElement = document.getElementById('card-errors');
                            errorElement.textContent = result.error.message;
                        } else {
                            console.log(result);
                            form.submit();
                        }
                    });
            });
        }();
    </script>
@endsection
@endsection
