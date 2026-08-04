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
                        {{ __('IP lookup') }}
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row row-deck row-cards">
                {{-- Search IP Lookup --}}
                <div class="col-sm-10 col-lg-10">
                    <form action="{{ route('user.result.ip-lookup') }}" method="post" class="card">
                        @csrf
                        <div class="card-body">

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

                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="row">
                                        {{-- IP Address --}}
                                        <div class="col-md-12 col-xl-12">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('IP Address') }}</label>
                                                <input type="text" class="form-control" name="ip"
                                                    placeholder="{{ $result['traits']['ip_address'] ?? (old('ip') ?? request()->ip()) }}" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-xl-4 my-3">
                                            <div class="mb-3">
                                                <button type="submit" class="btn btn-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-search" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <circle cx="10" cy="10" r="7"></circle>
                                                        <line x1="21" y1="21" x2="15" y2="15"></line>
                                                    </svg>
                                                    {{ __('Search') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>


                {{-- Result --}}
                @if(!empty($result))
                <div class="col-xl-10 col-md-10 card border-0 shadow-sm mt-3">
                    <div class="card-header align-items-center">
                        <div class="row">
                            <div class="col">
                                <div class="font-weight-medium py-1">{{ __('Result') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mb-n3">
                        <div class="form-row">
                            <div class="col-xl-12 col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="i-country">{{ __('Country') }}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <span class="avatar avatar-xs" style="background-image: url({{ asset('/images/icons/countries/'. mb_strtolower($result['country']['iso_code'] ?? 'unknown')) }}.svg)"></span>
                                            </div>
                                        </div>
                                        <input id="i-country" class="form-control" type="text"
                                            value="{{ __($result['country']['names']['en'] ?? 'Unknown') }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-12 col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="i-city">{{ __('City') }}</label>
                                    <input id="i-city" class="form-control" type="text"
                                        value="{{ __($result['city']['names']['en'] ?? 'Unknown') }}" readonly>
                                </div>
                            </div>

                            <div class="col-xl-12 col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="i-postal-code">{{ __('Postal code') }}</label>
                                    <input id="i-postal-code" class="form-control" type="text"
                                        value="{{ __($result['postal']['code'] ?? 'Unknown') }}" readonly>
                                </div>
                            </div>

                            <div class="col-12 col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="i-latitude">{{ __('Latitude') }}</label>
                                    <input id="i-latitude" class="form-control" type="text"
                                        value="{{ __($result['location']['latitude'] ?? 'Unknown') }}" readonly>
                                </div>
                            </div>

                            <div class="col-12 col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="i-longitude">{{ __('Longtitude') }}</label>
                                    <input id="i-longitude" class="form-control" type="text"
                                        value="{{ __($result['location']['longitude'] ?? 'Unknown') }}" readonly>
                                </div>
                            </div>

                            <div class="col-12 col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="i-timezone">{{ __('Timezone') }}</label>
                                    <input id="i-timezone" class="form-control" type="text"
                                        value="{{ __($result['location']['time_zone'] ?? 'Unknown') }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Footer --}}
    @include('user.includes.footer')
</div>
@endsection