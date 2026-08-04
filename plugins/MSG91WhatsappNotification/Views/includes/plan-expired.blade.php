{{-- Plan Expired Notification --}}
<div class="accordion-item">
    {{-- User Plan Expired Notification Form --}}
    <form action="{{ route('admin.msg91_whatsapp_template_user_plan_expired_notification.update') }}" method="post"
        enctype="multipart/form-data" id="myForm5">
        @csrf

        {{-- Accordion Header --}}
        <h2 class="accordion-header" id="heading-5">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapse-5" aria-expanded="false">
                <h3 class="card-title">{{ __('User Plan Expired Notification') }}</h3>
            </button>
        </h2>

        {{-- Accordion Body --}}
        <div id="collapse-5" class="accordion-collapse collapse" data-bs-parent="#accordion-example">
            <div class="alert alert-important alert-info alert-dismissible mx-3" role="alert">
                <div class="d-flex">
                    <div>
                        {{ __('Note: You need to specify the date and time in the cron job to send notifications. (Settings->Cron Jobs)') }}
                    </div>
                </div>
                <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            <div class="accordion-body pt-0">
                <div class="row">
                    {{-- Sections --}}
                    @php
                        $sections = [
                            [
                                'title' => 'User',
                                'data' => $user_plan_expired_notification,
                                'decoded' => json_decode($user_plan_expired_notification->variables ?? '[]', true),
                                'tableId' => 'variablesTable7',
                                'inputId' => 'variablesInput7',
                                'inputName' => 'variables',
                                'key' => 'user',
                            ],
                        ];

                        $options = [
                            'app_name' => 'App Name',
                            'name' => 'User Name',
                            'email' => 'User Email',
                            'plan_name' => 'Purchased Plan Name',
                            'expiry_date' => 'Purchased Plan Expiry Date',
                        ];
                    @endphp

                    {{-- Loop sections --}}
                    @foreach ($sections as $s)
                        {{-- Title --}}
                        <div>
                            <h3>{{ __('For ' . $s['title']) }}</h3>
                        </div>

                        {{-- Is enabled --}}
                        <div class="col-md-4">
                            <div class="mb-3">
                                <div class="form-label">{{ __('Send Notification to ' . $s['title']) }}</div>
                                <select class="form-select tom-select" name="{{ $s['key'] }}_plan_expired_notification">
                                    <option value="1" {{ $s['data']->is_enabled == 1 ? 'selected' : '' }}>
                                        {{ __('Yes') }}</option>
                                    <option value="0" {{ $s['data']->is_enabled == 0 ? 'selected' : '' }}>
                                        {{ __('No') }}</option>
                                </select>
                            </div>
                        </div>

                        {{-- Template name --}}
                        <div class="mb-3 col-md-4">
                            <div class="form-label">{{ __($s['title'] . ' Template Name') }}</div>
                            <input type="text" class="form-control"
                                name="{{ $s['key'] }}_plan_expired_notification_template_id"
                                value="{{ $s['data']->template_id }}" placeholder="{{ __('Template Name') }}">
                        </div>

                        {{-- Template Namespace --}}
                        <div class="mb-3 col-md-4">
                            <div class="form-label">{{ __('Template Namespace') }}</div>
                            <input type="text" class="form-control"
                                name="{{ $s['key'] }}_plan_expired_notification_template_namespace"
                                value="{{ $s['data']->namespace }}" placeholder="{{ __('Template Namespace') }}">
                        </div>
                    @endforeach

                    {{-- Variables --}}
                    <div class="row">
                        {{-- Loop sections --}}
                        @foreach ($sections as $s)
                            <div class="col-12 col-md-6">
                                {{-- Title --}}
                                <h3 class="mb-3">{{ __('Variables ' . $s['title']) }}</h3>

                                {{-- Variables Table --}}
                                <table class="table border" id="{{ $s['tableId'] }}">
                                    {{-- Header --}}
                                    <tr>
                                        <th class="p-3 border-end w-1">{{ __('#') }}</th>
                                        <th class="p-3 border-end">{{ __('Variable') }}</th>
                                        <th class="p-3 w-50">{{ __('Value') }}</th>
                                    </tr>
                                    {{-- Body --}}
                                    @for ($i = 1; $i <= 5; $i++)
                                        <tr>
                                            <td class="p-3 border-end text-center align-middle">
                                                <input type="checkbox" class="form-check-input variable-check"
                                                    {{ isset($s['decoded'][$i - 1]) ? 'checked' : '' }}>
                                            </td>

                                            <td class="p-3 border-end align-middle">
                                                {!! '@{{ ' . $i . ' }}' !!}
                                            </td>

                                            <td class="p-3">
                                                <select class="form-select variable-select tom-select">
                                                    @foreach ($options as $val => $label)
                                                        <option value="{{ $val }}"
                                                            {{ ($s['decoded'][$i - 1] ?? '') == $val ? 'selected' : '' }}>
                                                            {{ __($label) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    @endfor
                                </table>
                                {{-- Hidden input to hold final array --}}
                                <input type="hidden" name="{{ $s['inputName'] }}" id="{{ $s['inputId'] }}">
                            </div>
                        @endforeach
                    </div>

                    {{-- Update --}}
                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
