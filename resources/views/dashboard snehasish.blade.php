@extends('layouts.app')

@section('title','Dashboard')

@section('css')
<style>
    .order-card {
        color: #fff;
    }

    .bg-c-blue {
        background: linear-gradient(45deg,#4099ff,#73b4ff);
    }

    .bg-c-green {
        background: linear-gradient(45deg,#2ed8b6,#59e0c5);
    }

    .bg-c-yellow {
        background: linear-gradient(45deg,#FFB64D,#ffcb80);
    }

    .bg-c-pink {
        background: linear-gradient(45deg,#FF5370,#ff869a);
    }

    .bg-c-red {
        background: linear-gradient(45deg, #FF5370, #ff869a);
    }

    .bg-c-purple {
        background: linear-gradient(45deg, #a56bf0, #c39df6);
    }


    .card {
        border-radius: 5px;
        -webkit-box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16);
        box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16);
        border: none;
        margin-bottom: 30px;
        -webkit-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
    }

    .card .card-block {
        padding: 25px;
    }

    .order-card i {
        font-size: 26px;
    }

    .f-left {
        float: left;
    }

    .f-right {
        float: right;
    }

    canvas {
        width: 100% !important;
        height: 250px !important;
    }
</style>
@endsection

@section('content')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Dashboard</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Analysis</li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->

<div class="row">

    @if(Auth::user()->hasRole('Driver'))

    <div class="col-md-4 col-xl-3">
        <div class="card bg-c-pink order-card">
            <a href="javascript:void(0);">
                <div class="card-block">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="m-b-20 text-light">{{ Auth::user()->name }}</h6>
                            @if(!empty($deliveries[0]))
                            <h6 class="text-right text-light">Vehicle No. {{ $deliveries[0]->vehicle->vehicle_number }} </h6>
                            @else
                            <h4 class="text-right text-light">Not Assigned</h4>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <i class='material-icons-outlined fs-1 text-light'>route</i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-md-4 col-xl-3">
        <div class="card bg-c-blue order-card">
            <a href="javascript:void(0);">
                <div class="card-block">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="m-b-20 text-light">Total Assigned</h6>
                        <h2 class="text-right text-light"><span>{{ $totalAssignedShops ?? 0 }}</span></h2>
                        </div>
                        <div class="col-md-4">
                            <i class='material-icons-outlined fs-1 text-light'>store</i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-md-4 col-xl-3">
        <div class="card bg-c-green order-card">
            <a href="javascript:void(0);">
                <div class="card-block">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="m-b-20 text-light">Total Delivered</h6>
                        <h2 class="text-right text-light"><span>{{ $totalDelivered ?? 0 }}</span></h2>
                        </div>
                        <div class="col-md-4">
                            <i class='material-icons-outlined fs-1 text-light'>check_circle</i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-md-4 col-xl-3">
        <div class="card bg-c-yellow order-card">
            <a href="javascript:void(0);">
                <div class="card-block">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="m-b-20 text-light">Total Pending</h6>
                        <h2 class="text-right text-light"><span>{{ $totalPending ?? 0 }}</span></h2>
                        </div>
                        <div class="col-md-4">
                            <i class='material-icons-outlined fs-1 text-light'>schedule</i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @if(!empty($deliveries[0]))
    <div class="col-lg-12 col-xxl-12 d-flex align-items-stretch">
        <div class="card w-100 rounded-4">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class=""><h5 class="mb-0">Assigned Shops</h5></div>
                </div>
                {{-- <div class="order-search position-relative my-3">
                    <input class="form-control rounded-5 px-5" type="text" placeholder="Search">
                    <span class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50">search</span>
                </div> --}}
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Shop Name</th>
                                <th>Address</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($deliveries[0]->shops as $shop)
                            @php
                                // Find the matching delivery status for this shop
                                $shopDelivery = $deliveries[0]->deliveryScheduleShops->firstWhere('shop_id', $shop->id);
                                $status = $shopDelivery ? $shopDelivery->is_delivered : null;
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if($shop->hasMedia('shop-image'))
                                        <div class="">
                                            <img src="{{ $shop->getFirstMediaUrl('shop-image') }}" class="rounded-circle" width="50" height="50" alt="">
                                        </div>
                                        @endif
                                        <p class="mb-0">{{ $shop->shop_name }}</p>
                                    </div>
                                </td>
                                <td>{{ $shop->shop_address }}</td>
                                <td>
                                    Contact Person : {{ $shop->shop_contact_person_name }} <br>
                                    Contact Number : {{ $shop->shop_contact_person_phone }}
                                </td>
                                <td>
                                    @if ($status === 1)
                                        <p class="dash-lable mb-0 bg-success bg-opacity-10 text-success rounded-2">Completed</p>
                                    @elseif ($status === 0)
                                        <p class="dash-lable mb-0 bg-warning bg-opacity-10 text-warning rounded-2">Pending</p>
                                    @elseif ($status === 2)
                                        <p class="dash-lable mb-0 bg-danger bg-opacity-10 text-danger rounded-2">Not Delivered</p>
                                    @endif
                                </td>
                                <td>
                                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $shop->shop_latitude ?? '' }},{{ $shop->shop_longitude ?? '' }}" target="_blank" class="btn btn-primary btn-sm">Navigate</a>
                                    @if ($status === 0)
                                    <button class="btn btn-success btn-sm deliver-btn" data-id="{{ $shopDelivery->id ?? '' }}">Delivered</button>
                                    <button class="btn btn-danger btn-sm not-delivered-btn" data-id="{{ $shopDelivery->id ?? '' }}">Not Delivered</button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- OTP Modal -->
    <div class="modal fade" id="otpModal" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 py-2">
                    <h5 class="modal-title">Enter OTP</h5>
                    <a href="javascript:;" class="primaery-menu-close" data-bs-dismiss="modal">
                        <i class="material-icons-outlined">close</i>
                    </a>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="delevery_id" id="delevery_id">
                    <input type="text" id="otpInput" class="form-control" placeholder="Enter OTP">
                    <button class="btn btn-primary mt-2" id="verifyOtp">Verify OTP</button>
                    <button class="btn btn-warning mt-2" id="resendOtp">Resend OTP</button>
                </div>   
            </div>
        </div>
    </div>

    <!-- Reason Modal -->
    <div class="modal fade" id="reasonModal" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 py-2">
                    <h5 class="modal-title">Enter Reason</h5>
                    <a href="javascript:;" class="primaery-menu-close" data-bs-dismiss="modal">
                        <i class="material-icons-outlined">close</i>
                    </a>
                </div>
                <div class="modal-body">
                    <textarea id="reasonInput" class="form-control" placeholder="Enter reason"></textarea>
                    <button class="btn btn-danger mt-2" id="submitReason">Submit</button>
                </div>   
            </div>
        </div>
    </div>
    @endif
    @else
        <!--<div class="col-md-4 col-xl-3">-->
        <!--    <div class="card bg-c-blue order-card">-->
        <!--        <a href="{{ route('system-user.index') }}">-->
        <!--            <div class="card-block">-->
        <!--                <div class="row align-items-center">-->
        <!--                    <div class="col-md-8">-->
        <!--                        <h6 class="m-b-20 text-light">System User</h6>-->
        <!--                    <h2 class="text-right text-light"><span>{{ $system_user ?? 0 }}</span></h2>-->
        <!--                    </div>-->
        <!--                    <div class="col-md-4">-->
        <!--                        <i class='material-icons-outlined fs-1 text-light'>admin_panel_settings</i>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </a>-->
        <!--    </div>-->
        <!--</div>-->

        <!-- Company & Structure Section -->
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-purple order-card">
                <a href="{{ route('company.index') }}">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="m-b-20 text-light">Total Company</h6>
                                <h2 class="text-right text-light"><span>{{ $company ?? 0 }}</span></h2>
                            </div>
                            <div class="col-md-4">
                                <i class='material-icons-outlined fs-1 text-light'>business</i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-purple order-card">
                <a href="{{ route('branch.index') }}">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="m-b-20 text-light">Total Branch</h6>
                                <h2 class="text-right text-light"><span>{{ $branch ?? 0 }}</span></h2>
                            </div>
                            <div class="col-md-4">
                                <i class='material-icons-outlined fs-1 text-light'>corporate_fare</i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- People Section -->
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-blue order-card">
                <a href="{{ route('employee.index') }}">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="m-b-20 text-light">Total Employee</h6>
                                <h2 class="text-right text-light"><span>{{ $employee ?? 0 }}</span></h2>
                            </div>
                            <div class="col-md-4">
                                <i class='material-icons-outlined fs-1 text-light'>badge</i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-blue order-card">
                <a href="{{ route('driver.index') }}">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="m-b-20 text-light">Total Driver</h6>
                                <h2 class="text-right text-light"><span>{{ $driver ?? 0 }}</span></h2>
                            </div>
                            <div class="col-md-4">
                                <i class='material-icons-outlined fs-1 text-light'>drive_eta</i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Assets Section -->
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-green order-card">
                <a href="{{ route('vehicle.index') }}">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="m-b-20 text-light">Total Vehicles</h6>
                                <h2 class="text-right text-light"><span>{{ $vehicle ?? 0 }}</span></h2>
                            </div>
                            <div class="col-md-4">
                                <i class='material-icons-outlined fs-1 text-light'>local_shipping</i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Business Operations Section -->
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-yellow order-card">
                <a href="{{ route('shop.index') }}">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="m-b-20 text-light">Total Shop</h6>
                                <h2 class="text-right text-light"><span>{{ $shop ?? 0 }}</span></h2>
                            </div>
                            <div class="col-md-4">
                                <i class='material-icons-outlined fs-1 text-light'>store</i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Delivery & Tasks Section -->
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-red order-card">
                <a href="{{ route('delivery-schedule.index') }}">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="m-b-20 text-light">Total Delivery</h6>
                                <h2 class="text-right text-light"><span>{{ $delivery_schedule ?? 0 }}</span></h2>
                            </div>
                            <div class="col-md-4">
                                <i class='material-icons-outlined fs-1 text-light'>assignment</i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-red order-card">
                <a href="{{ route('delivery-schedule.index') }}">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="m-b-20 text-light">Total Task</h6>
                                <h2 class="text-right text-light"><span>{{ $delivery_schedule_task ?? 0 }}</span></h2>
                            </div>
                            <div class="col-md-4">
                                <i class='material-icons-outlined fs-1 text-light'>list_alt</i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-green order-card">
                <a href="{{ route('delivery-schedule.index') }}">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="m-b-20 text-light">Total Complete Task</h6>
                                <h2 class="text-right text-light"><span>{{ $total_completed_task ?? 0 }}</span></h2>
                            </div>
                            <div class="col-md-4">
                                <i class='material-icons-outlined fs-1 text-light'>check_circle</i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-red order-card">
                <a href="{{ route('delivery-schedule.index') }}">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="m-b-20 text-light">Total Cancelled Task</h6>
                                <h2 class="text-right text-light"><span>{{ $total_cancelled_task ?? 0 }}</span></h2>
                            </div>
                            <div class="col-md-4">
                                <i class='material-icons-outlined fs-1 text-light'>cancel</i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4 col-xl-3">
                    <div class="card order-card h-100">
                        <canvas id="userRoleChart"></canvas>
                    </div>
                </div>
        
                <div class="col-md-4 col-xl-5">
                    <div class="card order-card h-100">
                        <canvas id="taskOverview"></canvas>
                    </div>
                </div>
        
                <div class="col-md-4 col-xl-4">
                    <div class="card order-card h-100">
                        <canvas id="growthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@endsection

@section('scripts')
<script>
    $(document).on('click', '.deliver-btn', function() {
        $('#delevery_id').val($(this).data('id'));
        $('#otpModal').modal('show');
    });

    $('#verifyOtp').click(function() {
        var button = $(this); // Get the button reference
        var originalText = button.text(); // Save original button text

        var otp = $('#otpInput').val(); // Get OTP input

        // Check if OTP is empty (optional)
        if (!otp) {
            round_error_noti("OTP is required!");
            return;
        }

        button.text("Processing...").prop("disabled", true);
        $.ajax({
            url:'{{ route("delivery.update") }}',
            type:"POST",
            data:{status:'delivered',delivery_id:$('#delevery_id').val(),otp: $('#otpInput').val(), _token:"{{ csrf_token() }}"},
            success:function(resp){
                if (resp.success) {
                    round_success_noti(resp.message);
                    location.reload();
                } else {
                    round_error_noti(resp.message || "Something went wrong!");
                    button.text(originalText).prop("disabled", false); // Restore button
                }
            },
            error: function(xhr) {
                button.text(originalText).prop("disabled", false); // Restore button

                if (xhr.status === 400) {
                    round_error_noti(xhr.responseJSON.error || "Invalid request!"); // Handle 400 error
                } else if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = "";
                    $.each(errors, function(key, value) {
                        errorMessage += value[0] + "\n"; // Collect all validation errors
                    });
                    round_warning_noti(errorMessage);
                } else if (xhr.status === 500) {
                    round_error_noti("Server error! Please try again.");
                } else {
                    round_error_noti("Something went wrong! Please try again.");
                }
            }
        });
    });

    $('#resendOtp').click(function() {
        var button = $(this); // Get the button reference
        var originalText = button.text(); // Save original button text

        button.text("Processing...").prop("disabled", true);
        $.ajax({
            url:'{{ route("delivery.resend-otp") }}',
            type:"POST",
            data:{delivery_id:$('#delevery_id').val(), _token:"{{ csrf_token() }}"},
            success:function(resp){
                if (resp.success) {
                    round_success_noti(resp.message);
                    button.text(originalText).prop("disabled", false);
                    // location.reload();
                } else {
                    round_error_noti(resp.message || "Something went wrong!");
                    button.text(originalText).prop("disabled", false); // Restore button
                }
            },
            error: function(xhr) {
                button.text(originalText).prop("disabled", false); // Restore button

                if (xhr.status === 400) {
                    round_error_noti(xhr.responseJSON.error || "Invalid request!"); // Handle 400 error
                } else if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = "";
                    $.each(errors, function(key, value) {
                        errorMessage += value[0] + "\n"; // Collect all validation errors
                    });
                    round_warning_noti(errorMessage);
                } else if (xhr.status === 500) {
                    round_error_noti("Server error! Please try again.");
                } else {
                    round_error_noti("Something went wrong! Please try again.");
                }
            }
        });
    });

    $(document).on('click', '.not-delivered-btn', function() {
        $('#delevery_id').val($(this).data('id'));
        $('#reasonModal').modal('show');
    });

    $('#submitReason').click(function() {
        var button = $(this); // Get the button reference
        var originalText = button.text(); // Save original button text

        var reasonInput = $('#reasonInput').val(); // Get OTP input

        // Check if OTP is empty (optional)
        if (!reasonInput) {
            round_error_noti("Reason is required!");
            return;
        }

        button.text("Processing...").prop("disabled", true);
        $.ajax({
            url:'{{ route("delivery.update") }}',
            type:"POST",
            data:{status:'delivered_cancel',delivery_id:$('#delevery_id').val(),reason: $('#reasonInput').val(), _token:"{{ csrf_token() }}"},
            success:function(resp){
                if (resp.success) {
                    round_success_noti(resp.message);
                    location.reload();
                } else {
                    round_error_noti(resp.message || "Something went wrong!");
                    button.text(originalText).prop("disabled", false); // Restore button
                }
            },
            error: function(xhr) {
                button.text(originalText).prop("disabled", false); // Restore button

                if (xhr.status === 400) {
                    round_error_noti(xhr.responseJSON.error || "Invalid request!"); // Handle 400 error
                } else if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = "";
                    $.each(errors, function(key, value) {
                        errorMessage += value[0] + "\n"; // Collect all validation errors
                    });
                    round_warning_noti(errorMessage);
                } else if (xhr.status === 500) {
                    round_error_noti("Server error! Please try again.");
                } else {
                    round_error_noti("Something went wrong! Please try again.");
                }
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const userRoleChart = new Chart(document.getElementById('userRoleChart'), {
        type: 'pie',
        data: {
            labels: ['Company', 'Branch', 'Employee', 'Driver', 'System User'],
            datasets: [{
                data: [
                    {{ $company }},
                    {{ $branch }},
                    {{ $employee }},
                    {{ $driver }},
                    {{ $system_user }}
                ],
                backgroundColor: ['#4caf50', '#2196f3', '#ff9800', '#f44336', '#9c27b0']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'User Role Distribution'
                }
            }
        }
    });
</script>

<script>
    const taskOverview = new Chart(document.getElementById('taskOverview'), {
        type: 'bar',
        data: {
            labels: ['Total Tasks', 'Completed', 'Cancelled'],
            datasets: [{
                label: 'Task Count',
                data: [
                    {{ $delivery_schedule_task }},
                    {{ $total_completed_task }},
                    {{ $total_cancelled_task }}
                ],
                backgroundColor: ['#2196f3', '#4caf50', '#f44336']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: { display: true, text: 'Task Summary' }
            }
        }
    });
</script>

<script>
    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

    const growthChart = new Chart(document.getElementById('growthChart'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Vehicles Added',
                    data: [@foreach(range(1,12) as $m) {{ $vehicleGrowth[$m] ?? 0 }}, @endforeach],
                    borderColor: '#4caf50',
                    tension: 0.3
                },
                {
                    label: 'Deliveries Created',
                    data: [@foreach(range(1,12) as $m) {{ $deliveryGrowth[$m] ?? 0 }}, @endforeach],
                    borderColor: '#2196f3',
                    tension: 0.3
                }
            ]
        },
        options: {
            plugins: {
                title: { display: true, text: 'Monthly Growth Trends' }
            },
            responsive: true
        }
    });
</script>
@endsection