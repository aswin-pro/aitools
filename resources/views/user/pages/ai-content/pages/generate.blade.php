@extends('user.layouts.app')

{{-- Custom CSS --}}
@section('custom-css')
    {{-- Record CSS --}}
    <link rel="stylesheet" href="{{ asset('css/record.min.css') }}">
    <style>
        .record-btn {
            bottom: 4px;
        }
    </style>
    <!-- Bootstrap core CSS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.0.1/tinymce.min.js"
        integrity="sha512-KGtsnWohFUg0oksKq7p7eDgA1Rw2nBfqhGJn463/rGhtUY825dBqGexj8eP04LwfnsSW6dNAHAlOqKJKquHsnw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <style>
        
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
                            {{ __($template_details[0]->name) }}
                        </h2>
                        <p class="text-muted">{{ __($template_details[0]->description) }}</p>
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
                                    <input type="hidden" class="form-control" name="type" id="type"
                                        value="{{ $template_details[0]->unique_slug }}">
                                    {{-- Tone --}}
                                    <div
                                        class="{{ count($template_details) == 2 ? 'col-sm-6 col-md-6' : 'col-sm-6 col-md-4' }}">
                                        <div class="mb-3">
                                            <label class="form-label required">{{ __('Tone') }}</label>
                                            <select class="form-control form-select" name="tone" id="tone" required>
                                                <option value="Professional">{{ __('Professional') }}</option>
                                                <option value="Informal" selected>{{ __('Informal') }}</option>
                                                <option value="Optimistic">{{ __('Optimistic') }}</option>
                                                <option value="Pessimistic">{{ __('Pessimistic') }}</option>
                                                <option value="Joyful">{{ __('Joyful') }}</option>
                                                <option value="Sad">{{ __('Sad') }}</option>
                                                <option value="Sincere">{{ __('Sincere') }}</option>
                                                <option value="Hypocritical">{{ __('Hypocritical') }}</option>
                                                <option value="Fearful">{{ __('Fearful') }}</option>
                                                <option value="Hopeful">{{ __('Hopeful') }}</option>
                                                <option value="Happy">{{ __('Happy') }}</option>
                                                <option value="Serious">{{ __('Serious') }}</option>
                                                <option value="Funny">{{ __('Funny') }}</option>
                                                <option value="Bold">{{ __('Bold') }}</option>
                                                <option value="Casual">{{ __('Casual') }}</option>
                                                <option value="Excited">{{ __('Excited') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Language --}}
                                    <div
                                        class="{{ count($template_details) == 2 ? 'col-sm-6 col-md-6' : 'col-sm-6 col-md-4' }}">
                                        <div class="mb-3">
                                            <label class="form-label required">{{ __('Language') }}</label>
                                            <select class="tomselected form-select " name="lang" id="langs" required>
                                                @include('user.pages.ai-content.includes.lang')
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Fields --}}
                                    @foreach ($template_details as $index => $field)
                                        <div
                                            class="{{ count($template_details) == 2 ? 'col-sm-6 col-md-6' : 'col-sm-6 col-md-4' }}">
                                            <div class="mb-3 position-relative">
                                                <!-- Added position-relative to the container -->
                                                <label class="form-label required">{{ __($field->field_name) }}</label>

                                                @if ($field->field_type == 'text')
                                                    <input type="text" class="form-control"
                                                        name="{{ str_replace('##', '', $field->ai_input) }}"
                                                        id="{{ str_replace('##', '', $field->ai_input) }}"
                                                        placeholder="{{ __($field->field_description) }}"
                                                        maxlength="{{ $config[30]->config_value == null ? '600' : $config[30]->config_value }}"
                                                        required>
                                                @endif

                                                @if ($field->field_type == 'textarea')
                                                    <textarea class="form-control" name="{{ str_replace('##', '', $field->ai_input) }}"
                                                        id="{{ str_replace('##', '', $field->ai_input) }}" rows="3"
                                                        maxlength="{{ $config[30]->config_value == null ? '600' : $config[30]->config_value }}"
                                                        placeholder="{{ __($field->field_description) }}" required></textarea>
                                                @endif

                                                @if ($field->field_type == 'url')
                                                    <input type="url" class="form-control"
                                                        name="{{ str_replace('##', '', $field->ai_input) }}"
                                                        id="{{ str_replace('##', '', $field->ai_input) }}"
                                                        placeholder="{{ __($field->field_description) }}" required>
                                                @endif

                                                {{-- Record Button (inside the field, at the bottom-right corner) --}}
                                                <a href="#" class="record-btn"
                                                    onclick="toggleRecording('{{ str_replace('##', '', $field->ai_input) }}')">
                                                    <svg id="microphone-icon-{{ str_replace('##', '', $field->ai_input) }}"
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
                                                    <svg id="pause-icon-{{ str_replace('##', '', $field->ai_input) }}"
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
                                    @endforeach

                                    {{-- Randomness and Creativity --}}
                                    <div class="col-sm-6 col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label required">{{ __('Creativity Level') }}</label>
                                            <select class="form-control form-select" name="level" id="level"
                                                required>
                                                <option value="0.1">{{ __('Repetitive') }}</option>
                                                <option value="0.5">{{ __('Deterministic') }}
                                                </option>
                                                <option value="1.0" selected>{{ __('Original') }}</option>
                                                <option value="1.5">{{ __('Creative') }}</option>
                                                <option value="2.0">{{ __('Imaginative') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    {{-- Max Word Length --}}
                                    <div class="col-sm-6 col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label required">{{ __('Max Word Length') }}</label>
                                            <input type="number" class="form-control" name="max_length" id="max_length"
                                                placeholder="{{ __('Eg: 200') }}"
                                                value="{{ $config[30]->config_value == null ? '600' : $config[30]->config_value }}"
                                                maxlength="{{ $config[30]->config_value == null ? '600' : $config[30]->config_value }}"
                                                required>
                                        </div>
                                    </div>
                                    {{-- No. Of Results --}}
                                    <div class="col-sm-6 col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label required">{{ __('No. Of Results') }}</label>
                                            <select class="form-control form-select" name="results" id="results"
                                                required>
                                                <option value="1">{{ __('1') }}</option>
                                                <option value="2">{{ __('2') }}</option>
                                                <option value="3">{{ __('3') }}</option>
                                            </select>
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
                            <form action="{{ route('user.update.ai.content') }}" id="saveForm" method="POST"
                                class="card">
                                @csrf
                                <div class="p-3">
                                    <input type="hidden" name="generateId" id="generateId">
                                    <textarea class="form-control" name="result" id="result"></textarea>

                                    <div class="card-footer text-end">
                                        <button type="submit" id="submit"
                                            class="btn btn-primary">{{ __('Update') }}</button>
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
            window.TomSelect && (new TomSelect(el = document.getElementById('langs'), {
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
        document.addEventListener("DOMContentLoaded", function() {
            "use strict";
            var el;
            window.TomSelect && (new TomSelect(el = document.getElementById('tone'), {
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
        document.addEventListener("DOMContentLoaded", function() {
            "use strict";
            var el;
            window.TomSelect && (new TomSelect(el = document.getElementById('level'), {
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
    </script>
    <script>
        tinymce.init({
            selector: 'textarea#result',
            plugins: 'preview importcss searchreplace autolink autosave save directionality visualblocks visualchars link table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
            menubar: 'file edit view insert format tools table help',
            toolbar: 'wordcount | undo redo | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | preview save print | insertfile link anchor | ltr rtl',
            toolbar_sticky: true,
            height: 300,
            menubar: false,
            statusbar: false,
            autosave_interval: '30s',
            autosave_prefix: '{path}{query}-{id}-',
            autosave_restore_when_empty: false,
            autosave_retention: '2m',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
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
                        url: "{{ route('user.generate.ai.content') }}",
                        type: "POST",
                        data: $('#saveForm').serialize(),
                        success: function(response) {
                            // Check result
                            if (response[0] != null) {
                                // Remove attribute
                                $('#response').removeClass('d-none');
                                $('#submit').html(`{{ __('Generate') }}`);
                                $("#submit").attr("disabled", false);

                                // Get value
                                var myContent = tinymce.activeEditor.getContent();
                                // Set value
                                var textarea = myContent + '<br>' + response[0];

                                textarea = textarea.replace(/\n/g, '<br/>');

                                $("#generateId").val(response[1]);
                                tinymce.activeEditor.setContent(textarea);
                            } else {
                                Swal.fire(
                                    `{{ __('Content Creation Failed') }}`,
                                    ``,
                                    'error'
                                );
                                // Remove attribute
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
