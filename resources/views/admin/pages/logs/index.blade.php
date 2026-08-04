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
                        {{ __('Logs') }}
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row row-deck row-cards">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">

                        {{-- Logs --}}
                        <div class="table-responsive px-2 py-2">
                            <table class="table table-vcenter card-table" id="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('#') }}</th>
                                        <th>{{ __('IP Address') }}</th>
                                        <th>{{ __('Browser') }}</th>
                                        {{-- <th>{{ __('Location') }}</th> --}}
                                        <th>{{ __('Login At') }}</th>
                                        <th>{{ __('Login Successful') }}</th>
                                        <th>{{ __('Logout at') }}</th>
                                        <th>{{ __('Cleared by user') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Logs --}}
                                    @foreach ($logs as $log)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $log->ip_address }}</td>

                                        <td><span class="badge bg-success">{{ $log->platform }} - {{ $log->browser
                                                }}</span></td>

                                        {{-- <td><a href="https://www.google.com/maps/place/{{ $log->location }}"
                                                target="_blank">{{ $log->location }}</a></td> --}}

                                        @if ($log->login_at == "")
                                        <td>{{ __('-')}}</td>
                                        @else
                                        <td>{{ date('d M Y h:i A', strtotime($log->login_at)) }}</td>
                                        @endif

                                        @if ($log->login_successful == '1')
                                        <td><span class="badge bg-success">{{ __('YES') }}</span></td>
                                        @else
                                        <td><span class="badge bg-danger">{{ __('NO') }}</span></td>
                                        @endif

                                        @if ($log->logout_at == "")
                                        <td>{{ __('-')}}</td>
                                        @else
                                        <td>{{ date('d M Y h:i A', strtotime($log->logout_at)) }}</td>
                                        @endif

                                        @if ($log->cleared_by_user == '1')
                                        <td><span class="badge bg-success">{{ __('YES') }}</span></td>
                                        @else
                                        <td><span class="badge bg-danger">{{ __('NO') }}</span></td>
                                        @endif
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
@endsection