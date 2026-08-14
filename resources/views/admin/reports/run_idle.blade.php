@extends('layouts.app')

@section('title', 'Run & Idle Report')

@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Run & Idle Report</div>
    <div class="ps-3">
        <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
            <li class="breadcrumb-item active" aria-current="page">Run & Idle</li>
        </ol>
    </div>
</div>
<!--end breadcrumb-->

<div class="card mt-4">
    <div class="card-body">

        <!-- Filter Form -->
        <form method="GET" action="{{ route('reports.runIdle') }}" class="row g-2 mb-3">
            <div class="col-md-3">
                <label>Start Date</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label>End Date</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-2 align-self-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table id="example2" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>SL No.</th>
                        <th>Date</th>
                        <th>Vehicle</th>
                        <th>Driver Name</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Run Time</th>
                        <th>Idle Time</th>
                        <th>Total Stops</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reports as $report)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ formated_date($report['date']) ?? '-' }}</td>
                            <td>{{ $report['vehicle'] ?? '-' }}</td>
                            <td>{{ $report['driver'] }}</td>
                            <td>{{ format_time($report['start_time']) ?? '-' }}</td>
                            <td>{{ format_time($report['end_time']) ?? '-' }}</td>
                            <td><span class="badge bg-success">{{ $report['run_time'] }}</span></td>
                            <td><span class="badge bg-warning text-dark">{{ $report['idle_time'] }}</span></td>
                            <td>{{ $report['stops'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No data available for this period</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
