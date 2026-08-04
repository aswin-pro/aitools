@extends('user.layouts.app')

{{-- Custom CSS --}}
@section('custom-css')
    {{-- Record CSS --}}
    <link rel="stylesheet" href="{{ asset('css/record.min.css') }}">
    <style>
    .record-btn {
        bottom: 8px;
    }
    </style>
@endsection

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
                            {{ __('AI Images') }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="container-fluid">

                {{-- Failed --}}
                @if (Session::has('failed'))
                    <div class="alert alert-important alert-danger alert-dismissible" role="alert">
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
                    <div class="alert alert-important alert-success alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div>
                                {{ Session::get('success') }}
                            </div>
                        </div>
                        <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                <div class="row row-deck row-cards">
                    {{-- Parameters --}}
                    <div class="col-xl-12">
                        <form action="javascript:void(0)" id="saveForm" method="POST" class="card">
                            @csrf
                            <div class="card-body">
                                <div class="row row-cards">
                                    {{-- Image you want to create?? --}}
                                    <div class="col-sm-6 col-md-6">
                                        <div class="mb-3 position-relative">
                                            <label class="form-label required">{{ __('Image you want to create?') }}</label>
                                            <input type="text" class="form-control text-capitalize" name="name"
                                                id="name" placeholder="{{ __('Eg: Dog') }}" maxlength="110" required>

                                            {{-- Record Button (inside the field, at the bottom-right corner) --}}
                                            <a href="#" class="record-btn"
                                                onclick="toggleRecording('name')">
                                                <svg id="microphone-icon-name"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icon-tabler-microphone record-icon-tabler">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M9 2m0 3a3 3 0 0 1 3 -3h0a3 3 0 0 1 3 3v5a3 3 0 0 1 -3 3h0a3 3 0 0 1 -3 -3z" />
                                                    <path d="M5 10a7 7 0 0 0 14 0" />
                                                    <path d="M8 21l8 0" />
                                                    <path d="M12 17l0 4" />
                                                </svg>
                                                <svg id="pause-icon-name"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icon-tabler-player-pause record-icon-tabler"
                                                    style="display: none;">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M6 5m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v12a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z" />
                                                    <path
                                                        d="M14 5m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v12a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z" />
                                                </svg>
                                            </a>
                                        </div> 
                                    </div>
                                    {{-- Size --}}
                                    <div class="col-sm-6 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">{{ __('Size') }}</label>
                                            <select class="form-control form-select" name="size" id="size" required>
                                                <option disabled selected>{{ __('Size') }}</option>
                                                @if ($config[46]->config_value == 'dall-e-2')
                                                    <option value="256x256" selected>{{ __('256x256') }}</option>
                                                    <option value="512x512">{{ __('512x512') }}</option>
                                                    <option value="1024x1024">{{ __('1024x1024') }}</option>
                                                @endif

                                                @if ($config[46]->config_value == 'dall-e-3')
                                                    <option value="1024x1024">{{ __('1024x1024') }}</option>
                                                    <option value="1024x1792">{{ __('1024x1792') }}</option>
                                                    <option value="1792x1024">{{ __('1792x1024') }}</option>
                                                @endif

                                                @if ($config[46]->config_value == 'gpt-image-1.5' || $config[46]->config_value == 'chatgpt-image-latest' || $config[46]->config_value == 'gpt-image-1' || $config[46]->config_value == 'gpt-image-1-mini')
                                                    <option value="1024x1024">{{ __('1024x1024') }}</option>
                                                    <option value="1536x1024">{{ __('1536x1024') }}</option>
                                                    <option value="1024x1536">{{ __('1024x1536') }}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    {{-- Styles --}}
                                    <div class="col-sm-6 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">{{ __('Styles') }}</label>
                                            <select class="form-select" name="style" id="styles" required>
                                                {{-- Styles --}}
                                                @include('user.pages.ai-images.includes.styles')
                                            </select>
                                        </div>
                                    </div>
                                    {{-- No. Of Results --}}
                                    <div class="col-sm-6 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">{{ __('No. Of Results') }}</label>
                                            <select class="form-control form-select" name="results" id="results" required>
                                                <option disabled selected>{{ __('Results') }}</option>
                                                {{-- Images options --}}
                                                @if ($config[46]->config_value == 'dall-e-2')
                                                    @for ($i = 1; $i <= $config[42]->config_value; $i++)
                                                        <option value="{{ $i }}"
                                                            {{ $i == 1 ? 'selected' : '' }}>{{ $i }}</option>
                                                    @endfor
                                                @else
                                                    <option value="1" {{ 1 == 1 ? 'selected' : '' }}>
                                                        {{ __('1') }}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button type="submit" id="submit" class="btn btn-primary">{{ __('Generate') }}</button>
                            </div>
                        </form>
                    </div>

                    {{-- Result data --}}
                    <div class="col-xl-12 px-2 d-none" id="response">
                        <div class="row row-cards">
                            <!-- Result -->
                            <div class="col-lg-12 my-4">
                                <h2 class="page-title">
                                    {{ __('Results') }}
                                </h2>
                            </div>
                            {{-- Photo --}}
                            <div id="result" class="row"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    @include('user.includes.footer')
    </div>

    {{-- Custom JS --}}
@section('custom-js')
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/tom-select.base.min.js') }}"></script>
    {{-- Record --}}
    <script src="{{ asset('js/record-ai.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var el;
            window.TomSelect && (new TomSelect(el = document.getElementById('styles'), {
                copyClassesToDropdown: false,
                dropdownParent: 'body',
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
            }));
        });
    </script>
    <script>
        // Fill
        (function($) {
            "use strict";
            $("#saveForm").validate({
                submitHandler: function(form) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $('#submit').html(`{{ __('Please Wait...') }}`);
                    // $("#submit"). attr("disabled", true);
                    $.ajax({
                        url: "{{ route('user.generate.ai.image') }}",
                        type: "POST",
                        data: $('#saveForm').serialize(),
                        success: function(response) {
                            // Check result
                            if ($.isArray(response)) {
                                // Base URL
                                var baseURL = "{{ env('APP_URL') }}";

                                // Remove attribute
                                $('#submit').html(`{{ __('Generate') }}`);
                                $("#submit").attr("disabled", false);
                                // Result
                                var html = "";
                                $.each(response, function(key, val) {
                                    var number = 1 + Math.floor(Math.random() *
                                        9999999999);
                                    html +=
                                        '<div class="col-lg-3"><div class="img-responsive img-responsive-1x1 rounded-3 border mb-3 result" style="background-image: url(' +
                                        baseURL + '/' + val + ')"></div><a href="' +
                                        baseURL + '/' + val +
                                        '" class="btn btn-dark" download="' + number +
                                        '.png"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-photo-down" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M15 8h.01"></path><path d="M12 20h-5a3 3 0 0 1 -3 -3v-10a3 3 0 0 1 3 -3h10a3 3 0 0 1 3 3v5"></path><path d="M4 15l4 -4c.928 -.893 2.072 -.893 3 0l4 4"></path><path d="M14 14l1 -1c.617 -.593 1.328 -.793 2.009 -.598"></path><path d="M19 16v6"></path><path d="M22 19l-3 3l-3 -3"></path></svg>{{ __('Download') }}</a></div>';
                                });
                                $('#response').removeClass('d-none');
                                $('#result').html(html)
                            } else {
                                Swal.fire(
                                    response.message,
                                    ``,
                                    'error'
                                );
                                $('#submit').html(`{{ __('Generate') }}`);
                                $("#submit").attr("disabled", false);
                            }
                        }
                    });
                }
            })
        })(jQuery);
    </script>
@endsection
@endsection
