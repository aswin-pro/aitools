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
                    {{-- Plan Details --}}
                    <div class="card-body">
                        <h3 class="card-title">{{ $plan_details->name }}</h3>
                        <button id="pay-button" class="btn btn-primary">{{ __('Pay Now') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    @include('user.includes.footer')

    {{-- Custom JS --}}
@section('custom-js')
    <script src="https://cdn.transaction.cloud/latest/widget.min.js"></script>
    <script>
        tc.getProduct(`{{ $plan_details->plan_id }}`).then(
            (product) => {
                document.getElementById('product-model').innerText = JSON.stringify(product, null, 4);
            },
            (error) => {
                document.getElementById('product-model').innerText = error;
            }
        );
        document.getElementById('pay-button').addEventListener('click', () => {
            tc.buy(`{{ $plan_details->plan_id }}`, {
                email: `{{ Auth::user()->email }}`,
                firstName: `{{ Auth::user()->billing_name }}`,
                lastName: `{{ Auth::user()->billing_name }}`,
                zipCode: `{{ Auth::user()->billing_zipcode }}`,
                companyId: `{{ Auth::user()->vat_number }}`,
                payload: `{{ $transaction_id }}`
            }).then(
                () => {},
                (error) => {
                    window.location = "../../dashboard/user/subscriptions/plans";
                }
            );
        })

        window.addEventListener('load', () => {
            tc.buy(`{{ $plan_details->plan_id }}`, {
                email: `{{ Auth::user()->email }}`,
                firstName: `{{ Auth::user()->billing_name }}`,
                lastName: `{{ Auth::user()->billing_name }}`,
                zipCode: `{{ Auth::user()->billing_zipcode }}`,
                companyId: `{{ Auth::user()->vat_number }}`,
                payload: `{{ $transaction_id }}`
            }).then(
                () => {},
                (error) => {
                    window.location = "../../dashboard/user/subscriptions/plans";
                }
            );
        });
    </script>
@endsection
@endsection
