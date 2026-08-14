@extends('frontend.layouts.app')
@section('title', $page->meta_title ?? 'Booking')
@section('meta_description', $page->meta_description ?? '')
@section('meta_keywords', $page->meta_keywords ?? '')
@section('css')
    @include('admin.vehicle_layout.partials.style')
    {{-- <style>
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
    </style> --}}

    <style>
        .seat-Numbner-layout {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding-top: 9px;
            padding-bottom: 9px;
            margin-top: 12px;
        }

        .seat.booked {
            background-color: #cbcbcb;
            color: white;
            pointer-events: none;
            opacity: 0.6;
        }

        .seat.selected {
            background-color: #000000;
            color: white;
        }

        .partially-booked.seat.selected {
            background-color: #000000;
            color: white;
        }

        .seat.partially-booked {
            /* background: repeating-linear-gradient(45deg,
                #cbcbcb,
                #cbcbcb 10px,
                #dff0d8 10px,
                #dff0d8
                20px); */
            /* background: linear-gradient(#dff0d8, #cbcbcb);
                color: black; */
            /* border: 2px solid #ff8c00; */
        }

        .seat.partially-booked::after {
            content: "Partially Booked";
            font-size: 10px;
            position: absolute;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 2px 5px;
            border-radius: 4px;
            display: none;
        }

        .seat.partially-booked:hover::after {
            display: block;
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
                                
                                <div class="banner_warp">
                                <img src="{{ asset('assets/frontend_assets/img/home/top-longer-banner.jpg') }}" alt="">
                                </div>
                                
                                
                                <div class="accordion" id="accordionPanelsStayOpenExample">

                                    <div class="accordion-item border-0">
                                        <div class="accordion-header shadow my-2 p-1" id="panelsStayOpen-headingThree">
                                            <form action="" id="proceedToBooking">
                                                @csrf

                                                @php
                                                    $fare = getFare(
                                                        $vehicle->route_id,
                                                        $from_bus_stop->id,
                                                        $to_bus_stop->id,
                                                    );

                                                @endphp
                                                <input type="hidden" name="booking_date" value="{{ request()->date }}">
                                                <input type="hidden" name="from_stop_id" id="fromStopId" value="{{ $from_bus_stop->id }}">
                                                <input type="hidden" name="to_stop_id" id="toStopId" value="{{ $to_bus_stop->id }}">
                                                <input type="hidden" name="route_id" id="routeId" value="{{ $vehicle->route_id }}">
                                                <input type="hidden" name="layout_id" id="layoutId" value="{{ $vehicle->layout_id }}">
                                                <input type="hidden" name="seat_number[]" id="seatNumber">
                                                <input type="hidden" id="totalAmountHidden" name="totalAmount" value="{{ $fare }}">
                                                <input type="hidden" name="vehicle_id" id="vehicleId" value="{{ $vehicle->id }}">

                                                <input type="hidden" name="ticket_price" id="ticketPrice" value="{{ $fare }}">
                                                <input type="hidden" name="booking_charge" id="bookingCharge" value="{{ $seat_booking_price }}">
                                                <input type="hidden" name="time_table_id" id="time_table_id" value="{{ $timeTable->id }}">
                                                <input type="hidden" name="journey_id" id="journey_id" value="{{ $journey->id ?? '' }}">

                                                <div class="row p-2">
                                                    
                                                    <div class="list_warp2">
                                                    <div class="col-lg-4">
                                                        <h6>
                                                            {{ optional($vehicle->route->fromBusStop)->name }} -
                                                            {{ optional($vehicle->route->toBusStop)->name }}
                                                            {{ optional($vehicle->route->viaBusStop)->name ? 'Via ' . optional($vehicle->route->viaBusStop)->name : '' }}
                                                        </h6>
                                                    </div>
                                                    @php
                                                        $fromstoppage = $timeTable
                                                            ->stoppageTimeForStop($from_bus_stop->id)
                                                            ->first();
                                                        $tostoppage = $timeTable
                                                            ->stoppageTimeForStop($to_bus_stop->id)
                                                            ->first();
                                                    @endphp
                                                    <div class="col-lg-2">
                                                        {{-- <p>{{ format_time(optional($vehicle->timeTable)->departure_time) }} --}}
                                                        <p>{{ format_time($fromstoppage->time) }}</p>
                                                        <p>{{ optional($from_bus_stop)->name }}</p>
                                                    </div>
                                                    <!--<div class="col-lg-2">-->
                                                    <!--    {{-- <p>{{ calculate_duration(optional($vehicle->timeTable)->departure_time, optional($vehicle->timeTable)->arrival_time) }} --}}-->
                                                    <!--    </p>-->
                                                    <!--</div>-->
                                                    <div class="col-lg-2">
                                                        {{-- <p>{{ format_time(optional($vehicle->timeTable)->arrival_time) }}</p> --}}
                                                        <p>{{ format_time($tostoppage->time) }}</p>
                                                        <p>{{ optional($to_bus_stop)->name }}</p>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <p>INR {{ $fare }}</p>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <p>{{ $avaliable_seat }} Seats available</p>
                                                    </div>
                                                    </div>
                                                    
                                                </div>

                                                <div id="panelsStayOpen-collapse{{ $vehicle->id }}"
                                                    class="accordion-collapse collapse show"
                                                    aria-labelledby="panelsStayOpen-headingThree"
                                                    data-bs-parent="#accordionPanelsStayOpenExample">
                                                    <div class="accordion-body p-0 my-3">
                                                        <div class="">
                                                            <div class="row p-2">

                                                                <div class="col-md-8">
                                                                    
                                                <div class="row">
                                                     <!--<div class="col-md-1">&nbsp;</div>-->
    <!--<div class="col-md-1">-->
        
    <!--</div>-->

        
        <div class="col-md-3 col-lg-1">
        <div id="seatLegend" class="mt-3" style="display: none;">
            <div class="d-flex flex-column gap-3 align-items-center">
                <div class="legend-item d-flex flex-column align-items-center">
                    <span class="vertical-text">Ladies Seat</span>
                    <div class="legend-color ladies-seat mb-1"></div>
                </div>
                <div class="legend-item d-flex flex-column align-items-center">
                    <span class="vertical-text">Senior Citizen</span>
                    <div class="legend-color senior-seat mb-1"></div>
                </div>
                <div class="legend-item d-flex flex-column align-items-center">
                    <span class="vertical-text">Handicapped</span>
                    <div class="legend-color handicapped-seat mb-1"></div>
                </div>
                <div class="legend-item d-flex flex-column align-items-center">
                    <span class="vertical-text">Regular</span>
                    <div class="legend-color regular-seat mb-1"></div>
                </div>
            </div>
        </div>

    </div>
    

    <div class="col-md-9 col-lg-9">
        <div class="che_ck"><h2>Click on an Available seat to proceed with your transaction.</h2></div>
        <div id="seatLayoutContainer" class="d-flex flex-wrap gap-2">
            <!-- Seat layout here -->
        </div>
         </div>
        

    
    <div class="col-md-2 col-lg-2">&nbsp;</div>
</div>

<div class="row">
    <div class="banner_warp2">
                                <img src="https://logistic.flexcellents.com/public/assets/frontend_assets/img/home/top-longer-banner.jpg" alt="">
                                </div>
</div>


                                                                    
                                                                </div>
                                                                
                                                               
                                                                
                                                                <div class="col-md-4 mt-2">
                                                                    
                                                                        <div class="card partially_warp">
                                                                        <div class="card-header how_works">
                                                                            Partially Booked Seats & Available From
                                                                        </div>
                                                                        <div class="card-body">
                                                                            {{-- @php print_r($partially_booked_avaliable_from) @endphp --}}
                                                                            @if(!empty($partially_booked_avaliable_from))
                                                                                <div class="row">
                                                                                    @foreach($partially_booked_avaliable_from as $seat)
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
                                                                    
                                                                    <!-- Seat Selection Box (Initially Hidden) -->
                                                                    <div id="seatSelectionBox" class="shadow_warp p-3"
                                                                        style="display: none; background-color: #fcf9eb;">
                                                                        <strong>Boarding & Dropping</strong>
                                                                        <div class="seat-Numbner-layout mb-3">
                                                                            <div class="row justify-content-between">
                                                                                <div class="col-md-12">
                                                                                    <strong>Seat No.</strong>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <span id="reflect-seat-number"
                                                                                        class="float-end">{{ $seat_number }}</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row justify-content-between der_t">
                                                                            <div class="col-md-12">
                                                                                <strong>Fare Details</strong>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <div class="row justify-content-between">
                                                                                    <div class="col-md-8">
                                                                                        <p><small class="text-muted">Travel Price</small></p>
                                                                                        <p><small class="text-muted">Seat Booking Charge</small></p>
                                                                                        <p><small class="text-muted">Basic Fare</small></p>
                                                                                        {{-- <p><small class="text-muted">Discount</small></p> --}}
                                                                                    </div>
                                                                                    <div class="col-md-4 text-md-end">
                                                                                        <p><small class="text-muted">INR {{ $fare }}</small></p>
                                                                                        <p><small class="text-muted">INR {{ $seat_booking_price }}</small></p>
                                                                                        {{-- <p><small class="text-muted" id="basicFare">INR {{ $fare + $seat_booking_price }}</small></p> --}}
                                                                                        <p><small class="text-muted" id="basicFare">INR</small></p>
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
                                                                                    <div class="col-md-6 text-md-end fw-bold text-primary">
                                                                                        <p id="totalAmount">INR</p>
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
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script>
        $(document).ready(function() {

            function getBusStopId(seatNumber) {
                let seatData = partiallyBookedAvaliableFrom.find(seat => seat.seat_number == seatNumber);
                return seatData ? seatData.bus_stop_id : null;
            }
            // $(document).on('click', '.view-seats-btn', function() {
            // var layoutId = $(this).data('layout-id');

            var layoutId = {{ $vehicle->layout_id }};
            let bookedSeats = {{ json_encode($booked_seats) }};
            let partiallyBookedSeats = {{ json_encode($partially_booked_seats) }};
            let partiallyBookedAvaliableFrom = {!! json_encode($partially_booked_avaliable_from) !!};
            console.log(partiallyBookedAvaliableFrom);
            const url_seat_number = "{{ $seat_number ?? '/' }}";
            // console.log(layoutId);
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
                                '<div class="d-flex w-100 gap-2"></div>'
                            );
                            for (let j = 0; j < numColumns; j++) {
                                const seat = layoutData.find(item => item.row === i && item
                                    .column === j);

                                if (seat) {
                                    const seatClass = getSeatClass(seat);
                                    let isBookable = !['empty', 'driver-seat', 'gate-seat'].includes(
                                        seatClass) ? 'get-seat-number' : '';
                                    // Handle booked and partially booked seats
                                    if (bookedSeats.includes(seat.seat_number)) {
                                        isBookable = 'booked';
                                    } else if (partiallyBookedSeats.includes(seat.seat_number)) {
                                        isBookable = 'partially-booked';
                                    }


                                    const seatIcon = getSeatIcon(seat.type);
                                    // const is_selected = url_seat_numer == seat.seat_number ? 'selected' : '';
                                    const is_selected = url_seat_number == seat.seat_number ? 'selected' : '';

                                    const seatDiv = $(
                                        `<div class="${isBookable} seat ${seatClass} ${is_selected}" 
                                        data-seat-number="${seat.seat_number}"
                                        ${getBusStopId(seat.seat_number) ? `data-seat-available-from="${getBusStopId(seat.seat_number)}"` : ''} >
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



            // $(document).on('click', '.get-seat-number', function() {
            //     var seatNumber = $(this).data('seat-number');
            //     $(this).toggleClass('selected');
            //     var selectedSeats = [];
            //     $('.seat.selected').each(function() {
            //         selectedSeats.push($(this).data('seat-number'));
            //     });
            //     $('#seatSelectionBox').show();
            //     console.log(selectedSeats);
            //     $('#reflect-seat-number').text(selectedSeats);
            //     // $('#reflect-seat-number').text(selectedSeats.join(", "));
            //     $('#seatNumber').val(selectedSeats.join(","));

            //     var fare = $('#ticketPrice').val();

            //     // var fare = 200;
            //     var totalFare = fare * selectedSeats.length;
            //     $('#basicFare').text("INR " + totalFare.toFixed(2));
            //     $('#totalAmount').text("INR " + totalFare.toFixed(2));
            //     $('#totalAmountHidden').val(totalFare.toFixed(2));
            // });

            $(document).on('click', '.get-seat-number, .partially-booked', function() {
                var seatNumber = $(this).data('seat-number');
                // console.log(seatNumber);

                // Allow selection of partially booked seats
                // if ($(this).hasClass('partially-booked')) {
                //     $(this).removeClass('partially-booked').addClass('selected');
                // } else {
                    $(this).toggleClass('selected');
                // }


                // var selectedSeats = [];
                // $('.seat.selected').each(function() {
                //     selectedSeats.push($(this).data('seat-number'));
                // });

                // $('#seatSelectionBox').show();
                // // console.log(selectedSeats);
                // $('#reflect-seat-number').text(selectedSeats);
                // $('#seatNumber').val(selectedSeats.join(","));

                var selectedSeats = [];
                var seatDetails = [];

                $('.seat.selected').each(function() {
                    var num = $(this).data('seat-number');
                    var availableFrom = $(this).data('seat-available-from') || ''; // Default empty if not set

                    selectedSeats.push(num);
                    seatDetails.push({ seat_number: num, available_from: availableFrom });
                });

                $('#seatSelectionBox').show();
                $('#reflect-seat-number').text(selectedSeats);
                $('#seatNumber').val(JSON.stringify(seatDetails));

                var fare = parseFloat($('#ticketPrice').val());
                var bookingCharge = parseFloat($('#bookingCharge').val());

                // var totalFare = fare * selectedSeats.length;
                var totalFare = (fare + bookingCharge) * selectedSeats.length; 
                $('#basicFare').text("INR " + totalFare.toFixed(2));
                $('#totalAmount').text("INR " + totalFare.toFixed(2));
                $('#totalAmountHidden').val(totalFare.toFixed(2));
            });


            $(document).on('click', '.partially-booked', function() {
                var seatNumber = $(this).data('seat-number');
                var vehicleId = {{ $vehicle->id }};


                $.ajax({
                    url: "{{ route('home.getAvailableBusStop') }}",
                    type: 'POST',
                    data: {
                        seat_number: seatNumber,
                        vehicle_id: vehicleId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {

                            // round_warning_noti(message);
                            let message =
                                `Seat ${seatNumber} will be available after ${response.bus_stop}.`;
                            round_success_noti(message);

                        } else {
                            round_error_noti("Error: " + response.message);

                        }
                    },
                    error: function() {
                        round_error_noti("An error occurred. Please try again.");
                    }
                });

                // $(this).removeClass('partially-booked').addClass('selected');
                // $(this).addClass('selected');
                // if ($(this).hasClass('selected')) {
                //     $(this).removeClass('selected');
                // } else {
                //     $(this).addClass('selected');
                // }
            });


            $('#proceedToBooking').on('submit', function(e) {
                e.preventDefault();
                // var selectedSeats = [];
                // $('.seat.selected').each(function() {
                //     selectedSeats.push($(this).data('seat-number'));
                // });
                // $('#seatNumber').val(selectedSeats.join(","));

                var selectedSeats = [];
                var seatDetails = [];

                $('.seat.selected').each(function() {
                    var num = $(this).data('seat-number');
                    var availableFrom = $(this).data('seat-available-from') || ''; // Default empty if not set

                    selectedSeats.push(num);
                    seatDetails.push({ seat_number: num, available_from: availableFrom });
                });

                $('#seatSelectionBox').show();
                $('#reflect-seat-number').text(selectedSeats);
                $('#seatNumber').val(JSON.stringify(seatDetails));

                // if (selectedSeats.length === 0) {
                //     round_warning_noti("Please select at least one seat before proceeding.")
                //     return;
                // }


                // 14-04-2025 by protoy
                var fare = parseFloat($('#ticketPrice').val());
                var totalFare = fare ; 
                $('#basicFare').text("INR " + totalFare.toFixed(2));
                $('#totalAmount').text("INR " + totalFare.toFixed(2));
                $('#totalAmountHidden').val(totalFare.toFixed(2));
                // 14-04-2025 by protoy


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
                            if(response.recharge_wallet == 1){
                                var options = {
                                    "key": "{{ env('RAZORPAY_KEY') }}", // Razorpay Key
                                    "amount": response.amount, // Amount in paise
                                    "currency": "INR",
                                    "name": "SAROTHI",
                                    "description": "Add funds to your wallet",
                                    "order_id": response.order_id, // Order ID from Razorpay
                                    "handler": function(paymentResponse) {
                                        // Send payment details to backend to verify and save
                                        $.ajax({
                                            url: '{{ route('front.wallet.recharge') }}',
                                            type: 'POST',
                                            data: {
                                                _token: '{{ csrf_token() }}',
                                                razorpay_payment_id: paymentResponse
                                                    .razorpay_payment_id,
                                                razorpay_order_id: paymentResponse
                                                    .razorpay_order_id,
                                                razorpay_signature: paymentResponse
                                                    .razorpay_signature,
                                                amount: response.amount,
                                                user_id: '{{ auth()->user()->id }}'
                                            },
                                            success: function(saveResponse) {
                                                if (saveResponse.success) {
                                                    round_success_noti(saveResponse.message);
                                                    $("#proceedToBooking").submit();
                                                } else {
                                                    round_error_noti(
                                                        'Failed to add money to wallet.'
                                                    );
                                                }
                                            },
                                            error: function() {
                                                round_error_noti(
                                                    'Payment verification failed.'
                                                );
                                            }
                                        });
                                    },
                                    "prefill": {
                                        "name": "{{ auth()->user()->name }}",
                                        "phone": "{{ auth()->user()->phone }}"
                                    },
                                    "theme": {
                                        "color": "#ebd873"
                                    }
                                };
                                var rzp = new Razorpay(options);
                                rzp.open();
                            }else{
                                round_warning_noti(response.message);
                            }
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

        // $(document).ready(function() {
        //     var layoutId = {{ $vehicle->layout_id }};
        //     let bookedSeats = {{ json_encode($booked_seats) }};
        //     let partiallyBookedSeats = {{ json_encode($partially_booked_seats) }};
        //     const url_seat_number = {{ $seat_number }};

        //     console.log(layoutId);

        //     $.ajax({
        //         url: "{{ route('vehicle-layout.get-layout') }}",
        //         type: 'POST',
        //         data: {
        //             layout_id: layoutId,
        //             _token: "{{ csrf_token() }}"
        //         },
        //         success: function(resp) {
        //             if (resp.status) {
        //                 layoutData = resp.layout_data;
        //                 numRows = resp.rows;
        //                 numColumns = resp.columns;

        //                 $('#seatLegend').hide();
        //                 const container = $('#seatLayoutContainer');
        //                 container.empty();

        //                 for (let i = 0; i < numRows; i++) {
        //                     const row = $(
        //                         '<div class="d-flex w-100 justify-content-center gap-2"></div>');
        //                     for (let j = 0; j < numColumns; j++) {
        //                         const seat = layoutData.find(item => item.row === i && item.column ===
        //                             j);

        //                         if (seat) {
        //                             const seatClass = getSeatClass(seat);
        //                             let isBookable = !['empty', 'driver-seat', 'gate-seat'].includes(
        //                                 seatClass) ? 'get-seat-number' : '';

        //                             // Handle booked and partially booked seats
        //                             if (bookedSeats.includes(seat.seat_number)) {
        //                                 isBookable = 'booked';
        //                             } else if (partiallyBookedSeats.includes(seat.seat_number)) {
        //                                 isBookable = 'partially-booked';
        //                             }

        //                             const seatIcon = getSeatIcon(seat.type);
        //                             const is_selected = url_seat_number == seat.seat_number ?
        //                                 'selected' : '';

        //                             const seatDiv = $(`
    //                         <div class="${isBookable} seat ${seatClass} ${is_selected}" data-seat-number="${seat.seat_number}">
    //                             ${seatIcon ? `<img src="${seatIcon}" alt="${seat.type}" style="max-width: 100%; max-height: 100%;">` : seat.seat_number || ''}
    //                         </div>
    //                     `);
        //                             row.append(seatDiv);
        //                         } else {
        //                             row.append('<div class="seat empty"></div>');
        //                         }
        //                     }
        //                     container.append(row);
        //                 }
        //                 $('#seatLegend').show();
        //             } else {
        //                 $('#seatLegend').hide();
        //                 $('#seatLayoutContainer').empty();
        //             }
        //         }
        //     });

        //     $('#seatSelectionBox').show();

        //     // Seat selection event
        //     $(document).on('click', '.get-seat-number', function() {
        //         var seatNumber = $(this).data('seat-number');

        //         if (partiallyBookedSeats.includes(seatNumber)) {
        //             if (!confirm("This seat is partially booked. Do you still want to proceed?")) {
        //                 return;
        //             }
        //         }

        //         $(this).toggleClass('selected');

        //         updateSeatSelection();
        //     });

        //     function updateSeatSelection() {
        //         var selectedSeats = [];
        //         $('.seat.selected').each(function() {
        //             selectedSeats.push($(this).data('seat-number'));
        //         });

        //         $('#seatSelectionBox').show();
        //         $('#reflect-seat-number').text(selectedSeats.join(", "));
        //         $('#seatNumber').val(selectedSeats.join(","));

        //         var fare = $('#ticketPrice').val();
        //         var totalFare = fare * selectedSeats.length;

        //         $('#basicFare').text("INR " + totalFare.toFixed(2));
        //         $('#totalAmount').text("INR " + totalFare.toFixed(2));
        //         $('#totalAmountHidden').val(totalFare.toFixed(2));
        //     }

        //     $('#proceedToBooking').on('submit', function(e) {
        //         e.preventDefault();

        //         var selectedSeats = [];
        //         $('.seat.selected').each(function() {
        //             selectedSeats.push($(this).data('seat-number'));
        //         });

        //         $('#seatNumber').val(selectedSeats.join(","));

        //         if (selectedSeats.length === 0) {
        //             round_warning_noti("Please select at least one seat before proceeding.");
        //             return;
        //         }

        //         let formData = new FormData(this);
        //         $.ajax({
        //             url: '{{ route('home.proceed.booking') }}',
        //             type: 'POST',
        //             data: formData,
        //             processData: false,
        //             contentType: false,
        //             beforeSend: function() {
        //                 $('#bookingBtn').prop('disabled', true).text('Processing...');
        //             },
        //             success: function(response) {
        //                 if (response.success) {
        //                     round_success_noti(response.message);
        //                     window.location.href = response.redirect_url;
        //                 } else {
        //                     round_warning_noti(response.message);
        //                 }
        //             },
        //             error: function(xhr) {
        //                 if (xhr.responseJSON && xhr.responseJSON.message) {
        //                     let errorMessage = "";
        //                     $.each(xhr.responseJSON.message, function(key, value) {
        //                         errorMessage += value[0] + "<br>";
        //                     });
        //                     round_error_noti(errorMessage);
        //                 } else {
        //                     round_error_noti('An unexpected error occurred!');
        //                 }
        //             },
        //             complete: function() {
        //                 $('#bookingBtn').prop('disabled', false).text('Proceed');
        //             }
        //         });
        //     });
        // });
    </script>
@endsection
