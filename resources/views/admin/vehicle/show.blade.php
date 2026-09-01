@extends('layouts.app')

@section('title')
    Vehicle Details
@endsection

@section('content')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Vehicle</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('vehicle.index') }}">Vehicle</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $data->vehicle_number }}</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                @can('Vehicle Edit')
                    <a class="btn btn-grd-primary text-light px-4" href="{{ route('vehicle.edit', $data->id) }}">
                        <i class="bx bx-edit"></i> Edit
                    </a>
                @endcan
                <a class="btn btn-primary px-4" href="{{ route('vehicle.index') }}">
                    <i class="fadeIn animated bx bx-arrow-back"></i>Back
                </a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-bold">Vehicle Registration Details</span>
            @if ($data->rc_verified_at)
                <span class="badge bg-success text-light">
                    <i class="bx bx-check-shield"></i> RC Verified &middot;
                    {{ $data->rc_verified_at->format('d M Y') }}
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
                            $rows = ['Vehicle RC Number' => $data->vehicle_number];
                            foreach (\App\Models\Vehicle::RC_FIELDS as $key => $label) {
                                if ($key === 'is_commercial') {
                                    $rows[$label] = is_null($data->is_commercial)
                                        ? null
                                        : ($data->is_commercial ? 'TRUE = YES' : 'FALSE = NO');
                                } else {
                                    $rows[$label] = $data->{$key};
                                }
                            }
                        @endphp
                        @foreach ($rows as $label => $value)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-uppercase text-muted">{{ $label }}</td>
                                <td class="fw-semibold">{{ ($value === null || $value === '') ? '—' : $value }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>{{ count($rows) + 1 }}</td>
                            <td class="text-uppercase text-muted">Status</td>
                            <td>{!! check_status($data->is_visible) !!}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @if (!empty($data->rc_verification_data))
                <details class="mt-4">
                    <summary class="text-muted" style="cursor:pointer;">Raw BankU RC response</summary>
                    <pre class="bg-light-subtle border rounded p-3 mt-2" style="max-height:400px;overflow:auto;">{{ json_encode($data->rc_verification_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                </details>
            @endif
        </div>
    </div>
@endsection
