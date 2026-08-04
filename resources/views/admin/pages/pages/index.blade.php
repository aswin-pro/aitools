@extends('admin.layouts.app')

@section('content')
<div class="page-wrapper">
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

            <div class="row row-deck row-cards">
                <div class="col">
                    <h2 class="page-title">
                        {{ __('Pages') }}
                    </h2>
                </div>

                {{-- Pages --}}
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="table-responsive px-2 py-2">
                            <table class="table table-vcenter card-table" id="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('#') }}</th>
                                        <th>{{ __('Page') }}</th>
                                        <th>{{ __('Slug') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th class="w-1">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pages as $page)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-capitalize">{{ __($page->name) }}</td>
                                        <td><a href="{{ env('APP_URL') }}{{ $page->slug == 'home' || $page->slug == 'hero' || $page->slug == 'footer' ? '/' : '/'.$page->slug }}"
                                                target="_blank" rel="noopener noreferrer">{{ $page->slug == '/' ? '/' :
                                                '/'.$page->slug }}</a></td>
                                        <td class="text-muted">
                                            @if ($page->status == 0)
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
                                                    <a class="dropdown-item" href="{{ route('admin.edit.page', $page->slug)}}">{{ __('Edit') }}</a>
                                                    @if ($page->status == 0)
                                                    <a class="dropdown-item" href="#" onclick="getDisablePage('{{ $page->slug }}'); return false;">{{ __('Enable') }}</a>
                                                    @else
                                                    @if ($page->slug != 'home' && $page->slug != 'hero' && $page->slug != 'newsletter' && $page->slug != 'pricing-home' && $page->slug != 'social-links' )
                                                    <a class="dropdown-item" href="#" onclick="getDisablePage('{{ $page->slug }}'); return false;">{{ __('Disable') }}</a>
                                                    @endif
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

                {{-- Custom Pages --}}
                <div class="col">
                    <h2 class="page-title">
                        {{ __('Custom Pages') }}
                    </h2>
                </div>
                <!-- Add page -->
                <div class="col-auto ms-auto d-print-none">
                    <a type="button" href="{{ route('admin.add.page') }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        {{ __('Add New Page') }}
                    </a>
                </div>
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="table-responsive px-2 py-2">
                            <table class="table table-vcenter card-table" id="table1">
                                <thead>
                                    <tr>
                                        <th>{{ __('#') }}</th>
                                        <th>{{ __('Page') }}</th>
                                        <th>{{ __('Slug') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th class="w-1">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($custom_pages as $page)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-capitalize">{{ $page->name }}</td>
                                        <td><a href="{{ env('APP_URL') }}{{ $page->slug == 'home' ? '' : '/p/'.$page->slug }}"
                                                target="_blank" rel="noopener noreferrer">{{ $page->slug == '/' ? '/' :
                                                '/'.$page->slug }}</a></td>
                                        <td class="text-muted">
                                            @if ($page->status == 0)
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
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.edit.custom.page', $page->id)}}">{{ __('Edit')
                                                        }}</a>
                                                    @if ($page->status == 0)
                                                    <a class="dropdown-item" href="#"
                                                        onclick="getPage('{{ $page->id }}'); return false;">{{
                                                        __('Enable') }}</a>
                                                    @else
                                                    <a class="dropdown-item" href="#"
                                                        onclick="getPage('{{ $page->id }}'); return false;">{{
                                                        __('Disable') }}</a>
                                                    @endif
                                                    <a class="dropdown-item" href="#"
                                                        onclick="deletePage('{{ $page->id }}', 'delete'); return false;">{{
                                                        __('Delete') }}</a>
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
            </div>
        </div>
    </div>

    {{-- Footer --}}
    @include('admin.includes.footer')
</div>

{{-- Enable/Disable Page Modal --}}
<div class="modal modal-blur fade" id="status-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-title">{{ __('Are you sure?')}}</div>
                <div>{{ __('If you proceed, you will enable/disable this page.')}}</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary me-auto" data-bs-dismiss="modal">{{
                    __('Cancel')}}</button>
                <a class="btn btn-danger" id="page_id">{{ __('Yes, proceed')}}</a>
            </div>
        </div>
    </div>
</div>

{{-- Delete Page Modal --}}
<div class="modal modal-blur fade" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-title">{{ __('Are you sure?')}}</div>
                <div id="delete_status"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary me-auto" data-bs-dismiss="modal">{{
                    __('Cancel')}}</button>
                <a class="btn btn-danger" id="delete_qr_code_id">{{ __('Yes, proceed')}}</a>
            </div>
        </div>
    </div>
</div>

{{-- Custom JS --}}
@section('custom-js')
<script>
    function getPage(parameter) {
    "use strict";
    $("#status-modal").modal("show");
    var link = document.getElementById("page_id");
    link.getAttribute("href");
    link.setAttribute("href", "{{ route('admin.status.page') }}?id=" + parameter);
}
// Disable page
function getDisablePage(parameter) {
    "use strict";
    $("#status-modal").modal("show");
    var link = document.getElementById("page_id");
    link.getAttribute("href");
    link.setAttribute("href", "{{ route('admin.disable.page') }}?id=" + parameter);
}
// Delete QR
function deletePage(deletePageId, deletePageStatus) {
    "use strict";
    $("#delete-modal").modal("show");
    var delete_status = document.getElementById("delete_status");
    delete_status.innerHTML = "<?php echo __('If you proceed, you will') ?> " + deletePageStatus + " <?php echo __('this page.') ?>"
    var delete_link = document.getElementById("delete_qr_code_id");
    delete_link.getAttribute("href");
    delete_link.setAttribute("href", "{{ route('admin.delete.page') }}?id=" + deletePageId);
}
</script>
@endsection
@endsection