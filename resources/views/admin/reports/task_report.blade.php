@extends('layouts.app')

@section('title', 'Task Report')

@section('css')
<style>
    .task-report .kpi-card {
        position: relative;
        border: 0;
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .task-report .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 26px rgba(15, 23, 42, 0.1);
    }

    .task-report .kpi-card.is-active {
        box-shadow: 0 0 0 2px var(--kpi-accent, #4f46e5), 0 12px 26px rgba(15, 23, 42, 0.1);
    }

    .task-report .kpi-card .kpi-bar {
        position: absolute;
        inset: 0 auto 0 0;
        width: 4px;
        background: var(--kpi-accent, #4f46e5);
    }

    .task-report .kpi-card .card-body {
        padding: 18px 18px 18px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .task-report .kpi-icon {
        width: 46px;
        height: 46px;
        min-width: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        background: color-mix(in srgb, var(--kpi-accent, #4f46e5) 12%, #fff);
        color: var(--kpi-accent, #4f46e5);
    }

    .task-report .kpi-label {
        font-size: 12.5px;
        font-weight: 600;
        letter-spacing: .03em;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: 4px;
    }

    .task-report .kpi-value {
        font-size: 26px;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.1;
    }

    .task-report .kpi-sub {
        font-size: 12px;
        color: #94a3b8;
        margin-top: 4px;
    }

    .task-report .panel-card {
        border: 0;
        border-radius: 16px;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
    }

    .task-report .filter-toolbar {
        background: #f8fafc;
        border: 1px solid #eef2f7;
        border-radius: 14px;
        padding: 16px;
    }

    .task-report .filter-toolbar label {
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #94a3b8;
        margin-bottom: 4px;
    }

    .task-report .filter-toolbar .form-control,
    .task-report .filter-toolbar .form-select {
        border-radius: 10px;
        border-color: #e2e8f0;
    }

    .task-report .btn-filter {
        border-radius: 10px;
        font-weight: 600;
        padding: 8px 20px;
    }

    .task-report table.premium-table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .task-report table.premium-table thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .04em;
        border-bottom: 1px solid #eef2f7;
        white-space: nowrap;
    }

    .task-report table.premium-table tbody tr {
        transition: background-color .12s ease;
    }

    .task-report table.premium-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .task-report table.premium-table td {
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .task-report .shop-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #eef2ff;
        color: #4f46e5;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
        margin-right: 8px;
    }

    .task-report .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
    }

    .task-report .status-pill.pill-success { background: #dcfce7; color: #15803d; }
    .task-report .status-pill.pill-danger  { background: #fee2e2; color: #b91c1c; }
    .task-report .status-pill.pill-primary { background: #dbeafe; color: #1d4ed8; }
    .task-report .status-pill.pill-warning { background: #fef9c3; color: #a16207; }
    .task-report .status-pill.pill-secondary { background: #e2e8f0; color: #475569; }
    .task-report .status-pill.pill-dark { background: #1e293b; color: #f1f5f9; }

    .task-report .lr-code {
        font-family: 'Courier New', monospace;
        font-size: 12.5px;
        color: #64748b;
        background: #f1f5f9;
        padding: 2px 8px;
        border-radius: 6px;
    }

    .task-report .empty-state {
        padding: 50px 20px;
        text-align: center;
        color: #94a3b8;
    }

    .task-report .empty-state i {
        font-size: 46px;
        color: #cbd5e1;
        margin-bottom: 10px;
        display: block;
    }
</style>
@endsection

@section('content')
<div class="task-report">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Task Report</div>
        <div class="ps-3">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Task Report</li>
            </ol>
        </div>
    </div>
    <!--end breadcrumb-->

    @php
        $totalTasks = array_sum($counts) ?: 1;
        $kpis = [
            'accepted'  => ['label' => 'Accepted',  'icon' => 'bx-check-circle', 'accent' => '#16a34a'],
            'rejected'  => ['label' => 'Rejected',  'icon' => 'bx-x-circle',     'accent' => '#dc2626'],
            'delivered' => ['label' => 'Delivered', 'icon' => 'bx-package',      'accent' => '#2563eb'],
            'pending'   => ['label' => 'Pending',   'icon' => 'bx-time-five',    'accent' => '#d97706'],
            'cancelled' => ['label' => 'Cancelled', 'icon' => 'bx-block',        'accent' => '#64748b'],
        ];
    @endphp

    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3 mb-3">
        @foreach ($kpis as $key => $kpi)
            <div class="col">
                <a href="{{ route('reports.taskReport', array_merge(request()->except(['status','page']), ['status' => $key])) }}" class="text-decoration-none">
                    <div class="kpi-card {{ $status === $key ? 'is-active' : '' }}" style="--kpi-accent: {{ $kpi['accent'] }};">
                        <span class="kpi-bar"></span>
                        <div class="card-body">
                            <div>
                                <div class="kpi-label">{{ $kpi['label'] }}</div>
                                <div class="kpi-value">{{ $counts[$key] }}</div>
                                <div class="kpi-sub">{{ number_format(($counts[$key] / $totalTasks) * 100, 1) }}% of total</div>
                            </div>
                            <div class="kpi-icon"><i class='bx {{ $kpi['icon'] }}'></i></div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-8">
            <div class="card panel-card h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Status Distribution</h6>
                    <div id="taskStatusChart" style="min-height: 260px;"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card panel-card h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <h6 class="fw-bold mb-3">Acceptance Rate</h6>
                    @php
                        $decided = $counts['accepted'] + $counts['rejected'];
                        $acceptanceRate = $decided > 0 ? round(($counts['accepted'] / $decided) * 100, 1) : 0;
                    @endphp
                    <div class="text-center">
                        <div class="kpi-value" style="font-size:38px;">{{ $acceptanceRate }}%</div>
                        <div class="kpi-sub mb-3">of decided tasks were accepted</div>
                        <div class="progress" style="height:10px; border-radius:999px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $acceptanceRate }}%; border-radius:999px;"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span class="kpi-sub">{{ $counts['accepted'] }} accepted</span>
                            <span class="kpi-sub">{{ $counts['rejected'] }} rejected</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card panel-card">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.taskReport') }}" class="filter-toolbar row g-3 align-items-end mb-4">
                <div class="col-md-3">
                    <label><i class='bx bx-flag'></i> Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="accepted" {{ $status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="delivered" {{ $status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="expired" {{ $status === 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label><i class='bx bx-calendar'></i> From Date</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <label><i class='bx bx-calendar'></i> To Date</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label><i class='bx bx-search'></i> Search (Shop / Driver)</label>
                    <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Search...">
                </div>
                <div class="col-md-2 text-end">
                    <a href="{{ route('reports.taskReport') }}" class="btn btn-outline-secondary btn-filter">Reset</a>
                    <button type="submit" class="btn btn-primary btn-filter">Filter</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table premium-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>SL No.</th>
                            <th>LR No.</th>
                            <th>Shop</th>
                            <th>Driver</th>
                            <th>Vehicle</th>
                            <th>Delivery Date</th>
                            <th>Status</th>
                            <th>Accepted At</th>
                            <th>Delivered At</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports as $report)
                            <tr>
                                <td>{{ $loop->iteration + ($reports->currentPage() - 1) * $reports->perPage() }}</td>
                                <td><span class="lr-code">{{ $report->lr_no }}</span></td>
                                <td>
                                    <span class="shop-avatar">{{ strtoupper(substr($report->shop->shop_name ?? '?', 0, 1)) }}</span>
                                    {{ $report->shop->shop_name ?? 'N/A' }}
                                </td>
                                <td>{{ $report->deliverySchedule->driver->name ?? 'N/A' }}</td>
                                <td>{{ $report->deliverySchedule->vehicle->vehicle_number ?? 'N/A' }}</td>
                                <td>{{ $report->deliverySchedule->delivery_date ?? 'N/A' }}</td>
                                <td>
                                    @if ($report->is_delivered)
                                        <span class="status-pill pill-primary"><i class='bx bx-package'></i> Delivered</span>
                                    @elseif ($report->is_cancelled)
                                        <span class="status-pill pill-secondary"><i class='bx bx-block'></i> Cancelled</span>
                                    @elseif ($report->status === 'accepted')
                                        <span class="status-pill pill-success"><i class='bx bx-check-circle'></i> Accepted</span>
                                    @elseif ($report->status === 'rejected')
                                        <span class="status-pill pill-danger"><i class='bx bx-x-circle'></i> Rejected</span>
                                    @elseif ($report->status === 'expired')
                                        <span class="status-pill pill-dark"><i class='bx bx-history'></i> Expired</span>
                                    @else
                                        <span class="status-pill pill-warning"><i class='bx bx-time-five'></i> Pending</span>
                                    @endif
                                </td>
                                <td>{{ $report->accepted_at ? format_datetime($report->accepted_at) : '-' }}</td>
                                <td>{{ $report->delivered_at ? format_datetime($report->delivered_at) : '-' }}</td>
                                <td>{{ $report->amount ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10">
                                    <div class="empty-state">
                                        <i class='bx bx-search-alt'></i>
                                        No tasks found for the selected filters.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var chartEl = document.querySelector('#taskStatusChart');
        if (chartEl && window.ApexCharts) {
            var options = {
                chart: { type: 'donut', height: 260 },
                series: [
                    {{ $counts['accepted'] }},
                    {{ $counts['rejected'] }},
                    {{ $counts['delivered'] }},
                    {{ $counts['pending'] }},
                    {{ $counts['cancelled'] }}
                ],
                labels: ['Accepted', 'Rejected', 'Delivered', 'Pending', 'Cancelled'],
                colors: ['#16a34a', '#dc2626', '#2563eb', '#d97706', '#64748b'],
                legend: { position: 'bottom' },
                dataLabels: { enabled: true },
                stroke: { width: 2 },
            };
            new ApexCharts(chartEl, options).render();
        }
    });
</script>
@endsection
