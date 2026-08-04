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
                        {{ __('WHOIS lookup') }}
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row row-deck row-cards">
                {{-- Search Whois Lookup --}}
                <div class="col-sm-12 col-lg-12">
                    <form action="{{ route('user.result.whois-lookup') }}" method="post" class="card">
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
                                                <a href="{{ route('user.whois-lookup') }}" class="btn btn-dark">
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


                {{-- Result --}}
                @if(isset($result))
                <div class="col-xl-10 col-md-10 card border-0 shadow-sm mt-3">
                    <div class="card-header align-items-center">
                        <div class="row">
                            <div class="col">
                                <div class="font-weight-medium py-1">{{ __('Domain name details') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if(empty($result))
                        {{ __('No domain details found.') }}
                        @else
                        <div class="list-group list-group-flush my-n3">
                            <div class="list-group-item px-0">
                                <div class="row align-items-center">
                                    <div class="col-12 col-lg-4 text-break text-muted">{{ __('Domain Name') }}</div>
                                    <div class="col-12 col-lg-8 text-break d-flex align-items-center">
                                        <img class="avatar"
                                            src="https://icons.duckduckgo.com/ip3/{{ $result->domainName }}.ico"
                                            rel="noreferrer" class="rounded">
                                        <span>{{ $result->domainName }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="list-group-item px-0">
                                <div class="row align-items-center">
                                    <div class="col-12 col-lg-4 text-break text-muted">{{ __('Registrar Name') }}</div>
                                    <div class="col-12 col-lg-8 text-break">{{ $result->registrar }}</div>
                                </div>
                            </div>

                            @if($result->owner)
                            <div class="list-group-item px-0">
                                <div class="row align-items-center">
                                    <div class="col-12 col-lg-4 text-break text-muted">{{ __('Registrant Name') }}</div>
                                    <div class="col-12 col-lg-8 text-break">{{ $result->owner }}</div>
                                </div>
                            </div>
                            @endif

                            <div class="list-group-item px-0">
                                <div class="row align-items-center">
                                    <div class="col-12 col-lg-4 text-break text-muted">{{ __('Created date') }}</div>
                                    <div class="col-12 col-lg-8 text-break">
                                        {{ __(':date at :time (UTC :offset)', ['date' =>
                                        \Carbon\Carbon::createFromTimestamp($result->creationDate)->tz(Auth::user()->timezone
                                        ?? config('app.timezone'))->format(__('Y-m-d')), 'time' =>
                                        \Carbon\Carbon::createFromTimestamp($result->creationDate)->tz(Auth::user()->timezone
                                        ?? config('app.timezone'))->format(__('H:i:s')), 'offset' =>
                                        \Carbon\CarbonTimeZone::create((Auth::user()->timezone ??
                                        config('app.timezone')))->toOffsetName()]) }}
                                    </div>
                                </div>
                            </div>

                            <div class="list-group-item px-0">
                                <div class="row align-items-center">
                                    <div class="col-12 col-lg-4 text-break text-muted">{{ __('Updated date') }}</div>
                                    <div class="col-12 col-lg-8 text-break">
                                        {{ __(':date at :time (UTC :offset)', ['date' =>
                                        \Carbon\Carbon::createFromTimestamp($result->updatedDate)->tz(Auth::user()->timezone
                                        ?? config('app.timezone'))->format(__('Y-m-d')), 'time' =>
                                        \Carbon\Carbon::createFromTimestamp($result->updatedDate)->tz(Auth::user()->timezone
                                        ?? config('app.timezone'))->format(__('H:i:s')), 'offset' =>
                                        \Carbon\CarbonTimeZone::create((Auth::user()->timezone ??
                                        config('app.timezone')))->toOffsetName()]) }}
                                    </div>
                                </div>
                            </div>

                            <div class="list-group-item px-0">
                                <div class="row align-items-center">
                                    <div class="col-12 col-lg-4 text-break text-muted">{{ __('Expiration date') }}</div>
                                    <div class="col-12 col-lg-8 text-break">
                                        {{ __(':date at :time (UTC :offset)', ['date' =>
                                        \Carbon\Carbon::createFromTimestamp($result->expirationDate)->tz(Auth::user()->timezone
                                        ?? config('app.timezone'))->format(__('Y-m-d')), 'time' =>
                                        \Carbon\Carbon::createFromTimestamp($result->expirationDate)->tz(Auth::user()->timezone
                                        ?? config('app.timezone'))->format(__('H:i:s')), 'offset' =>
                                        \Carbon\CarbonTimeZone::create((Auth::user()->timezone ??
                                        config('app.timezone')))->toOffsetName()]) }}
                                    </div>
                                </div>
                            </div>

                            <div class="list-group-item px-0">
                                <div class="row align-items-center">
                                    <div class="col-12 col-lg-4 text-break text-muted">{{ __('Name servers') }}</div>
                                    <div class="col-12 col-lg-8 text-break">
                                        @foreach($result->nameServers as $nameServer)
                                        <div class="text-break {{ !$loop->first ? 'mt-1' : '' }}">
                                            {{ $nameServer }}
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="list-group-item px-0">
                                <div class="row align-items-center">
                                    <div class="col-12 col-lg-4 text-break text-muted">{{ __('State') }}</div>
                                    <div class="col-12 col-lg-8 text-break">
                                        @foreach($result->states as $state)
                                        <div class="text-break {{ !$loop->first ? 'mt-1' : '' }}">
                                            {{ $state }}
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            @if($result->whoisServer)
                            <div class="list-group-item px-0">
                                <div class="row align-items-center">
                                    <div class="col-12 col-lg-4 text-break text-muted">{{ __('WHOIS server details') }}</div>
                                    <div class="col-12 col-lg-8 text-break">{{ $result->whoisServer }}</div>
                                </div>
                            </div>
                            @endif
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