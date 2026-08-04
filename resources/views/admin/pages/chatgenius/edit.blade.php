@extends('admin.layouts.app')

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
                            {{ __('Update Chat Assistant') }}
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

                <div class="row row-cards">
                    {{-- Save Chat Assistant --}}
                    <div class="col-sm-12 col-lg-12">
                        <form action="{{ route('admin.update.chatgenius') }}" method="post" enctype="multipart/form-data"
                            class="card">
                            @csrf
                            <div class="card-header">
                                <h4 class="page-title">{{ __('Chat Assistant Details') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="row">

                                            {{-- Chat Assistant ID --}}
                                            <input type="hidden" name="chat_genius_id"
                                                value="{{ $chatgenius->chat_genius_id }}" />

                                            {{-- Chat Assistant Image --}}
                                            <div class="col-md-6 col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('Thumbnail') }}</label>
                                                    <input type="file" class="form-control" name="chat_genius_image"
                                                        id="chat_genius_image" accept=".jpeg,.jpg,.png,.webp" />
                                                </div>
                                            </div>

                                            {{-- Chat Assistant Name --}}
                                            <div class="col-md-6 col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Name') }}</label>
                                                    <input type="text" class="form-control text-capitalize"
                                                        value="{{ old('chat_genius_name', $chatgenius->chat_genius_name) }}"
                                                        name="chat_genius_name" id="chat_genius_name" maxlength="200"
                                                        placeholder="{{ __('Ex: Fitness Guru') }}" required>
                                                </div>
                                            </div>

                                            {{-- Chat Assistant Expert --}}
                                            <div class="col-md-6 col-xl-4">
                                                <div class="mb-3">
                                                    <label
                                                        class="form-label required">{{ __('Expert') }}</label>
                                                    <input type="text" class="form-control text-capitalize"
                                                        value="{{ old('chat_genius_expert', $chatgenius->chat_genius_expert) }}"
                                                        name="chat_genius_expert" id="chat_genius_expert" maxlength="200"
                                                        placeholder="{{ __('Ex: Personal Trainer') }}" required>
                                                </div>
                                            </div>

                                            {{-- Chat Assistant Description --}}
                                            <div class="col-md-12 col-xl-6">
                                                <div class="mb-3">
                                                    <label
                                                        class="form-label required">{{ __('Description') }}</label>
                                                    <textarea class="form-control" name="chat_genius_description" id="chat_genius_description" cols="30" rows="5"
                                                        placeholder="{{ __('Ex: I am a personal trainer and I can help you achieve your fitness goals.') }}" required>{{ old('chat_genius_description', $chatgenius->chat_genius_description) }}</textarea>
                                                </div>
                                            </div>

                                            {{-- Chat Assistant System Prompt --}}
                                            <div class="col-md-12 col-xl-6">
                                                <div class="mb-3">
                                                    <label
                                                        class="form-label required">{{ __('System Prompt') }}</label>
                                                    <textarea class="form-control" name="chat_genius_message" id="chat_genius_message" cols="30" rows="5"
                                                        placeholder="{{ __('Ex: Hi, I\'m John and I\'m a personal trainer. How can I help you achieve your fitness goals?') }}"
                                                        required>{{ old('chat_genius_message', $chatgenius->chat_genius_message) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <div class="d-flex">
                                    <a href="{{ route('admin.chatgenius') }}"
                                        class="btn btn-outline-primary btn-md">{{ __('Cancel') }}</a>
                                    <button type="submit" class="btn btn-primary btn-md ms-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                            <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                            <path d="M14 4l0 4l-6 0l0 -4" />
                                        </svg>
                                        {{ __('Update') }}
                                    </button>
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
@endsection
