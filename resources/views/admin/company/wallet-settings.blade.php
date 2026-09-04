@extends('layouts.app')

@section('title')
    Company API Rate Settings
@endsection

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
                    <li class="breadcrumb-item"><a href="{{ route('company.index') }}">Company</a></li>
                    <li class="breadcrumb-item active" aria-current="page">API Rate Settings</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                @can('Wallet Management Show')
                    <a class="btn btn-grd-success text-light px-4" href="{{ route('company.wallet.show', $company->id) }}">
                        <i class="bx bx-wallet"></i> Wallet
                    </a>
                @endcan
                <a class="btn btn-primary px-4" href="{{ route('company.index') }}">
                    <i class="fadeIn animated bx bx-arrow-back"></i>Back
                </a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card mt-4">
        <div class="card-header text-center">
            <span class="fw-bold">API Rate Settings &mdash; {{ $company->name }}</span>
        </div>
        <div class="card-body">
            <p class="text-muted small mb-3">
                Enable the document-verification APIs this company can use, and set the amount deducted from their
                wallet on every successful call. A disabled or unconfigured API is blocked before BankU is ever
                contacted.
            </p>

            <form class="needs-validation" action="{{ route('company.walletSettings.update', $company->id) }}"
                method="post" novalidate>
                @csrf

                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width:45%;">API</th>
                                <th style="width:25%;">Amount</th>
                                <th style="width:20%;">Enabled</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (\App\Models\CompanyApiRate::API_TYPES as $key => $label)
                                @php
                                    $rate = $rates->get($key);
                                    $amount = old("rates.$key.amount", $rate->amount ?? 0);
                                    $enabled = old("rates.$key.enabled", $rate->is_enabled ?? false);
                                @endphp
                                <tr>
                                    <td class="fw-semibold">{{ $label }}</td>
                                    <td>
                                        <input type="number" step="0.01" min="0" max="9999.99"
                                            class="form-control" name="rates[{{ $key }}][amount]"
                                            value="{{ $amount }}" required>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input type="hidden" name="rates[{{ $key }}][enabled]" value="0">
                                            <input type="checkbox" class="form-check-input"
                                                name="rates[{{ $key }}][enabled]" value="1"
                                                {{ $enabled ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @can('Wallet Settings Edit')
                    <div class="d-md-flex d-grid align-items-center gap-3 mt-3">
                        <button type="submit" class="btn btn-grd-primary px-4 text-light">Save</button>
                    </div>
                @endcan
            </form>
        </div>
    </div>
@endsection
