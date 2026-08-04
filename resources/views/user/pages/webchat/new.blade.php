@extends('user.layouts.app')

{{-- Custom CSS --}}
@section('custom-css')
    <style>
    #message {
        border-radius: 0px !important;
        border: 1px solid #e7e7e7;
    }
    
    #scrollable {
        height: 22rem;
    }

    /* Styles for tablets and desktops */
    @media (min-height: 800px) { /* Adjust the min-width value as necessary for your design */
        #scrollable {
            height: 30rem; /* For tablets and desktops */
        }
    }
    </style>
    <script>
        window.onload = function() {
            var element = document.getElementById('scrollable');
            element.scrollTop = element.scrollHeight;
        };
    </script>
@endsection

{{-- Format message --}}
@php
if (!function_exists('wrapPhpWithPre')) {
    function wrapPhpWithPre($code)
    {
        // Ensure it's a string
    if (!is_string($code)) {
        return '<pre style="background: #E9F0F9; color: #000000; word-wrap: break-word; white-space: pre-wrap; padding: 10px 0 0 0; font-family: system-ui, sans-serif; font-size: 14px; line-height: 1.5; border-radius: 6px;">' . htmlspecialchars($code) . '</pre>'; // Return safely if not a string
    }

    // Check for PHP tags or code blocks
    if (strpos($code, '<?php') !== false || strpos($code, '```') !== false) {
        // Return wrapped with <pre> if found
        return '<pre style="background: #E9F0F9; color: #000000; word-wrap: break-word; white-space: pre-wrap; padding: 10px 0 0 0; font-family: system-ui, sans-serif; font-size: 14px; line-height: 1.5; border-radius: 6px;">' . htmlspecialchars($code) . '</pre>';
    }

    // Return as is if no PHP code or code blocks are present
    return '<pre style="background: #E9F0F9; color: #000000; word-wrap: break-word; white-space: pre-wrap; padding: 10px 0 0 0; font-family: system-ui, sans-serif; font-size: 14px; line-height: 1.5; border-radius: 6px;">' .
        htmlspecialchars($code) .
        '</pre>';
    }
}
@endphp

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
                            {{ __('Chat with ' . $webChatArray['chat_genius_name']) }}
                        </h2>
                    </div>
                    {{-- New Chat Genius --}}
                    <div class="col-auto ms-auto">
                        <a href="{{ route('user.new.ai.webchat', $webChatArray['chat_genius_id']) }}"
                            class="btn btn-icon btn-primary" data-bs-toggle="tooltip" data-bs-placement="left"
                            title="{{ __('Start new conversation') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24"
                                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            <span class="visually-hidden">{{ __('Start new conversation') }}</span>
                        </a>
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
                    @else
                        <div class="session-message"></div>
                    @endif

                    <div id="failed-alert" class="alert alert-important alert-danger alert-dismissible" role="alert" style="display:none;">
                        <div class="d-flex">
                            <div id="failed-message"></div>
                        </div>
                        <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>

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

                    <div id="success-alert" class="alert alert-important alert-success alert-dismissible" role="alert" style="display:none;">
                        <div class="d-flex">
                            <div id="success-message"></div>
                        </div>
                        <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>

                    {{-- Mobile chats button --}}
                    <a href="#" class="btn btn-primary d-block d-md-none mb-2" data-bs-toggle="modal"
                        data-bs-target="#mobileChatsModal">
                        {{ __('Chats') }}
                    </a>

                    {{-- Mobile chats modal --}}
                    @if (count($webChatArray['chats']) > 0)
                        <div class="modal modal-blur fade" id="mobileChatsModal" tabindex="-1" role="dialog"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <div class="card-body p-0 scrollable">
                                            <div class="nav flex-column nav-pills" id="pills-tab" role="tablist"
                                                aria-orientation="vertical">
                                                {{-- Conversations --}}
                                                @if (count($webChatArray['chats']) > 0)
                                                    @foreach ($webChatArray['chats'] as $chat)
                                                        <a href="#chat{{ $chat['chat_id'] }}"
                                                            onclick="setChatId('{{ $chat['chat_id'] }}'), setChatName('{{ $chat['chat_id'] }}')"
                                                            class="nav-link text-start mw-100 p-3 {{ $loop->first ? 'active' : '' }}"
                                                            id="chat{{ $chat['chat_id'] }}-tab" data-bs-toggle="pill"
                                                            role="tab" aria-controls="chat{{ $chat['chat_id'] }}"
                                                            aria-selected="false" tabindex="-1">
                                                            <div class="row align-items-center flex-fill">
                                                                <div class="col-auto">
                                                                    <span class="avatar rounded-circle"
                                                                        style="background-image: url({{ asset($webChatArray['chat_genius_image']) }})"></span>
                                                                </div>
                                                                <div class="col text-body">
                                                                    <div>{{ __($chat['chat_title']) }}</div>
                                                                    <div class="text-secondary text-truncate w-100">
                                                                        {{ __($webChatArray['chat_genius_expert']) }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>

                                                        {{-- Buttons (Edit, Delete, Share) --}}
                                                        @php
                                                            $top = 26 + 70 * ($loop->iteration - 1);
                                                        @endphp

                                                        <div class="d-flex justify-content-end position-absolute"
                                                            style="right: 4px; top: {{ $loop->iteration == 1 ? '26px' : $top . 'px' }}; z-index: 10;">
                                                            <div class="row">
                                                                <div class="col-auto">
                                                                    <a class="text-primary" href="#" onclick="editChat('{{ $chat['chat_title'] }}', '{{ $chat['chat_id'] }}')">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="24" height="24"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                                                            <path stroke="none" d="M0 0h24v24H0z"
                                                                                fill="none" />
                                                                            <path
                                                                                d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                                            <path
                                                                                d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                                            <path d="M16 5l3 3" />
                                                                        </svg>
                                                                    </a>
                                                                </div>
                                                                {{-- <div class="col-4">
                                                                    <a class="text-primary" href="#">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="24" height="24"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-share">
                                                                            <path stroke="none" d="M0 0h24v24H0z"
                                                                                fill="none" />
                                                                            <path
                                                                                d="M6 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                                                            <path
                                                                                d="M18 6m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                                                            <path
                                                                                d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                                                            <path d="M8.7 10.7l6.6 -3.4" />
                                                                            <path d="M8.7 13.3l6.6 3.4" />
                                                                        </svg>
                                                                    </a>
                                                                </div> --}}
                                                                <div class="col-auto">
                                                                    <a class="text-danger" href="#" onclick="deleteChat('{{ $chat['chat_id'] }}')">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="24" height="24"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                                                            <path stroke="none" d="M0 0h24v24H0z"
                                                                                fill="none" />
                                                                            <path d="M4 7l16 0" />
                                                                            <path d="M10 11l0 6" />
                                                                            <path d="M14 11l0 6" />
                                                                            <path
                                                                                d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                                            <path
                                                                                d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                                        </svg>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="card">
                        <div class="row g-0">
                            {{-- Desktop chats button --}}
                            <div class="col-12 col-lg-5 col-xl-3 border-end d-none d-md-block">
                                <div class="card-body p-0 scrollable">
                                    <div class="nav flex-column nav-pills" id="pills-tab" role="tablist"
                                        aria-orientation="vertical">
                                        {{-- Conversations --}}
                                        @if (count($webChatArray['chats']) > 0)
                                            @foreach ($webChatArray['chats'] as $chat)
                                                <a href="#chat{{ $chat['chat_id'] }}"
                                                    onclick="setChatId('{{ $chat['chat_id'] }}')"
                                                    class="nav-link text-start mw-100 p-3 {{ $loop->first ? 'active' : '' }}"
                                                    id="chat{{ $chat['chat_id'] }}-tab" data-bs-toggle="pill"
                                                    role="tab" aria-controls="chat{{ $chat['chat_id'] }}"
                                                    aria-selected="false" tabindex="-1">
                                                    <div class="row align-items-center flex-fill">
                                                        <div class="col-auto">
                                                            <span class="avatar rounded-circle"
                                                                style="background-image: url({{ asset($webChatArray['chat_genius_image']) }})"></span>
                                                        </div>
                                                        <div class="col text-body">
                                                            <div>{{ __($chat['chat_title']) }}</div>
                                                            <div class="text-secondary text-truncate w-100">
                                                                {{ __($webChatArray['chat_genius_expert']) }}</div>
                                                        </div>
                                                    </div>
                                                </a>

                                                {{-- Buttons (Edit, Delete, Share) --}}
                                                @php
                                                    $top = 25 + 70 * ($loop->iteration - 1);
                                                @endphp

                                                <div class="d-flex justify-content-end position-absolute"
                                                    style="right: -5px; top: {{ $loop->iteration == 1 ? '25px' : $top . 'px' }}; z-index: 10;">
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <a class="text-primary" href="#"
                                                                onclick="editChat('{{ $chat['chat_title'] }}', '{{ $chat['chat_id'] }}')">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path
                                                                        d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                                    <path
                                                                        d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                                    <path d="M16 5l3 3" />
                                                                </svg>
                                                            </a>
                                                        </div>
                                                        {{-- <div class="col-3">
                                                            <a class="text-primary" href="#"
                                                                onclick="shareChat('{{ $chat['chat_id'] }}')">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-share">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path d="M6 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                                                    <path d="M18 6m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                                                    <path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                                                    <path d="M8.7 10.7l6.6 -3.4" />
                                                                    <path d="M8.7 13.3l6.6 3.4" />
                                                                </svg>
                                                            </a>
                                                        </div> --}}
                                                        <div class="col-4">
                                                            <a class="text-danger" href="#"
                                                                onclick="deleteChat('{{ $chat['chat_id'] }}')">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path d="M4 7l16 0" />
                                                                    <path d="M10 11l0 6" />
                                                                    <path d="M14 11l0 6" />
                                                                    <path
                                                                        d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-7 col-xl-9 d-flex flex-column">
                                <div class="tab-content card-body scrollable" id="scrollable">
                                    {{-- Conversations --}}
                                    @if (count($webChatArray['chats']) > 0)
                                        @foreach ($webChatArray['chats'] as $chat)
                                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                                id="chat{{ $chat['chat_id'] }}" role="tabpanel"
                                                aria-labelledby="chat{{ $chat['chat_id'] }}-tab">
                                                <div class="chat-bubbles">
                                                    {{-- Conversations --}}
                                                    <div class="chat-item" id="chatConversation_{{ $chat['chat_id'] }}">
                                                        @foreach ($chat['chat_messages'] as $message)
                                                            <div
                                                                class="row align-items-end mb-2 {{ $message['responsed_by'] == 'user' ? 'justify-content-end' : 'justify-content-start' }}">
                                                                @if ($message['responsed_by'] != 'user')
                                                                    <div class="col-auto" style="margin-bottom: 10px">
                                                                        <span class="avatar rounded-circle"
                                                                            style="background-image: url('{{ asset($webChatArray['chat_genius_image']) }}')"></span>
                                                                    </div>
                                                                @endif
                                                                <div class="col col-lg-6">
                                                                    <div
                                                                        class="chat-bubble {{ Auth::user()->id == $chat['chat_user_id'] ? 'chat-bubble-me' : 'chat-bubble-them' }}">
                                                                        <div class="chat-bubble-title">
                                                                            <div class="row">
                                                                                <div class="col chat-bubble-author">
                                                                                    @if ($message['responsed_by'] == 'user')
                                                                                        {{ Auth::user()->name }}
                                                                                    @else
                                                                                        {{ $webChatArray['chat_genius_name'] }}
                                                                                    @endif
                                                                                </div>
                                                                                <div class="col-auto chat-bubble-date">
                                                                                    {{ $message['created_at']->diffForHumans() }}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="chat-bubble-body">
                                                                            <div
                                                                                id="chatMessage-{{ $message['chat_message_id'] }}">
                                                                                {!! wrapPhpWithPre($message['message_content']) !!}
                                                                            </div>
                                                                            <button
                                                                                class="btn btn-sm btn-icon copy-btn float-end"
                                                                                data-message-id="{{ $message['chat_message_id'] }}">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    width="24" height="24"
                                                                                    viewBox="0 0 24 24" fill="none"
                                                                                    stroke="currentColor" stroke-width="2"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-copy">
                                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                                        fill="none" />
                                                                                    <path
                                                                                        d="M7 7m0 2.667a2.667 2.667 0 0 1 2.667 -2.667h8.666a2.667 2.667 0 0 1 2.667 2.667v8.666a2.667 2.667 0 0 1 -2.667 2.667h-8.666a2.667 2.667 0 0 1 -2.667 -2.667z" />
                                                                                    <path
                                                                                        d="M4.012 16.737a2.005 2.005 0 0 1 -1.012 -1.737v-10c0 -1.1 .9 -2 2 -2h10c.75 0 1.158 .385 1.5 1" />
                                                                                </svg>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @if ($message['responsed_by'] == 'user')
                                                                    <div class="col-auto" style="margin-bottom: 10px">
                                                                        <span class="avatar rounded-circle"
                                                                            style="background-image: url({{ asset(Auth::user()->profile_image == '' ? 'images/profile.png' : Auth::user()->profile_image) }})"></span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="card-footer">
                                    <form id="chatgenius-form">
                                        <div class="input-group input-group-flat">
                                            {{-- CSRF Token --}}
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="chat_id"
                                                value="{{ $webChatArray['chats'][0]['chat_id'] }}">

                                            {{-- Record --}}
                                            <span class="input-group-text">
                                                <a href="#" id="microphone-btn" class="link-secondary">
                                                    <svg id="microphone-icon" xmlns="http://www.w3.org/2000/svg"
                                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-microphone">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path
                                                            d="M9 2m0 3a3 3 0 0 1 3 -3h0a3 3 0 0 1 3 3v5a3 3 0 0 1 -3 3h0a3 3 0 0 1 -3 -3z" />
                                                        <path d="M5 10a7 7 0 0 0 14 0" />
                                                        <path d="M8 21l8 0" />
                                                        <path d="M12 17l0 4" />
                                                    </svg>
                                                    <svg id="pause-icon" xmlns="http://www.w3.org/2000/svg"
                                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-player-pause"
                                                        style="display: none;">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path
                                                            d="M6 5m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v12a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z" />
                                                        <path
                                                            d="M14 5m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v12a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z" />
                                                    </svg>
                                                </a>
                                            </span>
                                            <input type="text" class="form-control" id="message" name="message"
                                                placeholder="{{ __('Type your message') }}" autocomplete="off">

                                            <span class="input-group-text">
                                                <button type="submit" id="submit-btn"
                                                    class="btn btn-primary btn-icon ms-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-send" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <line x1="10" y1="14" x2="21"
                                                            y2="3" />
                                                        <path
                                                            d="M21 3l-6 18a0.55 .55 0 0 1 -1 0l-4 -8l-8 -4a0.55 .55 0 0 1 0 -1l18 -6" />
                                                    </svg>
                                                </button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('user.includes.footer')
    </div>

    {{-- Edit chat --}}
    <div class="modal modal-blur fade" id="editChatModal" tabindex="-1" role="dialog" aria-labelledby="editChatModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editChatModalLabel">{{ __('Edit Chat') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editChatForm">
                        <!-- New Chat Conversation Input -->
                        <div class="form-group">
                            <label class="form-label" for="newChatConversation">{{ __('Chat Name') }}</label>
                            <input type="text" class="form-control" id="newChatConversation" name="newChatConversation" required>
                        </div>
                        <!-- Hidden Chat ID -->
                        <input type="hidden" id="chatId" name="chatId">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-primary" onclick="submitChatUpdate()">{{ __('Save changes') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete chat modal --}}
    <div class="modal modal-blur fade" id="deleteChatModal" tabindex="-1" role="dialog" aria-labelledby="deleteChatModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteChatModalLabel">{{ __('Delete Chat') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="deleteChatForm">
                        <p>{{ __('Are you sure you want to delete this chat?') }}</p>
                        <!-- New Chat Conversation Input -->
                        <input type="text" class="form-control" id="chatId" name="chatId" hidden>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-danger" onclick="submitChatDelete()">{{ __('Delete') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom JavaScript --}}
@section('custom-js')
    {{-- Speech to text --}}
    <script src="{{ asset('js/speech-recognition.js') }}"></script>
    {{-- Moment --}}
    <script src="{{ asset('js/moment.min.js') }}"></script>
    {{-- Typewriter --}}
    <script src="{{ asset('js/typed.min.js') }}"></script>

    {{-- Edit chat --}}
    <script>
        function editChat(chatName, chatId) {
            "use strict";

            // Set the chatId in the hidden input field
            $('#newChatConversation').val(chatName);
            $('#chatId').val(chatId);

            // Open the modal
            $('#editChatModal').modal('show');
        }

        // Function to handle the form submission
        function submitChatUpdate() {
            "use strict";

            // Get form data
            var formData = {
                newChatConversation: $('#newChatConversation').val(),
                chatId: $('#chatId').val()
            };

            // AJAX call to updateChatDetails
            $.ajax({
                url: '{{ route('user.update.ai.webchat.details') }}',
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // For CSRF protection in Laravel
                },
                success: function(response) {
                    // Close modal
                    $('#editChatModal').modal('hide');

                    // Set the message inside the alert
                    $('#success-message').text(`{{ __('Chat updated successfully') }}`);

                    // Show the alert
                    $('#success-alert').slideDown();

                    // Optionally, auto-hide the alert after a few seconds
                    setTimeout(function() {
                        $('#success-alert').slideUp();
                        // Page redirect
                        window.location.reload(true);
                    }, 3000); // 5 seconds
                },
                error: function(xhr, status, error) {
                    // Handle errors (optional)
                    console.error(error);
                }
            });
        }
    </script>

    {{-- Delete chat --}}
    <script>
        function deleteChat(chatId) {
            "use strict";

            // Set the chatId in the hidden input field
            $('#chatId').val(chatId);

            // Open the modal
            $('#deleteChatModal').modal('show');
        }

        // Function to handle the form submission
        function submitChatDelete() {
            "use strict";

            // Get form data
            var formData = {
                chatId: $('#chatId').val()
            };

            // AJAX call to updateChatDetails
            $.ajax({
                url: '{{ route('user.delete.ai.webchat') }}',
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // For CSRF protection in Laravel
                },
                success: function(response) {
                    // Close modal
                    $('#deleteChatModal').modal('hide');

                    // Set the message inside the alert
                    $('#success-message').text(`{{ __('Chat deleted successfully') }}`);

                    // Show the alert
                    $('#success-alert').slideDown();

                    // Optionally, auto-hide the alert after a few seconds
                    setTimeout(function() {
                        $('#success-alert').slideUp();
                        // Page redirect
                        window.location.reload(true);
                    }, 3000); // 5 seconds
                },
                error: function(xhr, status, error) {
                    // Handle errors (optional)
                    console.error(error);
                }
            });
        }
    </script>

    {{-- Set chat id --}}
    <script>
        function setChatId(chatId) {
            // Set chat ID in the hidden input field
            document.querySelector('input[name="chat_id"]').value = chatId;

            // Wait for the content to fully load before scrolling
            setTimeout(() => {
                const scrollableElement = document.querySelector('#scrollable');
                scrollableElement.scrollTop = scrollableElement.scrollHeight;
            }, 200); // Adjust the timeout duration based on content loading speed
        }

        function setChatName(chatId) {
            // Set chat ID in the hidden input field
            document.querySelector('input[name="chat_id"]').value = chatId;

            // Wait for the content to fully load before scrolling
            setTimeout(() => {
                const scrollableElement = document.querySelector('#scrollable');
                scrollableElement.scrollTop = scrollableElement.scrollHeight;
            }, 200); // Adjust the timeout duration based on content loading speed

            $('#mobileChatsModal').modal('hide');
        }
    </script>

    {{-- Ajax call to get the chat conversation --}}
    <script>
        $(document).ready(function() {
            $('#chatgenius-form').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                // Create FormData object to gather form data, including the file
                var formData = new FormData(this);
                var chat_id = $('input[name="chat_id"]', this).val();
                var user_message = $("#message").val();
                var random_chat_id = Math.floor(Math.random() * 1000000);

                // Create user chat bubble
                var userBubble = `
    <div class="row align-items-end mb-2 justify-content-end">
        <div class="col col-lg-6">
            <div class="chat-bubble chat-bubble-them">
                <div class="chat-bubble-title">
                    <div class="row">
                        <div class="col chat-bubble-author">{{ Auth::user()->name }}</div>
                        <div class="col-auto chat-bubble-date">{{ Carbon\Carbon::now()->diffForHumans() }}</div>
                    </div>
                </div>
                <div class="chat-bubble-body">
                    <p id="chatMessage-${random_chat_id}">${user_message}</p>
                    <button class="btn btn-sm btn-icon copy-btn float-end" data-message-id="${random_chat_id}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round"
                             class="icon icon-tabler icons-tabler-outline icon-tabler-copy">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M7 7m0 2.667a2.667 2.667 0 0 1 2.667 -2.667h8.666a2.667 2.667 0 0 1 2.667 2.667v8.666a2.667 2.667 0 0 1 -2.667 2.667h-8.666a2.667 2.667 0 0 1 -2.667 -2.667z" />
                            <path d="M4.012 16.737a2.005 2.005 0 0 1 -1.012 -1.737v-10c0 -1.1 .9 -2 2 -2h10c.75 0 1.158 .385 1.5 1" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-auto" style="margin-bottom: 10px">
            <span class="avatar rounded-circle" style="background-image: url({{ asset(Auth::user()->profile_image ?: 'images/profile.png') }})"></span>
        </div>
    </div>`;

                $("#chatConversation_" + chat_id).append(userBubble);
                $('#scrollable').scrollTop($('#scrollable')[0].scrollHeight);
                $('#message').val('');

                // Generate and display "Thinking" chat bubble
                var generate_chat_id = Math.floor(Math.random() * 1000000);
                var generatingChatBubble = `
    <div class="row align-items-end mb-2 justify-content-start">
        <div class="col-auto" style="margin-bottom: 10px">
            <span class="avatar rounded-circle" style="background-image: url({{ asset($webChatArray['chat_genius_image']) }})"></span>
        </div>
        <div class="col col-lg-6">
            <div class="chat-bubble chat-bubble-them">
                <div class="chat-bubble-title">
                    <div class="row">
                        <div class="col chat-bubble-author">{{ $webChatArray['chat_genius_name'] }}</div>
                        <div class="col-auto chat-bubble-date">${moment().fromNow()}</div>
                    </div>
                </div>
                <div class="chat-bubble-body">
                    <p id="chatMessage-${generate_chat_id}">{{ __('Analyzing') }}<span class="animated-dots"></span></p>
                    <button class="btn btn-sm btn-icon copy-btn float-end" data-message-id="${generate_chat_id}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round"
                             class="icon icon-tabler icons-tabler-outline icon-tabler-copy">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M7 7m0 2.667a2.667 2.667 0 0 1 2.667 -2.667h8.666a2.667 2.667 0 0 1 2.667 2.667v8.666a2.667 2.667 0 0 1 -2.667 2.667h-8.666a2.667 2.667 0 0 1 -2.667 -2.667z" />
                            <path d="M4.012 16.737a2.005 2.005 0 0 1 -1.012 -1.737v-10c0 -1.1 .9 -2 2 -2h10c.75 0 1.158 .385 1.5 1" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>`;

                setTimeout(function() {
                    $("#chatConversation_" + chat_id).append(generatingChatBubble);
                    $('#scrollable').scrollTop($('#scrollable')[0].scrollHeight);
                }, 1000);

                // Send AJAX request
                $.ajax({
                    url: "{{ route('user.generate.ai.webchat') }}",
                    type: "POST",
                    data: formData,
                    contentType: false, // Important for file uploads
                    processData: false, // Prevent jQuery from processing the data
                    success: function(response) {
                        var message = response.webChatArray;
                        var chatBubble = `<p id="chatMessage-${message.chat_message_id}"></p>`;

                        setTimeout(function() {
                            $("#chatMessage-" + generate_chat_id).html(chatBubble);
                            new Typewriter(document.getElementById(
                                    `chatMessage-${message.chat_message_id}`), {
                                    loop: false,
                                    autoStart: true,
                                    cursor: "",
                                    delay: 20
                                }).typeString(wrapPhpWithPre(message.chat_message))
                                .pauseFor(100)
                                .start();
                            $('#scrollable').scrollTop($('#scrollable')[0]
                                .scrollHeight);
                        }, 2000);
                    },
                    error: function(xhr) {
                        // Set the message inside the alert
                        $('#failed-message').text(`{{ __('Chat generation failed') }}`);

                        // Show the alert
                        $('#failed-alert').slideDown();

                        // Optionally, auto-hide the alert after a few seconds
                        setTimeout(function() {
                            $('#failed-alert').slideUp();
                        }, 3000); // 5 seconds
                    }
                });
            });

            // Copy to clipboard
            $(document).on('click', '.copy-btn', function() {
                var messageId = $(this).data('message-id');
                var messageText = $(`#chatMessage-${messageId}`).text();
                copyToClipboard(messageText);
                $(this).attr('data-bs-original-title', 'Copied!').tooltip('show');
                setTimeout(() => $(this).tooltip('hide'), 2000);
            });

            // Copy to clipboard function
            function copyToClipboard(text) {
                "use strict";
                var tempInput = document.createElement('textarea');
                tempInput.value = text;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);
            }
        });

        // Enter submit and click submit button
        $(document).ready(function() {
            // Handling the Enter key press
            $('#message').on('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault(); // Prevent default Enter key behavior
                    var messageText = $(this).val().trim(); // Get the trimmed value of the message field

                    if (messageText) {
                        $('#chatgenius-form').submit(); // Submit the form if not empty
                    } else {
                        // Set the message inside the alert
                        $('#failed-message').text(`{{ __('Please enter a message.') }}`);

                        // Show the alert
                        $('#failed-alert').slideDown();

                        // Optionally, auto-hide the alert after a few seconds
                        setTimeout(function() {
                            $('#failed-alert').slideUp();
                        }, 3000); // 5 seconds
                    }
                }
            });

            // Handling the Submit button click
            $('#submit-btn').on('click', function(event) {
                event.preventDefault(); // Prevent default form submission
                var messageText = $('#message').val().trim(); // Get the trimmed value of the message field

                if (messageText) {
                    $('#chatgenius-form').submit(); // Submit the form if not empty
                } else {
                    // Set the message inside the alert
                    $('#failed-message').text(`{{ __('Please enter a message.') }}`);

                    // Show the alert
                    $('#failed-alert').slideDown();

                    // Optionally, auto-hide the alert after a few seconds
                    setTimeout(function() {
                        $('#failed-alert').slideUp();
                    }, 3000); // 5 seconds
                }
            });
        });

        // Function to detect and format code blocks with <pre> tags
        function wrapPhpWithPre(code) {
            // Check if the input contains PHP tags or code blocks using a regex
            if (typeof code === 'string' && /<\?php|```/.test(code)) {
                // Wrap the code in <pre> tags and return
                return `<pre>${code}</pre>`;
            }
            // Escape HTML and return the modified string
            return escapeHtml(code);
        }

        // Function to escape HTML
        function escapeHtml(html) {
            const text = document.createTextNode(html);
            const div = document.createElement('div');
            div.appendChild(text);
            return div.innerHTML;
        }
    </script>
@endsection
@endsection
