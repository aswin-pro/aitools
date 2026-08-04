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
                        {{ __('DNS lookup') }}
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row row-deck row-cards">
                {{-- Search DNS Lookup --}}
                <div class="col-sm-12 col-lg-12">
                    <form action="{{ route('user.result.dns-lookup') }}" method="post" class="card">
                        @csrf
                        <div class="card-body">

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

                            <div class="row">
                                <div class="col-xl-10">
                                    <div class="row">
                                        {{-- Domain --}}
                                        <div class="col-md-10 col-xl-10">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Domain') }}</label>
                                                <input type="url" class="form-control" name="domain"
                                                    value="{{ $domain ?? (old('domain') ?? '') }}"
                                                    placeholder="{{ __('Eg: domain.com') }}" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-xl-4 my-3">
                                            <div class="mb-3">
                                                <button type="submit" class="btn btn-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-search" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <circle cx="10" cy="10" r="7"></circle>
                                                        <line x1="21" y1="21" x2="15" y2="15"></line>
                                                    </svg>
                                                    {{ __('Search') }}
                                                </button>
                                                <a href="{{ route('user.dns-lookup') }}" class="btn btn-dark">
                                                    {{ __('Reset') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Domain DNS details --}}
                @if(isset($results))
                <div class="col-xl-10 col-md-10 card border-0 shadow-sm mt-3">
                    <div class="card-header align-items-center">
                        <div class="row">
                            <div class="col">
                                <div class="font-weight-medium py-1">{{ __('Domain DNS details') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if(empty($results))
                        {{ __('No domain details found.') }}
                        @else
                        <ul class="nav nav-pills d-flex flex-fill flex-column flex-md-row mb-3" id="pills-tab"
                            role="tablist">
                            <li class="nav-item flex-grow-1 text-center">
                                <a class="nav-link active" id="pills-dns-a" data-toggle="pill" href="#pills-dns-a"
                                    role="tab" aria-controls="pills-dns-a" aria-selected="true">{{ __('A') }}</a>
                            </li>
                            <li class="nav-item flex-grow-1 text-center">
                                <a class="nav-link" id="pills-dns-aaaa" data-toggle="pill" href="#pills-dns-aaaa"
                                    role="tab" aria-controls="pills-dns-aaaa" aria-selected="false">{{ __('AAAA') }}</a>
                            </li>
                            <li class="nav-item flex-grow-1 text-center">
                                <a class="nav-link" id="pills-dns-cname" data-toggle="pill" href="#pills-dns-cname"
                                    role="tab" aria-controls="pills-dns-cname" aria-selected="false">{{ __('CNAME')
                                    }}</a>
                            </li>
                            <li class="nav-item flex-grow-1 text-center">
                                <a class="nav-link" id="pills-dns-mx" data-toggle="pill" href="#pills-dns-mx"
                                    role="tab" aria-controls="pills-dns-mx" aria-selected="false">{{ __('MX') }}</a>
                            </li>
                            <li class="nav-item flex-grow-1 text-center">
                                <a class="nav-link" id="pills-dns-txt" data-toggle="pill" href="#pills-dns-txt"
                                    role="tab" aria-controls="pills-dns-txt" aria-selected="false">{{ __('TXT') }}</a>
                            </li>
                            <li class="nav-item flex-grow-1 text-center">
                                <a class="nav-link" id="pills-dns-ns" data-toggle="pill" href="#pills-dns-ns"
                                    role="tab" aria-controls="pills-dns-ns" aria-selected="false">{{ __('NS') }}</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-dns-a" role="tabpanel"
                                aria-labelledby="pills-dns-a">
                                <div class="list-group list-group-flush mb-n3">
                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center text-muted">
                                            <div class="col-12 col-lg-2 text-truncate">{{ __('Type') }}</div>
                                            <div class="col-12 col-lg-4 text-truncate">{{ __('Hostname') }}</div>
                                            <div class="col-12 col-lg-4 text-truncate">{{ __('IP') }}</div>
                                            <div class="col-12 col-lg-2 text-truncate">{{ __('TTL') }}</div>
                                        </div>
                                    </div>

                                    @foreach($results as $result)
                                    @if(strtolower($result['type']) == 'a')
                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center text-muted">
                                            <div class="col-12 col-lg-2 text-break">{{ $result['type'] }}</div>
                                            <div class="col-12 col-lg-4 text-break">{{ $result['host'] }}</div>
                                            <div class="col-12 col-lg-4 text-break">{{ $result['ip'] }}</div>
                                            <div class="col-12 col-lg-2 text-break">{{ $result['ttl'] }}</div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pills-dns-aaaa" role="tabpanel"
                                aria-labelledby="pills-dns-aaaa">
                                <div class="list-group list-group-flush mb-n3">
                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center text-muted">
                                            <div class="col-12 col-lg-2 text-truncate">{{ __('Type') }}</div>
                                            <div class="col-12 col-lg-4 text-truncate">{{ __('Hostname') }}</div>
                                            <div class="col-12 col-lg-4 text-truncate">{{ __('IPv6') }}</div>
                                            <div class="col-12 col-lg-2 text-truncate">{{ __('TTL') }}</div>
                                        </div>
                                    </div>

                                    @foreach($results as $result)
                                    @if(strtolower($result['type']) == 'aaaa')
                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center text-muted">
                                            <div class="col-12 col-lg-2 text-break">{{ $result['type'] }}</div>
                                            <div class="col-12 col-lg-4 text-break">{{ $result['host'] }}</div>
                                            <div class="col-12 col-lg-4 text-break">{{ $result['ipv6'] }}</div>
                                            <div class="col-12 col-lg-2 text-break">{{ $result['ttl'] }}</div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pills-dns-cname" role="tabpanel"
                                aria-labelledby="pills-dns-cname">
                                <div class="list-group list-group-flush mb-n3">
                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center text-muted">
                                            <div class="col-12 col-lg-2 text-truncate">{{ __('Type') }}</div>
                                            <div class="col-12 col-lg-4 text-truncate">{{ __('Hostname') }}</div>
                                            <div class="col-12 col-lg-4 text-truncate">{{ __('Target') }}</div>
                                            <div class="col-12 col-lg-2 text-truncate">{{ __('TTL') }}</div>
                                        </div>
                                    </div>

                                    @foreach($results as $result)
                                    @if(strtolower($result['type']) == 'cname')
                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center text-muted">
                                            <div class="col-12 col-lg-2 text-break">{{ $result['type'] }}</div>
                                            <div class="col-12 col-lg-4 text-break">{{ $result['host'] }}</div>
                                            <div class="col-12 col-lg-4 text-break">{{ $result['target'] }}</div>
                                            <div class="col-12 col-lg-2 text-break">{{ $result['ttl'] }}</div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pills-dns-mx" role="tabpanel"
                                aria-labelledby="pills-dns-mx">
                                <div class="list-group list-group-flush mb-n3">
                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center text-muted">
                                            <div class="col-12 col-lg-2 text-truncate">{{ __('Type') }}</div>
                                            <div class="col-12 col-lg-3 text-truncate">{{ __('Hostname') }}</div>
                                            <div class="col-12 col-lg-3 text-truncate">{{ __('Target') }}</div>
                                            <div class="col-12 col-lg-2 text-truncate">{{ __('Priority') }}</div>
                                            <div class="col-12 col-lg-2 text-truncate">{{ __('TTL') }}</div>
                                        </div>
                                    </div>

                                    @foreach($results as $result)
                                    @if(strtolower($result['type']) == 'mx')
                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center text-muted">
                                            <div class="col-12 col-lg-2 text-break">{{ $result['type'] }}</div>
                                            <div class="col-12 col-lg-3 text-break">{{ $result['host'] }}</div>
                                            <div class="col-12 col-lg-3 text-break">{{ $result['target'] }}</div>
                                            <div class="col-12 col-lg-2 text-break">{{ $result['pri'] }}</div>
                                            <div class="col-12 col-lg-2 text-break">{{ $result['ttl'] }}</div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pills-dns-txt" role="tabpanel"
                                aria-labelledby="pills-dns-txt">
                                <div class="list-group list-group-flush mb-n3">
                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center text-muted">
                                            <div class="col-12 col-lg-2 text-truncate">{{ __('Type') }}</div>
                                            <div class="col-12 col-lg-4 text-truncate">{{ __('Hostname') }}</div>
                                            <div class="col-12 col-lg-4 text-truncate">{{ __('Entries') }}</div>
                                            <div class="col-12 col-lg-2 text-truncate">{{ __('TTL') }}</div>
                                        </div>
                                    </div>

                                    @foreach($results as $result)
                                    @if(strtolower($result['type']) == 'txt')
                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center text-muted">
                                            <div class="col-12 col-lg-2 text-break">{{ $result['type'] }}</div>
                                            <div class="col-12 col-lg-4 text-break">{{ $result['host'] }}</div>
                                            <div class="col-12 col-lg-4 text-break">
                                                @foreach($result['entries'] as $entry)
                                                <div class="text-break {{ !$loop->first ? 'mt-1' : '' }}">{{ $entry }}
                                                </div>
                                                @endforeach
                                            </div>
                                            <div class="col-12 col-lg-2 text-break">{{ $result['ttl'] }}</div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pills-dns-ns" role="tabpanel"
                                aria-labelledby="pills-dns-ns">
                                <div class="list-group list-group-flush mb-n3">
                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center text-muted">
                                            <div class="col-12 col-lg-2 text-truncate">{{ __('Type') }}</div>
                                            <div class="col-12 col-lg-4 text-truncate">{{ __('Hostname') }}</div>
                                            <div class="col-12 col-lg-4 text-truncate">{{ __('Target') }}</div>
                                            <div class="col-12 col-lg-2 text-truncate">{{ __('TTL') }}</div>
                                        </div>
                                    </div>

                                    @foreach($results as $result)
                                    @if(strtolower($result['type']) == 'ns')
                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center text-muted">
                                            <div class="col-12 col-lg-2 text-break">{{ $result['type'] }}</div>
                                            <div class="col-12 col-lg-4 text-break">{{ $result['host'] }}</div>
                                            <div class="col-12 col-lg-4 text-break">{{ $result['target'] }}</div>
                                            <div class="col-12 col-lg-2 text-break">{{ $result['ttl'] }}</div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Footer --}}
    @include('user.includes.footer')
</div>
@endsection