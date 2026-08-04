@extends('user.layouts.app')

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
                    <h2 class="page-title text-uppercase">
                        {{ __('Name') }} : {{ $images->name }}
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body mt-4">
        <div class="container-fluid">
            <div class="row row-deck row-cards">
                {{-- Result data --}}
                <div class="col-xl-12 px-2">
                    <div class="row row-cards">
                        {{-- Photo --}}
                        <div class="row">
                            @foreach (json_decode($images->result) as $image)
                            {{-- Check json --}}
                            <div class="col-lg-3 mb-3">
                                @if ($images->format == "b64_json")
                                <a data-fslightbox="gallery" href="data:image/png;base64,{{ $image->b64_json }}">
                                    <div class="img-responsive img-responsive-1x1 rounded-3 border mb-3"
                                        style="background-image: url(data:image/png;base64,{{ $image->b64_json }})">
                                    </div>
                                </a>
                                @endif
                                @if ($images->format == "url")
                                <a data-fslightbox="gallery" href="{{ url($image) }}">
                                    <div class="img-responsive img-responsive-1x1 rounded-3 border mb-3"
                                        style="background-image: url({{ url($image) }})">
                                    </div>
                                </a>
                                @endif
                                {{-- Download --}}
                                <a href="{{ $images->format == "url" ? url($image) : "data:image/png;base64,$image->b64_json" }}" class="btn btn-dark"
                                    download="{{ strtolower($images->name) }}.png"><svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-photo-down" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M15 8h.01"></path>
                                        <path d="M12 20h-5a3 3 0 0 1 -3 -3v-10a3 3 0 0 1 3 -3h10a3 3 0 0 1 3 3v5">
                                        </path>
                                        <path d="M4 15l4 -4c.928 -.893 2.072 -.893 3 0l4 4"></path>
                                        <path d="M14 14l1 -1c.617 -.593 1.328 -.793 2.009 -.598"></path>
                                        <path d="M19 16v6"></path>
                                        <path d="M22 19l-3 3l-3 -3"></path>
                                    </svg>{{ __("Download") }}
                                </a>
                            </div>
                            @endforeach
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
<script type="text/javascript" src="{{ asset('js/lightgallery.min.js') }}"></script>
@endsection

@endsection