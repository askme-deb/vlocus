@extends('layouts.app')

@section('title', 'Emergency SOS Report')

@section('content')
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Emergency SOS</div>
    <div class="ps-3">
        <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
            <li class="breadcrumb-item active" aria-current="page">SOS Report</li>
        </ol>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <div class="table-responsive">
            <table id="example2" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>SL No.</th>
                        <th>Driver Name</th>
                        <th>Contact</th>
                        <th>Message</th>
                        <th>Location</th>
                        <th>SOS Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $report->driver->name }}</td>
                            <td>{{ $report->driver->phone  }}</td>
                            <td>{{ $report->message }}</td>
                            <td>
                                <a href="https://www.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}" target="_blank">
                                    {{ $report->latitude }},{{ $report->longitude }}
                                </a>
                            </td>
                            <td>{{ format_datetime($report->created_at) }}</td>
                            <td><span class="badge bg-danger">Emergency</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $reports->links() }}
    </div>
</div>
@endsection
