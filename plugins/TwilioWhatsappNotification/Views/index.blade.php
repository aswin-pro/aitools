@extends('admin.layouts.app')

@php
    // get templates
    $templates = collect($twilio_whatsapp_notification_templates)->keyBy('template_name');

    $new_user_registration_admin = $templates['New User Registration Admin'] ?? null;
    $plan_purchase_admin = $templates['Plan Purchase Admin'] ?? null;
    $plan_purchase_user = $templates['Plan Purchase User'] ?? null;
    $plan_renewal_admin = $templates['Plan Renewal Admin'] ?? null;
    $plan_renewal_user = $templates['Plan Renewal User'] ?? null;
    $user_plan_expiry_remainder = $templates['User Plan Expiry Remainder'] ?? null;
    $user_plan_expired_notification = $templates['User Plan Expired Notification'] ?? null;
@endphp

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
                            {{ __('Twilio Whatsapp Notification Settings') }}
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
                        <form action="{{ route('admin.twilio_whatsapp_notification_settings.update') }}" method="post"
                            class="card">
                            @csrf
                            <div class="card-header">
                                <h4 class="page-title">{{ __('Twilio Whatsapp Notification Credentials') }}</h4>
                            </div>
                            <div class="card-body">
                                {{-- Twilio Settings --}}
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="row">
                                            {{-- Account SID --}}
                                            <div class="col-md-6 col-xl-6">
                                                <div class="mb-3">
                                                    <div class="form-label required">{{ __('Account SID') }}</div>
                                                    <input type="text" class="form-control" name="account_sid"
                                                        value="{{ isset($twilio_whatsapp_notification_settings) ? $twilio_whatsapp_notification_settings->account_sid ?? '' : '' }}"
                                                        placeholder="{{ __('Account SID') }}" autofocus required>
                                                </div>
                                            </div>
                                            {{-- Auth Token --}}
                                            <div class="col-md-6 col-xl-6">
                                                <div class="mb-3">
                                                    <div class="form-label required">{{ __('Auth Token') }}</div>
                                                    <input type="text" class="form-control" name="auth_token"
                                                        value="{{ isset($twilio_whatsapp_notification_settings) ? $twilio_whatsapp_notification_settings->auth_token ?? '' : '' }}"
                                                        placeholder="{{ __('Auth Token') }}" required>
                                                </div>
                                            </div>
                                            {{-- Twilio Business Whatsapp Number (with country code) --}}
                                            <div class="col-md-6 col-xl-6">
                                                <div class="mb-3">
                                                    <div class="form-label required">
                                                        {{ __('Twilio Business Whatsapp Number (with country code)') }}
                                                    </div>
                                                    <input type="number" class="form-control" name="from_number"
                                                        value="{{ isset($twilio_whatsapp_notification_settings) ? $twilio_whatsapp_notification_settings->from_number ?? '' : '' }}"
                                                        placeholder="{{ __('Whatsapp Number') }}" required>
                                                </div>
                                            </div>
                                            {{-- Admin Whatsapp Number (with country code) --}}
                                            <div class="col-md-6 col-xl-6">
                                                <div class="mb-3">
                                                    <div class="form-label required">
                                                        {{ __('Admin Whatsapp Number (with country code)') }}</div>
                                                    <input type="number" class="form-control" name="admin_number"
                                                        value="{{ isset($twilio_whatsapp_notification_settings) ? $twilio_whatsapp_notification_settings->admin_number ?? '' : '' }}"
                                                        placeholder="{{ __('Whatsapp Number') }}" required>
                                                </div>
                                            </div>
                                            <span>{{ __('If you did not get these credentials, create a') }}
                                                <a href="https://www.twilio.com/console"
                                                    target="_blank">{{ __('new Twilio Whatsapp Notification Credentials.') }}</a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end mt-3">
                                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-sm-12 col-lg-12 card">
                        <div class="card-header">
                            <h4 class="page-title">{{ __('Twilio Whatsapp Notification Templates And Controls') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="accordion" id="accordion-example">

                                @php
                                    // Shortcodes
                                    $shortcodes = [
                                        'user_basic' => [
                                            ['code' => '{{app_name}}', 'label' => 'App Name'],
                                            ['code' => '{{name}}', 'label' => 'User Name'],
                                            ['code' => '{{email}}', 'label' => 'User Email'],
                                        ],
                                        'plan_basic' => [
                                            ['code' => '{{app_name}}', 'label' => 'App Name'],
                                            ['code' => '{{name}}', 'label' => 'User Name'],
                                            ['code' => '{{email}}', 'label' => 'User Email'],
                                            ['code' => '{{plan_name}}', 'label' => 'Purchased Plan Name'],
                                            ['code' => '{{currency}}', 'label' => 'Currency Code (Ex: USD)'],
                                            ['code' => '{{plan_price}}', 'label' => 'Purchased Plan Price'],
                                            ['code' => '{{plan_validity}}', 'label' => 'Purchased Plan Validity'],
                                            ['code' => '{{plan_expiry_date}}', 'label' => 'Plan Expiry Date'],
                                        ],
                                        'plan_expiry' => [
                                            ['code' => '{{app_name}}', 'label' => 'App Name'],
                                            ['code' => '{{name}}', 'label' => 'User Name'],
                                            ['code' => '{{email}}', 'label' => 'User Email'],
                                            ['code' => '{{plan_name}}', 'label' => 'Purchased Plan Name'],
                                            ['code' => '{{expiry_date}}', 'label' => 'Purchased Plan Expiry Date'],
                                        ]
                                    ];

                                    // Sections
                                    $sections = [
                                        [
                                            'title' => 'New User Registration Notification',
                                            'route' => 'admin.twilio_whatsapp_template_user_register.update',
                                            'form_id' => 'myForm1',
                                            'blocks' => [
                                                [
                                                    'fields' => [
                                                        [
                                                            'label' => 'Send Notification to Admin',
                                                            'name' => 'new_user_registration_admin',
                                                            'model' => $new_user_registration_admin,
                                                        ],
                                                        [
                                                            'label' => 'Admin Template SID',
                                                            'name' => 'new_user_registration_admin_template_sid',
                                                            'model' => $new_user_registration_admin,
                                                            'type' => 'text',
                                                        ],
                                                    ],
                                                    'shortcodes' => 'user_basic',
                                                ],
                                            ],
                                        ],
                                        [
                                            'title' => 'Plan Purchase Notification',
                                            'route' => 'admin.twilio_whatsapp_template_plan_purchase.update',
                                            'form_id' => 'myForm2',
                                            'blocks' => [
                                                [
                                                    'fields' => [
                                                        [
                                                            'label' => 'Send Notification to Admin',
                                                            'name' => 'plan_purchase_admin',
                                                            'model' => $plan_purchase_admin,
                                                        ],
                                                        [
                                                            'label' => 'Admin Template SID',
                                                            'name' => 'plan_purchase_admin_template_sid',
                                                            'model' => $plan_purchase_admin,
                                                            'type' => 'text',
                                                        ],
                                                        [
                                                            'label' => 'Send Notification to Business',
                                                            'name' => 'plan_purchase_user',
                                                            'model' => $plan_purchase_user,
                                                        ],
                                                        [
                                                            'label' => 'Business Template SID',
                                                            'name' => 'plan_purchase_user_template_sid',
                                                            'model' => $plan_purchase_user,
                                                            'type' => 'text',
                                                        ],
                                                    ],
                                                    'shortcodes' => 'plan_basic',
                                                ],
                                            ],
                                        ],
                                        [
                                            'title' => 'Plan Renewal Notification',
                                            'route' => 'admin.twilio_whatsapp_template_plan_renewal.update',
                                            'form_id' => 'myForm3',
                                            'blocks' => [
                                                [
                                                    'fields' => [
                                                        [
                                                            'label' => 'Send Notification to Admin',
                                                            'name' => 'plan_renewal_admin',
                                                            'model' => $plan_renewal_admin,
                                                        ],
                                                        [
                                                            'label' => 'Admin Template SID',
                                                            'name' => 'plan_renewal_admin_template_sid',
                                                            'model' => $plan_renewal_admin,
                                                            'type' => 'text',
                                                        ],
                                                        [
                                                            'label' => 'Send Notification to Business',
                                                            'name' => 'plan_renewal_user',
                                                            'model' => $plan_renewal_user,
                                                        ],
                                                        [
                                                            'label' => 'Business Template SID',
                                                            'name' => 'plan_renewal_user_template_sid',
                                                            'model' => $plan_renewal_user,
                                                            'type' => 'text',
                                                        ],
                                                    ],
                                                    'shortcodes' => 'plan_basic',
                                                ],
                                            ],
                                        ],
                                        [
                                            'title' => 'Plan Expiry Remainder',
                                            'route' => 'admin.twilio_whatsapp_template_plan_expiry_remainder.update',
                                            'form_id' => 'myForm4',
                                            'alert' => true,
                                            'blocks' => [
                                                [
                                                    'fields' => [
                                                        ['label' => 'Send Notification to User', 'name' => 'user_plan_expiry_remainder', 'model' => $user_plan_expiry_remainder],
                                                        ['label' => 'User Template SID', 'name' => 'user_plan_expiry_remainder_template_sid', 'model' => $user_plan_expiry_remainder, 'type' => 'text'],
                                                    ],
                                                    'shortcodes' => 'plan_expiry',
                                                ],
                                            ],
                                        ],
                                        [
                                            'title' => 'Plan Expired Notification',
                                            'route' => 'admin.twilio_whatsapp_template_plan_expired_notification.update',
                                            'form_id' => 'myForm5',
                                            'alert' => true,
                                            'blocks' => [
                                                [
                                                    'fields' => [
                                                        ['label' => 'Send Notification to User', 'name' => 'user_plan_expired_notification', 'model' => $user_plan_expired_notification],
                                                        ['label' => 'User Template SID', 'name' => 'user_plan_expired_notification_template_sid', 'model' => $user_plan_expired_notification, 'type' => 'text'],
                                                    ],
                                                    'shortcodes' => 'plan_expiry',
                                                ],
                                            ],
                                        ],
                                    ];
                                @endphp

                                @foreach ($sections as $i => $section)
                                    <div class="accordion-item">
                                        <form action="{{ route($section['route']) }}" method="post"
                                            enctype="multipart/form-data" id="{{ $section['form_id'] }}">
                                            @csrf

                                            <h2 class="accordion-header" id="heading-{{ $i }}">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapse-{{ $i }}">
                                                    <h3 class="card-title">{{ __($section['title']) }}</h3>
                                                </button>
                                            </h2>

                                            <div id="collapse-{{ $i }}" class="accordion-collapse collapse"
                                                data-bs-parent="#accordion-example">

                                                @if(!empty($section['alert']))
                                                    <div class="alert alert-important alert-info alert-dismissible mx-3">
                                                        {{ __('Note: You need to specify the date and time in the cron job to send notifications. (Settings->Cron Jobs)') }}
                                                        <a class="btn-close btn-close-white" data-bs-dismiss="alert"></a>
                                                    </div>
                                                @endif

                                                <div class="accordion-body pt-0">
                                                    <div class="row">

                                                        @foreach ($section['blocks'] as $block)
                                                            @foreach ($block['fields'] as $field)
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <div class="form-label">{{ __($field['label']) }}
                                                                        </div>

                                                                        @if (($field['type'] ?? 'select') === 'text')
                                                                            <input type="text" class="form-control"
                                                                                name="{{ $field['name'] }}"
                                                                                value="{{ $field['model']->template_sid }}"
                                                                                placeholder="{{ __($field['label']) }}">
                                                                        @else
                                                                            <select class="form-select tom-select"
                                                                                name="{{ $field['name'] }}">
                                                                                <option value="1"
                                                                                    {{ $field['model']->is_enabled ? 'selected' : '' }}>
                                                                                    {{ __('Yes') }}</option>
                                                                                <option value="0"
                                                                                    {{ !$field['model']->is_enabled ? 'selected' : '' }}>
                                                                                    {{ __('No') }}</option>
                                                                            </select>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endforeach

                                                            <div class="col-12 col-md-6">
                                                                <h3 class="mb-3">{{ __('Short codes/Variables :') }}
                                                                </h3>
                                                                <table class="border">
                                                                    <tr>
                                                                        <th class="p-3">{{ __('Short Code') }}</th>
                                                                        <th class="p-3">{{ __('Value') }}</th>
                                                                    </tr>
                                                                    @foreach ($shortcodes[$block['shortcodes']] as $sc)
                                                                        <tr>
                                                                            <td class="p-3">{{ $sc['code'] }}</td>
                                                                            <td class="p-3">{{ __($sc['label']) }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </table>
                                                            </div>
                                                        @endforeach

                                                        <div class="mt-3 text-end">
                                                            <button type="submit"
                                                                class="btn btn-primary">{{ __('Update') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Footer --}}
        @include('admin.includes.footer')
    </div>

    {{-- Custom JS --}}
@section('custom-js')
    {{-- Tom Select --}}
    <script src="{{ asset('js/tom-select.base.min.js') }}"></script>
    <script>
        const els = document.querySelectorAll('.tom-select');

        els.forEach(el => {
            new TomSelect(el, {
                copyClassesToDropdown: false,
                dropdownClass: 'dropdown-menu ts-dropdown',
                optionClass: 'dropdown-item',
                maxOptions: null,
                controlInput: '<input>',
                render: {
                    item: function(data, escape) {
                        if (data.customProperties) {
                            return '<div><span class="dropdown-item-indicator">' +
                                data.customProperties +
                                '</span>' + escape(data.text) + '</div>';
                        }
                        return '<div>' + escape(data.text) + '</div>';
                    },
                    option: function(data, escape) {
                        if (data.customProperties) {
                            return '<div><span class="dropdown-item-indicator">' +
                                data.customProperties +
                                '</span>' + escape(data.text) + '</div>';
                        }
                        return '<div>' + escape(data.text) + '</div>';
                    },
                },
            });
        });
    </script>
@endsection
@endsection
