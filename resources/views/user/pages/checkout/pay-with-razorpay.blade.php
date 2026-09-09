@extends('user.layouts.app')

{{-- Custom CSS --}}
@section('custom-css')
    <style>
        .page-wrapper {
            flex: initial !important;
        }
    </style>
@endsection

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
                    {{-- Plan Details --}}
                    <div class="card-body">
                        <h3 class="card-title">{{ $plan_details->name }}</h3>
                        <button id="rzp-button1" class="btn btn-primary">{{ __('Pay Now') }}</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        @include('user.includes.footer')
    </div>

@section('custom-js')
    <script type="text/javascript" src="{{ asset('js/razorpay-checkout.js') }}"></script>
    <script>
        ! function() {
            "use strict";
            var options = {
                "key": "{{ $config[6]->config_value }}",
                "amount": "{{ $order->amount }}",
                "currency": "{{ $order->currency }}",
                "name": "{{ env('APP_NAME') }}",
                "description": "Upgrade Package",
                "image": "{{ asset($settings->site_logo) }}",
                "order_id": "{{ $order->id }}",
                "handler": function(response) {
                    window.location = "../../razorpay-payment/status/" + response.razorpay_order_id + "/" + response
                        .razorpay_payment_id;
                },
                "prefill": {
                    "name": "{{ Auth::user()->name }}",
                    "email": "{{ Auth::user()->email }}",
                    "contact": ""
                },
                "notes": {
                    "transaction_id": "{{ $transaction_id }}"
                },
                "theme": {
                    "color": "#613BBB"
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.on('payment.failed',
                function(response) {
                    window.location = "../../razorpay-payment-status/" + response.error.metadata.order_id + "/" + response
                        .error
                        .metadata.payment_id;
                });
            document.getElementById('rzp-button1').onclick = function(e) {
                rzp1.open();
                e.preventDefault();
            }

            document.addEventListener("DOMContentLoaded", function() {
                rzp1.open();
            });
        }();
    </script>
@endsection
@endsection
