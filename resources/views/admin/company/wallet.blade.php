@extends('layouts.app')

@section('title')
    Company Wallet
@endsection

@php
    $selfView = $selfView ?? false;
    $backRoute = $selfView ? route('dashboard') : route('company.index');
@endphp

@section('content')

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Company</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    @unless ($selfView)
                        <li class="breadcrumb-item"><a href="{{ route('company.index') }}">Company</a></li>
                    @endunless
                    <li class="breadcrumb-item active" aria-current="page">Wallet</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                @can('Wallet Settings Show')
                    @unless ($selfView)
                        <a class="btn btn-grd-info text-light px-4"
                            href="{{ route('company.walletSettings.edit', $company->id) }}">
                            <i class="bx bx-cog"></i> API Rate Settings
                        </a>
                    @endunless
                @endcan
                <a class="btn btn-primary px-4" href="{{ $backRoute }}">
                    <i class="fadeIn animated bx bx-arrow-back"></i>Back
                </a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="row">
        <div class="col-lg-4">
            <div class="card mt-4">
                <div class="card-header text-center"><span class="fw-bold">{{ $company->name }}</span></div>
                <div class="card-body text-center">
                    <p class="text-muted mb-1">Wallet Balance</p>
                    <h2 class="mb-3">&#8377;{{ number_format((float) $wallet->balance, 2) }}</h2>
                </div>
            </div>

            @can('Wallet Management Edit')
                <div class="card mt-4">
                    <div class="card-header text-center"><span class="fw-bold">Top Up Wallet</span></div>
                    <div class="card-body">
                        <form class="needs-validation" action="{{ route('company.wallet.topUp', $company->id) }}"
                            method="post" novalidate>
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Amount <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0.01" class="form-control" name="amount"
                                    value="{{ old('amount') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Note <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="note"
                                    placeholder="e.g. Manual recharge" value="{{ old('note') }}" required>
                            </div>
                            <button type="submit" class="btn btn-grd-primary px-4 text-light w-100">Add Balance</button>
                        </form>
                    </div>
                </div>
            @endcan
        </div>

        <div class="col-lg-8">
            <div class="card mt-4">
                <div class="card-header text-center"><span class="fw-bold">Transaction History</span></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Balance After</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $transaction)
                                    <tr>
                                        <td>{{ format_datetime($transaction->created_at) }}</td>
                                        <td>
                                            @if ($transaction->type === 'credit')
                                                <span class="badge bg-success text-light">Credit</span>
                                            @else
                                                <span class="badge bg-danger text-light">Debit</span>
                                            @endif
                                        </td>
                                        <td>&#8377;{{ number_format((float) $transaction->amount, 2) }}</td>
                                        <td>&#8377;{{ number_format((float) $transaction->balance_after, 2) }}</td>
                                        <td>{{ $transaction->description }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No transactions yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
