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
                            {{ __('Chat Assistant') }}
                        </h2>
                    </div>
                    <!-- Create Category -->
                    @if (count($chatgenius) > 0)
                        <div class="col-auto ms-auto">
                            <a type="button" href="{{ route('admin.create.chatgenius') }}" class="btn btn-icon btn-primary"
                                data-bs-toggle="tooltip" data-bs-placement="left" title="{{ __('Create Chat Assistant') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </a>
                        </div>
                    @endif
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
                    {{-- Chat Assistant --}}
                    @if (count($chatgenius) == 0)
                        <div class="col-md-12 col-xl-12">
                            <div class="empty">
                                <div class="empty-img">
                                    <img src="{{ asset('images/chatgenius/chat_bot.svg') }}" alt="chat genius">
                                </div>
                                <p class="empty-title">{{ __('No chat assistants found') }}</p>
                                <p class="empty-subtitle text-secondary">
                                    {{ __('Try adjusting your search or filter to find what you\'re looking for.') }}</p>
                                <div class="empty-action">
                                    <a href="{{ route('admin.create.chatgenius') }}" class="btn btn-primary">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M12 5l0 14"></path>
                                            <path d="M5 12l14 0"></path>
                                        </svg>
                                        {{ __('Create Chat Assistant') }}
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="col-sm-12 col-lg-12">
                                <div class="card">
                                    <div class="table-responsive px-2 py-2">
                                        <table class="table table-vcenter table-mobile-md card-table" id="table">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('#') }}</th>
                                                    <th>{{ __('Chat Assistant Name') }}</th>
                                                    <th>{{ __('Chat Assistant Expert') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                    <th class="w-1">{{ __('Actions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($chatgenius as $chatgenius)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td data-label="Name">
                                                            <div class="d-flex py-1 align-items-center">
                                                                <span class="avatar me-2"
                                                                    style="background-image: url({{ asset($chatgenius->chat_genius_image) }})"></span>
                                                                <div class="flex-fill">
                                                                    <div class="font-weight-medium">
                                                                        {{ __($chatgenius->chat_genius_name) }}</div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ __($chatgenius->chat_genius_expert) }}</td>
                                                        <td class="text-muted">
                                                            @if ($chatgenius->status == 0)
                                                                <span class="badge bg-red text-white">{{ __('Disabled') }}</span>
                                                            @else
                                                                <span class="badge bg-green text-white">{{ __('Enabled') }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-end">
                                                            <span class="dropdown">
                                                                <button class="btn small-btn dropdown-toggle align-text-top"
                                                                    data-bs-boundary="viewport" data-bs-toggle="dropdown"
                                                                    aria-expanded="false">{{ __('Actions') }}</button>
                                                                <div class="dropdown-menu dropdown-menu-end" style="">
                                                                    @if ($chatgenius->id > 55)
                                                                        {{-- Edit --}}
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('admin.edit.chatgenius', $chatgenius->chat_genius_id) }}">{{ __('Edit') }}</a>
                                                                    @endif
                                                                    {{-- Update status --}}
                                                                    @if ($chatgenius->status == 0)
                                                                        {{-- Activate --}}
                                                                        <a class="dropdown-item" href="#"
                                                                            onclick="getChatAssistant('{{ $chatgenius->chat_genius_id }}', 'activate'); return false;">{{ __('Activate') }}</a>
                                                                    @else
                                                                        {{-- Deactivate --}}
                                                                        <a class="dropdown-item" href="#"
                                                                            onclick="getChatAssistant('{{ $chatgenius->chat_genius_id }}', 'deactivate'); return false;">{{ __('Deactivate') }}</a>
                                                                    @endif
                                                                    @if ($chatgenius->id > 55)
                                                                        {{-- Delete --}}
                                                                        <a class="dropdown-item" href="#"
                                                                            onclick="deleteChatAssistant('{{ $chatgenius->chat_genius_id }}', 'delete'); return false;">{{ __('Delete') }}</a>
                                                                    @endif
                                                                </div>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Action modal --}}
    <div class="modal modal-blur fade" id="action-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-title">{{ __('Are you sure?') }}</div>
                    <div id="action_status"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-auto"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-danger" id="chatgeniusId">{{ __('Yes, proceed') }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Chat Assistant Modal --}}
    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-title text-danger text-capitalize">{{ __('WARNING!') }}</div>
                    <div>{{ __('This action will remove chat genius data. It is not revertable action.') }}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-auto"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-danger" id="deleted_chat_genius_id">{{ __('Yes, proceed') }}</a>
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
        function getChatAssistant(chatgeniusId, chatgeniusStatus) {
            "use strict";
            $("#action-modal").modal("show");
            var delete_status = document.getElementById("action_status");
            delete_status.innerHTML = "<?php echo __('If you proceed, you will'); ?> " + chatgeniusStatus + " <?php echo __('this chat assistant.'); ?>"
            var actionLink = document.getElementById("chatgeniusId");
            actionLink.getAttribute("href");
            actionLink.setAttribute("href", "{{ route('admin.action.chatgenius') }}?id=" + chatgeniusId + "&mode=" +
                chatgeniusStatus);
        }
    </script>
    <script>
        function deleteChatAssistant(chatgeniusId, chatgeniusStatus) {
            "use strict";
            $("#delete-modal").modal("show");
            var link = document.getElementById("deleted_chat_genius_id");
            link.getAttribute("href");
            link.setAttribute("href", "{{ route('admin.delete.chatgenius') }}?id=" + chatgeniusId);
        }
    </script>
@endsection
@endsection
