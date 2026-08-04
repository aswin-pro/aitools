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
                        {{ __('View Content') }}
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
                        <div class="card-header">
                            {{-- Content Title --}}
                            <h3 class="card-title text-capitalize">{{ $content->name }}</h3>

                            {{-- Options --}}
                            <div class="col-auto ms-auto d-print-none">
                                <div class="btn-list">
                                    <div class="col-auto ms-auto d-print-none">
                                        <span class="dropdown">
                                            {{-- Exports --}}
                                            <button class="btn btn-primary dropdown-toggle align-text-top"
                                                data-bs-boundary="viewport" data-bs-toggle="dropdown"
                                                aria-expanded="true">{{ __('Exports') }}</button>
                                            <div class="dropdown-menu dropdown-menu-end custom-drop"
                                                style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(0px, 37.7143px, 0px) !important;"
                                                data-popper-placement="bottom-end">
                                                {{-- Edit --}}
                                                <a href="{{ route('user.edit.ai.speech.to.text', $content->generate_id) }}"
                                                    class="dropdown-item">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon dropdown-item-icon icon-tabler-pencil" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
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
                                                <button class="dropdown-item" id="copyText"
                                                    data-clipboard-target="#copy" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" title="{{ __('Copy') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon dropdown-item-icon icon-tabler icon-tabler-clipboard"
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
                                                    {{ __('Copy')
                                                    }}</button>
                                                {{-- Print --}}
                                                <button type="button" class="dropdown-item"
                                                    onclick="javascript:window.print();">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon dropdown-item-icon" width="24" height="24"
                                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                                                <a href="{{ route('user.export.docs.speech.to.text', $content->generate_id) }}"
                                                    class="dropdown-item">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon dropdown-item-icon icon-tabler-notes" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
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
                        </div>
                        <div class="card-body" id="copy">
                            {{-- Result --}}
                            <p>{!! Str::markdown($content->content) !!}</p>
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
<script>
    new ClipboardJS('#copyText');
</script>
@endsection
@endsection