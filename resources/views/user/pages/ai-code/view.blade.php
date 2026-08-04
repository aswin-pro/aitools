@extends('user.layouts.app')

{{-- Custom CSS --}}
@section('custom-css')
    <style>
        .custom-drop.show {
            display: block;
            transform: translate3d(0px, 44.7143px, 0px) !important;
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
                            {{ __('View Code') }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-fluid">
                <div class="row row-cards">
                    <div class="col-lg-12 col-md-12 px-3">
                        <div class="card">

                            {{-- Card Header --}}
                            <div class="card-header">
                                <h3 class="card-title text-capitalize">{{ $content->name }}</h3>

                                {{-- Options --}}
                                <div class="col-auto ms-auto d-print-none">
                                    <div class="btn-list">
                                        <span class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle align-text-top"
                                                data-bs-boundary="viewport" data-bs-toggle="dropdown"
                                                aria-expanded="true">{{ __('Exports') }}</button>
                                            <div class="dropdown-menu dropdown-menu-end custom-drop"
                                                data-popper-placement="bottom-end">

                                                {{-- Edit --}}
                                                <a href="{{ route('user.edit.ai.code', $content->generate_id) }}"
                                                    class="dropdown-item">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon"
                                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4">
                                                        </path>
                                                        <path d="M13.5 6.5l4 4"></path>
                                                    </svg>
                                                    {{ __('Edit') }}
                                                </a>

                                                {{-- Copy --}}
                                                <button class="dropdown-item" onclick="copyCode()">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon"
                                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path
                                                            d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2">
                                                        </path>
                                                        <path
                                                            d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z">
                                                        </path>
                                                    </svg>
                                                    <span id="copyText">{{ __('Copy') }}</span>
                                                </button>

                                                {{-- Print --}}
                                                <button type="button" class="dropdown-item"
                                                    onclick="javascript:window.print();">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon"
                                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path
                                                            d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2">
                                                        </path>
                                                        <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4"></path>
                                                        <path
                                                            d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z">
                                                        </path>
                                                    </svg>
                                                    {{ __('Print') }}
                                                </button>

                                                {{-- Export Docs --}}
                                                <a href="{{ route('user.export.docs.code', $content->generate_id) }}"
                                                    class="dropdown-item">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon"
                                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
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
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="card-body p-0">

                                {{-- Toolbar --}}
                                <div class="d-flex align-items-center justify-content-between px-3 py-2"
                                    style="background:#2d2d2d; border-radius:0;">
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
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2">
                                            </rect>
                                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                        </svg>
                                        <span id="copyBtnText">{{ __('Copy') }}</span>
                                    </button>
                                </div>

                                {{-- Code Block --}}
                                <pre id="codeDisplay"
                                    style="background:#1e1e1e; color:#d4d4d4; padding:20px;
                                           border-radius:0 0 4px 4px; overflow-x:auto; font-size:13px;
                                           line-height:1.6; min-height:400px; margin:0;
                                           white-space:pre-wrap; word-wrap:break-word;"><code
                                        id="codeBlock">{{ $content->content }}</code></pre>

                            </div>
                            {{-- /Card Body --}}

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
    <script>
        "use strict";

        function copyCode() {
            const code = document.getElementById('codeBlock').innerText;
            navigator.clipboard.writeText(code).then(() => {
                // Update both copy buttons (toolbar + dropdown)
                document.getElementById('copyBtnText').textContent = '{{ __('Copied!') }}';
                document.getElementById('copyText').textContent = '{{ __('Copied!') }}';
                setTimeout(() => {
                    document.getElementById('copyBtnText').textContent = '{{ __('Copy') }}';
                    document.getElementById('copyText').textContent = '{{ __('Copy') }}';
                }, 2000);
            });
        }
    </script>
@endsection
@endsection
