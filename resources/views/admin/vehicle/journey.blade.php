@extends('layouts.app')

@section('title')
    Journeys
@endsection

@section('css')
    <style>
        #bus-route-table-container {
            width: 100%;
            max-width: 600px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 2px solid #ddd;
            display: flex;
            justify-content: center;
        }

        #bus-route-table {
            width: 100px;
            border-collapse: collapse;
            text-align: center;
        }

        #bus-route-table td {
            vertical-align: middle;
            position: relative;
            padding: 10px;
        }

        .bus-stop-name-left,
        .bus-stop-name-right {
            background: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            color: #000;
            white-space: nowrap;
        }

        .bus-stop-name-left {
            text-align: left;
        }

        .bus-stop-name-right {
            text-align: left;
        }

        .route-line-cell {
            width: 20px;
            position: relative;
        }

        .route-line {
            width: 8px;
            height: 100%;
            background-color: #5597dd;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: 0;
        }

        .bus-stop {
            width: 15px;
            height: 15px;
            background-color: #ff0000;
            border-radius: 50%;
            border: 2px solid #fff;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        .fare-chart-table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .fare-chart-table td {
            vertical-align: middle;
        }


        /* Highlight arrived stops */
        .arrived-stop {
            background: #4CAF50 !important;
            /* Green background */
            color: #fff !important;
            /* White text */
            font-weight: bold;
        }

        /* Change bus stop color when arrived */
        .arrived-stop .bus-stop {
            background-color: #4CAF50 !important;
            border: 2px solid darkgreen;
        }

        /* Adjusting text for visibility */
        .arrived-stop.bus-stop-name-left,
        .arrived-stop.bus-stop-name-right {
            color: white !important;
        }
    </style>
@endsection

@section('content')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Journeys</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Journeys</li>
                </ol>
            </nav>
        </div>
        <a class="btn btn-primary px-4" href="#" data-bs-toggle="modal" data-bs-target="#addJourneyModal">
            <i class="bi bi-plus-lg me-2"></i>Add New Journey
        </a>

    </div>
    <!--end breadcrumb-->

    <div class="card mt-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Vehicle Journeys</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example2" class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Start Time</th>

                            @canany(['Vehicle Edit', 'Vehicle Delete'])
                                <th>Action</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($journeys as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <a href="{{ route('vehicle.booking_list', $item->id) }}">{{ $item->route->fromBusStop->name }}
                                        - {{ $item->route->toBusStop->name }}
                                        {{ optional($item->route->viaBusStop)->name ? 'Via ' . optional($item->route->viaBusStop)->name : '' }}</a>

                                </td>

                                <a href="{{ route('vehicle.journey', $item->id) }}">{{ $item->name }}</a>

                                <td>
                                    {{ $item->start_time ? format_datetime($item->start_time) : 'Not Started' }}
                                </td>
                                

                                <td class="d-flex">

                                    <a class="btn view-map" data-bs-toggle="modal" data-bs-target="#ScrollableModal"
                                        data-journey-id="{{ $item->id }}" data-route-id="{{ $item->route_id }}">
                                        <i class="text-primary" data-feather="eye"></i>
                                    </a>
                                    <a class="btn start-journey" href="{{route('vehicle.journey.start',$item->id)}}" data-url="{{ route('vehicle.journey.start', $item->id) }}" alt="Start">
                                        <i class="text-success" data-feather="play"></i>
                                    </a>

                                    @can('Vehicle Delete')
                                        <a class="btn" href="javascript:void(0);" onclick="deleteItem(this)"
                                            data-url="{{ route('vehicle.journey.delete', $item->id) }}" data-item="Journey"
                                            alt="delete"><i class="text-danger" data-feather="trash-2"></i></a>
                                    @endcan
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">No bookings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Journey Modal -->
    <!-- Add Journey Modal -->
    <div class="modal fade" id="addJourneyModal" tabindex="-1" aria-labelledby="addJourneyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addJourneyModalLabel">Create New Journey</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="journeyForm" action="{{ route('vehicle.journey.store') }}" method="POST">
                        @csrf

                        <!-- Read-only Vehicle ID -->
                        <div class="mb-3">
                            <label for="vehicle_display" class="form-label">Vehicle</label>
                            <input type="text" class="form-control" id="vehicle_display" value="{{ $vehicle->name }}"
                                readonly>
                            <input type="hidden" id="vehicle_id" name="vehicle_id" value="{{ $vehicle->id }}">
                        </div>

                        <!-- Route Selection -->
                        <div class="mb-3">
                            <label for="route_id" class="form-label">Select Route</label>
                            <select class="form-select" id="route_id" name="route_id" required>
                                <option value="">Choose Route</option>
                                @foreach ($routes as $route)
                                    <option value="{{ $route->id }}">
                                        {{ $route->fromBusStop->name }} - {{ $route->toBusStop->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary" id="journeyCreateForm">Create Journey</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ScrollableModal">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 bg-grd-primary py-2">
                    <h5 class="modal-title text-light">View Bus Route</h5>
                    <a href="javascript:;" class="primaery-menu-close" data-bs-dismiss="modal">
                        <i class="material-icons-outlined text-light">close</i>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="card mb-3 w-100">
                            <div class="card-body">
                                <div id="bus-route-table-container">
                                    <table id="bus-route-table">
                                        <tbody id="bus-route-body"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-grd-danger text-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {


            $(".view-map").click(function() {
                var routeId = $(this).data("route-id");
                var journeyId = $(this).data("journey-id");

                var busRouteBody = $("#bus-route-body");
                busRouteBody.empty(); // Clear previous data

                $.ajax({
                    url: "{{ route('vehicle.view.map') }}",
                    type: "GET",
                    data: {
                        route_id: routeId,
                        journey_id: journeyId
                    },
                    success: function(response) {
                        if (response.success && response.busStops.length > 0) {
                            let routeLine = `<div class="route-line"></div>`;

                            response.busStops.forEach((stop, index) => {
                                let hasArrived = stop.arrival_time ? "arrived-stop" :
                                    ""; // Check if arrived

                                let stopRow = `
                        <tr class="${hasArrived}">
                            <td class="bus-stop-name-left">${index + 1}</td>
                            <td class="bus-stop-name-left">${stop.name}</td>
                            <td class="route-line-cell">${routeLine}<div class="bus-stop ${hasArrived}"></div></td>
                        </tr>
                    `;

                                busRouteBody.append(stopRow);
                            });
                        } else {
                            busRouteBody.append(
                                "<tr><td colspan='3' class='text-center'>No Routes found for this route.</td></tr>"
                            );
                        }
                    },
                    error: function() {
                        alert("Error fetching Routes.");
                    }
                });
            });




        });
    </script>
@endsection
