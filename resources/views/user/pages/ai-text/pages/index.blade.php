@extends('user.layouts.app')

{{-- Custom CSS --}}
@section('custom-css')
    <!-- Bootstrap core CSS -->
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>

    {{-- Record CSS --}}
    <link rel="stylesheet" href="{{ asset('css/record.min.css') }}">
    <style>
    .record-btn {
        bottom: 30px;
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
                            {{ __('Text to Speech') }}
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
                        <form action="javascript:void(0)" id="saveForm" method="POST" enctype="multipart/form-data"
                            class="card">
                            @csrf
                            <div class="card-body">
                                <div class="row row-cards">

                                    {{-- Name --}}
                                    <div class="col-sm-4 col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label required">{{ __('Audio Name') }}</label>
                                            <input type="text" class="form-control" name="name" id="name"
                                                placeholder="{{ __('Audio Name') }}"
                                                maxlength="{{ $config[30]->config_value == null ? '600' : $config[30]->config_value }}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6"></div>

                                    {{-- Voices --}}
                                    <div class="col-sm-4 col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label required">{{ __('Voices') }}</label>
                                            <select class="form-control form-select" name="voices" id="voices" required>
                                                <option value="alloy">{{ __('Alloy') }}</option>
                                                <option value="echo">{{ __('Echo') }}</option>
                                                <option value="fable">{{ __('Fable') }}</option>
                                                <option value="onyx">{{ __('Onyx') }}</option>
                                                <option value="nova">{{ __('Nova') }}</option>
                                                <option value="shimmer">{{ __('Shimmer') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Speed --}}
                                    <div class="col-sm-4 col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label required">{{ __('Speed') }}</label>
                                            <select class="form-control form-select" name="speed" id="speed" required>
                                                <option value="0.25">{{ __('0.25x') }}</option>
                                                <option value="0.50">{{ __('0.5x') }}</option>
                                                <option value="1.0" selected>{{ __('1.0x') }}</option>
                                                <option value="1.5">{{ __('1.5x') }}</option>
                                                <option value="2.0">{{ __('2x') }}</option>
                                                <option value="2.5">{{ __('2.5x') }}</option>
                                                <option value="3.0">{{ __('3x') }}</option>
                                                <option value="3.5">{{ __('3.5x') }}</option>
                                                <option value="4.0">{{ __('4x') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Audio format --}}
                                    <div class="col-sm-4 col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label required">{{ __('Audio format') }}</label>
                                            <select class="form-control form-select" name="audio_format" id="audio_format"
                                                required>
                                                <option value="mp3" selected>{{ __('mp3') }}</option>
                                                <option value="opus">{{ __('opus') }}</option>
                                                <option value="aac">{{ __('aac') }}</option>
                                                <option value="flac">{{ __('flac') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Text to generate audio --}}
                                    <div class="col-sm-12 col-md-12">
                                        <div class="mb-3 position-relative">
                                            <label class="form-label">{{ __('Text to generate audio') }}</label>
                                            <textarea class="form-control" name="content" id="content" rows="3" maxlength="4096"></textarea>
                                            <small
                                                class="form-hint">{{ __('The text to generate audio for. The maximum length is 4096 characters.') }}</small>

                                            {{-- Record Button (inside the field, at the bottom-right corner) --}}
                                            <a href="#" class="record-btn" onclick="toggleRecording('content')">
                                                <svg id="microphone-icon-content" xmlns="http://www.w3.org/2000/svg"
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
                                                <svg id="pause-icon-content" xmlns="http://www.w3.org/2000/svg"
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
                                <button type="submit" id="submit"
                                    class="btn btn-primary">{{ __('Generate') }}</button>
                            </div>
                        </form>
                    </div>

                    {{-- Result data --}}
                    <div class="col-xl-12 px-3 d-none" id="response">
                        <div class="row row-cards">
                            <div class="col-md-6 col-lg-6">
                                <div class="card">
                                    <div class="list-group card-list-group">
                                        <div id="result"></div>
                                    </div>
                                </div>
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

    {{-- Custom JS --}}
@section('custom-js')
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/tom-select.base.min.js') }}"></script>
    {{-- Record --}}
    <script src="{{ asset('js/record-ai.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            "use strict";
            var el;
            // Voices
            window.TomSelect && (new TomSelect(el = document.getElementById('voices'), {
                copyClassesToDropdown: false,
                dropdownClass: 'dropdown-menu ts-dropdown',
                optionClass: 'dropdown-item',
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

            // Speed
            window.TomSelect && (new TomSelect(el = document.getElementById('speed'), {
                copyClassesToDropdown: false,
                dropdownClass: 'dropdown-menu ts-dropdown',
                optionClass: 'dropdown-item',
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

            // Audio format
            window.TomSelect && (new TomSelect(el = document.getElementById('audio_format'), {
                copyClassesToDropdown: false,
                dropdownClass: 'dropdown-menu ts-dropdown',
                optionClass: 'dropdown-item',
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
                    $("#submit").attr("disabled", true);

                    $.ajax({
                        url: "{{ route('user.generate.ai.text.to.speech') }}",
                        type: "POST",
                        data: $('#saveForm').serialize(),
                        success: function(response) {
                            var html = "";
                            html += `
                        <div class="list-group-item">
                            <div class="row g-2 align-items-center">
                                <div class="col">
                                    ` + response[0] + `
                                    <div class="text-secondary">
                                        ` + response[2] + `
                                    </div>
                                </div>
                                <div class="col">
                                    <audio controls controlslist="noplaybackrate">
                                        <source src="` + window.location.origin + '/' + response[1] +
                                `" type="audio/` + response[2] + `">
                                    </audio>
                                </div>
                            </div>
                        </div>`;
                            $('#result').html(html)
                            $('#response').removeClass('d-none');
                            $('#submit').html(`{{ __('Generate') }}`);
                            $("#submit").attr("disabled", false);
                        }
                    });

                    // Play audio
                    $("#play").on("click", function() {
                        $('#audio').get(0).play();
                    });
                }
            })
        })(jQuery);
    </script>
@endsection
@endsection
