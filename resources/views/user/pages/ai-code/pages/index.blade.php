@extends('user.layouts.app')

{{-- Custom CSS --}}
@section('custom-css')
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>

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
                            {{ __('AI Code') }}
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
                            <div>{{ Session::get('failed') }}</div>
                        </div>
                        <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                {{-- Success --}}
                @if (Session::has('success'))
                    <div class="alert alert-important alert-success alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div>{{ Session::get('success') }}</div>
                        </div>
                        <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                <div class="row row-deck row-cards">

                    {{-- Parameters --}}
                    <div class="col-xl-12">
                        {{-- FIX: renamed id from saveForm -> generateForm --}}
                        <form action="javascript:void(0)" id="generateForm" method="POST" class="card">
                            @csrf
                            <div class="card-body">
                                <div class="row row-cards">
                                    <div class="col-sm-12 col-md-12">
                                        <div class="mb-3 position-relative">
                                            <label class="form-label">{{ __('Which program do you need?') }}</label>
                                            <textarea class="form-control" name="description" id="description" rows="3"
                                                placeholder="{{ __("Write a PHP program to 'Hello World'") }}"
                                                maxlength="{{ $config[30]->config_value == null ? '600' : $config[30]->config_value }}"></textarea>

                                            {{-- Record Button --}}
                                            <a href="#" class="record-btn" onclick="toggleRecording('description')">
                                                <svg id="microphone-icon-description" xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    class="icon icon-tabler icon-tabler-microphone record-icon-tabler">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M9 2m0 3a3 3 0 0 1 3 -3h0a3 3 0 0 1 3 3v5a3 3 0 0 1 -3 3h0a3 3 0 0 1 -3 -3z" />
                                                    <path d="M5 10a7 7 0 0 0 14 0" />
                                                    <path d="M8 21l8 0" />
                                                    <path d="M12 17l0 4" />
                                                </svg>
                                                <svg id="pause-icon-description" xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"
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
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button type="submit" id="submit" class="btn btn-primary">{{ __('Generate') }}</button>
                            </div>
                        </form>
                    </div>

                    {{-- Result data --}}
                    <div class="col-xl-12 px-3 d-none" id="response">
                        <div class="row row-cards">
                            {{-- FIX: this keeps id="saveForm" for the update form only --}}
                            <form action="{{ route('user.update.ai.code') }}" id="saveForm" method="POST" class="card">
                                @csrf
                                <div class="p-3">
                                    <input type="hidden" name="generateId" id="generateId">
                                    <input type="hidden" name="result" id="result">

                                    {{-- Code Display --}}
                                    <div class="position-relative">
                                        {{-- Copy Button --}}
                                        <button type="button" id="copyBtn" onclick="copyCode()"
                                            class="btn btn-sm btn-secondary position-absolute"
                                            style="top: 10px; right: 10px; z-index: 10;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="9" y="9" width="13" height="13" rx="2"
                                                    ry="2"></rect>
                                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                            </svg>
                                            <span id="copyText">{{ __('Copy') }}</span>
                                        </button>

                                        {{-- Code Block --}}
                                        <pre id="codeDisplay"
                                            style="background:#1e1e1e; color:#d4d4d4; padding:20px; border-radius:8px;
                                                   overflow-x:auto; font-size:13px; line-height:1.6;
                                                   min-height:200px; margin:0; white-space:pre-wrap; word-wrap:break-word;">
                                            <code id="codeBlock"></code>
                                        </pre>
                                    </div>

                                    <div class="card-footer text-end px-0">
                                        <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    @include('user.includes.footer')
    </div>

@section('custom-js')
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    {{-- Record --}}
    <script src="{{ asset('js/record-ai.js') }}"></script>

    <script>
        "use strict";

        // FIX: target #generateForm (not #saveForm)
        (function($) {
            $("#generateForm").validate({
                submitHandler: function(form) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $('#submit').html(`{{ __('Please Wait...') }}`);
                    $("#submit").attr("disabled", true);

                    $.ajax({
                        url: "{{ route('user.generate.ai.code') }}",
                        type: "POST",
                        // FIX: serialize generateForm, not saveForm
                        data: $('#generateForm').serialize(),
                        success: function(response) {
                            if (response[0] != null) {
                                // FIX: call setCodeResult() instead of TinyMCE
                                setCodeResult(response[1], response[0]);
                            } else {
                                Swal.fire(
                                    `{{ __('Content Creation Failed') }}`,
                                    ``,
                                    'error'
                                );
                            }

                            $('#submit').html(`{{ __('Generate') }}`);
                            $("#submit").attr("disabled", false);
                        },
                        error: function() {
                            Swal.fire(`{{ __('Something went wrong') }}`, ``, 'error');
                            $('#submit').html(`{{ __('Generate') }}`);
                            $("#submit").attr("disabled", false);
                        }
                    });
                }
            });
        })(jQuery);

        // Populate code block and show result panel
        function setCodeResult(generateId, content) {
            document.getElementById('generateId').value = generateId;
            document.getElementById('result').value = content;
            document.getElementById('codeBlock').textContent = content;
            document.getElementById('response').classList.remove('d-none');
        }

        // Copy to clipboard
        function copyCode() {
            const code = document.getElementById('result').value;
            navigator.clipboard.writeText(code).then(() => {
                const copyText = document.getElementById('copyText');
                copyText.textContent = '{{ __('Copied!') }}';
                setTimeout(() => copyText.textContent = '{{ __('Copy') }}', 2000);
            });
        }
    </script>
@endsection
@endsection
