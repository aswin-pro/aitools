@extends('admin.layouts.app')

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
                        {{ __('Edit Payment Method') }}
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-fluid">

            {{-- Failed --}}
            @if (Session::has("failed"))
            <div class="alert alert-important alert-danger alert-dismissible" role="alert">
                <div class="d-flex">
                    <div>
                        {{Session::get('failed')}}
                    </div>
                </div>
                <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            @endif

            {{-- Success --}}
            @if(Session::has("success"))
            <div class="alert alert-important alert-success alert-dismissible" role="alert">
                <div class="d-flex">
                    <div>
                        {{Session::get('success')}}
                    </div>
                </div>
                <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            @endif

            {{-- Update payment gateway --}}
            <div class="row row-deck row-cards">
                <div class="col-sm-12 col-lg-12">
                    <form action="{{ route('admin.update.payment.method') }}" method="post"
                        enctype="multipart/form-data" class="card">
                        @csrf
                        <div class="card-header">
                            <h4 class="page-title">{{ __('Payment Method Details') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="row">
                                        {{-- Payment Gateway ID --}}
                                        <input type="hidden" class="form-control" name="payment_gateway_id" value="{{ $gateway_details->id }}">

                                        {{-- Payment Gateway Image (Upload) --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('Payment Gateway Image') }}</label>
                                                <div class="input-group">
                                                    <input type="file" class="form-control input-radius" name="payment_gateway_image" accept="image/png, image/jpeg, image/jpg, image/gif, image/svg, image/webp">
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Payment Gateway Name --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <label
                                                    class="form-label required">{{ __('Payment Method Name') }}</label>
                                                <input type="text" class="form-control" name="payment_gateway_name" autocomplete="off"
                                                    placeholder="{{ __('Payment Method') }}"
                                                    value="{{ $gateway_details->name }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    @include('admin.includes.footer')
</div>
@endsection