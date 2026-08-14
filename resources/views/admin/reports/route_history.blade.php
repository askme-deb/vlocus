@extends('layouts.app')

@section('title', 'Route History Report')

@section('content')
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Route History</div>
    <div class="ps-3">
        <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
            <li class="breadcrumb-item active" aria-current="page">Route History Report</li>
        </ol>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-4">
            <div class="col-md-3">
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
        <div class="table-responsive">
            <table id="example2" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>SL No.</th>
                        <th>Date</th>
                        <th>Vehicle</th>
                        <th>Driver</th>
                        <th>Total Stops</th>
                        <th>Total Distance</th>
                        <th>View Route</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $key => $schedule)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($schedule->delivery_date)->format('d-m-Y') }}</td>
                            <td>{{ $schedule->vehicle->vehicle_number ?? 'N/A' }}</td>
                            <td>{{ $schedule->driver->name ?? 'N/A' }}</td>
                            <td>{{ $schedule->shops->count() }}</td>
                            <td>{{ $schedule->total_distance }}</td>
                            <td>
                                {{-- @if($schedule->route_polyline)
                                    <a href="https://www.google.com/maps/dir/?api=1&origin={{ $schedule->deliveryScheduleShops->first()->accepted_lat }},{{ $schedule->deliveryScheduleShops->first()->accepted_long }}&destination={{ $schedule->deliveryScheduleShops->last()->deliver_lat }},{{ $schedule->deliveryScheduleShops->last()->deliver_long }}&travelmode=driving"
                                    class="btn btn-sm btn-success" target="_blank">View Map</a>
                                @else
                                    N/A
                                @endif --}}

                                <a href="{{ route('route.playback', ['driver_id' => $schedule->driver?->driver->id, 'date' => $schedule->delivery_date]) }}" 
                                    class="btn btn-sm btn-success" 
                                    target="_blank">
                                    Route Playback
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
