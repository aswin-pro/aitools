@extends('user.layouts.app')

@section('custom-css')
    <style>
        .border {
            border: 2px dotted #bbbcbe !important;
        }

        .addNew {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 130px;
        }

        .fixed-title {
            width: 100%;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .ai-image-wrapper {
            height: 280px;
            overflow: hidden;
            background: var(--tblr-bg-surface-secondary);
            border-radius: .5rem .5rem 0 0;
        }

        .ai-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
    </style>
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="container-fluid">
            {{-- Page Title --}}
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="page-pretitle">
                            {{ __('Overview') }}
                        </div>
                        <h2 class="page-title">
                            {{ __('Recently Generated Images') }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-fluid">

                {{-- Failed Message --}}
                @if (Session::has('failed'))
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        {{ Session::get('failed') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Success Message --}}
                @if (Session::has('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        {{ Session::get('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row row-cards">
                    {{-- New Image --}}
                    <div class="col-6 col-md-3 col-lg-3 col-xl-2">
                        <div class="card border border-muted rounded addNew">
                            <a href="{{ route('user.new.ai.image') }}" class="text-muted text-decoration-none w-100">
                                <div class="card-body text-center p-5" data-bs-toggle="tooltip"
                                    title="{{ __('New Image') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                        <path d="M9 12l6 0" />
                                        <path d="M12 9l0 6" />
                                    </svg>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Generated Images --}}
                    @foreach ($images as $image)
                        <div class="col-6 col-md-3 col-lg-3 col-xl-2">
                            <div class="card h-100">

                                {{-- Base64 Images --}}
                                @if ($image->format == 'b64_json')
                                    @php
                                        $imageUrl = 'data:image/png;base64,' . json_decode($image->result)[0]->b64_json;
                                    @endphp

                                    <a href="{{ $imageUrl }}" data-fslightbox="gallery">
                                        <div class="ai-image-wrapper">
                                            <img src="{{ $imageUrl }}" alt="{{ $image->name }}" class="card-img-top">
                                        </div>
                                    </a>
                                @endif

                                {{-- URL Images --}}
                                @if ($image->format == 'url')
                                    @php
                                        $imageUrl = json_decode($image->result)[0];
                                    @endphp

                                    <a href="{{ asset($imageUrl) }}" data-fslightbox="gallery">
                                        <div class="ai-image-wrapper">
                                            <img src="{{ asset($imageUrl) }}" alt="{{ $image->name }}"
                                                class="card-img-top">
                                        </div>
                                    </a>
                                @endif

                                <div class="card-body">
                                    <span class="badge bg-red text-white text-capitalize">
                                        {{ str_replace('-', ' ', $image->type) }}
                                    </span>
                                    <h4 class="card-title fixed-title mt-2" data-bs-toggle="tooltip"
                                        title="{{ $image->name }}">
                                        {{ strtoupper($image->name) }}
                                    </h4>
                                    <div class="text-secondary small">
                                        {{ __('Image Count') }}:
                                        <strong>{{ $image->n }}</strong>
                                        <br>
                                        {{ __('Size') }}:
                                        <strong>{{ $image->size }}</strong>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <small class="text-muted d-block">
                                                {{ __('Generated at') }}
                                            </small>
                                            <span class="text-primary">
                                                {{ $image->updated_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <a href="{{ route('user.view.ai.image', $image->generate_id) }}"
                                            class="btn btn-primary btn-icon ms-auto" data-bs-toggle="tooltip"
                                            title="{{ __('View') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M10 12a2 2 0 1 0 4 0" />
                                                <path d="M21 12c-2.4 4-5.4 6-9 6s-6.6-2-9-6c2.4-4 5.4-6 9-6s6.6 2 9 6" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="col-12 mt-4">
                        {{ $images->links() }}
                    </div>
                </div>
            </div>
        </div>
        @include('user.includes.footer')
    </div>

    {{-- Delete Modal --}}
    <div class="modal modal-blur fade" id="delete-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-title">
                        {{ __('Are you sure?') }}
                    </div>
                    <div id="deleteStatus"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-auto" data-bs-dismiss="modal">
                        {{ __('Cancel') }}
                    </button>
                    <a class="btn btn-danger" id="deleteImageId">
                        {{ __('Yes, proceed') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script type="text/javascript" src="{{ asset('js/lightgallery.min.js') }}"></script>
    <script>
        function deleteImage(deleteImageId, deleteImageStatus) {
            "use strict";
            $('#delete-modal').modal('show');
            document.getElementById('deleteStatus').innerHTML = 'If you proceed, you will ' + deleteImageStatus +
                ' this image.';
            document.getElementById('deleteImageId').href = '{{ route('user.delete.ai.image') }}?id=' + deleteImageId;
        }
    </script>
@endsection
