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
                        {{ __('Add Payment Method') }}
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

            <div class="row row-deck row-cards">
                {{-- Save payment method --}}
                <div class="col-sm-12 col-lg-12">
                    <form action="{{ route('admin.save.payment.method') }}" method="post" enctype="multipart/form-data"
                        class="card">
                        @csrf
                        <div class="card-header">
                            <h4 class="page-title">{{ __('Payment Method Details') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-10">
                                    <div class="row">
                                        {{-- Upload logo --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __('Thumb Image') }}</div>
                                                <input type="file" class="form-control" name="logo"
                                                    placeholder="{{ __('Thumb Image') }}" required />
                                            </div>
                                        </div>

                                        {{-- Payment gateway name --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Payment Method Name')
                                                    }}</label>
                                                <input type="text" class="form-control" name="name"
                                                    placeholder="{{ __('Payment Method Name') }}" required>
                                            </div>
                                        </div>

                                        {{-- Display name --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Display Name')
                                                    }}</label>
                                                <input type="text" class="form-control" name="display_name"
                                                    placeholder="{{ __('Display Name') }}" required>
                                            </div>
                                        </div>


                                        <h2 class="page-title my-3">
                                            {{ __('Payment Settings') }}
                                        </h2>
                                        {{-- Client ID --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Client ID') }} <span
                                                        class="text-muted">({{ __('Set 0 to disable it')
                                                        }})</span></label>
                                                <input type="text" class="form-control" name="client_id"
                                                    placeholder="{{ __('Client ID') }}" required>
                                            </div>
                                        </div>

                                        {{-- Secret Key --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Secret Key') }} <span
                                                        class="text-muted">({{ __('Set 0 to disable it')
                                                        }})</span></label>
                                                <input type="text" class="form-control" name="secret_key"
                                                    placeholder="{{ __('Secret Key') }}" required>
                                            </div>
                                        </div>

                                        <div class="text-end">
                                            <div class="d-flex">
                                                <button type="submit" class="btn btn-primary btn-md ms-auto">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-plus" width="24" height="24"
                                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                                    </svg>
                                                    {{ __('Add') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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