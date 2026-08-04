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
                        {{ __('Add Plan') }}
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
                {{-- Save Plan --}}
                <div class="col-sm-12 col-lg-12">
                    <form action="{{ route('admin.save.plan') }}" method="post" class="card">
                        @csrf
                        <div class="card-header">
                            <h4 class="page-title">{{ __('Plan Details') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="row">

                                        {{-- Recommended --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __('Recommended') }}</div>
                                                <label class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="recommended">
                                                </label>
                                            </div>
                                        </div> 

                                        {{-- Private Plan --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __('Private Plan') }}</div>
                                                <label class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="is_private">
                                                </label>
                                                <small class="text-muted">{{ __('This plan does not show on the customer page. Only the admin panel can assign this plan to the customer.') }} </small>
                                            </div>
                                        </div>

                                        {{-- Plan / Product ID --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('Plan / Product ID') }}</label>
                                                <input type="text" class="form-control text-capitalize" name="product_id"
                                                    placeholder="{{ __('Plan / Product ID') }}">
                                            </div>
                                        </div>

                                        {{-- Plan Name --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Plan Name') }}</label>
                                                <input type="text" class="form-control text-capitalize" name="name"
                                                    placeholder="{{ __('Plan Name') }}" required>
                                            </div>
                                        </div>

                                        {{-- Plan Description --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Description') }}</label>
                                                <textarea class="form-control text-capitalize" name="description" rows="3"
                                                    placeholder="{{ __('Description') }}.." required></textarea>

                                            </div>
                                        </div>

                                        {{-- Plan Prices --}}
                                        <h2 class="page-title my-3">
                                            {{ __('Plan Prices') }}
                                        </h2>
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Price') }}</label>
                                                <input type="number" class="form-control" name="price" min="0"
                                                    step="0.01" placeholder="{{ __('Price') }}" required>
                                                <small class="text-muted">{{ __('Set 0 for "Free"')}} </small>
                                            </div>
                                        </div>

                                        {{-- Plan Validity --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Validity') }}</label>
                                                <input type="number" class="form-control" name="validity" min="1"
                                                    max="9999" placeholder="{{ __('Validity') }}"
                                                    value="{{ $config[8]->config_value == 'monthly' ? 31 : 365 }}"
                                                    required>
                                                <small class="text-muted">{{ __("Set 31 for 'Month', Set 365 for 'Year', Set 9999 for 'Forever'")}} </small>
                                            </div>
                                        </div>

                                        {{-- Templates --}}
                                        <h2 class="page-title my-3">
                                            {{ __('Templates') }}
                                        </h2>

                                        @foreach ($templates as $template)
                                        <div class="col-md-3 col-xl-3">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __($template->name) }}</div>
                                                <label class="form-check form-switch">
                                                    <input class="form-check-input availableTemplates" type="checkbox" onclick="checkTotalCheckedTemplates()"
                                                        name="{{ $template->unique_slug }}">
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach

                                        {{-- Features --}}
                                        <h2 class="page-title my-3">
                                            {{ __('Features') }}
                                        </h2>

                                        {{-- Templates --}}
                                        <div class="col-md-6 col-xl-4">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Templates') }} <span
                                                        class="text-muted"></label>
                                                <input type="number" class="form-control totalTemplates" name="templates" min="1" readonly
                                                    placeholder="{{ __('Templates') }}" required>
                                            </div>
                                        </div>

                                        {{-- Words Per Month --}}
                                        <div class="col-md-6 col-xl-4">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Words Per Month') }}</label>
                                                <input type="number" class="form-control" name="words" min="1"
                                                    placeholder="{{ __('Eg: 3000') }}" required>
                                            </div>
                                        </div>

                                        {{-- Images Per Month --}}
                                        <div class="col-md-6 col-xl-4">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Images Per Month') }}</label>
                                                <input type="number" class="form-control" name="images" min="1"
                                                    placeholder="{{ __('Eg: 100') }}" required>
                                            </div>
                                        </div>

                                        {{-- AI Speech to Text --}}
                                        <div class="col-md-3 col-xl-3">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __('AI Speech to Text') }}</div>
                                                <label class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                    name="ai_speech_to_text">
                                                </label>
                                            </div>
                                        </div>

                                        {{-- AI Text To Speech --}}
                                        <div class="col-md-3 col-xl-3">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __('AI Text to Speech') }}</div>
                                                <label class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                    name="ai_text_to_speech">
                                                </label>
                                            </div>
                                        </div>

                                        {{-- AI Code --}}
                                        <div class="col-md-3 col-xl-3">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __('AI Code') }}</div>
                                                <label class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                    name="ai_code">
                                                </label>
                                            </div>
                                        </div>

                                        {{-- AI Chat Assistant --}}
                                        <div class="col-md-3 col-xl-3">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __('AI Chat Assistant') }}</div>
                                                <label class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                    name="ai_chatgenius">
                                                </label>
                                            </div>
                                        </div>

                                        {{-- AI File Analyzer --}}
                                        <div class="col-md-3 col-xl-3">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __('AI File Analyzer') }}</div>
                                                <label class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                    name="ai_docsassist">
                                                </label>
                                            </div>
                                        </div>

                                        {{-- AI Web Chat --}}
                                        <div class="col-md-3 col-xl-3">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __('AI Web Chat') }}</div>
                                                <label class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                    name="ai_webchat">
                                                </label>
                                            </div>
                                        </div>

                                        {{-- Additional Features --}}
                                        <h2 class="page-title my-3">
                                            {{ __('Additional') }}
                                        </h2>

                                        {{-- Additional Tools --}}
                                        <div class="col-md-3 col-xl-3">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __('Additional Tools') }}</div>
                                                <label class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                    name="additional_tools">
                                                </label>
                                            </div>
                                        </div>

                                        {{-- Support --}}
                                        <div class="col-md-3 col-xl-3">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __('Support') }}</div>
                                                <label class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="support">
                                                </label>
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

{{-- Custom JS --}}
@section('custom-js')
<script>
function checkTotalCheckedTemplates(){
    "use strict";
    var templates = $('.availableTemplates:checked').length;
    $(".totalTemplates").val(0);
    if (templates != 0) {
        $(".totalTemplates").val(templates);
    }
}
</script>
@endsection
@endsection