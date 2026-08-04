@extends('admin.layouts.app')

@section('content')
    {{-- Page Content --}}
    <div class="page-wrapper">
        <div class="container-fluid mt-3">

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
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="container-fluid">
                {{-- Message --}}
                @if (session()->has('message'))
                    <div class="alert alert-important alert-success alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div>
                                <!-- Download SVG icon from http://tabler-icons.io/i/info-circle -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>
                                    <path d="M12 9h.01"></path>
                                    <path d="M11 12h1v4h1"></path>
                                </svg>
                            </div>
                            <div>
                                {!! session('message') !!}
                            </div>
                        </div>
                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                    @php
                        session()->forget('message');
                    @endphp
                @endif

                <div class="row row-deck row-cards mb-5">
                    {{-- This Month Income --}}
                    <div class="col-sm-6 col-md-3">
                        <div class="card bg-custom1">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="subheader text-custom">{{ __('This Month Income') }}</div>
                                </div>
                                <div class="h1 text-custom">{{ currency($this_month_income) }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Today Income --}}
                    <div class="col-sm-6 col-md-3">
                        <div class="card bg-custom2">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="subheader text-custom">{{ __('Today Income') }}</div>
                                </div>
                                <div class="h1 text-custom">{{ currency($today_income) }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Overall Users --}}
                    <div class="col-sm-6 col-md-3">
                        <div class="card bg-custom3">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="subheader text-custom">{{ __('Overall Users') }}</div>
                                </div>
                                <div class="h1 text-custom">{{ $overall_users }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Today Users --}}
                    <div class="col-sm-6 col-md-3">
                        <div class="card bg-custom4">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="subheader text-custom">{{ __('Today Users') }}</div>
                                </div>
                                <div class="h1 text-custom">{{ $today_users }}</div>
                            </div>
                        </div>
                    </div>

                    {{--  Sales Chart --}}
                    <div class="col-sm-6 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <div class="row">
                                        <div class="col-9">
                                            <h3>{{ __('Total Sales Overview') }}</h3>
                                        </div>
                                    </div>

                                    <canvas id="sales"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Users Chart --}}
                    <div class="col-sm-6 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <div class="row">
                                        <div class="col-9">
                                            <h3>{{ __('New Users Overview') }}</h3>
                                        </div>
                                    </div>

                                    <canvas id="users"></canvas>
                                </div>
                            </div>
                        </div>
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
        "use strict";

        // Get text color based on body attribute
        function getTextColor() {
            const theme = document.body.getAttribute('data-bs-theme');
            return theme === 'dark' ? '#ffffff' : '#182433';
        }

        // Labels for months
        const labels = [
            `{{ __('Jan') }}`, `{{ __('Feb') }}`, `{{ __('Mar') }}`,
            `{{ __('Apr') }}`, `{{ __('May') }}`, `{{ __('Jun') }}`,
            `{{ __('Jul') }}`, `{{ __('Aug') }}`, `{{ __('Sep') }}`,
            `{{ __('Oct') }}`, `{{ __('Nov') }}`, `{{ __('Dec') }}`
        ];

        // Data arrays from Blade
        const salesNumbers = [{{ $monthIncome }}];
        const usersNumbers = [{{ $monthUsers }}];

        // Common chart options generator
        function getChartOptions(textColor) {
            return {
                responsive: true,
                animation: {
                    duration: 1200,
                    easing: 'easeOutQuart'
                },
                animations: {
                    x: {
                        duration: 1200,
                        easing: 'easeOutQuart'
                    },
                    y: {
                        duration: 1200,
                        easing: 'easeOutQuart'
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                family: "Figtree, sans-serif",
                                weight: 600
                            },
                            color: textColor
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: textColor,
                            font: {
                                family: "Figtree, sans-serif",
                                weight: 600
                            }
                        },
                        grid: {
                            color: textColor === '#ffffff' ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: textColor,
                            font: {
                                family: "Figtree, sans-serif",
                                weight: 600
                            }
                        },
                        grid: {
                            color: textColor === '#ffffff' ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)'
                        }
                    }
                }
            };
        }

        // Sales chart
        const salesChart = new Chart(document.getElementById('sales'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: `{{ __('Total Sales') }}`,
                    data: salesNumbers,
                    fill: false,
                    tension: 0.35,
                    pointRadius: 3,
                    borderWidth: 2,
                    backgroundColor: 'rgb(246,149,69)',
                    borderColor: 'rgb(246,149,69)'
                }]
            },
            options: getChartOptions(getTextColor())
        });

        // Users chart
        const usersChart = new Chart(document.getElementById('users'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: `{{ __('New Users') }}`,
                    data: usersNumbers,
                    fill: false,
                    tension: 0.35,
                    pointRadius: 3,
                    borderWidth: 2,
                    backgroundColor: 'rgb(69,149,246)',
                    borderColor: 'rgb(69,149,246)'
                }]
            },
            options: getChartOptions(getTextColor())
        });

        // Force animation
        setTimeout(() => {
            [salesChart, usersChart].forEach(chart => {
                if (typeof chart.reset === 'function') chart.reset();
                chart.update();
            });
        }, 50);

        // Watch for theme changes via data-bs-theme
        const observer = new MutationObserver(() => {
            const newColor = getTextColor();
            [salesChart, usersChart].forEach(chart => {
                chart.options.plugins.legend.labels.color = newColor;
                chart.options.scales.x.ticks.color = newColor;
                chart.options.scales.y.ticks.color = newColor;
                chart.options.scales.x.grid.color = newColor === '#ffffff' ? 'rgba(255,255,255,0.1)' :
                    'rgba(0,0,0,0.1)';
                chart.options.scales.y.grid.color = newColor === '#ffffff' ? 'rgba(255,255,255,0.1)' :
                    'rgba(0,0,0,0.1)';
                chart.update();
            });
        });

        // Observe changes to body attribute
        observer.observe(document.body, {
            attributes: true,
            attributeFilter: ['data-bs-theme']
        });
    </script>
@endsection
@endsection
