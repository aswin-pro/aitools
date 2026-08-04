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
                        {{ __('Templates') }}
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-fluid">
            <div class="card">
                {{-- Templates Categories --}}
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                        <li class="nav-item">
                            <a href="#tabs-all" class="nav-link active" data-bs-toggle="tab">
                                {{ __('All') }}
                            </a>
                        </li>
                        @foreach ($templateCategories as $category)
                        <li class="nav-item">
                            <a href="#tabs-{{ strtolower(str_replace(" ","-",$category->category_name)) }}"
                                class="nav-link" data-bs-toggle="tab">
                                {{ __($category->category_name) }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        {{-- All tamplates --}}
                        <div class="tab-pane active show" id="tabs-all">
                            <div class="row">
                                @foreach ($allTemplates as $template)
                                {{-- Template --}}
                                <div class="col-md-4 col-lg-4 mb-3">
                                    <div class="card">
                                        @php
                                        $planTemplate = json_decode($active_plan->templates, true);
                                        $currentTemplate = $template->unique_slug;
                                        @endphp
                                        {{-- Check --}}
                                        @isset($planTemplate[$currentTemplate])
                                        @if ($planTemplate[$currentTemplate] == 1)
                                        <div class="ribbon rounded bg-success">
                                            {{ __('New') }}
                                        </div>
                                        @else
                                        <div class="ribbon rounded bg-red">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-crown" width="24" height="24"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M12 6l4 6l5 -4l-2 10h-14l-2 -10l5 4z"></path>
                                            </svg>
                                        </div>
                                        @endif
                                        {{-- Link --}}
                                        <a class="{{ $planTemplate[$currentTemplate] == 1 ? '' : 'open-premium' }}"
                                            href="{{ $planTemplate[$currentTemplate] == 1 ? route('user.new.ai.content', $template->unique_slug) : '#premiumModel' }}">
                                            <div class="card-body">
                                                <h3 class="card-title text-muted mt-4">{{ __($template->name) }}</h3>
                                                <p class="text-muted">{{ __(__($template->description)) }}</p>
                                            </div>
                                        </a>
                                        @endisset
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Category wise templates --}}
                        @foreach ($templateCategories as $index => $category)
                        <div class="tab-pane" id="tabs-{{ strtolower(str_replace(" ","-",$category->category_name))
                            }}">
                            <div class="row">
                                @foreach ($templateCategories[$index]['templates'] as $template)
                                {{-- Template --}}
                                <div class="col-md-4 col-lg-4 mb-3">
                                    <div class="card">
                                        @php
                                        $planTemplate = json_decode($active_plan->templates, true);
                                        $currentTemplate = $template->unique_slug;
                                        @endphp
                                        {{-- Check --}}
                                        @isset($planTemplate[$currentTemplate])
                                        @if ($planTemplate[$currentTemplate] == 1)
                                        <div class="ribbon rounded bg-success">
                                            {{ __('New') }}
                                        </div>
                                        @else
                                        <div class="ribbon rounded bg-red">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-crown" width="24" height="24"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M12 6l4 6l5 -4l-2 10h-14l-2 -10l5 4z"></path>
                                            </svg>
                                        </div>
                                        @endif
                                        {{-- Link --}}
                                        <a class="{{ $planTemplate[$currentTemplate] == 1 ? '' : 'open-premium' }}"
                                            href="{{ $planTemplate[$currentTemplate] == 1 ? route('user.new.ai.content', $template->unique_slug) : '#premiumModel' }}">
                                            <div class="card-body">
                                                <h3 class="card-title text-muted mt-4">{{ __($template->name) }}</h3>
                                                <p class="text-muted">{{ __(__($template->description)) }}</p>
                                            </div>
                                        </a>
                                        @endisset
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal --}}
<div class="modal modal-blur fade" id="premiumModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-danger"></div>
            <div class="modal-body text-center py-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon-tabler-crown text-danger" width="48" height="48"
                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M12 6l4 6l5 -4l-2 10h-14l-2 -10l5 4z"></path>
                </svg>
                <h3>{{ __('Premium Tool') }}</h3>
                <div class="text-muted">{{ __('This is a premium tool. If you want to access this tool, you need a
                    premium plan.') }}</div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col"><a href="#" class="btn btn-dark w-100" data-bs-dismiss="modal">
                                {{ __('Cancel') }}
                            </a></div>
                        <div class="col"><a href="{{ route('user.plans') }}" class="btn btn-danger w-100">
                                {{ __('Go to plans') }}
                            </a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Custom JS --}}
@section('custom-js')
<script>
    // Modal
$(document).on("click", ".open-premium", function () {
    "use strict";
    $('#premiumModal').modal('show');
});
</script>
@endsection
@endsection