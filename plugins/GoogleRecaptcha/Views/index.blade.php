@extends('admin.layouts.app')

@section('content')
    <div class="page-wrapper">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <div class="container-fluid">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <div class="page-pretitle">
                            {{ __('Overview') }}
                        </div>
                        <h2 class="page-title mb-2">
                            {{ __('Google reCAPTCHA Settings') }}
                        </h2>
                    </div>
                    <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">
                            <a href="{{ route('admin.plugins.index') }}" class="btn btn-primary text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-left">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M5 12l14 0" />
                                    <path d="M5 12l6 6" />
                                    <path d="M5 12l6 -6" />
                                </svg>
                                {{ __('Back') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-fluid">
                {{-- Failed --}}
                @if (Session::has('failed'))
                    <div class="alert alert-important alert-danger alert-dismissible mb-2" role="alert">
                        <div class="d-flex">
                            <div>
                                {{ Session::get('failed') }}
                            </div>
                        </div>
                        <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                {{-- Success --}}
                @if (Session::has('success'))
                    <div class="alert alert-important alert-success alert-dismissible mb-2" role="alert">
                        <div class="d-flex">
                            <div>
                                {{ Session::get('success') }}
                            </div>
                        </div>
                        <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                <div class="row row-deck row-cards">
                    <div class="col-sm-12 col-lg-12">
                        <form action="{{ route('admin.google_recaptcha_settings.update') }}" method="post" class="card">
                            @csrf
                            <div class="card-header">
                                <h4 class="page-title">{{ __('Google reCAPTCHA Credentials') }}</h4>
                            </div>
                            <div class="card-body">
                                {{-- Google reCAPTCHA --}}
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="row">
                                            <div class=" col-xl-3">
                                                <div class="mb-3">
                                                    <div class="form-label">{{ __('reCAPTCHA Enable') }}</div>
                                                    <select class="form-select tom-select"
                                                        placeholder="{{ __('Select a reCAPTCHA') }}" id="recaptcha_enable"
                                                        name="recaptcha_enable">
                                                        <option value="on"
                                                            {{ $recaptcha_configuration['RECAPTCHA_ENABLE'] == 'on' ? 'selected' : '' }}>
                                                            {{ __('On') }}</option>
                                                        <option value="off"
                                                            {{ $recaptcha_configuration['RECAPTCHA_ENABLE'] == 'off' ? 'selected' : '' }}>
                                                            {{ __('Off') }}</option>
                                                    </select>
                                                </div>
                                                <span>{{ __('If you did not get a reCAPTCHA (v2 Checkbox), create a') }}
                                                    <a href="https://www.google.com/recaptcha/about/"
                                                        target="_blank">{{ __('reCAPTCHA') }}</a> </span>
                                            </div>

                                            {{-- reCAPTCHA Site Key --}}
                                            <div class=" col-xl-3">
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('reCAPTCHA Site Key') }}</label>
                                                    <input type="text" class="form-control" name="recaptcha_site_key"
                                                        value="{{ $recaptcha_configuration['RECAPTCHA_SITE_KEY'] }}"
                                                        placeholder="{{ __('reCAPTCHA Site Key') }}">
                                                </div>
                                            </div>

                                            {{-- reCAPTCHA Secret Key --}}
                                            <div class=" col-xl-3">
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('reCAPTCHA Secret Key') }}</label>
                                                    <input type="text" class="form-control" name="recaptcha_secret_key"
                                                        value="{{ $recaptcha_configuration['RECAPTCHA_SECRET_KEY'] }}"
                                                        placeholder="{{ __('reCAPTCHA Secret Key') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        @include('admin.includes.footer')
    </div>

@section('custom-js')
    {{-- Tom Select --}}
    <script src="{{ asset('js/tom-select.base.min.js') }}"></script>
    <script>
        const el = document.querySelector('.tom-select');
        // Apply TomSelect to the element
        new TomSelect(el, {
            copyClassesToDropdown: false,
            dropdownClass: 'dropdown-menu ts-dropdown',
            optionClass: 'dropdown-item',
            maxOptions: null,
            controlInput: '<input>',
            render: {
                item: function(data, escape) {
                    if (data.customProperties) {
                        return '<div><span class="dropdown-item-indicator">' + data
                            .customProperties + '</span>' + escape(data.text) + '</div>';
                    }
                    return '<div>' + escape(data.text) + '</div>';
                },
                option: function(data, escape) {
                    if (data.customProperties) {
                        return '<div><span class="dropdown-item-indicator">' + data
                            .customProperties + '</span>' + escape(data.text) + '</div>';
                    }
                    return '<div>' + escape(data.text) + '</div>';
                },
            },
        });
    </script>
@endsection
@endsection
