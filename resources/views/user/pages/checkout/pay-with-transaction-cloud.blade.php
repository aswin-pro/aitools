@extends('user.layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="container-fluid">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Overview') }}
                    </div>
                    <h2 class="page-title">
                        {{ __('Checkout') }}
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid mt-3">
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
            () => { },
            (error) => {
                window.location = "../user/plans";
            }
        );
    })
</script>
@endsection
@endsection