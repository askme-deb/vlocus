@extends('frontend.layouts.app')
@section('title','Booking')
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

        .seat.selected {
            background-color: #000000;
            color: white;
        }

        .seat.booked {
            background-color: #cbcbcb;
            color: white;
        }
    </style>
@endsection
@section('content')
    {{-- <section class="fromTo shadow py-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 d-flex">
                    <div class="d-flex flex-column me-3">
                        <label for="from">From</label>
                        <input type="text" id="from" class="border-bottom">
                    </div>
                    <span class="alignment me-3">
                        <i class="fas fa-exchange-alt"></i>

                    </span>
                    <div class="d-flex flex-column me-3">
                        <label for="to">To</label>
                        <input type="text" id="to" class="border-bottom">
                    </div>
                    <div class="d-flex flex-column me-3">
                        <label for="date">Date</label>
                        <input type="date" id="date" class="border-bottom">
                    </div>
                    <div class="d-flex flex-column" style="justify-content: end;">
                        <button class="btn btn_style">Search</button>
                    </div>

                </div>
                <div class="col-lg-6">

                </div>
            </div>
        </div>
    </section> --}}

    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <section class="viewBusesBox">
                        <div class="container">
                            <div class="row">
                                <div class="accordion" id="accordionPanelsStayOpenExample">

                                    <div class="accordion-item">
                                        <div class="accordion-header shadow my-2 p-1" id="panelsStayOpen-headingThree">
                                            <form action="" id="proceedToBooking">
                                                @csrf

                                                @php
                                                   $fare= getFare($vehicle->route_id, $from_bus_stop->id, $to_bus_stop->id);

                                                   
                                                @endphp
                                                <input type="hidden" name="from_stop_id" id="fromStopId" value="{{ $from_bus_stop->id }}">
                                                <input type="hidden" name="to_stop_id" id="toStopId" value="{{ $to_bus_stop->id }}">
                                                <input type="hidden" name="route_id" id="routeId" value="{{ $vehicle->route_id }}">
                                                <input type="hidden" name="layout_id" id="layoutId" value="{{ $vehicle->layout_id }}">
                                                <input type="hidden" name="seat_number[]" id="seatNumber">
                                                <input type="hidden" id="totalAmountHidden" name="totalAmount" value="200">
                                                <input type="hidden" name="vehicle_id" id="vehicleId" value="{{ $vehicle->id }}">

                                                <input type="hidden" name="ticket_price" id="ticketPrice" value="{{ $fare }}">
                                                
                                                <div class="row p-2">
                                                    <div class="col-lg-3">
                                                        <h6>
                                                            {{ optional($vehicle->route->fromBusStop)->name }} -
                                                            {{ optional($vehicle->route->toBusStop)->name }}
                                                            {{ optional($vehicle->route->viaBusStop)->name ? 'Via ' . optional($vehicle->route->viaBusStop)->name : '' }}
                                                        </h6>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <p>{{ format_time(optional($vehicle->timeTable)->departure_time) }}
                                                        </p>
                                                        <p>{{ optional($from_bus_stop)->name }}</p>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <p>{{ calculate_duration(optional($vehicle->timeTable)->departure_time, optional($vehicle->timeTable)->arrival_time) }}
                                                        </p>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <p>{{ format_time(optional($vehicle->timeTable)->arrival_time) }}
                                                        </p>
                                                        <p>{{ optional($to_bus_stop)->name }}</p>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <p>INR {{ $fare }}</p>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <p>{{ $avaliable_seat }} Seats available</p>
                                                    </div>
                                                </div>

                                                <div id="panelsStayOpen-collapse{{ $vehicle->id }}"
                                                    class="accordion-collapse collapse show"
                                                    aria-labelledby="panelsStayOpen-headingThree"
                                                    data-bs-parent="#accordionPanelsStayOpenExample">
                                                    <div class="accordion-body p-0 my-3">
                                                        <div class="shadow">
                                                            <div class="row p-2">

                                                                <div class="col-md-6">
                                                                    <div id="seatLegend" class="mb-3"
                                                                        style="display: none;">
                                                                        <div class="d-flex align-items-center gap-3">
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

                                                                    <div id="seatLayoutContainer"
                                                                        class="d-flex flex-wrap justify-content-center gap-2">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <!-- Seat Selection Box (Initially Hidden) -->
                                                                    <div id="seatSelectionBox" class="shadow p-3 mt-2"
                                                                        style="display: none; background-color: #fcf9eb;">
                                                                        <strong>Boarding & Dropping</strong>
                                                                        <div class="seat-Numbner-layout mb-3">
                                                                            <div class="row justify-content-between">
                                                                                <div class="col-md-4">
                                                                                    <strong>Seat No.</strong>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <span id="reflect-seat-number"
                                                                                        class="float-end">{{ $seat_number }}</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row justify-content-between">
                                                                            <div class="col-md-12">
                                                                                <strong>Fare Details</strong>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <div class="row justify-content-between">
                                                                                    <div class="col-md-6">
                                                                                        <p><small class="text-muted">Basic
                                                                                                Fare</small></p>
                                                                                        <p><small
                                                                                                class="text-muted">Discount</small>
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="col-md-6 text-md-end">
                                                                                        <p><small class="text-muted" id="basicFare">INR {{ $fare }}</small></p>
                                                                                        {{-- <p><small class="text-muted">- INR 5.00</small></p> --}}
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <div class="row align-items-center py-3">
                                                                                    <div class="col-md-6">
                                                                                        <p class="mb-1 fw-bold">Amount</p>
                                                                                        <small class="text-muted">Taxes will be calculated during payment</small>
                                                                                    </div>
                                                                                    <div
                                                                                        class="col-md-6 text-md-end fw-bold text-primary">
                                                                                        <p id="totalAmount">INR {{ $fare }}</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <button type="submit" id="bookingBtn"
                                                                                    class="btn btn-warning text-center w-100">Proceed
                                                                                    To Book</button>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
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

            // $(document).on('click', '.view-seats-btn', function() {
            // var layoutId = $(this).data('layout-id');

            var layoutId = {{ $vehicle->layout_id }};
            let bookedSeats = {{ json_encode($booked_seats) }};
            const url_seat_numer = {{ $seat_number }};
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
                        const container = $('#seatLayoutContainer');
                        container.empty();
                        for (let i = 0; i < numRows; i++) {
                            const row = $(
                                '<div class="d-flex w-100 justify-content-center gap-2"></div>'
                            );
                            for (let j = 0; j < numColumns; j++) {
                                const seat = layoutData.find(item => item.row === i && item
                                    .column === j);

                                if (seat) {
                                    const seatClass = getSeatClass(seat);
                                    let isBookable = !['empty', 'driver-seat', 'gate-seat'].includes(seatClass) ? 'get-seat-number' : '';
                                    
                                    // Check if seat is booked
                                    if (bookedSeats.includes(seat.seat_number)) {
                                        isBookable = 'booked'; // Remove get-seat-number because it's booked
                                    }

                                    const seatIcon = getSeatIcon(seat.type);
                                    const is_selected = url_seat_numer == seat.seat_number ? 'selected' : '';

                                    const seatDiv = $(
                                        `<div class="${isBookable} seat ${seatClass} ${is_selected}" data-seat-number="${seat.seat_number}" >
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
                        const container = $('#seatLayoutContainer');
                        container.empty();
                    }
                },

            });
            // });

            $('#seatSelectionBox').show();

            $(document).on('click', '.get-seat-number', function() {
                var seatNumber = $(this).data('seat-number');
                $(this).toggleClass('selected');
                var selectedSeats = [];
                $('.seat.selected').each(function() {
                    selectedSeats.push($(this).data('seat-number'));
                });
                $('#seatSelectionBox').show();
                console.log(selectedSeats);
                $('#reflect-seat-number').text(selectedSeats);
                // $('#reflect-seat-number').text(selectedSeats.join(", "));
                $('#seatNumber').val(selectedSeats.join(","));

                var fare = $('#ticketPrice').val(); 

                // var fare = 200;
                var totalFare = fare * selectedSeats.length;
                $('#basicFare').text("INR " + totalFare.toFixed(2));
                $('#totalAmount').text("INR " + totalFare.toFixed(2));
                $('#totalAmountHidden').val(totalFare.toFixed(2));
            });

            $('#proceedToBooking').on('submit', function(e) {
                e.preventDefault();
                var selectedSeats = [];
                $('.seat.selected').each(function() {
                    selectedSeats.push($(this).data('seat-number'));
                });
                $('#seatNumber').val(selectedSeats.join(","));

                if (selectedSeats.length === 0) {
                    round_warning_noti("Please select at least one seat before proceeding.")
                    return;
                }

        

                let formData = new FormData(this);
                $.ajax({
                    url: '{{ route('home.proceed.booking') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {

                        $('#bookingBtn').prop('disabled', true).text('Processing...');
                    },
                    success: function(response) {

                        if (response.success) {
                            round_success_noti(response.message);
                            // window.open(response.redirect_url, '_blank');
                            window.location.href = response.redirect_url;
                        } else {
                            round_warning_noti(response.message)
                            // round_error_noti('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            let errorMessage = "";
                            $.each(xhr.responseJSON.message, function(key, value) {
                                errorMessage += value[0] +
                                    "<br>";
                            });
                            round_error_noti(errorMessage);
                        } else {
                            round_error_noti('An unexpected error occurred!');
                        }
                    },
                    complete: function() {
                        $('#bookingBtn').prop('disabled', false).text('Proceed');
                    }

                });
            });

        });
    </script>
@endsection
