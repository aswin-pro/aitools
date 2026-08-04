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
                        <h2 class="page-title">
                            {{ __('Chat Assistants') }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="container-fluid my-auto">

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
                    {{-- Chat Genius --}}
                    @if (count($chatgenius) == 0)
                        <div class="col-md-12 col-xl-12">
                            <div class="empty">
                                <div class="empty-img">
                                    <img src="{{ asset('images/chatgenius/chat_bot.svg') }}" alt="chat genius">
                                </div>
                                <p class="empty-title">{{ __('No chat assistants found') }}</p>
                            </div>
                        </div>
                    @else
                        @foreach ($chatgenius as $chatgenius)
                            <div class="col-sm-4 col-md-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <span class="avatar avatar-2xl rounded-circle"
                                                style="background-image: url({{ asset($chatgenius->chat_genius_image) }})"></span>
                                        </div>
                                        <div class="card-title mb-1">{{ __($chatgenius->chat_genius_name) }}</div>
                                        <div class="text-secondary mb-3"><strong>{{ __($chatgenius->chat_genius_expert) }}</strong></div>
                                        <div class="text-secondary mb-3">{{ __($chatgenius->chat_genius_description) }}</div>
                                        <a href="{{ route('user.new.ai.chatgenius', $chatgenius->chat_genius_id) }}"
                                            class="btn btn-primary w-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-message-dots">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M12 11v.01" />
                                                <path d="M8 11v.01" />
                                                <path d="M16 11v.01" />
                                                <path
                                                    d="M18 4a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-5l-5 3v-3h-2a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3z" />
                                            </svg>
                                            {{ __('Chat') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        @include('user.includes.footer')
    </div>
@endsection
