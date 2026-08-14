<div class="vehicle-item">

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
        {{-- {{ $tostoppage }} --}}
        <div class="accordion-header list_warp shadow mb-4 mt-4 p-1" 
        onclick="window.location.href='{{ route('home.seat.booking', $bus->id) }}?timeid={{ $timetable->id }}&journey_id={{ $journey_id ?? '' }}&from={{ urlencode($from) }}&to={{ urlencode($to) }}&date={{ urlencode($date) }}'" style="cursor: pointer; flex-wrap:wrap;">
            <div class="row">
                <div class="col-lg-2 pic_warp">
                    <h5>
                        {{ optional($bus->route->fromBusStop)->name }} -
                        {{ optional($bus->route->toBusStop)->name }}
                        {{ optional($bus->route->viaBusStop)->name ? 'Via ' . optional($bus->route->viaBusStop)->name : '' }}
                    </h5>
                </div>
                <div class="col-lg-2 pic_warp">
                    <p>From: {{ $from }}</p>
                    <h5 class="departur-time">{{ format_time($fromstoppage->time) ?? '' }}</h5>
                </div>
                <div class="col-lg-2 pic_warp">
                    <h5>{{ calculate_duration($fromstoppage->time, $tostoppage->time) ?? '' }}</h5>
                </div>
                <div class="col-lg-2 pic_warp">
                    <p>To: {{ $to }}</p>
                    <h5>{{ format_time($tostoppage->time) ?? '' }}</h5>
                </div>
                <div class="col-lg-2 pic_warp">
                    <h5>INR {{ $timetableData['fare'] ?? 'N/A' }}</h5>
                </div>
                <div class="col-lg-2 pic_warp no_bor">
                    <h5>{{ $timetableData['available_seat'] }} Seats available</h5>
                </div>
            </div>

            {{-- <div class="d-flex justify-content-end">
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
            </div> --}}

            {{-- <div id="collapse{{ $bus->id }}-{{ $timetable->id }}" class="accordion-collapse collapse" data-bs-parent="#accordionPanelsStayOpenExample">
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
                            <div id="seatLayoutContainer{{ $bus->id }}-{{ $timetable->id }}" class="d-flex flex-wrap justify-content-center gap-2"></div>
                        </div>
                        <div class="col-md-9 mt-2">
                            <div class="card">
                                <div class="card-header how_works">
                                    Partially Booked Seats &amp; Available From
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
            </div> --}}
        </div>
    @endforeach
@endforeach

</div>
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
            let $accordion = $(".vehicle-item");
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