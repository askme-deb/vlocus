@extends('layouts.app')

@section('title', 'Trip Summary Report')

@section('content')
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Trip Summary</div>
    <div class="ps-3">
        <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
            <li class="breadcrumb-item active" aria-current="page">Trip Summary Report</li>
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
                        <th>Date</th>
                        <th>Vehicle</th>
                        <th>Driver</th>
                        <th>Start Location</th>
                        <th>End Location</th>
                        <th>Distance (km)</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Duration</th>
                        <th>Stops</th>
                        <th>Total Amount</th>
                        <th>Total QTY</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Route</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $key => $report)
                        <tr>
                            {{-- SL No. --}}
                            <td>{{ $key + 1 }}</td>

                            {{-- Date (from delivery_schedule) --}}
                            <td>{{ optional($report->deliverySchedule)->delivery_date 
                                ? \Carbon\Carbon::parse($report->deliverySchedule->delivery_date)->format('d-m-Y') 
                                : '-' }}
                            </td>

                            {{-- Vehicle --}}
                            <td>{{ optional($report->deliverySchedule->vehicle)->vehicle_number ?? 'N/A' }}</td>

                            {{-- Driver --}}
                            <td>{{ optional($report->deliverySchedule->driver)->name ?? 'N/A' }}</td>

                            {{-- Start Location --}}
                            <td>
                                @if($report->accepted_lat && $report->accepted_long)
                                    <a href="https://www.google.com/maps?q={{ $report->accepted_lat }},{{ $report->accepted_long }}" target="_blank">
                                        {{ $report->origin_address }}
                                    </a>
                                @elseif($report->accepted_lat)
                                    {{ $report->accepted_lat }}
                                @elseif($report->accepted_long)
                                    {{ $report->accepted_long }}
                                @else
                                    -
                                @endif
                            </td>

                            {{-- End Location (Shop) --}}
                            <td>
                                @if($report->deliver_lat && $report->deliver_long)
                                    <a href="https://www.google.com/maps?q={{ $report->deliver_lat }},{{ $report->deliver_long }}" target="_blank">
                                        {{ $report->destination_address }}
                                    </a>
                                @elseif($report->deliver_lat)
                                    {{ $report->deliver_lat }}
                                @elseif($report->deliver_long)
                                    {{ $report->deliver_long }}
                                @else
                                    -
                                @endif
                            </td>

                            {{-- Distance (if you calculate or store it) --}}
                            <td>{{ $report->calculated_distance ?? 'N/A' }}</td>

                            {{-- Start Time --}}
                            <td>
                                {{ $report->accepted_at 
                                    ? \Carbon\Carbon::parse($report->accepted_at)->format('h:i A') 
                                    : '-' }}
                            </td>

                            {{-- End Time --}}
                            <td>
                                {{ $report->delivered_at 
                                    ? \Carbon\Carbon::parse($report->delivered_at)->format('h:i A') 
                                    : '-' }}
                            </td>

                            {{-- Duration --}}
                            <td>
                                @if($report->accepted_at && $report->delivered_at)
                                    @php
                                        $start = \Carbon\Carbon::parse($report->accepted_at);
                                        $end = \Carbon\Carbon::parse($report->delivered_at);
                                        $diff = $end->diff($start);
                                        $hours = $diff->h;
                                        $minutes = $diff->i;
                                        $seconds = $diff->s;
                                        $days = $diff->d;
                                    @endphp
                                    @if($days > 0)
                                        {{ $days }} day{{ $days > 1 ? 's' : '' }} {{ sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds) }}
                                    @else
                                        {{ sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds) }}
                                    @endif
                                @else
                                    -
                                @endif
                            </td>

                            {{-- Stops (number of shops under this schedule) --}}
                            <td>{{ $reports->where('delivery_schedule_id', $report->delivery_schedule_id)->count() }}</td>

                            {{-- Total Amount --}}
                            <td>{{ $report->amount ?? 0.00 }}</td>

                            {{-- Total QTY --}}
                            <td>{{ $report->products->count() ?? 0 }}</td>

                            {{-- Status --}}
                            <td>{{ ucfirst($report->status) ?? '-' }}</td>

                            {{-- Remarks --}}
                            <td>{{ $report->remarks ?? '-' }}</td>
                            <td>
                                <a href="{{ route('route.playback', ['driver_id' =>$report->deliverySchedule->driver?->driver->id, 'date' => $report->deliverySchedule->delivery_date]) }}" 
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
