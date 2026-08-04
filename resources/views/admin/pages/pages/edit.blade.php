@extends('admin.layouts.app')

{{-- Custom CSS --}}
@section('custom-css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.0.1/tinymce.min.js"
        integrity="sha512-KGtsnWohFUg0oksKq7p7eDgA1Rw2nBfqhGJn463/rGhtUY825dBqGexj8eP04LwfnsSW6dNAHAlOqKJKquHsnw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
                        <h2 class="mb-3 page-title">
                            {{ __('Edit Page') }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="container-fluid">
                <div class="row row-deck row-cards">
                    <div class="col-sm-12 col-lg-12">
                        <form action="{{ route('admin.update.page', Request::segment(3)) }}" method="post"
                            enctype="multipart/form-data" class="card">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-12">
                                        @for ($i = 0; $i < 1; $i++)
                                            <div id="section{{ $i }}" class="row">
                                                <div class="col-md-12 col-xl-12">
                                                    <div class="mb-3">
                                                        <label class="form-label required">{{ __('Content') }}</label>
                                                        <textarea rows="6" cols="10" class="form-control body" name="section{{ $i }}"
                                                            placeholder="{{ $sections[$i]->title }}">{!! $sections[$i]->body !!}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor

                                    </div>


                                    <h2 class="mt-5 mb-3 page-title">
                                        {{ __('SEO Configuration') }}
                                    </h2>

                                    {{-- Title --}}
                                    <div class="col-md-4 col-xl-6">
                                        <div class="mb-3">
                                            <label class="form-label required">{{ __('Title') }}</label>
                                            <textarea class="form-control" name="page_title" rows="3" placeholder="{{ __('Title') }}" required>{{ $sections[0]->page_title }}</textarea>
                                        </div>
                                    </div>

                                    {{-- Description --}}
                                    <div class="col-md-4 col-xl-6">
                                        <div class="mb-3">
                                            <label class="form-label required">{{ __('Description') }}</label>
                                            <textarea class="form-control" name="description" rows="3" placeholder="{{ __('Description') }}" required>{{ $sections[0]->description }}</textarea>
                                        </div>
                                    </div>

                                    {{-- Keywords --}}
                                    <div class="col-md-4 col-xl-12">
                                        <div class="mb-3">
                                            <label class="form-label required">{{ __('Keywords') }}</label>
                                            <textarea class="form-control required" name="keywords" rows="3"
                                                placeholder="{{ __('Keywords (Keyword 1, Keyword 2)') }}" required>{{ $sections[0]->keywords }}</textarea>
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <div class="d-flex">
                                            <button type="submit" class="btn btn-primary btn-md ms-auto">
                                                {{ __('Save') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.includes.footer')
    </div>

    {{-- Website CSS --}}
    @php
        $frontEndPrimaryCSS = asset('css/tailwind.min.css');
        $frontEndSecondaryCSS = '';
        if ($config[48]->config_value == '330599619570398') {
            $frontEndPrimaryCSS = asset('themes/modern/css/tailwind/tailwind.min.css');
            $frontEndSecondaryCSS = asset('themes/modern/css/main.css');
        }
        if ($config[48]->config_value == '317109101703740') {
            $frontEndPrimaryCSS = asset('themes/modern-orange/css/tailwind/tailwind.min.css');
            $frontEndSecondaryCSS = asset('themes/modern-orange/css/main.css');
        }
    @endphp

    {{-- Custom JS --}}
@section('custom-js')
    <script>
        tinymce.init({
            content_css: [`{{ $frontEndPrimaryCSS }}`, `{{ $frontEndSecondaryCSS }}`],
            selector: 'textarea.body',
            plugins: 'code importcss searchreplace autolink autosave save directionality visualblocks visualchars link charmap pagebreak nonbreaking anchor advlist lists wordcount quickbars emoticons image',
            height: 600,
            menubar: false,
            statusbar: false,
            extended_valid_elements: "*[*]",
            setup: function(editor) {
                editor.on('init', function() {
                    // Get the document of the TinyMCE iframe
                    var doc = editor.iframeElement.contentWindow;

                    // Set the data-bs-theme attribute on the body element
                    var htmlElement = doc.document.querySelector("body");
                    htmlElement.setAttribute("class",
                        "mce-content-body antialiased bg-body text-body font-body zoom");
                });
            },
            toolbar: 'code undo redo | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | image outdent indent | numlist bullist | forecolor backcolor removeformat | pagebreak | insertfile link anchor',
            /* enable title field in the Image dialog*/
            image_title: true,
            /* enable automatic uploads of images represented by blob or data URIs*/
            automatic_uploads: true,
            /*
                URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
                images_upload_url: 'postAcceptor.php',
                here we add custom filepicker only to Image dialog
            */
            file_picker_types: 'image',
            /* and here's our custom image picker*/
            file_picker_callback: function(cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');

                /*
                Note: In modern browsers input[type="file"] is functional without
                even adding it to the DOM, but that might not be the case in some older
                or quirky browsers like IE, so you might want to add it to the DOM
                just in case, and visually hide it. And do not forget do remove it
                once you do not need it anymore.
                */

                input.onchange = function() {
                    var file = this.files[0];

                    var reader = new FileReader();
                    reader.onload = function() {
                        /*
                        Note: Now we need to register the blob in TinyMCEs image blob
                        registry. In the next release this part hopefully won't be
                        necessary, as we are looking to handle it internally.
                        */
                        var id = 'blobid' + (new Date()).getTime();
                        var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];
                        var blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);

                        /* call the callback and populate the Title field with the file name */
                        cb(blobInfo.blobUri(), {
                            title: file.name
                        });
                    };
                    reader.readAsDataURL(file);
                };

                input.click();
            },
            content_style: 'body { font-family:Times New Roman,Arial,sans-serif; font-size:16px }',
        });

        // Page redirect
        jQuery(document).ready(function($) {
            "use strict";
            if (window.history && window.history.pushState) {
                $(window).on('popstate', function() {
                    var hashLocation = location.hash;
                    var hashSplit = hashLocation.split("#!/");
                    var hashName = hashSplit[1];

                    if (hashName !== '') {
                        var hash = window.location.hash;
                        if (hash === '') {
                            alert('Back button was pressed.');
                            window.location = `{{ route('admin.pages') }}`;
                            return false;
                        }
                    }
                });
                window.history.pushState('forward', null, window.location.origin + '/admin/pages');
            }
        });
    </script>
@endsection
@endsection
