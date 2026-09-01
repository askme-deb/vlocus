@extends('layouts.app')

@section('title')
    Driver Details
@endsection

@section('content')
    @php($user = $driver->user)

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Driver</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('driver.index') }}">Drivers</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ optional($user)->name ?: $driver->driving_license_number }}</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                @can('Driver Edit')
                    @if ($user)
                        <a class="btn btn-grd-primary text-light px-4" href="{{ route('driver.edit', $user->id) }}">
                            <i class="bx bx-edit"></i> Edit
                        </a>
                    @endif
                @endcan
                <a class="btn btn-primary px-4" href="{{ route('driver.index') }}">
                    <i class="fadeIn animated bx bx-arrow-back"></i>Back
                </a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="row">
        <div class="col-lg-4">
            <div class="card mt-4">
                <div class="card-header text-center"><span class="fw-bold">Driver</span></div>
                <div class="card-body text-center">
                    <img src="{{ optional($user)->getFirstMediaUrl('system-user-image') }}" alt="" width="110"
                        class="img-thumbnail rounded-circle mb-3">
                    <h6 class="mb-1">{{ optional($user)->name ?: '—' }}</h6>
                    <p class="text-muted mb-1">{{ optional($user)->email }}</p>
                    <p class="text-muted mb-2">{{ optional($user)->phone }}</p>
                    @if ($user)
                        {!! check_status($user->status) !!}
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold">DRIVING LICENSE DETAILS</span>
                    @if ($driver->driving_license_verified_at)
                        <span class="badge bg-success text-light">
                            <i class="bx bx-check-shield"></i> DL Verified &middot;
                            {{ $driver->driving_license_verified_at->format('d M Y') }}
                        </span>
                    @else
                        <span class="badge bg-secondary text-light">Manually entered</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="width:60px;">SL</th>
                                    <th style="width:35%;">Particulars</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $rows = ['D.L Number' => $driver->driving_license_number];
                                    foreach (\App\Models\Driver::DL_FIELDS as $key => $label) {
                                        $rows[$label] = $driver->{$key};
                                    }
                                @endphp
                                @foreach ($rows as $label => $value)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-uppercase text-muted">{{ $label }}</td>
                                        <td class="fw-semibold">{{ $value === null || $value === '' ? '—' : $value }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if (!empty($driver->driving_license_verification_data))
                        <details class="mt-4">
                            <summary class="text-muted" style="cursor:pointer;">Raw BankU DL response</summary>
                            <pre class="bg-light-subtle border rounded p-3 mt-2"
                                style="max-height:400px;overflow:auto;">{{ json_encode($driver->driving_license_verification_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                        </details>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header"><span class="fw-bold">Driver KYC</span></div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold">Aadhaar</span>
                        @if (optional($user)->aadhaar_verified_at)
                            <span class="badge bg-success text-light">
                                <i class="bx bx-check-shield"></i> Verified &middot;
                                {{ $user->aadhaar_verified_at->format('d M Y') }}
                            </span>
                        @else
                            <span class="badge bg-secondary text-light">Not verified</span>
                        @endif
                    </div>
                    <p class="text-muted mb-2">Number: <span class="fw-semibold">{{ optional($user)->aadhar_card_number ?: '—' }}</span></p>
                    @if (!empty(optional($user)->aadhaar_verification_data))
                        <details class="mt-2">
                            <summary class="text-muted" style="cursor:pointer;">Raw BankU Aadhaar response</summary>
                            <pre class="bg-light-subtle border rounded p-3 mt-2"
                                style="max-height:320px;overflow:auto;">{{ json_encode($user->aadhaar_verification_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                        </details>
                    @endif
                </div>
                <div class="col-lg-6">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold">PAN</span>
                        @if (optional($user)->pan_verified_at)
                            <span class="badge bg-success text-light">
                                <i class="bx bx-check-shield"></i> Verified &middot;
                                {{ $user->pan_verified_at->format('d M Y') }}
                            </span>
                        @else
                            <span class="badge bg-secondary text-light">Not verified</span>
                        @endif
                    </div>
                    <p class="text-muted mb-2">Number: <span class="fw-semibold">{{ optional($user)->pan_card_number ?: '—' }}</span></p>
                    @if (!empty(optional($user)->pan_verification_data))
                        <details class="mt-2">
                            <summary class="text-muted" style="cursor:pointer;">Raw BankU PAN response</summary>
                            <pre class="bg-light-subtle border rounded p-3 mt-2"
                                style="max-height:320px;overflow:auto;">{{ json_encode($user->pan_verification_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                        </details>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
