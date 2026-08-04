@extends('admin.layouts.app')

{{-- Custom CSS --}}
@section('custom-css')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.0.1/tinymce.min.js" integrity="sha512-KGtsnWohFUg0oksKq7p7eDgA1Rw2nBfqhGJn463/rGhtUY825dBqGexj8eP04LwfnsSW6dNAHAlOqKJKquHsnw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
                        {{ __('Add Page') }}
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-fluid">

            {{-- Error --}}
            <div class="alert alert-important alert-danger alert-dismissible d-none" role="alert" id="fillError">
                <div class="d-flex">
                    <div>
                        {{ __('Fill the all the required fields') }}
                    </div>
                </div>
                <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
            </div>

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
                {{-- Save page --}}
                <div class="col-sm-12 col-lg-12">
                    <form action="{{ route('admin.save.page') }}" method="post" enctype="multipart/form-data"
                        class="card" id="customPageForm">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="row">

                                        {{-- Name --}}
                                        <input type="hidden" class="form-control" name="name" value="Custom Page"
                                            value="{{ old('name') }}" placeholder="{{ __('Name') }}" required />

                                        {{-- Title --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <div class="form-label required">{{ __('Title') }}</div>
                                                <input type="text" class="form-control text-capitalize" name="title"
                                                    id="title" value="{{ old('title') }}"
                                                    placeholder="{{ __('Title') }}" required />
                                            </div>
                                        </div>

                                        {{-- Slug --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <div class="form-label required">{{ __('Slug') }}</div>
                                                <input type="text" class="form-control" name="slug" id="slug"
                                                    value="{{ old('slug') }}" placeholder="{{ __('Slug') }}" required />
                                            </div>
                                        </div>

                                        {{-- Body --}}
                                        <div class="col-md-12 col-xl-12">
                                            <div class="mb-3">
                                                <div class="form-label required">{{ __('Body') }}</div>
                                                <textarea name="body" id="body" cols="30" rows="5" class="form-control"
                                                    placeholder="{{ __('Body') }}">{{ old('body') }}</textarea>
                                            </div>
                                        </div>

                                        <h2 class="page-title mt-3 mb-3">
                                            {{ __('SEO Configurations') }}
                                        </h2>

                                        {{-- Title --}}
                                        <div class="col-md-12 col-xl-12">
                                            <div class="mb-3">
                                                <div class="form-label required">{{ __('Title') }}</div>
                                                <textarea name="page_title" id="page_title" cols="30" rows="1"
                                                    class="form-control text-capitalize" placeholder="{{ __('Title') }}"
                                                    required>{{ old('page_title') }}</textarea>
                                            </div>
                                        </div>

                                        {{-- Description --}}
                                        <div class="col-md-12 col-xl-12">
                                            <div class="mb-3">
                                                <div class="form-label required">{{ __('Description') }}</div>
                                                <textarea name="description" id="description" cols="30" rows="5"
                                                    class="form-control text-capitalize"
                                                    placeholder="{{ __('Description') }}"
                                                    required>{{ old('description') }}</textarea>
                                            </div>
                                        </div>

                                        {{-- Keywords --}}
                                        <div class="col-md-12 col-xl-12">
                                            <div class="mb-3">
                                                <div class="form-label required">{{ __('Keywords') }}</div>
                                                <input type="text" class="form-control text-lowercase" name="keywords"
                                                    value="{{ old('keywords') }}" placeholder="{{ __('Keywords') }}"
                                                    required />
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
    // Convert slug
    $('#title').on("change keyup paste click", function() {
        "use strict";
        var Text = $(this).val();
        Text = Text.toLowerCase();
        Text = Text.replace(/[^a-zA-Z0-9]+/g, '-');
        $('#slug').val(Text);
    });

    tinymce.init({
      selector: 'textarea#body',
      plugins: 'code preview importcss searchreplace autolink autosave save directionality visualblocks visualchars link charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount charmap quickbars emoticons',
      menubar: 'file edit view insert format tools',
      toolbar: 'code undo redo | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | preview save print | insertfile link anchor | ltr rtl',
      content_style: 'body { font-family:Times New Roman,Arial,sans-serif; font-size:16px }',
      height: 600,
      menubar: false,
      statusbar: false,
    });

    $('#customPageForm').on('submit', function(e) {
    "use strict";
    if($('#body').summernote('isEmpty')) {
        $('#fillError').attr("class", "alert alert-important alert-danger alert-dismissible");
        e.preventDefault();
    }
    })
</script>
@endsection
@endsection