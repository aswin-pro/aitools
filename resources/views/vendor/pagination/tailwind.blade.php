@if ($paginator->hasPages())
<div class="card custom-pagination">
    <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-muted">{!! __('Showing') !!} <span>{{ $paginator->firstItem() }}</span> {!! __('to') !!}
            <span>{{ $paginator->lastItem() }}</span> {!! __('of') !!} <span>{{ $paginator->total() }}</span>
        </p>
        <nav class="custom-nav">
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                <li class="btn btn-sm btn-primary disabled" aria-disabled="true">
                    <span>@lang('pagination.previous')</span>
                </li>
                @else
                <li><a class="btn btn-sm btn-primary" href="{{ $paginator->previousPageUrl() }}"
                        rel="prev">@lang('pagination.previous')</a></li>
                @endif

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                <li><a class="btn btn-sm btn-primary" href="{{ $paginator->nextPageUrl() }}"
                        rel="next">@lang('pagination.next')</a></li>
                @else
                <li class="btn btn-sm btn-primary disabled" aria-disabled="true"><span>@lang('pagination.next')</span>
                </li>
                @endif
            </ul>
        </nav>
    </div>
</div>
@endif

<style>
    .btn-group-sm>.btn,
    .btn-sm {
        --tblr-btn-line-height: 1.5;
        --tblr-btn-icon-size: .75rem;
        margin-right: 5px;
        font-size: 11px !important;
        margin: 13px 0 10px 5px !important;
    }

    .custom-nav {
        position: absolute;
        right: 5px;
        top: -4px;
    }
</style>