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
                        {{ __('Transactions') }}
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
                        <div class="table-responsive px-2 py-2">
                            <table class="table card-table table-vcenter text-nowrap datatable" id="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('#') }}</th>
                                        <th>{{ __('Transaction Date') }}</th>
                                        <th class="w-1">{{ __('Payment ID') }}</th>
                                        <th>{{ __('Trans ID') }}</th>
                                        <th>{{ __('Payment Mode') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ formatDateForUser($transaction->created_at) }}</td>
                                        <td><span>{{ $transaction->transaction_amount != 0.0 ? $transaction->transaction_id : '-' }}</span></td>
                                        <td>{{ $transaction->transaction_id }}</td>
                                        <td>
                                            {{ $transaction->payment_gateway_name }}
                                        </td>
                                        <td>
                                            {{ currency($transaction->transaction_amount) }}
                                        </td>
                                        <td>
                                            @if ($transaction->payment_status == 'SUCCESS')
                                            <span class="badge bg-green text-white">{{ __('Paid') }}</span>
                                            @endif
                                            @if ($transaction->payment_status == 'FAILED')
                                            <span class="badge bg-red text-white">{{ __('Failed') }}</span>
                                            @endif
                                            @if ($transaction->payment_status == 'PENDING')
                                            <span class="badge bg-yellow text-white">{{ __('Pending') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if ($transaction->invoice_number > 0)
                                            <span class="dropdown">
                                                <button class="btn small-btn dropdown-toggle align-text-top"
                                                    data-bs-boundary="viewport" data-bs-toggle="dropdown"
                                                    aria-expanded="false">{{ __('Actions') }}</button>
                                                <div class="dropdown-menu dropdown-menu-end" style="">
                                                    <a class="dropdown-item"
                                                        href="{{ route('user.view.invoice', ['id' => $transaction->id])}}">{{
                                                        __('Invoice') }}</a>
                                                </div>
                                            </span>
                                            @endif
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
@endsection