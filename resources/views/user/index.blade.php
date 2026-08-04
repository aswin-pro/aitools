@extends('user.layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="container-fluid">
            <!-- Page title -->
            <div class="page-header d-print-none">
                <div class="row align-items-center">
                    <div class="col">
                        <!-- Page pre-title -->
                        <div class="page-pretitle">
                            {{ __('Overview') }}
                        </div>
                        <h2 class="page-title">
                            {{ __('Dashboard') }}
                        </h2>
                    </div>
                    {{-- Create new AI Content --}}
                    <div class="col-12 col-md-auto ms-auto d-print-none">
                        <div class="btn-list">
                            <a href="{{ route('user.all.ai.content') }}" class="btn btn-primary d-none d-sm-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-file-description" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                                    <path d="M9 17h6"></path>
                                    <path d="M9 13h6"></path>
                                </svg>
                                {{ __('Generate AI Content') }}
                            </a>
                        </div>
                    </div>
                    {{-- Create new AI Image --}}
                    <div class="col-12 col-md-auto ms-auto d-print-none">
                        <div class="btn-list">
                            <a href="{{ route('user.all.ai.images') }}" class="btn btn-primary d-none d-sm-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-photo"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M15 8l.01 0"></path>
                                    <path
                                        d="M4 4m0 3a3 3 0 0 1 3 -3h10a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-10a3 3 0 0 1 -3 -3z">
                                    </path>
                                    <path d="M4 15l4 -4a3 5 0 0 1 3 0l5 5"></path>
                                    <path d="M14 14l1 -1a3 5 0 0 1 3 0l2 2"></path>
                                </svg>
                                {{ __('Generate AI Image') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="container-fluid">

                {{-- Access deined --}}
                @if (Session::has('access'))
                    <div class="alert alert-important alert-danger alert-dismissible mb-2" role="alert">
                        <div class="d-flex">
                            <div>
                                {{ Session::get('access') }}
                            </div>
                        </div>
                        <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                {{-- Failed --}}
                @if (Session::has('failed'))
                    <div class="alert alert-important alert-danger alert-dismissible mb-2" role="alert">
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
                    <div class="alert alert-important alert-success alert-dismissible mb-2" role="alert">
                        <div class="d-flex">
                            <div>
                                {{ Session::get('success') }}
                            </div>
                        </div>
                        <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                <div class="row row-deck row-cards">
                    {{-- Current plan --}}
                    <div class="col-sm-4 col-lg-4">
                        <div class="card bg-custom5">
                            <div class="card-body text-custom">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="subheader text-custom">{{ __('Current Plan') }}</div>
                                </div>
                                @if ($active_plan->price == 0)
                                    <div class="h1">{{ __($active_plan->name) }}</div>
                                    <p>{{ __('FREE PLAN') }}</p><br>
                                @else
                                    <h2 class="text-uppercase"><b>{{ __($active_plan->name) }}</b></h3>
                                        <p>{{ __('Remaining Days') }} :
                                            {{ $remaining_days > 0 ? (int) $remaining_days : __('Plan Expired!') }}</p><br>
                                @endif
                                <a class="text-custom" href="{{ route('user.plans') }}">
                                    {{ __('Show details') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Today AI Contents --}}
                    <div class="col-sm-4 col-lg-4">
                        <div class="card bg-custom6">
                            <div class="card-body text-custom">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="subheader text-custom">{{ __('Today AI Contents') }}</div>
                                    <div class="ms-auto lh-1">
                                        <a class="text-custom" href="{{ route('user.all.ai.content') }}">
                                            {{ __('Show details') }}
                                        </a>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <h1>{{ $today_generates }}</h1><p>{{ __('Words') }}</p>
                                </div>
                                <div class="progress progress-separated mb-3">
                                    <div class="progress-bar bg-danger" role="progressbar"
                                        style="width: {{ number_format(((float) $usedWords / $current_words) * 100, 2, '.', '') }}%"
                                        aria-label="Regular"></div>
                                </div>
                                <div class="row">
                                    <div class="col-auto d-flex align-items-center pe-2">
                                        <span class="legend me-2 bg-danger"></span>
                                        <span>{{ __('Used AI Words') }}</span>
                                        <span
                                            class="d-none d-md-inline d-lg-none d-xxl-inline ms-2 text-muted">{{ $usedWords }}</span>
                                    </div>
                                    <div class="col-auto d-flex align-items-center ps-2">
                                        <span class="legend me-2"></span>
                                        <span>{{ __('Available AI Words') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Today AI Images --}}
                    <div class="col-sm-4 col-lg-4">
                        <div class="card bg-custom8">
                            <div class="card-body text-custom">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="subheader text-custom">{{ __('Today AI Images') }}</div>
                                    <div class="ms-auto lh-1">
                                        <a class="text-custom" href="{{ route('user.all.ai.content') }}">
                                            {{ __('Show details') }}
                                        </a>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <h1>{{ $today_images }}</h1><p>{{ __('Images') }}</p>
                                </div>
                                <div class="progress progress-separated mb-3">
                                    <div class="progress-bar bg-danger" role="progressbar"
                                        style="width: {{ number_format(((float) $usedImages / $current_images) * 100, 2, '.', '') }}%"
                                        aria-label="Regular"></div>
                                </div>
                                <div class="row">
                                    <div class="col-auto d-flex align-items-center pe-2">
                                        <span class="legend me-2 bg-danger"></span>
                                        <span>{{ __('Used AI Images') }}</span>
                                        <span
                                            class="d-none d-md-inline d-lg-none d-xxl-inline ms-2 text-muted">{{ $usedImages }}</span>
                                    </div>
                                    <div class="col-auto d-flex align-items-center ps-2">
                                        <span class="legend me-2"></span>
                                        <span>{{ __('Available AI Images') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Month wise AI Content --}}
                    <div class="col-sm-6 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                {{-- Title --}}
                                <div class="d-flex align-items-center mb-3">
                                    <div class="subheader">{{ __('AI Contents') }}</div>
                                </div>
                                {{-- Chart --}}
                                <div id="chart-month-contents" class="chart-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Month wise AI Images --}}
                    <div class="col-sm-6 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                {{-- Title --}}
                                <div class="d-flex align-items-center mb-3">
                                    <div class="subheader">{{ __('AI Images') }}</div>
                                </div>
                                {{-- Chart --}}
                                <div id="chart-month-images" class="chart-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Latest 10 Generation --}}
                    <div class="col-md-6 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Latest 10 Generation') }}</h3>
                            </div>
                            <div class="card-table table-responsive">
                                <table class="table table-vcenter card-table" id="table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('#') }}</th>
                                            <th>{{ __('Generated on') }}</th>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- AI Contents --}}
                                        @foreach ($generates as $generate)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ date('d/m/Y h:i A', strtotime($generate->created_at)) }}</td>
                                                <td data-label="Name">
                                                    @if ($generate->type != 'Text To Speech')
                                                        <a
                                                            href="{{ route('user.view.ai.content', $generate->generate_id) }}">
                                                            <div class="d-flex py-1 align-items-center">
                                                                <div class="flex-fill">
                                                                    <div class="font-weight-medium">
                                                                        {{ Str::ucfirst(mb_strimwidth($generate->name, 0, 27, '...')) }}
                                                                    </div>
                                                                    <div class="text-muted">{{ __('Used Credits') }} :
                                                                        {{ $generate->word_count }}</div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    @else
                                                        <a href="{{ asset($generate->content) }}" download>
                                                            <div class="d-flex py-1 align-items-center">
                                                                <div class="flex-fill">
                                                                    <div class="font-weight-medium">
                                                                        {{ Str::ucfirst(mb_strimwidth($generate->name, 0, 27, '...')) }}
                                                                    </div>
                                                                    <div class="text-muted">{{ __('Used Credits') }} :
                                                                        {{ $generate->word_count }}</div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <span class="dropdown">
                                                        <button class="btn small-btn dropdown-toggle align-text-top"
                                                            data-bs-boundary="viewport" data-bs-toggle="dropdown"
                                                            aria-expanded="false">{{ __('Actions') }}</button>
                                                        <div class="dropdown-menu dropdown-menu-end" style="">
                                                            @if ($generate->type != 'Text To Speech')
                                                                @if ($generate->type != 'Code')
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('user.view.ai.content', $generate->generate_id) }}">{{ __('View') }}</a>
                                                                @else
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('user.view.ai.code', $generate->generate_id) }}">{{ __('View') }}</a>
                                                                @endif
                                                                <a class="dropdown-item"
                                                                    href="{{ route('user.edit.ai.content', $generate->generate_id) }}">{{ __('Edit') }}</a>
                                                            @else
                                                                <a class="dropdown-item"
                                                                    href="{{ asset($generate->content) }}"
                                                                    download>{{ __('Download') }}</a>
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

                    {{-- Recent AI Images --}}
                    <div class="col-md-6 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Recent 10 AI Images') }}</h3>
                            </div>
                            <div class="card-table table-responsive">
                                <table class="table table-vcenter card-table" id="table1">
                                    <thead>
                                        <tr>
                                            <th>{{ __('#') }}</th>
                                            <th>{{ __('Generated on') }}</th>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- AI Images --}}
                                        @foreach ($images as $image)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ date('d/m/Y h:i A', strtotime($image->created_at)) }}</td>
                                                <td data-label="Name">
                                                    <a href="{{ route('user.view.ai.image', $image->generate_id) }}">
                                                        <div class="d-flex py-1 align-items-center">
                                                            {{-- Check format --}}
                                                            @if ($image->format == 'b64_json')
                                                                <span class="avatar me-2"
                                                                    style="background-image: url(data:image/png;base64,{{ json_decode($image->result)[0]->b64_json }})"></span>
                                                            @endif
                                                            @if ($image->format == 'url')
                                                                <span class="avatar me-2"
                                                                    style="background-image: url({{ url(json_decode($image->result)[0]) }})"></span>
                                                            @endif
                                                            <div class="flex-fill">
                                                                <div class="font-weight-medium">
                                                                    <strong>{{ Str::ucfirst(mb_strimwidth($image->name, 0, 15, '...')) }}</strong>
                                                                </div>
                                                                <div class="text-muted">{{ __('No') }} :
                                                                    {{ $image->n }}</div>
                                                                <div class="text-muted">{{ __('Size') }} :
                                                                    {{ $image->size }}</div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td class="text-end">
                                                    <span class="dropdown">
                                                        <button class="btn small-btn dropdown-toggle align-text-top"
                                                            data-bs-boundary="viewport" data-bs-toggle="dropdown"
                                                            aria-expanded="false">{{ __('Actions') }}</button>
                                                        <div class="dropdown-menu dropdown-menu-end" style="">
                                                            <a class="dropdown-item"
                                                                href="{{ route('user.view.ai.image', $image->generate_id) }}">{{ __('View') }}</a>
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
        @include('user.includes.footer')
    </div>

    {{-- Delete Content Modal --}}
    <div class="modal modal-blur fade" id="delete-content-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-title">{{ __('Are you sure?') }}</div>
                    <div id="delete_content_status"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-auto"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-danger" id="deleteContentId">{{ __('Yes, proceed') }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Image Modal --}}
    <div class="modal modal-blur fade" id="delete-image-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-title">{{ __('Are you sure?') }}</div>
                    <div id="delete_image_status"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-auto"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-danger" id="deleteImageId">{{ __('Yes, proceed') }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom JS --}}
@section('custom-js')
    <script src="{{ asset('js/apexcharts.min.js') }}"></script>
    <script>
        // Delete Content
        function deleteContent(deleteContentId, deleteContentStatus) {
            "use strict";
            $("#delete-content-modal").modal("show");
            var delete_content_status = document.getElementById("delete_content_status");
            delete_content_status.innerHTML = "<?php echo __('If you proceed, you will'); ?> " + deleteContentStatus + " <?php echo __('this generated content.'); ?>"
            var delete_link = document.getElementById("deleteContentId");
            delete_link.getAttribute("href");
            delete_link.setAttribute("href", "{{ route('user.delete.ai.content') }}?id=" + deleteContentId);
        }

        // Delete Image
        function deleteImage(deleteImageId, deleteImageStatus) {
            "use strict";
            $("#delete-image-modal").modal("show");
            var delete_image_status = document.getElementById("delete_image_status");
            delete_image_status.innerHTML = "<?php echo __('If you proceed, you will'); ?> " + deleteImageStatus + " <?php echo __('this generated image.'); ?>"
            var delete_link = document.getElementById("deleteImageId");
            delete_link.getAttribute("href");
            delete_link.setAttribute("href", "{{ route('user.delete.ai.image') }}?id=" + deleteImageId);
        }

        document.addEventListener("DOMContentLoaded", function() {
            "use strict";

            const charts = [{
                    id: 'chart-month-contents',
                    name: "{{ __('Generated AI Contents') }}",
                    data: [{{ $contentsData }}]
                },
                {
                    id: 'chart-month-images',
                    name: "{{ __('Generated AI Images') }}",
                    data: [{{ $imagesData }}]
                }
            ];

            const isDark = document.body.getAttribute('data-bs-theme') === 'dark';

            charts.forEach(chart => {
                if (!document.getElementById(chart.id)) return;

                new ApexCharts(document.getElementById(chart.id), {
                    chart: {
                        type: "area",
                        fontFamily: 'Figtree, sans-serif',
                        height: 240,
                        parentHeightOffset: 0,
                        toolbar: {
                            show: false
                        },
                        animations: {
                            enabled: true
                        },
                        stacked: true,
                    },
                    dataLabels: {
                        enabled: false
                    },
                    fill: {
                        opacity: 0.16,
                        type: 'solid'
                    },
                    stroke: {
                        width: 2,
                        lineCap: "round",
                        curve: "smooth"
                    },
                    series: [{
                        name: chart.name,
                        data: chart.data
                    }],
                    tooltip: {
                        theme: isDark ? 'dark' : 'light'
                    },
                    grid: {
                        padding: {
                            top: -20,
                            right: 0,
                            left: -4,
                            bottom: -4
                        },
                        strokeDashArray: 4
                    },
                    xaxis: {
                        labels: {
                            padding: 0,
                            style: {
                                fontFamily: 'Figtree, sans-serif',
                                fontWeight: 600
                            }
                        },
                        tooltip: {
                            enabled: false
                        },
                        axisBorder: {
                            show: false
                        },
                        categories: [
                            `{{ __('Jan') }}`, `{{ __('Feb') }}`,
                            `{{ __('Mar') }}`,
                            `{{ __('Apr') }}`, `{{ __('May') }}`,
                            `{{ __('Jun') }}`,
                            `{{ __('July') }}`, `{{ __('Aug') }}`,
                            `{{ __('Sept') }}`,
                            `{{ __('Oct') }}`, `{{ __('Nov') }}`,
                            `{{ __('Dec') }}`
                        ]
                    },
                    yaxis: {
                        labels: {
                            padding: 4,
                            style: {
                                fontFamily: 'Figtree, sans-serif',
                                fontWeight: 600
                            }
                        }
                    },
                    colors: ['#0059eb'],
                    legend: {
                        show: false
                    }
                }).render();
            });
        });
    </script>
@endsection
@endsection
