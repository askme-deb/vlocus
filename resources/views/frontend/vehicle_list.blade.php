@extends('frontend.layouts.app')
@section('title', $page->meta_title ?? 'List')
@section('meta_description', $page->meta_description ?? '')
@section('meta_keywords', $page->meta_keywords ?? '')
@section('css')
    @include('admin.vehicle_layout.partials.style')
    <style>
        .seat-Numbner-layout {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding-top: 9px;
            padding-bottom: 9px;
            margin-top: 12px;
        }

        h5 {
            font-weight: 600;
            font-size: 19px;
        }

        .seat.booked {
            background-color: #cbcbcb;
            color: white;
        }
        @media (max-width: 375px) {
  /* Apply styles for screens smaller than 768px */
  .accordion-body .row{
    padding: .5rem !important;
  }
 
}

    </style>
@endsection
@section('content')
    <section class="fromTo">
        <div class="container">
            <div class="row">
                {{-- <form action="{{ route('book-buss') }}" method="GET" class="booking-form">
                    <div class="row align-items-center">
                        <!-- From Location -->
                        <div class="col-lg-3 col-md-6 d-flex flex-column">
                            <label for="from">From</label>
                            <input type="text" id="from" name="from" placeholder="Enter departure city">
                        </div>

                        <!-- Exchange Icon -->
                        <div class="col-lg-1 col-md-12 text-center my-2">
                            <span class="alignment trans"><i class="fas fa-exchange-alt"></i></span>
                        </div>

                        <!-- To Location -->
                        <div class="col-lg-3 col-md-6 d-flex flex-column">
                            <label for="to">To</label>
                            <input type="text" id="to" name="to" placeholder="Enter destination">
                        </div>

                        <!-- Date Picker -->
                        <div class="col-lg-3 col-md-6 d-flex flex-column">
                            <label for="date">Date</label>
                            <input type="date" id="date" name="date" value="{{ date('Y-m-d') }}">
                        </div>

                        <!-- Search Button -->
                        <div class="col-lg-2 col-md-6 mt-3">
                            <button type="submit" class="btn btn_style">Search</button>
                        </div>
                    </div>
                </form> --}}
            </div>
        </div>
    </section>

    <section class="py-2">
        <div class="container">
            <div class="row">
                {{-- <div class="col-lg-2">
                    <div>
                        <h5>FILTERS</h5>
                        <hr>
                        <div class="d-flex flex-column">
                            <a href="">Live Tracking</a>
                            <a href="">Special Price</a>
                            <a href="">Primo Bus</a>
                        </div>
                    </div>
                    <div>
                        <h5>DEPARTURE TIME</h5>
                        <form action="form.php" class="d-flex flex-column">
                            <div>
                                <input type="checkbox" id="before">
                                <label for="before">Before 6am</label>
                            </div>
                            <div>
                                <input type="checkbox" id="morning">
                                <label for="morning">6am to 12pm</label>
                            </div>
                            <div>
                                <input type="checkbox" id="night">
                                <label for="night">12pm to 6pm</label>
                            </div>
                            <div>
                                <input type="checkbox" id="after">
                                <label for="after">After 6pm</label>
                            </div>

                        </form>
                    </div>
                    <div>
                        <h5>BUS TYPE</h5>
                        <form action="form.php" class="d-flex flex-column">
                            <div>
                                <input type="checkbox" id="before">
                                <label for="before">Seater</label>
                            </div>
                            <div>
                                <input type="checkbox" id="morning">
                                <label for="morning">Sleeper</label>
                            </div>
                            <div>
                                <input type="checkbox" id="night">
                                <label for="night">AC</label>
                            </div>
                            <div>
                                <input type="checkbox" id="after">
                                <label for="after">NonAC</label>
                            </div>

                        </form>
                    </div>
                    <div>
                        <h5>SEAT AVAILABILITY</h5>
                        <form action="form.php" class="d-flex flex-column">
                            <div>
                                <input type="checkbox" id="before">
                                <label for="before">Single Seats</label>
                            </div>
                        </form>
                    </div>
                    <div>
                        <h5>ARRIVAL TIME</h5>
                        <form action="form.php" class="d-flex flex-column">
                            <div>
                                <input type="checkbox" id="before">
                                <label for="before">Before 6am</label>
                            </div>
                            <div>
                                <input type="checkbox" id="morning">
                                <label for="morning">6am to 12pm</label>
                            </div>
                            <div>
                                <input type="checkbox" id="night">
                                <label for="night">12pm to 6pm</label>
                            </div>
                            <div>
                                <input type="checkbox" id="after">
                                <label for="after">After 6pm</label>
                            </div>

                        </form>
                    </div>
                    <div>

                        <form action="form.php">
                            <div class="d-flex flex-column">

                                <label for="before">BOARDING POINT </label>
                                <input type="text">
                            </div>
                        </form>
                    </div>

                    <div>

                        <form action="form.php">
                            <div class="d-flex flex-column">

                                <label for="before">DROPPING POINT </label>
                                <input type="text">
                            </div>
                        </form>
                    </div>
                    <div>

                        <form action="form.php">
                            <div class="d-flex flex-column">

                                <label for="before">OPERATOR</label>
                                <input type="text">
                            </div>
                        </form>
                    </div>
                    <div>
                        <h5>RTC BUS TYPE</h5>
                        <form action="form.php" class="d-flex flex-column">
                            <div>
                                <input type="checkbox" id="before">
                                <label for="before">EXPRESS</label>
                            </div>
                            <div>
                                <input type="checkbox" id="morning">
                                <label for="morning">HIM DHARA</label>
                            </div>
                            <div>
                                <input type="checkbox" id="night">
                                <label for="night">METRO</label>
                            </div>
                        </form>
                    </div>
                </div> --}}
                <div class="col-lg-12">

                    {{-- <section class="viewBusesBox">
                        <div class="container">
                            <div class="row">
                                <div class="accordion" id="accordionPanelsStayOpenExample">
                                    @foreach ($vehicles as $bus)
                                        <div class="accordion-item border-0">
                                            <div class="accordion-header shadow mb-4 mt-0 p-1" id="panelsStayOpen-headingThree">
                                                <div class="row pt-3 px-4">
                                                    <div class="col-lg-3">
                                                        <h5>
                                                            {{ optional($bus->route->fromBusStop)->name }} -
                                                            {{ optional($bus->route->toBusStop)->name }}
                                                            {{ optional($bus->route->viaBusStop)->name ? 'Via ' . optional($bus->route->viaBusStop)->name : '' }}
                                                        </h5>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <h5>{{ format_time(optional($bus->timeTable)->departure_time) }}
                                                        </h5>
                                                        <p>{{ $from ?? '' }}</p>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <h5>{{ calculate_duration(optional($bus->timeTable)->departure_time, optional($bus->timeTable)->arrival_time) }}
                                                        </h5>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <h5>{{ format_time(optional($bus->timeTable)->arrival_time) }}</h5>
                                                        <p>{{ $to ?? '' }}</p>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <h5>INR {{ $vehicleData[$bus->id]['fare'] ?? 'N/A' }}</h5>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <h5>{{ $vehicleData[$bus->id]['avaliable_seat'] ?? 0}} Seats available</h5>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    <button class="btn btn_style view-seats-btn" type="button"
                                                        data-layout-id="{{ $bus->layout_id }}"
                                                        data-bus-id="{{ $bus->id }}"
                                                        data-vehicle-id="{{ $bus->id }}" 
                                                        data-booked-seats="{{ json_encode($vehicleData[$bus->id]['booked_seats'] ?? []) }}"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#panelsStayOpen-collapse{{ $bus->id }}"
                                                        aria-expanded="true"
                                                        aria-controls="panelsStayOpen-collapse{{ $bus->id }}">VIEW SEATS
                                                    </button>
                                                </div>
                                                <div id="panelsStayOpen-collapse{{ $bus->id }}" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree" data-bs-parent="#accordionPanelsStayOpenExample">
                                                    <div class="accordion-body p-0 my-3">
                                                        <div class="">
                                                            <div class="row p-5">
                                                                <div class="d-flex justify-content-between">
                                                                    <h5>Select Your Seat</h5>
                                                                    <button type="button" class="btn btn-danger close-seats-btn"
                                                                            data-bs-toggle="collapse"
                                                                            data-bs-target="#panelsStayOpen-collapse{{ $bus->id }}">
                                                                        CLOSE
                                                                    </button>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div id="seatLegend" class="mb-3" style="display: none;">
                                                                        <div class="d-flex align-items-center gap-3 flex-wrap">
                                                                            <div
                                                                                class="legend-item d-flex align-items-center">
                                                                                <div class="legend-color ladies-seat">
                                                                                </div>
                                                                                <span>Ladies Seat</span>
                                                                            </div>
                                                                            <div
                                                                                class="legend-item d-flex align-items-center">
                                                                                <div class="legend-color senior-seat">
                                                                                </div>
                                                                                <span>Senior Citizen Seat</span>
                                                                            </div>
                                                                            <div
                                                                                class="legend-item d-flex align-items-center">
                                                                                <div class="legend-color handicapped-seat">
                                                                                </div>
                                                                                <span>Handicapped Seat</span>
                                                                            </div>
                                                                            <div
                                                                                class="legend-item d-flex align-items-center">
                                                                                <div class="legend-color regular-seat">
                                                                                </div>
                                                                                <span>Regular Seat</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div id="seatLayoutContainer{{ $bus->id }}" class="d-flex flex-wrap justify-content-center gap-2"></div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </section> --}}

                    {{-- <section class="viewBusesBox">
                        <div class="container">
                            <div class="row">
                                <div class="accordion" id="accordionPanelsStayOpenExample">
                                    @foreach ($vehicles as $bus)
                                        <div class="accordion-item border-0">
                                            <div class="accordion-header shadow mb-4 mt-0 p-1">
                                                <div class="row pt-3 px-4">
                                                    <div class="col-lg-12">
                                                        <h5>
                                                            {{ optional($bus->route->fromBusStop)->name }} -
                                                            {{ optional($bus->route->toBusStop)->name }}
                                                            {{ optional($bus->route->viaBusStop)->name ? 'Via ' . optional($bus->route->viaBusStop)->name : '' }}
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                           
                    
                                            @foreach ($vehicleData[$bus->id]['timetables'] as $timetable)
                                            
                                            @php
                                            $journey = $bus->journeys->first();
                                            $journey_id = $journey ? $journey->id : null;
                                            $last_departure_stop = getJourneyStoppageUpdate($journey_id);
                                            if(!empty($last_departure_stop)){
                                                $originLat = $last_departure_stop->busStop->latitude;
                                                // echo $originLat;
                                                $originLng = $last_departure_stop->busStop->longitude ;
                                                $destinationLat= $from_bus_stop->latitude ; 
                                                $destinationLng =  $from_bus_stop->longitude ;
    
                                               $distance= getDistanceFromAPI($originLat, $originLng, $destinationLat, $destinationLng);
                                            }else{
                                                $distance = [];
                                            }
                                            
                                        // echo "<pre>";
                                            // print_r( getDistanceFromAPI($originLat, $originLng, $destinationLat, $destinationLng));

                                     
                                           
                                       
                                            @endphp
              
                                                <div class="accordion-header shadow mb-4 mt-0 p-1">
                                                    <div class="row pt-3 px-4">
                                                        @php 
                                                            $fromstoppage = $timetable->stoppageTimeForStop($from_bus_stop->id)->first() ;
                                                            $tostoppage = $timetable->stoppageTimeForStop($to_bus_stop->id)->first() ;
                                                        @endphp 
                                                        <div class="col-lg-2">
                                                            <h5>format_time($timetable->departure_time) {{ format_time($fromstoppage->time) }}</h5>
                                                            <p>From: {{ $from }}</p>
                                                        </div>
                                                        <div class="col-lg-2">
                                                           
                                                            <h5>{{ calculate_duration($fromstoppage->time, $tostoppage->time) }}</h5>
                                                            @if(!empty($distance['duration_text']))
                                                            <p>{{$distance['duration_text']}} away</p>
                                                            @endif
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <h5>{{ format_time($tostoppage->time) }}</h5>
                                                            <p>To: {{ $to }}</p>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <h5>INR {{ $vehicleData[$bus->id]['fare'] ?? 'N/A' }}</h5>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <h5>{{ $vehicleData[$bus->id]['available_seat'] ?? 0 }} Seats available</h5>
                                                        </div>
                                                    </div>
                    
                                                    <div class="d-flex justify-content-end">
                                                        <button class="btn btn_style view-seats-btn" type="button"
                                                            data-layout-id="{{ $bus->layout_id }}"
                                                            data-bus-id="{{ $bus->id }}"
                                                            data-journey-id="{{ $journey_id }}" 
                                                            data-timetable-id="{{ $timetable->id }}"
                                                            data-vehicle-id="{{ $bus->id }}"
                                                            data-booked-seats="{{ json_encode($vehicleData[$bus->id]['booked_seats'] ?? []) }}"
                                                            data-partially-booked-seats="{{ json_encode($vehicleData[$bus->id]['partially_booked_seats'] ?? []) }}"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#collapse{{ $bus->id }}-{{ $timetable->id }}"
                                                            aria-expanded="true"
                                                            aria-controls="collapse{{ $bus->id }}-{{ $timetable->id }}">
                                                            VIEW SEATS
                                                        </button>
                                                    </div>
                    
                                                    <div id="collapse{{ $bus->id }}-{{ $timetable->id }}" 
                                                        class="accordion-collapse collapse"
                                                        data-bs-parent="#accordionPanelsStayOpenExample">
                                                        <div class="accordion-body p-0 my-3">
                                                            <div class="row p-5">
                                                                <div class="d-flex justify-content-between">
                                                                    <h5>Select Your Seat</h5>
                                                                    <button type="button" class="btn btn-danger close-seats-btn"
                                                                        data-bs-toggle="collapse"
                                                                        data-bs-target="#collapse{{ $bus->id }}-{{ $timetable->id }}">
                                                                        CLOSE
                                                                    </button>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div id="seatLayoutContainer{{ $bus->id }}-{{ $timetable->id }}"
                                                                        class="d-flex flex-wrap justify-content-center gap-2">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="card">
                                                                        <div class="card-header bg-info text-white">
                                                                            Partially Booked Seats & Available From
                                                                        </div>
                                                                        <div class="card-body">
                                                                            @if(!empty($vehicleData[$bus->id]['partially_booked_avaliable_from']))
                                                                                <div class="row">
                                                                                    @foreach($vehicleData[$bus->id]['partially_booked_avaliable_from'] as $seat)
                                                                                        <div class="col-6 mb-2">
                                                                                            <div class="p-2 border rounded bg-light text-center">
                                                                                                <strong>Seat {{ $seat['seat_number'] }}</strong><br>
                                                                                                <span class="text-success">{{ $seat['bus_stop_name'] }}</span>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endforeach
                                                                                </div>
                                                                            @else
                                                                                <p class="text-muted">No partially booked seats available.</p>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                    
                                                </div>
                                            @endforeach
                    
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </section> --}}

                    <section class="viewBusesBox">
                        <div class="container">
                            {{-- <select id="sort-options" class="form-control">
                                <option value="time">Sort by Departure Time</option>
                                <option value="fare">Sort by Fare</option>
                                <option value="seats">Sort by Available Seats</option>
                            </select> --}}
                            
                            <div class="row">
                                <div class="accordion" id="accordionPanelsStayOpenExample">
                                    <div class="accordion-item border-0">
                                        @foreach ($vehicles as $bus)
                                            @foreach ($vehicleData[$bus->id] as $timetableData)
                                                @php
                                                    $timetable = $timetableData['timetable'];
                                                    $fromstoppage = $timetable->stoppageTimeForStop($from_bus_stop->id)->first();
                                                    $tostoppage = $timetable->stoppageTimeForStop($to_bus_stop->id)->first();
                                                @endphp 

                                                @php
                                                $journey = $bus->journeys->first();
                                                $journey_id = $journey ? $journey->id : null;
                                                $last_departure_stop = getJourneyStoppageUpdate($journey_id);
                                                if(!empty($last_departure_stop)){
                                                    $originLat = $last_departure_stop->busStop->latitude;
                                                    // echo $originLat;
                                                    $originLng = $last_departure_stop->busStop->longitude;
                                                    $destinationLat= $from_bus_stop->latitude ; 
                                                    $destinationLng =  $from_bus_stop->longitude ;

                                                $distance= getDistanceFromAPI($originLat, $originLng, $destinationLat, $destinationLng);
                                                }else{
                                                    $distance = [];
                                                }

                                                // echo "<pre>";
                                                // print_r( getDistanceFromAPI($originLat, $originLng, $destinationLat, $destinationLng));
                                                @endphp

                                                <div class="accordion-header shadow mb-4 mt-0 p-1">
                                                    <div class="row pt-3 px-4">
                                                        <div class="col-lg-3">
                                                            <h5>
                                                                {{ optional($bus->route->fromBusStop)->name }} -
                                                                {{ optional($bus->route->toBusStop)->name }}
                                                                {{ optional($bus->route->viaBusStop)->name ? 'Via ' . optional($bus->route->viaBusStop)->name : '' }}
                                                            </h5>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <h5 class="departur-time">{{ format_time($fromstoppage->time) }}</h5>
                                                            <p>From: {{ $from }}</p>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <h5>{{ calculate_duration($fromstoppage->time, $tostoppage->time) }}</h5>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <h5>{{ format_time($tostoppage->time) }}</h5>
                                                            <p>To: {{ $to }}</p>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <h5>INR {{ $timetableData['fare'] ?? 'N/A' }}</h5>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <h5>{{ $timetableData['available_seat'] }} Seats available</h5>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex justify-content-end">
                                                        <button class="btn btn_style view-seats-btn" type="button"
                                                            data-layout-id="{{ $bus->layout_id }}"
                                                            data-bus-id="{{ $bus->id }}"
                                                            data-journey-id="{{ $journey_id ?? '' }}" 
                                                            data-timetable-id="{{ $timetable->id }}"
                                                            data-vehicle-id="{{ $bus->id }}"
                                                            data-booked-seats="{{ json_encode($vehicleData[$bus->id][$timetable->id]['booked_seats'] ?? []) }}"
                                                            data-partially-booked-seats="{{ json_encode($vehicleData[$bus->id][$timetable->id]['partially_booked_seats'] ?? []) }}"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#collapse{{ $bus->id }}-{{ $timetable->id }}"
                                                            aria-expanded="true"
                                                            aria-controls="collapse{{ $bus->id }}-{{ $timetable->id }}">
                                                            VIEW SEATS
                                                        </button>
                                                    </div>
                    
                                                    <div id="collapse{{ $bus->id }}-{{ $timetable->id }}" 
                                                        class="accordion-collapse collapse"
                                                        data-bs-parent="#accordionPanelsStayOpenExample">
                                                        <div class="accordion-body p-0 my-3">
                                                            <div class="row p-5">
                                                                <div class="d-flex justify-content-between">
                                                                    <h5>Select Your Seat</h5>
                                                                    <button type="button" class="btn btn-danger close-seats-btn"
                                                                        data-bs-toggle="collapse"
                                                                        data-bs-target="#collapse{{ $bus->id }}-{{ $timetable->id }}">
                                                                        CLOSE
                                                                    </button>
                                                                </div>
                                                                <div class="col-md-3 mt-2">
                                                                    <div id="seatLayoutContainer{{ $bus->id }}-{{ $timetable->id }}"
                                                                        class="d-flex flex-wrap justify-content-center gap-2">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-9 mt-2">
                                                                    <div class="card">
                                                                        <div class="card-header how_works">
                                                                            Partially Booked Seats & Available From
                                                                        </div>
                                                                        <div class="card-body">
                                                                            @if(!empty($vehicleData[$bus->id][$timetable->id]['partially_booked_avaliable_from']))
                                                                                <div class="row">
                                                                                    @foreach($vehicleData[$bus->id][$timetable->id]['partially_booked_avaliable_from'] as $seat)
                                                                                        <div class="col-6 mb-2">
                                                                                            <div class="p-2 border rounded bg-light text-center">
                                                                                                <strong>Seat {{ $seat['seat_number'] }}</strong><br>
                                                                                                <span class="text-success">{{ $seat['bus_stop_name'] }}</span>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endforeach
                                                                                </div>
                                                                            @else
                                                                                <p class="text-muted">No partially booked seats available.</p>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    @include('admin.vehicle_layout.partials.index-script')
    <script>
        $(document).ready(function() {
            
            $(document).on('click', '.view-seats-btn', function() {
                var layoutId = $(this).data('layout-id');
                let bookedSeats = $(this).data('booked-seats');
                let partiallyBookedSeats = $(this).data('partially-booked-seats');
                var busId = $(this).data('bus-id');
                var timeTableId = $(this).data('timetable-id');
                var vehicleId = $(this).data('vehicle-id');
                var journeyId = $(this).data('journey-id');

                console.log(layoutId);
                $.ajax({
                    url: "{{ route('vehicle-layout.get-layout') }}",
                    type: 'POST',
                    data: {
                        layout_id: layoutId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(resp) {
                        if (resp.status) {
                            layoutData = resp.layout_data;
                            numRows = resp.rows;
                            numColumns = resp.columns;

                            $('#seatLegend').hide();
                            const container = $('#seatLayoutContainer' + busId + '-' + timeTableId);
                            container.empty();
                            for (let i = 0; i < numRows; i++) {
                                const row = $(
                                    '<div class="d-flex w-100 justify-content-center gap-2"></div>'
                                );
                                for (let j = 0; j < numColumns; j++) {
                                    const seat = layoutData.find(item => item.row === i && item.column === j);

                                    if (seat) {
                                        const seatClass = getSeatClass(seat);
                                        let isBookable = !['empty', 'driver-seat', 'gate-seat'].includes(seatClass) ? 'get-seat-number' : '';
                                    
                                        // Check if seat is booked
                                        // if (bookedSeats.includes(seat.seat_number)) {
                                        //     isBookable = 'booked'; // Remove get-seat-number because it's booked
                                        // }
                                        if (bookedSeats.includes(seat.seat_number)) {
                                            isBookable = 'booked';
                                        } else if (partiallyBookedSeats.includes(seat.seat_number)) {
                                            isBookable = 'partially-booked get-seat-number';
                                        }
                                        const seatIcon = getSeatIcon(seat.type);
                                        const seatDiv = $(
                                            `<div class="${isBookable} seat ${seatClass}" data-seat-number="${seat.seat_number}"  data-singleVehicle-id="${vehicleId}" data-time-table-id="${timeTableId}" data-journey_id="${journeyId}">
                                                ${seatIcon ? `<img src="${seatIcon}" alt="${seat.type}" style="max-width: 100%; max-height: 100%;">` : seat.seat_number || ''}
                                            </div>`
                                        );
                                        row.append(seatDiv);
                                    } else {
                                        row.append('<div class="seat empty"></div>');
                                    }
                                }
                                container.append(row);
                            }
                            $('#seatLegend').show();
                        } else {
                            $('#seatLegend').hide();
                            const container = $('#seatLayoutContainer' + busId + '-' + timeTableId);
                            container.empty();
                        }
                    },
                });
            });

            $(document).on('click', '.get-seat-number', function() {
                var seatNumber = $(this).data('seat-number');
                var vehicleId = $(this).data('singlevehicle-id');
                var timeTableId = $(this).data('time-table-id');
                var journeyId = $(this).data('journey_id');
                var from = "{{ $from }}";
                var to = "{{ $to }}";
                var date = "{{ $date }}";

                console.log("Seat Number:", seatNumber);
                console.log("Vehicle ID:", vehicleId);

                if (seatNumber && vehicleId) {
                    var bookingUrl = "{{ route('home.seat.booking', ':vehicleId') }}".replace(':vehicleId',
                        vehicleId);
                    bookingUrl += `?seat_number=${seatNumber}&timeid=${timeTableId}&journey_id=${journeyId}&from=${encodeURIComponent(from)}&to=${encodeURIComponent(to)}&date=${encodeURIComponent(date)}`;

                    window.location.href = bookingUrl;
                } else {
                    console.error("Error: Missing seat number or vehicle ID");
                }
            });
            
            function toggleSeatBox(busId) {
                const seatBox = document.getElementById('panelsStayOpen-collapse' + busId + '-' + timeTableId);
                if (seatBox) {
                    const bsCollapse = new bootstrap.Collapse(seatBox);
                    bsCollapse.hide();
                }
            }

            function sortAccordionItems(sortBy) {
                let $accordion = $(".accordion-item");
                let $items = $accordion.children(".accordion-header");

                let sortedItems = $items.sort(function (a, b) {
                    let aValue, bValue;

                    if (sortBy === "time") {
                        aValue = $(a).find(".departur-time").first().text().trim();
                        bValue = $(b).find(".departur-time").first().text().trim();
                        let dateA = new Date("1970/01/01 " + aValue);
                        let dateB = new Date("1970/01/01 " + bValue);
                        return dateA - dateB;
                    }

                    if (sortBy === "fare") {
                        aValue = parseFloat($(a).find(".col-lg-1 h5").first().text().replace("INR ", ""));
                        bValue = parseFloat($(b).find(".col-lg-1 h5").first().text().replace("INR ", ""));
                        return aValue - bValue;
                    }

                    if (sortBy === "seats") {
                        aValue = parseInt($(a).find(".col-lg-2 h5").last().text());
                        bValue = parseInt($(b).find(".col-lg-2 h5").last().text());
                        return bValue - aValue; // Descending order (more seats first)
                    }
                });

                // Re-append sorted items
                $accordion.append(sortedItems);
            }

            // $("#sort-options").change(function () {
            //     sortAccordionItems($(this).val());
            // });
            sortAccordionItems('time');

        });
    </script>
@endsection
