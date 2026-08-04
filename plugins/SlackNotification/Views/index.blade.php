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
                            {{ __('Slack Settings') }}
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
                        <form action="{{ route('admin.slack_settings.update') }}" method="post" class="card">
                            @csrf
                            <div class="card-header">
                                <h4 class="page-title">{{ __('Slack Notification Credentials') }}</h4>
                            </div>
                            <div class="card-body">
                                {{-- Slack Settings --}}
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="row">
                                            <div class="col-md-6 col-xl-6">
                                                <div class="mb-3">
                                                    <div class="form-label required">{{ __('Webhook URL') }}</div>
                                                    <input type="text" class="form-control" name="slack_webhook_url"
                                                        value="{{ isset($slack_settings) ? $slack_settings->slack_webhook_url ?? '' : '' }}"
                                                        placeholder="{{ __('Webhook URL') }}" autofocus required>
                                                </div>
                                            </div>
                                            <span>{{ __('If you did not get a Slack Webhook URL, create a') }}
                                                <a href="https://api.slack.com/apps"
                                                    target="_blank">{{ __('new Slack Webhook URL.') }}</a> </span>
                                        </div>
                                    </div>
                                </div>
                                <h3 class="page-title mt-3 mb-3">{{ __('Notification Controls') }}</h3>
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="row">
                                            @php
                                                $notifications = [
                                                    'user_registration' => 'New User Registration Notification',
                                                    'plan_purchase' => 'User Plan Purchase Notification',
                                                    'plan_renewal' => 'User Plan Renewal Notification',
                                                    'error_logging' => 'Error Logs',
                                                ];
                                            @endphp

                                            @foreach ($notifications as $key => $label)
                                                <div class="col-sm-12 col-md-6 col-xl-4">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="{{ $key }}" name="{{ $key }}"
                                                            value="1"
                                                            {{ isset($slack_settings) && $slack_settings->$key == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="{{ $key }}">
                                                            {{ __($label) }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
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
@endsection
