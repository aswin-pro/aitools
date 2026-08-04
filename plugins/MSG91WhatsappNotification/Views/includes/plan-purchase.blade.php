{{-- Plan Purchase Notification --}}
<div class="accordion-item">
    {{-- Plan Purchase Notification Form --}}
    <form action="{{ route('admin.msg91_whatsapp_template_plan_purchase.update') }}" method="post"
        enctype="multipart/form-data" id="myForm2">
        @csrf

        {{-- Accordion Header --}}
        <h2 class="accordion-header" id="heading-2">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapse-2" aria-expanded="false">
                <h3 class="card-title">{{ __('Plan Purchase Notification') }}</h3>
            </button>
        </h2>

        {{-- Accordion Body --}}
        <div id="collapse-2" class="accordion-collapse collapse" data-bs-parent="#accordion-example">
            <div class="accordion-body pt-0">
                <div class="row">
                    {{-- Sections --}}
                    @php
                        $sections = [
                            [
                                'title' => 'Admin',
                                'data' => $plan_purchase_admin,
                                'decoded' => json_decode($plan_purchase_admin->variables ?? '[]', true),
                                'tableId' => 'variablesTable2',
                                'inputId' => 'variablesInput2',
                                'inputName' => 'variablesAdmin',
                                'key' => 'admin',
                            ],
                            [
                                'title' => 'User',
                                'data' => $plan_purchase_user,
                                'decoded' => json_decode($plan_purchase_user->variables ?? '[]', true),
                                'tableId' => 'variablesTable3',
                                'inputId' => 'variablesInput3',
                                'inputName' => 'variablesUser',
                                'key' => 'user',
                            ],
                        ];

                        $options = [
                            'app_name' => 'App Name',
                            'name' => 'User Name',
                            'email' => 'User Email',
                            'plan_name' => 'Plan Name',
                            'currency' => 'Currency Code',
                            'plan_price' => 'Plan Price',
                            'plan_validity' => 'Plan Validity',
                            'plan_expiry_date' => 'Plan Expiry Date',
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
                                <select class="form-select tom-select" name="plan_purchase_{{ $s['key'] }}">
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
                                name="plan_purchase_{{ $s['key'] }}_template_id"
                                value="{{ $s['data']->template_id }}" placeholder="{{ __('Template Name') }}">
                        </div>

                        {{-- Template Namespace --}}
                        <div class="mb-3 col-md-4">
                            <div class="form-label">{{ __('Template Namespace') }}</div>
                            <input type="text" class="form-control"
                                name="plan_purchase_{{ $s['key'] }}_template_namespace"
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
                                    @for ($i = 1; $i <= 8; $i++)
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
