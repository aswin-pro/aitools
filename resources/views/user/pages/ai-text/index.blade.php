@extends('user.layouts.app')

{{-- Custom CSS --}}
@section('custom-css')
<style>
    .border {
        border: 2px dotted #bbbcbe !important;
    }

    .addNew {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .fixed-title {
        width: 100%;
        overflow: hidden;
        text-wrap: nowrap;
        text-overflow: ellipsis;
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
                        {{ __('Recently Generated Text to Speech') }}
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-fluid d-flex flex-column justify-content-center">

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

            <div class="row row-cards row-deck mb-3">
                {{-- New Text to Speech --}}
                <div class="col-sm-6 col-md-3">
                    <div class="card border border-muted rounded addNew">
                        <a href="{{ route('user.new.ai.text.to.speech') }}" class="text-muted">
                            <div class="card-body text-center p-5" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                title="{{ __('New Text to Speech') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon-tabler-circle-plus" width="48"
                                    height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                                    <path d="M9 12l6 0"></path>
                                    <path d="M12 9l0 6"></path>
                                </svg>
                            </div>
                        </a>
                    </div>
                </div>
                {{-- Generated Text to Speech --}}
                @foreach ($textToSpeeches as $textToSpeech)
                <div class="col-sm-6 col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-red text-white text-whitetext-capitalize">{{ str_replace('-', ' ', $textToSpeech->type) }}</span>
                            </div>
                            <h4 class="card-title text-uppercase my-1 fixed-title" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $textToSpeech->name }}">{{ $textToSpeech->name }}</h4>
                            <div class="text-muted">
                                {{ __('Allocated Words') }} : {{ $textToSpeech->word_count }}
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex">
                                {{-- Last Updated --}}
                                <strong>{{ __('Generated at') }}: <br> <span class="text-primary">{{ $textToSpeech->updated_at->diffForHumans() }}</span></strong>
                                {{-- Download --}}
                                <a class="btn btn-primary btn-icon ms-auto" data-bs-toggle="tooltip" data-bs-placement="right" title="{{ __('Download') }}" href="{{ asset($textToSpeech->content) }}" download>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-download" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>
                                </a>
                                {{-- Delete --}}
                                <a class="btn btn-primary btn-icon mx-2" data-bs-toggle="tooltip" data-bs-placement="right" title="{{ __('Delete') }}" onclick="deleteAudio('{{ $textToSpeech->generate_id }}', 'delete'); return false;">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="col-lg-12 mt-3">
                    {{-- Pagination --}}
                    {{ $textToSpeeches->links() }}
                </div>
            </div>
        </div>
    </div>
    
    {{-- Footer --}}
    @include('user.includes.footer')
</div>

{{-- Delete content Modal --}}
<div class="modal modal-blur fade" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-title">{{ __('Are you sure?')}}</div>
                <div>{{ __('If you proceed, you will delete this audio.')}}</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary me-auto" data-bs-dismiss="modal">{{
                    __('Cancel')}}</button>
                <a class="btn btn-danger" id="deleteId">{{ __('Yes, proceed')}}</a>
            </div>
        </div>
    </div>
</div>

{{-- Custom JS --}}
@section('custom-js')
    <script>
    function deleteAudio(deleteId, deleteStatus) {
        "use strict";
            $("#delete-modal").modal("show");
            var delete_link = document.getElementById("deleteId");
            delete_link.getAttribute("href");
            delete_link.setAttribute("href", "{{ route('user.delete.ai.text.to.speech') }}?id=" + deleteId);
    }
</script>
@endsection
@endsection