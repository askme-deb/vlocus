@extends('frontend.layouts.app')
@section('title', 'Profile')

@section('css')
{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

@endsection
@section('content')

    <section class="tab-profile py-5">
        <div class="container">
            <div class="row">
                <!-- Sidebar Tabs -->
                <div class="col-lg-3">
                    <ul class="nav nav-tabs flex-column" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                                type="button" role="tab" aria-controls="home" aria-selected="true"><i
                                    class="fa-solid fa-map-pin"></i> My Trips</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                type="button" role="tab" aria-controls="profile" aria-selected="false"><i
                                    class="fa-solid fa-wallet"></i> Wallets / Cards</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact"
                                type="button" role="tab" aria-controls="contact" aria-selected="false"><i
                                    class="fa-solid fa-user"></i> My Profile</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="wallet-tab" data-bs-toggle="tab" data-bs-target="#wallet"
                                type="button" role="tab" aria-controls="wallet" aria-selected="false"><i
                                    class="fa-solid fa-wallet"></i> Wallet</button>
                        </li>
                    </ul>
                </div>

                <!-- Tab Content -->
                <div class="col-lg-9">
                    <div class="tab-content mt-3" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <h4>My Trips</h4>
                            {{-- <a href="#">Upcoming</a>
                            <!-- Booking History --> --}}
                            <h5 class="mt-4">Booking History</h5>
                            @if ($bookings->isEmpty())
                                <div class="mt-3 py-3 px-3 shadow">
                                    <p>No past trips found.</p>
                                </div>
                            @else
                                <ul class="list-group">
                                    @foreach ($bookings as $booking)
                                        <li class="list-group-item">
                                            <strong>Trip:</strong> {{ $booking->fromStop->name }}-
                                            {{ $booking->toStop->name }} <br>
                                            <strong>Date:</strong> {{ format_datetime($booking->created_at) }}<br>
                                            <strong>Status:</strong> {{ ucfirst($booking->status)  }}
                                      
{{-- 
                                            @if ($booking->qr_code)
                                                <div style="margin-top: 10px;">
                                                    <strong>QR Code:</strong> <br>
                                                    <img src="{{ asset('storage/' . str_replace('storage/app/public/', '', $booking->qr_code)) }}"
                                                        alt="QR Code" style="width: 100px; height: 100px;">
                                                </div>
                                            @endif --}}
                                            <div style="margin-top: 10px;">
                                                <a href="{{ route('home.print.ticket', $booking->id) }}" class="btn btn_style" target="_blank">
                                                    View Ticket
                                                </a>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="mt-3">
                                    {{ $bookings->links('vendor.pagination.bootstrap-4') }}
                                </div>
                            @endif
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <h4>Wallet</h4>
                            <div class="row mt-3 py-3 px-3 shadow mx-2">
                                <div class="col-lg-1"><i class="fa-solid fa-wallet"></i></div>
                                <div class="col-lg-11">
                                    <div>
                                        <div class="d-flex justify-content-between">
                                            <p>Your Wallet Balance</p>
                                             <p>INR {{optional($wallet)->balance}}</p>

                                        </div>

                                    </div>
                                    <div>
                                        <div class="d-flex justify-content-between">
                                            <p>Your Cash</p>
                                            <p>INR 0</p>

                                        </div>

                                    </div>
                                    <div>
                                        <div class="d-flex justify-content-between">
                                            <p>Offer Cash</p>
                                            <p>INR 0</p>

                                        </div>

                                    </div>
                                </div>

                            </div>
                            <div class="mt-3 py-3 px-3 shadow">
                                <p class="text-center">SAVED CARDS</p>
                                <p class="text-center">NO SAVED CARDS</p>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            <h4 class="mb-4">👤 My Profile</h4>
                            @php
                                $data = auth()->user();
                            @endphp
                            <div class="p-4 shadow bg-white rounded">
                                <form id="profileUpdateForm" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <!-- Name Field -->
                                            <input type="hidden" id="user_id" name="user_id"
                                                value="{{ auth()->user()->id }}">
                                            <div class="mb-3">
                                                <label for="name" class="form-label fw-bold">Full Name</label>
                                                <input type="text" id="name" name="name" class="form-control"
                                                    placeholder="Enter your name" required value="{{ $data->name }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <!-- Date of Birth -->
                                            <div class="mb-3">
                                                <label for="dob" class="form-label fw-bold">Date of Birth</label>
                                                <input type="date" id="dob" name="dob" class="form-control"
                                                    required value="{{ $data->date_of_birth }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Gender -->
                                    <div class="mb-3">
                                        <h5 class="fw-bold">Gender</h5>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="male" name="gender"
                                                value="male" {{ $data->gender == 'male' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="male">Male</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="female" name="gender"
                                                value="female" {{ $data->gender == 'female' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="female">Female</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <!-- Email -->
                                            <div class="mb-3">
                                                <label for="email" class="form-label fw-bold">Email Address</label>
                                                <input type="email" id="email" name="email" class="form-control"
                                                    placeholder="Enter your email" value="{{ $data->email }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <!-- Phone Number -->
                                            <div class="mb-3">
                                                <label for="phone" class="form-label fw-bold">Phone Number</label>
                                                <input type="tel" id="phone" name="phone" class="form-control"
                                                    placeholder="Enter your phone number" value="{{ $data->phone }}">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Submit Button -->
                                    <button type="submit" class="btn btn_style" id="saveProfile">Save Profile</button>
                                </form>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="wallet" role="tabpanel" aria-labelledby="wallet-tab">
                            <div class="cder_ew">
                                <h4>Wallet</h4>
                                <a class="btn btn-warning d-flex align-items-center" data-bs-toggle="modal"
                                    data-bs-target="#wallerRechargeleModal">
                                    Recharge

                                </a>
                            </div>
                            <div class="p-4 shadow">

                                <h5>Balance</h5>
                                <div>
                                    <div class="d-flex justify-content-between">
                                        <p>Your Wallet Balance</p>
                                        <p id="walletBalance">INR {{ auth()->user()->wallet->balance ?? 0 }}</p>

                                    </div>

                                </div>
                                {{-- <div>
                                    <div class="d-flex justify-content-between">
                                        <p>Your Cash</p>
                                        <p>INR 0</p>

                                    </div>

                                </div>
                                <div>
                                    <div class="d-flex justify-content-between">
                                        <p>Offer Cash</p>
                                        <p>INR 0</p>

                                    </div>

                                </div> --}}
                                <hr>
                                <h5>Activity</h5>
                                <div class="row font-weight-bold">
                                    <div class="col-lg-4">
                                        <p>Date</p>
                                    </div>
                                    <div class="col-lg-4">
                                        <p>Transaction</p>
                                    </div>
                                    <div class="col-lg-4">
                                        <p>Amount</p>
                                    </div>
                                </div>
                                <div id="transaction-list">
                                    <!-- Transactions will be appended here -->
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </section>
    <div class="modal fade" id="wallerRechargeleModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="wallerRechargeleModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="row">

                    <div class="col-lg-12">
                        <form class="px-3 py-3" id="walletRechargeForm">
                            @csrf
                            <input type="hidden" id="user_id" name="user_id" value="{{ auth()->user()->id }}">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="close border-0" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="py-3">
                                <label for="phone" class="form-label">Amount</label>
                                <input type="text" class="form-control" id="amount" name="amount">
                            </div>
                            {{-- <div class="py-3">
                                <label for="phone" class="form-label">Remarks</label>
                                <input type="text" class="form-control" id="remarks" name="remarks">

                            </div> --}}
                            <button type="submit" id="rechargeBtn" class="btn btn_style">Submit</button>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#profileUpdateForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: '{{ route('front.user.profile.update') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {

                        $('#saveProfile').prop('disabled', true).text('Saving...');
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.success) {
                            round_success_noti(response.message);
                            // $('#orderCreateForm')[0].reset();
                        } else {
                            round_error_noti('Something went wrong!');

                        }
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.message) {

                            round_error_noti(xhr.responseJSON.message);
                        } else {
                            round_error_noti('An unexpected error occurred!');

                        }
                    },
                    complete: function() {
                        $('#saveProfile').prop('disabled', false).text('Save Profile');
                    }
                });
            });

            // $('#walletRechargeForm').on('submit', function(e) {
            //     console.log(1234);

            //     e.preventDefault();
            //     let formData = new FormData(this);
            //     $.ajax({
            //         url: '{{ route('front.wallet.recharge') }}',
            //         type: 'POST',
            //         data: formData,
            //         processData: false,
            //         contentType: false,
            //         beforeSend: function() {
            //             $('#rechargeBtn').prop('disabled', true).text('Processing...');
            //         },
            //         success: function(response) {

            //             if (response.success) {
            //                 round_success_noti(response.message);
            //                 // $('#orderCreateForm')[0].reset();
            //                 $('#walletRechargeForm')[0].reset();
            //                 $('#wallerRechargeleModal').modal('hide');
            //                 $('#walletBalance').text(`INR ${response.balance}`);
            //             } else {
            //                 round_error_noti('Something went wrong!');

            //             }
            //         },
            //         error: function(xhr) {
            //             if (xhr.status === 422) {
            //                 let errors = xhr.responseJSON.errors;
            //                 let errorMessage = '';

            //                 $.each(errors, function(field, messages) {
            //                     errorMessage += messages.join('<br>') + '<br>';
            //                 });

            //                 round_error_noti(errorMessage);
            //             } else if (xhr.responseJSON && xhr.responseJSON.message) {
            //                 round_error_noti(xhr.responseJSON.message);
            //             } else {
            //                 round_error_noti('An unexpected error occurred!');
            //             }
            //         },
            //         complete: function() {
            //             $('#rechargeBtn').prop('disabled', false).text('Submit');
            //         }
            //     });
            // });

            $('#walletRechargeForm').on('submit', function(e) {
                e.preventDefault();
                let amount = $('#amount').val();

                if (!amount || isNaN(amount) || amount <= 0) {
                    round_error_noti('Please enter a valid amount');
                    return;
                }

                $.ajax({
                    url: '{{ route('front.wallet.create_order') }}', // New route to create Razorpay order
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        amount: amount
                    },
                    success: function(response) {
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
                                        amount: amount,
                                        user_id: '{{ auth()->user()->id }}'
                                    },
                                    success: function(saveResponse) {
                                        if (saveResponse.success) {
                                            $('#wallerRechargeleModal')
                                                .modal('hide');
                                            round_success_noti(saveResponse
                                                .message);
                                            $('#walletRechargeForm')[0]
                                                .reset();
                                            $('#walletBalance').text(
                                                `INR ${saveResponse.balance}`
                                            );
                                            loadTransactions();
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
                                "email": "{{ auth()->user()->phone }}"
                            },
                            "theme": {
                                "color": "#ebd873"
                            }
                        };
                        var rzp = new Razorpay(options);
                        rzp.open();
                    },
                    error: function() {
                        round_error_noti('Failed to create Razorpay order.');
                    }
                });
            });

            function loadTransactions() {
                $.ajax({
                    url: "{{ route('wallet.transactions') }}",
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        $('#transaction-list').empty();

                        if (response.length === 0) {
                            $('#transaction-list').append(
                                '<p class="text-center">No transactions found.</p>');
                        } else {
                            $.each(response, function(index, transaction) {
                                const formattedDate = dayjs(transaction.date).format(
                                    'DD-MM-YYYY HH:mm'); // Format here
                                var textColour = "";
                                if (transaction.type === "credit") {
                                    textColour = 'green';
                                }else{
                                    textColour = 'red';
                                }
                                const transactionRow = `
                                    <div class="row py-2 border-bottom">
                                        <div class="col-lg-4">
                                            <p>${formattedDate}</p>
                                        </div>
                                        <div class="col-lg-4">
                                            <p style="color: ${textColour};text-transform: capitalize;">${transaction.type}</p>
                                        </div>
                                        <div class="col-lg-4">
                                            <p>${transaction.amount}</p>
                                        </div>
                                    </div>
                                `;
                                $('#transaction-list').append(transactionRow);
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        $('#transaction-list').html(
                            '<p class="text-danger text-center">Failed to load transactions.</p>');
                    }
                });
            }

            loadTransactions();

        });
    </script>
@endsection
