@extends('user.layouts.app')

{{-- Custom CSS --}}
@section('custom-css')
    <style>
        .custom-body {
            padding: 0 !important;
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
                            {{ __('Edit Code') }}
                        </h2>
                        <span class="text-muted">
                            {{ __("Note: If you want to change the content format, first change the content and save. then hit 'EXPORT' button.") }}
                        </span>
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

                {{-- Update --}}
                <div class="row row-cards">
                    <div class="col-lg-12 col-md-12 px-3">
                        <div class="card">

                            {{-- Card Header --}}
                            <div class="card-header">
                                <h3 class="card-title text-capitalize">{{ $content->name }}</h3>
                                <div class="col-auto ms-auto d-print-none">
                                    <div class="btn-list">
                                        <a href="{{ route('user.export.docs.code', $content->generate_id) }}"
                                            class="btn btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon dropdown-item-icon icon-tabler-notes" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path
                                                    d="M5 3m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z">
                                                </path>
                                                <path d="M9 7l6 0"></path>
                                                <path d="M9 11l6 0"></path>
                                                <path d="M9 15l4 0"></path>
                                            </svg>
                                            {{ __('Export Docs') }}
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="card-body custom-body">
                                <form action="{{ route('user.update.ai.code') }}" id="saveForm" method="POST">
                                    @csrf
                                    <input type="hidden" name="generateId" value="{{ $content->generate_id }}"
                                        id="generateId">
                                    {{-- Hidden input holds value for form submit --}}
                                    <input type="hidden" name="result" id="result" value="{{ $content->content }}">

                                    {{-- Code Display Block --}}
                                    <div class="position-relative">

                                        {{-- Toolbar --}}
                                        <div class="d-flex align-items-center justify-content-between px-3 py-2"
                                            style="background:#2d2d2d; border-radius:8px 8px 0 0;">
                                            <div class="d-flex gap-1">
                                                <span
                                                    style="width:12px; height:12px; background:#ff5f57; border-radius:50%; display:inline-block;"></span>
                                                <span
                                                    style="width:12px; height:12px; background:#febc2e; border-radius:50%; display:inline-block;"></span>
                                                <span
                                                    style="width:12px; height:12px; background:#28c840; border-radius:50%; display:inline-block;"></span>
                                            </div>
                                            <button type="button" onclick="copyCode()"
                                                class="btn btn-sm btn-secondary d-flex align-items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2">
                                                    <rect x="9" y="9" width="13" height="13" rx="2"
                                                        ry="2"></rect>
                                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1">
                                                    </path>
                                                </svg>
                                                <span id="copyText">{{ __('Copy') }}</span>
                                            </button>
                                        </div>

                                        {{-- Editable Code Block --}}
                                        <pre id="codeDisplay"
                                            style="background:#1e1e1e; color:#d4d4d4; padding:20px;
                                                   border-radius:0 0 8px 8px; overflow-x:auto; font-size:13px;
                                                   line-height:1.6; min-height:400px; margin:0;
                                                   white-space:pre-wrap; word-wrap:break-word;"><code
                                                id="codeBlock"
                                                contenteditable="true"
                                                spellcheck="false"
                                                style="outline:none; display:block;"
                                                oninput="syncResult()">{{ $content->content }}</code></pre>
                                    </div>

                                    {{-- Card Footer --}}
                                    <div class="card-footer text-end">
                                        <div class="d-flex">
                                            <a href="{{ route('user.all.ai.code') }}"
                                                class="btn btn-link">{{ __('Cancel') }}</a>
                                            <a href="{{ route('user.view.ai.code', $content->generate_id) }}"
                                                class="btn btn-dark">{{ __('View') }}</a>
                                            <button type="submit" id="submit"
                                                class="btn btn-primary ms-auto">{{ __('Update') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

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
    <script>
        "use strict";

        // Sync contenteditable code block value → hidden input on every keystroke
        function syncResult() {
            document.getElementById('result').value =
                document.getElementById('codeBlock').innerText;
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

        // Sync once on load to make sure hidden input is populated
        syncResult();
    </script>
@endsection
@endsection
