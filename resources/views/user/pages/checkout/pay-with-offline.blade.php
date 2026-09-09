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

        <div class="container mt-3">
            <div class="row row-deck row-cards">
                <div class="col-sm-6 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('payment.offline.mark') }}" method="post">
                                @csrf
                                {{-- Plan Name --}}
                                <h3 class="card-title">{{ __('Plan Name : ') }}{{ $plan_details->name }}</h3>

                                {{-- plan id --}}
                                <input type="hidden" value="{{ $plan_details->id }}" name="plan_id">

                                {{-- Transaction ID --}}
                                <div class="mb-3">
                                    <label class="form-label required">{{ __('Transaction ID') }}</label>
                                    <input type="text" class="form-control" name="transaction_id"
                                        placeholder="{{ __('Transaction ID') }}" required>
                                </div>

                                {{-- verify payment --}}
                                <div class="col-md-6 col-xl-6 my-3">
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary">{{ __('Verify Payment') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">{{ __('Bank Details') }}</h3>
                            <div class="bg-dark text-white p-3 rounded">{!! $config[31]->config_value !!}</div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Footer --}}
            @include('user.includes.footer')
        </div>
    </div>
@endsection
