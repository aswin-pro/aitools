@extends('user.layouts.app')

{{-- Custom CSS --}}
@section('custom-css')
<!-- Bootstrap core CSS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.0.1/tinymce.min.js" integrity="sha512-KGtsnWohFUg0oksKq7p7eDgA1Rw2nBfqhGJn463/rGhtUY825dBqGexj8eP04LwfnsSW6dNAHAlOqKJKquHsnw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
                        {{ __('Edit Content') }}
                    </h2>
                    <span class="text-muted">{{ __("Note: If you want to change the content format, first change the
                        content and save. then hit 'EXPORT' button.") }}</span>
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

            {{-- Update --}}
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
                                            {{-- Export Docs --}}
                                            <a href="{{ route('user.export.docs.content', $content->generate_id) }}"
                                                class="btn btn-primary">
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
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('user.update.ai.content') }}" id="saveForm" method="POST">
                                @csrf
                                <div class="card-body custom-body">
                                    {{-- Edit Content --}}
                                    <input type="hidden" name="generateId" value="{{ $content->generate_id }}"
                                        id="generateId">
                                    <textarea class="form-control" name="result"
                                        id="result">{!! Str::markdown($content->content) !!}</textarea>
                                </div>
                                <div class="card-footer text-end">
                                    <div class="d-flex">
                                        <a href="{{ route('user.all.ai.content') }}" class="btn btn-link">{{
                                            __('Cancel')
                                            }}</a>
                                        <a href="{{ route('user.view.ai.content', $content->generate_id) }}"
                                            class="btn btn-dark">{{
                                            __('View')
                                            }}</a>
                                        <button type="submit" id="submit" class="btn btn-primary ms-auto">{{
                                            __('Update')}}</button>
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

{{-- Custom JS --}}
@section('custom-js')
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
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
</script>
@endsection
@endsection