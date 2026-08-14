@extends('layouts.app')

@section('title', 'Dispatch Details Report')

@section('css')
<style>
    .dispatch-report .panel-card {
        border: 0;
        border-radius: 16px;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
    }

    .dispatch-report .filter-toolbar {
        background: #f8fafc;
        border: 1px solid #eef2f7;
        border-radius: 14px;
        padding: 16px;
    }

    .dispatch-report .filter-toolbar label {
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #94a3b8;
        margin-bottom: 4px;
    }

    .dispatch-report .filter-toolbar .form-control {
        border-radius: 10px;
        border-color: #e2e8f0;
    }

    .dispatch-report .btn-filter {
        border-radius: 10px;
        font-weight: 600;
        padding: 8px 20px;
    }

    .dispatch-report table.premium-table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .dispatch-report table.premium-table thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .04em;
        border-bottom: 1px solid #eef2f7;
        white-space: nowrap;
    }

    .dispatch-report table.premium-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .dispatch-report table.premium-table td {
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        white-space: nowrap;
    }

    .dispatch-report .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
    }

    .dispatch-report .status-pill.pill-success { background: #dcfce7; color: #15803d; }
    .dispatch-report .status-pill.pill-danger  { background: #fee2e2; color: #b91c1c; }
    .dispatch-report .status-pill.pill-primary { background: #dbeafe; color: #1d4ed8; }
    .dispatch-report .status-pill.pill-warning { background: #fef9c3; color: #a16207; }
    .dispatch-report .status-pill.pill-dark { background: #1e293b; color: #f1f5f9; }

    .dispatch-report .bill-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: #eef2ff;
        color: #4f46e5;
    }

    .dispatch-report .bill-link:hover {
        background: #4f46e5;
        color: #fff;
    }

    .dispatch-report .empty-state {
        padding: 50px 20px;
        text-align: center;
        color: #94a3b8;
    }

    .dispatch-report .empty-state i {
        font-size: 46px;
        color: #cbd5e1;
        margin-bottom: 10px;
        display: block;
    }
</style>
@endsection

@section('content')
<div class="dispatch-report">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Dispatch Details</div>
        <div class="ps-3">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Dispatch Details</li>
            </ol>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card panel-card">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.dispatchDetails') }}" class="filter-toolbar row g-3 align-items-end mb-4">
                <div class="col-md-3">
                    <label><i class='bx bx-calendar'></i> From Date</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label><i class='bx bx-calendar'></i> To Date</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label><i class='bx bx-search'></i> Search (Invoice / Shop / Vehicle / Driver)</label>
                    <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Search...">
                </div>
                <div class="col-md-2 text-end">
                    <a href="{{ route('reports.dispatchDetails') }}" class="btn btn-outline-secondary btn-filter">Reset</a>
                    <button type="submit" class="btn btn-primary btn-filter">Filter</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table premium-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Order Date &amp; Time</th>
                            <th>Invoice No.</th>
                            <th>From Location</th>
                            <th>To Location</th>
                            <th>Route</th>
                            <th>District</th>
                            <th>Distance</th>
                            <th>Vehicle Number</th>
                            <th>Driver Number</th>
                            <th>Materials</th>
                            <th>Unit</th>
                            <th>Pmt Mode</th>
                            <th>Amount</th>
                            <th>Delivery Status</th>
                            <th>Delivery Date &amp; Time</th>
                            <th>Delay</th>
                            <th>Bill</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row['order_datetime'] }}</td>
                                <td>{{ $row['invoice_no'] }}</td>
                                <td>{{ $row['from_location'] }}</td>
                                <td>{{ $row['to_location'] }}</td>
                                <td>{{ $row['route'] }}</td>
                                <td>{{ $row['district'] }}</td>
                                <td>{{ $row['distance'] }}</td>
                                <td>{{ $row['vehicle_number'] }}</td>
                                <td>{{ $row['driver_number'] }}</td>
                                <td>{{ $row['material'] }}</td>
                                <td>{{ $row['unit'] }}</td>
                                <td>{{ $row['payment_mode'] }}</td>
                                <td>{{ $row['amount'] }}</td>
                                <td>
                                    @if ($row['delivery_status'] === 'Completed')
                                        <span class="status-pill pill-primary"><i class='bx bx-package'></i> Completed</span>
                                    @elseif ($row['delivery_status'] === 'Rejected')
                                        <span class="status-pill pill-danger"><i class='bx bx-x-circle'></i> Rejected</span>
                                    @elseif ($row['delivery_status'] === 'Live')
                                        <span class="status-pill pill-success"><i class='bx bx-check-circle'></i> Live</span>
                                    @elseif ($row['delivery_status'] === 'Expired')
                                        <span class="status-pill pill-dark"><i class='bx bx-history'></i> Expired</span>
                                    @else
                                        <span class="status-pill pill-warning"><i class='bx bx-time-five'></i> Pending</span>
                                    @endif
                                </td>
                                <td>{{ $row['delivered_at'] }}</td>
                                <td>{{ $row['delay'] }}</td>
                                <td>
                                    <a class="bill-link" title="View Bill"
                                        href="{{ route('delivery.invoice', [$row['delivery_schedule_id'], $row['shop_id']]) }}"
                                        target="_blank">
                                        <i class='bx bx-receipt'></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="18">
                                    <div class="empty-state">
                                        <i class='bx bx-search-alt'></i>
                                        No dispatch records found for the selected filters.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
