@extends('layouts.app')

@section('title')
    Delivery Schedule
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Flatpickr JS -->

    {{-- flatpickr --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">



    <style>
        .edit-task-selector {
            padding: 3px !important;
        }

        #shopMap {
            position: static !important;
            overflow: visible !important;
        }

        /* Base Reset */
        html,
        body {
            height: 100%;
            margin: 0;
            overflow: hidden;
        }

        /* Topbar */
        .topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            margin-top: 55px;
            height: 50px;
            background-color: #ddd;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1rem;
            z-index: 100;
        }

        /* Sidebar */
    .sidebar {
    /* height: 90vh; */
    position: fixed;
    top: 100px;
    left: 12px;
    width: 480px;
    background: #fff;
    overflow-y: auto;
    transition: transform 0.3s ease;
    transform: translateX(-100%);
    z-index: 50;
    margin-top: -30px;
    border-radius: 15px;
}

        .sidebar.show {
            transform: translateX(0);
        }

        /* Map Container */
        .map-container {
            position: absolute;
            top: 50px;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: calc(100vh - 50px);
            transition: margin-left 0.3s;
        }

        .sidebar.show~.map-container {
            /* margin-left: 700px;
                                width: calc(100% - 700px); */
            width: 100%;
        }

        /* Location List */
        #locations {
            height: 430px;
            overflow-y: auto;
        }

        /* Location Box */
        .location-box {
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 10px;
            overflow: hidden;
        }

        .location-header {
            background-color: #f5f5f5;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            cursor: pointer;
        }

        .location-content {
            padding: 10px;
        }

        .toggle-icon.rotate {
            transform: rotate(180deg);
            transition: transform 0.3s ease;
        }

        /* Task Section */
        .task-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            /* width: 100%; */
            padding: 0 10px;
            background-color: #349eff;
            color: white;
            font-weight: bold;
            font-size: 16px;
            /* margin-bottom: 10px; */
        }

        /* Task Item */
        .item-row {
            padding: 0.5rem;
            background-color: #fafafa;
            border-bottom: 1px solid #eee;
            border-radius: 5px;
            margin-bottom: 4px;
        }

        /* Buttons */
        .btn-icon {
            background: none;
            border: none;
            color: #dc3545;
            margin-left: 0.5rem;
        }

        .btn-submit {
            width: 48%;
        }

        /* Bottom Button Container */
        .bottom-button-container {
            position: fixed;
            bottom: 0;
            left: 0;
            padding: 16px;
            background-color: #fff;
            border-top: 1px solid #ddd;
            z-index: 20;
            width: 480px;
            margin-top: -555px;
        }

        /* Responsive Adjustments */

        @media only screen and (min-width: 1366px) and (max-width: 1600px) {
            #locations {
                height: 58vh;
                overflow-y: auto;
            }
        }

        @media (max-width: 1199px) {
            .bottom-button-container {
                width: 100%;
            }
        }

        @media(max-width:1050px) {
            #locations {
                height: 430px;
                overflow-y: auto;
            }

            .sidebar {
                width: 40%;
            }
        }

        @media (max-width: 770px) {
            .sidebar {
                width: 50%;
            }

            .sidebar.show~.map-container {
                margin-left: 0;
                width: 100%;
            }

            #locations {
                height: 450px;
                overflow-y: auto;
            }


        }

        /* Delivery Note Offcanvas */
        .delivery-note-overlay {
            position: fixed;
            z-index: 1065;
            width: 100%;
            max-width: 480px;
            background: white;
            transition: transform 0.3s ease-in-out;
        }

        .offcanvas-backdrop {
            z-index: 1064 !important;
            background-color: rgba(0, 0, 0, 0.2);
        }

        body.offcanvas-open {
            overflow: hidden;
        }

        /* Delivery Note Input Fields */
        .custom-input-wrapper {
            position: relative;
            width: 100%;
        }

        .custom-floating-label {
            position: absolute;
            top: -0.6rem;
            left: 1rem;
            background: white;
            padding: 0 5px;
            font-size: 0.75rem;
            color: #666;
            z-index: 1;
        }

        .custom-input {
            border: 1px solid #bbb;
            border-radius: 6px;
            padding: 12px;
            font-size: 0.875rem;
            /* margin-bottom: 25px; */
        }
    </style>
    <style>
        /* Default state - show top-header and primary-menu */
        .top-header {
            display: block;
        }

        .primary-menu {
            display: block;
        }

        /* Hide top-header when screen width is 1366px or more (full screen) */
        @media screen and (min-width: 1366px) {
            .top-header {
                display: none;
            }

            .primary-menu .navbar {
                top: 0;
            }

            .main-wrapper {
                margin-top: 50px;
            }
        }
    </style>

    <style>
        .order-card {
            color: #fff;
        }

        .bg-c-blue {
            background: linear-gradient(45deg, #4099ff, #73b4ff);
        }

        .bg-c-green {
            background: linear-gradient(45deg, #2ed8b6, #59e0c5);
        }

        .bg-c-yellow {
            background: linear-gradient(45deg, #FFB64D, #ffcb80);
        }

        .bg-c-pink {
            background: linear-gradient(45deg, #FF5370, #ff869a);
        }


        .card {
            border-radius: 5px;
            -webkit-box-shadow: 0 1px 2.94px 0.06px rgba(4, 26, 55, 0.16);
            box-shadow: 0 1px 2.94px 0.06px rgba(4, 26, 55, 0.16);
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
    </style>

    <style>
        .product-row {
            display: flex;
            gap: 5px;
            margin-bottom: 8px;
        }

        .product-title {
            flex: 1;
            /* take remaining space */
        }

        .product-unit {
            width: 120px;
            /* fixed width for select */
        }

        .product-qty {
            width: 80px;
            /* enough for ~5 digits */
            /* text-align: right; */
        }

        .product-row .remove-row,
        .product-row .add-row {
            height: 46px;
            padding: 0 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
        }

        .product-row .remove-row i,
        .product-row .add-row i {
            font-size: 18px;
        }

        .show-important {
            display: flex !important;
        }

        .hide-important {
            display: none !important;
        }
    </style>

    <style>
        /* Updated Code */
        @media screen and (min-width: 1440px) {
            .sidebar {
                top: 150px !important;
                height: 86vh !important;
            }
        }

        @media screen and (min-width: 1024px) {
            .sidebar {
                top: 106px !important;
                height: 88vh;
            }
        }

        @media screen and (min-width: 768px) {
            .sidebar {
                top: 108px !important;
                height: 88vh;
            }
        }

        @media screen and (min-width: 425px) {
            .sidebar {
                top: 108px !important;
                height: 84vh;
            }
        }
        a#openDeliveryNoteBtn {
    float: right;
    text-align: right;
}

/*--------------------------------New css 7-7-2026---------------------------------------------*/
/
.modal-body .row {
    display: flex;
    flex-direction: column;
}


.mb-3 {
    display: flex;
    align-items: center; 
    width: 100%;         
    gap: 20px;       
    margin-bottom: 15px;
}


.mb-3 label {
    flex: 0 0 200px;     
    margin-bottom: 0;
    font-weight: 600;
}

.mb-3 .form-control, 
.mb-3 .input-group, 
.mb-3 select {
    flex: 1;    
    width: 100%;
}

.sidebar {
    top: 138px !important;
    height: 82vh !important;
}
div#deliveryNote {
    margin-top: 0 !important;
}

div#suggestions {
    top: 40px;
    display: none;
}


/* ========================================================================== */
/* MOBILE RESPONSIVE FIXES FOR DELIVERY SCHEDULE & MAP LAYOUT                */
/* ========================================================================== */

@media (max-width: 991.98px) {
    /* Allow normal body scrolling on mobile */
    html, body {
        overflow: auto !important;
        height: auto !important;
    }

    /* Topbar adjustments */
    .topbar {
        position: relative !important;
        margin-top: 0 !important;
        height: auto !important;
        padding: 10px !important;
    }

    /* Make Sidebar full width and relative/stacked on mobile */
    .sidebar {
        position: relative !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: auto !important;
        max-height: none !important;
        transform: none !important;
        margin: 10px 0 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
    }

    /* Make map container stack beneath the sidebar cleanly */
    .map-container {
        position: relative !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 400px !important; /* Fixed touch-friendly height for mobile maps */
        margin-bottom: 80px;
    }

    /* Fix locations list height on mobile */
    #locations {
        height: auto !important;
        max-height: 350px !important;
        overflow-y: auto !important;
    }

    /* Fix bottom button container positioning for mobile */
    .bottom-button-container {
        position: relative !important;
        width: 100% !important;
        left: 0 !important;
        bottom: 0 !important;
        margin-top: 15px !important;
        box-shadow: none !important;
    }

    /* Offcanvas Delivery Note overlay full width */
    .delivery-note-overlay {
        max-width: 100% !important;
        width: 100% !important;
    }

    /* Fix Offcanvas fixed submit button bounds */
    #ofcanvus-submit-btn {
        width: 100% !important;
        left: 0 !important;
        padding: 10px 15px !important;
        box-sizing: border-box;
    }

    /* Form rows full stack */
    .product-row {
        flex-wrap: wrap !important;
    }

    .product-title, 
    .product-unit, 
    .product-qty {
        width: 100% !important;
        flex: 100% !important;
        margin-bottom: 5px;
    }
}

@media (max-width: 575.98px) {
    /* Extra spacing adjustments for smaller phones */
    .main-wrapper {
        padding: 5px !important;
    }
    
    .task-header {
        font-size: 14px !important;
        padding: 5px !important;
    }
}






    </style>
    
  <style>
  /* ==========================================================
     MOBILE VIEW: FORM (SIDEBAR) UPOR EBNGB MAP NICHE
     ========================================================== */
  @media (max-width: 991.98px) {
    /* Pura d-flex container ke column direction kora, jate form age ase */
    .d-flex:has(.sidebar) {
      display: flex !important;
      flex-direction: column !important;
    }

    /* Sidebar/Form ke upore rakha */
    .sidebar {
      order: 1 !important;
      position: relative !important;
      top: 0 !important;
      left: 0 !important;
      width: 100% !important;
      max-width: 100% !important;
      height: auto !important;
      max-height: none !important;
      transform: none !important;
      margin: 0 0 15px 0 !important;
      border-radius: 12px !important;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08) !important;
    }

    /* Map-ke tar niche ba tar pore rakha */
    .map-container {
      order: 2 !important;
      position: relative !important;
      top: 0 !important;
      left: 0 !important;
      width: 100% !important;
      height: 380px !important;
      margin-bottom: 20px !important;
      border-radius: 12px;
      overflow: hidden;
    }

    #shopMap {
      width: 100% !important;
      height: 100% !important;
    }
  }
</style>
    
    
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
@endsection

@section('content')
    <!--breadcrumb-->
    <div class="offcanvas offcanvas-start delivery-note-overlay" tabindex="-1" id="deliveryNote" aria-modal="true"
        role="dialog" style="width: 100%; margin-top:0px;">

        <form id="task-form">
            <div class="offcanvas-header border-bottom mt-2">
                <h6 class="offcanvas-title fw-bold">DELIVERY NOTE</h6>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                <button type="submit" class="btn-close" id="edit-submit-close-btn" style="display: none;"></button>
            </div>

            <div class="offcanvas-body" style="position: relative; height: 80vh;  overflow-y: auto;"
                style="margin-bottom:50px;">

                {{-- <div class="custom-input-wrapper mb-3">
                    <label class="custom-floating-label">DELIVERY DATE</label>
                    <input type="text" id="delivery_date" class="form-control custom-input" placeholder="DD/MM/YYYY">
                </div> --}}

                <div class="custom-input-wrapper mb-3">
                    <label class="custom-floating-label">Invoice No.</label>
                    <input type="text" class="form-control custom-input" name="invoice_no" placeholder="9999999999">
                </div>

                <input type="hidden" name="shop_id" id="shop_id">
                <input type="hidden" name="shop_name" id="shop_name">
                <input type="hidden" name="shop_addresse" id="shop_addresse">
                <input type="hidden" name="shop_latitude" id="shop_latitude">
                <input type="hidden" name="shop_longitude" id="shop_longitude">

                <div class="custom-input-wrapper mb-3">
                    <label class="custom-floating-label">
                        Booking From
                    </label>
                    @if (auth()->user()->hasRole('Employee'))
                        <input type="text" value="{{ auth()->user()->employee?->branch?->name ?? 'N/A' }}"
                            class="form-control custom-input" placeholder="Type here..." readonly>
                        <input type="hidden" value="{{ auth()->user()->employee?->branch?->id ?? '' }}" name="branch_id"
                            id="branch_id">
                    @endif

                    @if (auth()->user()->hasRole('Branch'))
                        <input type="text" value="{{ auth()->user()->name ?? 'N/A' }}" class="form-control custom-input"
                            placeholder="Type here..." readonly>
                        <input type="hidden" value="{{ auth()->user()->id ?? '' }}" name="branch_id" id="branch_id">
                    @endif

                    @if (! auth()->user()->hasRole('Employee') && ! auth()->user()->hasRole('Branch'))
                        <input type="hidden" name="branch_id" id="branch_id" value="">
                    @endif
                </div>

                <div class="custom-input-wrapper mb-3">
                    <label class="custom-floating-label">
                        Dastination To
                        <a class="" data-bs-toggle="modal" data-bs-target="#quickShopAddModal">
                            <i class="text-primary" data-feather="plus"></i>
                        </a>
                    </label>
                    <input type="text" class="form-control custom-input" id="search_shop_name" name="consignee_details"
                        placeholder="Type here...">
                    <div id="suggestions" class="list-group position-absolute z-index-3"
                        style="z-index: 99999; height: 100px; overflow-y: auto; width: 95%;background: white;">
                    </div>
                </div>

                {{-- <div class="custom-input-wrapper mb-3">
                    <label class="custom-floating-label">PRODUCTS</label>
                    <input type="text" class="form-control custom-input" placeholder="Type here...">
                </div>

                <div class="custom-input-wrapper mb-3">
                    <label class="custom-floating-label">TOTAL (PCS)</label>
                    <input type="text" class="form-control custom-input" placeholder="9999999999">
                </div> --}}

                <div id="productTotalSection" style="display:none;">
                    <div id="product-container">
                        <div class="product-row">
                            <input type="text" placeholder="Title" class="product-title form-control custom-input">
                            <select class="product-unit form-select custom-input">
                                <option value="unit">Unit</option>
                                <option value="box">Box</option>
                            </select>
                            <input type="number" min="1" value="1"
                                class="product-qty form-control custom-input">
                            <button type="button" class="remove-row btn btn-sm btn-danger" onclick="removeRow(this)">
                                <i class="material-icons-outlined">delete</i>
                            </button>
                            <button type="button" class="add-row btn btn-sm btn-success" onclick="addRow()">
                                <i class="material-icons-outlined">add</i>
                            </button>
                        </div>
                    </div>


                    {{-- <button type="button" class="btn btn-sm btn-success" onclick="addRow()">+ Add More</button> --}}

                    <div class="custom-input-wrapper mb-3" style="margin-top: 15px;">
                        <label class="custom-floating-label">TOTAL</label>
                        <input type="text" id="total-pcs" class="form-control custom-input" placeholder="0">
                    </div>
                </div>

                {{-- <div class="custom-input-wrapper mb-3">
                    <label class="custom-floating-label">ITEM DESCRIPTION WITH
                    (PCS)</label>
                    <input type="text" class="form-control custom-input" placeholder="Type here...">
                </div> --}}

                <div class="custom-input-wrapper mb-3">
                    <label class="custom-floating-label">PAYMENT DETAILS</label>
                    <select class="form-control form-select custom-input payment-type" name="payment_type">
                        <option value="" selected disabled>Select Type</option>
                        <option value="Pre-Paid">Pre-Paid</option>
                        <option value="COD">COD</option>
                    </select>
                </div>

                <div class="custom-input-wrapper mb-3" style="margin-top: 15px;">
                    <label class="custom-floating-label">TOTAL Amount</label>
                    <input type="number" class="form-control custom-input amount" name="amount"
                        placeholder="Enter amount">
                </div>

                {{-- <div class="custom-input-wrapper mb-3">
                    <label class="custom-floating-label">VEHICLE DETAILS</label>
                    <input type="text" class="form-control custom-input" placeholder="Vehicle Details">
                </div>

                <div class="custom-input-wrapper mb-3">
                    <label class="custom-floating-label">DRIVER DETAILS</label>
                    <input type="text" class="form-control custom-input" placeholder="Driver Details">
                </div> --}}

                {{-- <button type="submit" class="btn btn-primary text-uppercase fw-bold" style="position: fixed; bottom: 0; margin-bottom: 20px;width: 59%; z-index: 555px;">Add Delivery Task</button> --}}
                <div id="ofcanvus-submit-btn"
                    style="position: fixed; bottom: 0; background: white; padding: 10px 0; z-index: 10; width: 448px;">
                    <button type="submit" class="btn btn-primary text-uppercase fw-bold w-100">
                        Add Delivery Task
                    </button>
                </div>

                <div class="d-grid">
                </div>
            </div>
        </form>

        <!-- <div id="ofcanvus-submit-btn" style="position: sticky; bottom: 0; background: white; padding: 10px 0; z-index: 10; margin:0 15px;">
                                            <button type="submit" class="btn btn-primary text-uppercase fw-bold w-100">
                                                Add Delivery Task
                                            </button>
                                </div> -->
    </div>

    <form class="needs-validation" action="{{ route('delivery-schedule.update', $delivery_Schedule->id) }}" method="post" novalidate enctype="multipart/form-data">
        @csrf
        @method('PUT')
        {{-- <input type="hidden" name="shop_ids" id="shop_ids"> --}}
        <div class="d-flex ">
            <!-- Sidebar -->
            <div class="sidebar show" id="sidebar" style="z-index:5">
                <div class="d-flex task-header">
                    <div class="task-header">
                        <div class="py-1 px-2 rounded-circle  bg-white"><i class="bi bi-pencil-square text-black"></i>
                        </div>
                        Create Task
                    </div>




                    <div class="mb-2 mt-4">
                        {{-- <a href="javascript:void(0);" data-bs-toggle="offcanvas" data-bs-target="#deliveryNote"
                            class="btn-add text-decoration-none text-white">
                            <i class="bi bi-plus-circle me-1 display-6"></i>
                        </a> --}}
                        
                       
                            <input type="text" id="delivery_date" name="delivery_date"
                                value="{{ old('delivery_date', fromDbDate($delivery_Schedule->delivery_date)) }}" class="form-control" placeholder="DD/MM/YYYY">
                            <div class="invalid-feedback">Please enter a delivery date.</div>


                    </div>
                </div>

                <div class="p-3">
                    <div class="row p-1">
                        
                        <div class="custom-input-wrapper p-0 edit-task-selector">
                            <select class="form-select single-select-field" name="vehicle_id" id="vehicle_id" required>
                                <option value="" selected disabled>Select Vehicle</option>


                                @foreach ($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}"
                                        {{ old('vehicle_id', $delivery_Schedule->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                                        {{ $vehicle->name }} | {{ $vehicle->vehicle_number }}
                                    </option>
                                @endforeach
                                <option value="add_new">➕ Add New Vehicle</option>
                            </select>
                            <div class="invalid-feedback">Please select a vehicle.</div>
                        </div>

                        <div class="p-0 edit-task-selector">
                            <select class="form-select single-select-field" name="driver_id" id="driver_id" required>
                                <option value="" selected disabled>Select Driver</option>

                                @foreach ($drivers as $driver)
                                    <option value="{{ $driver->id }}"
                                        {{ old('driver_id', $delivery_Schedule->driver_id) == $driver->id ? 'selected' : '' }}>
                                        {{ $driver->name }} ({{ $driver->phone }})
                                    </option>
                                @endforeach
                                <option value="add_new">➕ Add New Driver</option>
                            </select>
                            <div class="invalid-feedback">Please select a driver.</div>
                        </div>
                        
                         
                        <a href="javascript:void(0);" id="openDeliveryNoteBtn"
                            class="btn-add text-decoration-none">
                            <i class="bi bi-plus-circle me-1 display-6"></i>
                        </a>
                        
                    </div>
                    <div class="mb-3" id="searchWrapper" style="display:none;">
                        <input type="text" class="form-control" id="searchShop" placeholder="Search Shop...">
                    </div>

                    <!-- Locations -->
                    {{-- <div id="locations"></div> --}}
                    <div id="locations">

                        @foreach ($selectedShops as $index => $item)
                            @php
                                $shop = $item->shop;
                            @endphp

                            @if ($shop)
                            <!-- Reusable location card -->
                            <div class="d-flex w-100 align-items-start gap-3 mb-3 location-item" data-shop-id="{{ $shop->id }}">
                                <!-- Step Circle -->
                                <div class="bg-primary d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                    style="width: 30px; height: 30px;">
                                    <p class="text-white m-0 fw-bold">{{ $loop->iteration}}</p>
                                </div>

                                <!-- Location Box -->
                                <div class="location-box flex-grow-1 w-100">
                                    <div class="location-header p-2 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#location{{ $loop->iteration}}" style="cursor: pointer;">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-geo-alt-fill me-2 text-primary"></i>
                                            {{ $shop->shop_name }}
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-chevron-down toggle-icon"></i>
                                            <button type="button" class="btn-icon p-0 remove-location"><i class="bi bi-trash-fill"></i></button>
                                            <i class="bi bi-pencil-square edit-location"></i>
                                        </div>
                                    </div>

                                    <input type="hidden" name="shop_ids[]" value="{{ $shop->id }}">
                                    <input type="hidden" name="shop_names[]" value="{{ $shop->shop_name }}">
                                    <input type="hidden" name="shop_addresses[]" value="{{ $shop->shop_address }}">
                                    <input type="hidden" name="shop_latitudes[]" value="{{ $shop->shop_latitude }}">
                                    <input type="hidden" name="shop_longitudes[]" value="{{ $shop->shop_longitude }}">
                                    <input type="hidden" name="branch_id[]" value="{{ auth()->user()->employee?->branch?->id ?? '' }}">

                                    <div class="collapse" id="location{{ $loop->iteration }}">
                                        <div class="location-content">
                                            <div class="row g-2 mb-2">
                                                <div class="col-md-4">
                                                    <input type="text" name="invoice_nos[]" class="form-control"
                                                        value="{{ $item->invoice_no }}" placeholder="Enter Invoice">
                                                </div>
                                                <div class="col-md-4">
                                                    <select name="payment_types[]" class="form-select">
                                                        <option value="Pre-Paid" {{ $item->payment_type == 'Pre-Paid' ? 'selected' : '' }}>Pre-Paid</option>
                                                        <option value="COD" {{ $item->payment_type == 'COD' ? 'selected' : '' }}>COD</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="number" name="amounts[]" class="form-control"
                                                        value="{{ $item->amount }}" placeholder="0.0">
                                                </div>
                                            </div>

                                            @foreach($item->products as $product)
                                                <div class="item-row">
                                                    {{ $product->title }} - {{ $product->qty }} {{ ucfirst($product->unit_or_box) }}
                                                    {{-- <i class="bi bi-pencil float-end"></i> --}}
                                                </div>

                                                <!-- hidden inputs for backend -->
                                                <input type="hidden" name="product_titles[{{ $loop->parent->index+1 }}][]" value="{{ $product->title }}">
                                                <input type="hidden" name="product_qtys[{{ $loop->parent->index+1 }}][]" value="{{ $product->qty }}">
                                                <input type="hidden" name="product_units[{{ $loop->parent->index+1 }}][]" value="{{ $product->unit_or_box }}">
                                            @endforeach
                                        </div>
                                    </div>

                                </div>
                            </div>
                            @endif
                        @endforeach

                    </div>

                    <!-- Buttons -->
                    <div class="bottom-button-container">
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-primary w-50 me-2" type="button"
                                id="sortByNearestBtn">Routing</button>
                            <button class="btn btn-primary w-50" type="submit">Submit</button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- -------------------------------- -->

            <!-- Map Section -->
            <div class="map-container">
                <div id="shopMap"></div>
            </div>
        </div>
    </form>

    <div class="modal fade" id="quickShopAddModal" style="z-index: 999999;">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 bg-grd-primary py-2">
                    <h5 class="modal-title text-light">Company Add Form</h5>
                    <a href="javascript:;" class="primaery-menu-close" data-bs-dismiss="modal">
                        <i class="material-icons-outlined text-light">close</i>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="card w-100">
                            <form action="" id="shopCreateForm" class="needs-validation" novalidate>
                                @csrf
                                <div class="card-body">

                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label" for="shop_name">Shop Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" value="{{ old('shop_name') }}"
                                                name="shop_name" id="shop_name" placeholder="Enter shop name" required>
                                            <div class="valid-feedback">Looks good!</div>
                                            <div class="invalid-feedback">Please enter shop name.</div>
                                            <div id="shop_suggestions"
                                                class="list-group position-absolute w-100 z-index-3">
                                            </div>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label" for="shop_contact_person_name">Shop Contact Person
                                                Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control"
                                                value="{{ old('shop_contact_person_name') }}"
                                                name="shop_contact_person_name" id="shop_contact_person_name"
                                                placeholder="Enter shop contact person name" required>
                                            <div class="valid-feedback">Looks good!</div>
                                            <div class="invalid-feedback">Please enter shop contact person name.</div>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label" for="shop_address">Shop Address <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="shop_address" id="shop_address" class="form-control" placeholder="Enter shop address"
                                                id="shop_address" required>{{ old('shop_address') }}</textarea>
                                            <div class="valid-feedback">Looks good!</div>
                                            <div class="invalid-feedback">Please enter a valid shop address.</div>

                                            <div id="shop_address_suggestions"
                                                class="list-group position-absolute w-100 z-index-3"></div>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label" for="shop_contact_person_phone">Shop Contact Person
                                                Phone No.
                                                <span class="text-danger">*</span></label>
                                            <input type="tel" minlength="10" maxlength="10" pattern="[0-9]{10}"
                                                class="form-control" value="{{ old('shop_contact_person_phone') }}"
                                                name="shop_contact_person_phone" id="shop_contact_person_phone"
                                                placeholder="Enter shop contact person phone no." required>
                                            <div class="valid-feedback">Looks good!</div>
                                            <div class="invalid-feedback">Please enter shop contact person phone no.</div>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label" for="shop_latitude">Latitude</label>
                                            <input type="text" class="form-control"
                                                value="{{ old('shop_latitude') }}" name="shop_latitude"
                                                id="shop_latitude" placeholder="Enter latitude">
                                            <div class="valid-feedback">Looks good!</div>
                                            <div class="invalid-feedback">Please enter latitude.</div>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label" for="shop_longitude">Longitude</label>
                                            <input type="text" class="form-control"
                                                value="{{ old('shop_longitude') }}" name="shop_longitude"
                                                id="shop_longitude" placeholder="Enter longitude">
                                            <div class="valid-feedback">Looks good!</div>
                                            <div class="invalid-feedback">Please enter longitude.</div>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label" for="shop_longitude">Longitude</label>
                                            <div class="mb-3">
                                                <img class="img-thumbnail rounded me-2" id="blah" alt=""
                                                    width="200" src="" data-holder-rendered="true"
                                                    style="display: none;">
                                            </div>
                                            <div class="mb-0">
                                                <input class="form-control" name="image" type="file"
                                                    id="imgInp">

                                                <input type="hidden" name="shop_image_from_google"
                                                    id="shop_image_from_google">

                                                {{-- <img id="shop_preview" src="" alt="Shop Preview" class="img-thumbnail mt-2" style="max-width: 200px; display: none;"> --}}

                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="d-flex">

                                    <button type="button" class="btn btn-grd-danger text-light "
                                        data-bs-dismiss="modal">Close</button>


                                    <button type="submit" id="submitBtn"
                                        class="btn btn-grd-primary px-4 mx-2 text-light">Submit</button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Add New Vehicle Modal -->
    <div class="modal fade" id="addVehicleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <form id="addVehicleForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Vehicle</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="response_for" value="modal">
                            <!-- Vehicle Name -->
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name"
                                    placeholder="Enter Vehicle Name" value="{{ old('name') }}" required>
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Please enter the vehicle name.</div>
                            </div>

                            <!-- Vehicle Number -->
                            <div class="mb-3 col-md-6">
                                <label for="vehicle_number" class="form-label">Vehicle Number <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="vehicle_number" id="vehicle_number"
                                        placeholder="Enter Vehicle Number" value="{{ old('vehicle_number') }}" required>
                                    <button type="button" class="btn btn-grd-info text-light" id="modalVerifyRcBtn">Verify</button>
                                </div>
                                <small id="modalRcVerifyStatus" class="d-block mt-1"></small>
                                <input type="hidden" name="rwc_number" id="rwc_number" value="{{ old('rwc_number') }}">
                                <input type="hidden" name="rc_verification_data" id="modal_rc_verification_data">
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Please enter the vehicle number.</div>
                            </div>

                            <!-- Engine Number -->
                            <div class="mb-3 col-md-6">
                                <label for="rwc_number" class="form-label">Engine Number <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="engine_number" id="engine_number"
                                    placeholder="Enter RWC Number" value="{{ old('engine_number') }}" required>
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Please enter the Engine number.</div>
                            </div>

                            <!-- Fuel Type -->
                            <div class="mb-3 col-md-6">
                                <label for="fuel_type" class="form-label">Fuel Type</label>

                                <select class="form-select" name="fuel_type" id="fuel_type" required>
                                    <option value="0" {{ old('fuel_type') == '0' ? 'selected' : '' }}>Petrol
                                    </option>
                                    <option value="1" {{ old('fuel_type') == '1' ? 'selected' : '' }}>Disel
                                    </option>
                                </select>
                                <div class="invalid-feedback">Please enter a valid fuel type.</div>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="brand_id" class="form-label">Vehicle Type <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" name="vehicle_type" id="vehicle_type" required>
                                    <option value="" selected>Select Vehicle Type</option>
                                    @foreach ($vehicle_types as $vehicle_type)
                                        <option value="{{ $vehicle_type->id }}"
                                            {{ old('vehicle_type') == $vehicle_type->id ? 'selected' : '' }}>
                                            {{ $vehicle_type->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Please select a vehicle type.</div>
                            </div>

                            <!-- Description -->
                            <div class="mb-3 col-md-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" class="form-control" placeholder="Enter Description">{{ old('description') }}</textarea>
                                <div class="invalid-feedback">Please enter a valid description.</div>
                            </div>

                            <div class="mb-3">
                                <img class="img-thumbnail rounded me-2" id="blah" alt="" width="200"
                                    src="" data-holder-rendered="true" style="display: none;">
                            </div>
                            <div class="mb-3">
                                <input type="file" class="form-control" name="image" id="imgInp" required>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Vehicle</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Add New Driver Modal -->
    <div class="modal fade" id="addDriverModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <form id="addDriverForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Driver</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="response_for" value="modal">
                            <div class="mb-3 col-md-6">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter First name"
                                    name="first_name" id="modal_first_name" value="{{ old('first_name') }}" required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Last name"
                                    name="last_name" id="modal_last_name" value="{{ old('last_name') }}" required>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" id="email"
                                    value="{{ old('email') }}" required>
                            </div>
                            {{-- <div class="mb-3 col-md-4">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="password"
                                    value="{{ old('password') }}" required>
                            </div> --}}
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <select class="form-control input-height" name="gender" required>
                                    <option value selected disabled>Select...</option>
                                    <option value="male" @if (old('gender') == 'male') selected @endif>Male</option>
                                    <option value="female" @if (old('gender') == 'female') selected @endif>Female
                                    </option>
                                    <option value="others" @if (old('gender') == 'others') selected @endif>Others
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Mobile No. <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="phone" value="{{ old('phone') }}"
                                    required>
                            </div>
                            {{-- <div class="mb-3 col-md-4">
                                <label class="form-label">Date Of Birth <span class="text-danger"></span></label>
                                <input type="date" class="form-control" name="date_of_birth"
                                    value="{{ old('date_of_birth') }}">
                            </div> --}}


                            <div class="mb-3 col-md-4">
                                <label class="form-label">Date Of Birth <span class="text-danger"></span></label>

                                <div class="input-group">
                                    <input id="modal_date_of_birth" name="date_of_birth" type="text" class="form-control" placeholder="Select Date"
                                        readonly>

                                    <span class="input-group-text calendar-btn" id="openModalDobCalendar">
                                        <i class="bi bi-calendar3"></i>
                                    </span>
                                </div>
                            </div>





                            <div class="mb-3 col-md-4">
                                <label class="form-label">Address <span class="text-danger"></span></label>
                                <input type="text" class="form-control" name="address" id="modal_address" value="{{ old('address') }}">
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Driving License Number <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="driving_license_number"
                                        id="modal_driving_license_number" value="{{ old('driving_license_number') }}">
                                    <button type="button" class="btn btn-grd-info text-light" id="modalVerifyDlBtn">Verify</button>
                                </div>
                                <small id="modalDlVerifyStatus" class="d-block mt-1"></small>
                                <input type="hidden" name="driving_license_verification_data" id="modal_dl_verification_data">
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label">Vehicle Types <span class="text-danger">*</span></label>
                                <select class="form-control input-height" name="vehicle_type" required>
                                    <option value selected disabled>Select...</option>
                                    @foreach ($vehicle_types as $item)
                                        <option value="{{ $item->id }}"
                                            @if (old('vehicle_type') == $item->id) selected @endif>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label">Driving Exprience <span class="text-danger"></span></label>
                                <input type="text" class="form-control" name="driving_exprience"
                                    value="{{ old('driving_exprience') }}">
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Driver</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets/dashboard-assets/assets/plugins/select2/js/select2-custom.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <script>
        flatpickr("#delivery_date", {
            dateFormat: "d/m/Y",
            minDate: "today",
            closeOnSelect: true,
        });
    </script>
    <script>
        // Initialize Flatpickr on the "Add New Driver" modal's DOB input
        const modalDobPicker = flatpickr("#modal_date_of_birth", {
            dateFormat: "Y-m-d",
            allowInput: true,
        });

        // Open calendar when clicking the icon
        document.getElementById("openModalDobCalendar").addEventListener("click", function() {
            modalDobPicker.open();
        });
    </script>

    <script>
        $(document).ready(function () {
            $("#openDeliveryNoteBtn").on("click", function () {
                let isValid = true;

                // reset previous invalid states
                $("#delivery_date, #vehicle_id, #driver_id").removeClass("is-invalid");

                // check delivery date
                if ($("#delivery_date").val().trim() === "") {
                    $("#delivery_date").addClass("is-invalid");
                    isValid = false;
                }

                // check vehicle
                if (!$("#vehicle_id").val()) {
                    $("#vehicle_id").addClass("is-invalid");
                    isValid = false;
                }

                // check driver
                if (!$("#driver_id").val()) {
                    $("#driver_id").addClass("is-invalid");
                    isValid = false;
                }

                if (!isValid) {
                    return false; // stop if any invalid
                }

                $("#ofcanvus-submit-btn button[type='submit']").text("Add Delivery Task");

                $(".btn-close[data-bs-dismiss='offcanvas']").show();
                // .attr("data-bs-dismiss", "") // remove dismiss attribute
                // .attr("type", "submit")      // make it submit button
                // .attr("id", "editSubmitBtn") // optional: give ID for tracking
                // .off("click");               // remove default close behavior

                $("#edit-submit-close-btn").hide();

                // Product/Total fields stay hidden until a destination shop is picked
                $('#productTotalSection').hide();

                // Open offcanvas manually
                let offcanvas = new bootstrap.Offcanvas($("#deliveryNote")[0]);
                offcanvas.show();
            });
        });
    </script>

    
    <script>
        function addRow() {
            const container = document.getElementById('product-container');
            const row = document.createElement('div');
            row.classList.add('product-row');
            row.innerHTML = `
                <input type="text" placeholder="Title" class="product-title form-control custom-input">
                <select class="product-unit form-select custom-input">
                    <option value="unit">Unit</option>
                    <option value="box">Box</option>
                </select>
                <input type="number" min="1" value="1" class="product-qty form-control custom-input">
                <button type="button" class="remove-row btn btn-sm btn-danger" onclick="removeRow(this)">
                    <i class="material-icons-outlined">delete</i>
                </button>
                <button type="button" class="add-row btn btn-sm btn-success" onclick="addRow()">
                    <i class="material-icons-outlined">add</i>
                </button>
            `;
            container.appendChild(row);
            bindEvents(row);
            updateTotal();
        }

        function removeRow(btn) {
            const container = document.getElementById('product-container');
            const rows = container.getElementsByClassName('product-row');

            if (rows.length <= 1) {
                alert("At least one product row is required.");
                return; // stop here so the row is not deleted
            }

            btn.parentElement.remove();
            updateTotal();
        }


        function bindEvents(row) {
            row.querySelectorAll('input, select').forEach(el => {
            el.addEventListener('input', updateTotal);
            });
        }

        function updateTotal() {
            let total = 0;
            let units = new Set();

            document.querySelectorAll('.product-row').forEach(row => {
                let qty = parseInt(row.querySelector('.product-qty').value) || 0;
                let unit = row.querySelector('.product-unit').value;
                total += qty;
                units.add(unit);
            });

            let unitDisplay = (units.size === 1) ? [...units][0] : 'Mixed';
            document.getElementById('total-pcs').value = total + ' ' + unitDisplay;
        }

        // Bind events for the initial row
        bindEvents(document.querySelector('.product-row'));
    </script>

    <script>
        $(document).on('change', '.payment-type', function() {
            let $amountInput = $(this).closest('.custom-input-wrapper').next('.custom-input-wrapper').find('.amount');

            if ($(this).val() === 'Pre-Paid') {
                $amountInput.val('0.00').prop('readonly', true);
            } else if ($(this).val() === 'COD') {
                $amountInput.val('').prop('readonly', false);
            } else {
                $amountInput.val('').prop('readonly', false);
            }
        });
    </script>

    <script>
        $(document).ready(function() {

            $('#shopCreateForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: '{{ route('delivery-schedule.add_shop') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#submitBtn').prop('disabled', true).text('Saving...');
                    },
                    success: function(response) {
                        if (response.success) {
                            round_success_noti(response.message);
                            $('#shopCreateForm')[0].reset();
                            $('#quickShopAddModal').modal('hide');


                        } else {
                            round_error_noti('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'An unexpected error occurred!';
                        if (xhr.responseJSON) {
                            if (typeof xhr.responseJSON.message === 'object') {
                                errorMessage = '';
                                $.each(xhr.responseJSON.message, function(key, value) {
                                    errorMessage += value[0] + "<br>";
                                });
                            } else if (typeof xhr.responseJSON.message === 'string') {
                                errorMessage = xhr.responseJSON.message;
                            }
                        }
                        round_error_noti(errorMessage);
                    },
                    complete: function() {
                        $('#submitBtn').prop('disabled', false).text('Submit');
                    }
                });
            });

        });
    </script>

    {{-- RC verification for the "Add New Vehicle" modal --}}
    <script>
        let modalRcVerified = false;

        function lockModalRcField() {
            modalRcVerified = true;
            $('#vehicle_number').prop('readonly', true);
            $('#modalVerifyRcBtn')
                .prop('disabled', true)
                .removeClass('btn-grd-info')
                .addClass('btn-grd-success')
                .html('<i class="bx bx-check"></i> Verified');
        }

        function resetModalRcVerification() {
            modalRcVerified = false;
            $('#modal_rc_verification_data').val('');
            $('#modalRcVerifyStatus').removeClass('text-success text-danger').text('');
            $('#vehicle_number').prop('readonly', false);
            $('#modalVerifyRcBtn')
                .prop('disabled', false)
                .removeClass('btn-grd-success')
                .addClass('btn-grd-info')
                .text('Verify');
        }

        // Invalidate a previously verified payload if the user edits the
        // vehicle number afterwards, so mismatched data never gets saved.
        $('#vehicle_number').on('input', function() {
            $('#rwc_number').val($(this).val());
            if (modalRcVerified) return;
            $('#modal_rc_verification_data').val('');
            $('#modalRcVerifyStatus').removeClass('text-success text-danger').text('');
        });

        $('#modalVerifyRcBtn').on('click', function() {
            if (modalRcVerified) return;

            let rwcNumber = $('#vehicle_number').val().trim().toUpperCase();
            let $btn = $(this);
            let $status = $('#modalRcVerifyStatus');

            $status.removeClass('text-success text-danger').text('');
            $('#modal_rc_verification_data').val('');

            if (!rwcNumber) {
                round_error_noti('Please enter the vehicle / registration number first.');
                return;
            }

            $('#vehicle_number').val(rwcNumber);
            $('#rwc_number').val(rwcNumber);

            function callVerify(lat, lng) {
                $.ajax({
                    url: '{{ route('vehicle.verifyRc') }}',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        vehicle_registration_number: rwcNumber,
                        latitude: lat,
                        longitude: lng,
                    },
                    beforeSend: function() {
                        $btn.prop('disabled', true).text('Verifying...');
                    },
                    success: function(response) {
                        if (response.success) {
                            let data = response.data || {};
                            console.log('BankU RC verify response:', data);

                            if (data.engine && !$('#engine_number').val()) {
                                $('#engine_number').val(data.engine);
                            }

                            if ((data.reg_no || data.vehicle_number) && !$('#vehicle_number').val()) {
                                $('#vehicle_number').val(data.reg_no || data.vehicle_number);
                            }
                            $('#rwc_number').val($('#vehicle_number').val());

                            let fuelType = (data.type || '').trim().toLowerCase();
                            if (fuelType === 'petrol') {
                                $('#fuel_type').val('0');
                            } else if (fuelType === 'diesel' || fuelType === 'disel') {
                                $('#fuel_type').val('1');
                            }

                            // Keep the full verified payload so it's persisted with the
                            // vehicle record on submit, even for fields with no matching input.
                            $('#modal_rc_verification_data').val(JSON.stringify(data));

                            $status.removeClass('text-danger').addClass('text-success')
                                .text('RC verified successfully.');
                            round_success_noti('RC verified successfully.');

                            lockModalRcField();
                        } else {
                            $status.removeClass('text-success').addClass('text-danger')
                                .text(response.message || 'RC verification failed.');
                            round_error_noti(response.message || 'RC verification failed.');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'An unexpected error occurred while verifying the RC.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        $status.removeClass('text-success').addClass('text-danger').text(errorMessage);
                        round_error_noti(errorMessage);
                    },
                    complete: function() {
                        if (!modalRcVerified) {
                            $btn.prop('disabled', false).text('Verify');
                        }
                    }
                });
            }

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        callVerify(position.coords.latitude, position.coords.longitude);
                    },
                    function() {
                        callVerify(28.6139, 77.2090);
                    }, {
                        timeout: 3000
                    }
                );
            } else {
                callVerify(28.6139, 77.2090);
            }
        });
    </script>

    {{-- add new vehicle --}}
    <script>
        $(document).ready(function () {

    // Show modal when Add New Vehicle is selected
    $('#vehicle_id').on('change', function () {
        if ($(this).val() === 'add_new') {
            $('#addVehicleModal').modal('show');
        }
    });

    // Submit Vehicle Form
    $('#addVehicleForm').on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('vehicle.storeFromModal') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,

            success: function (response) {

                if (response.success) {

                    let optionText = response.vehicle.name +
                        ' | ' +
                        response.vehicle.vehicle_number;

                    let newOption = $('<option>')
                        .val(response.vehicle.id)
                        .text(optionText);

                    $('#vehicle_id option[value="add_new"]')
                        .before(newOption);

                    $('#vehicle_id').val(response.vehicle.id);

                    $('#addVehicleModal').modal('hide');
                    $('#addVehicleForm')[0].reset();
                    resetModalRcVerification();

                    toastr.success(response.message);
                }
            },

            error: function (xhr) {

                if (xhr.status === 422) {

                    let errors = xhr.responseJSON.errors;
                    let message = '';

                    $.each(errors, function (key, value) {
                        message += value[0] + '\n';
                    });

                    toastr.error(message);

                } else {
                    toastr.error('Something went wrong.');
                }
            }
        });
    });

});
        // $(document).ready(function() {
        //     // Open modal when "Add New Vehicle" is selected
        //     $("#vehicle_id").on("change", function() {
        //         if ($(this).val() === "add_new") {
        //             $("#addVehicleModal").modal("show");
        //         }
        //     });

        //     // Handle form submission
        //     $("#addVehicleForm").on("submit", function(e) {
        //         e.preventDefault();

        //         let formData = $(this).serialize();
        //         $.ajax({
        //             url: "{{ route('vehicle.store') }}", // your store route
        //             type: "POST",
        //             data: formData,
        //             success: function(response) {
        //                 if (response.success) {
        //                     // Insert new option before "Add New"
        //                     let newOption = $("<option>")
        //                         .val(response.vehicle.id)
        //                         .text(response.vehicle.name + " | " + response.vehicle
        //                             .vehicle_number);

        //                     $("#vehicle_id option[value='add_new']").before(newOption);

        //                     // Select the newly added vehicle
        //                     $("#vehicle_id").val(response.vehicle.id);

        //                     // Close modal & reset form
        //                     $("#addVehicleModal").modal("hide");
        //                     $("#addVehicleForm")[0].reset();
        //                 } else {
        //                     alert("Error: " + response.message);
        //                 }
        //             },
        //             error: function(xhr) {
        //                 alert("Something went wrong!");
        //             }
        //         });
        //     });
        // });
    </script>

    {{-- Driving License verification for the "Add New Driver" modal --}}
    <script>
        let modalDlVerified = false;

        function lockModalDlFields() {
            modalDlVerified = true;
            $('#modal_driving_license_number').prop('readonly', true);
            $('#modal_date_of_birth').prop('readonly', true);
            $('#modalVerifyDlBtn')
                .prop('disabled', true)
                .removeClass('btn-grd-info')
                .addClass('btn-grd-success')
                .html('<i class="bx bx-check"></i> Verified');
        }

        function resetModalDlVerification() {
            modalDlVerified = false;
            $('#modal_dl_verification_data').val('');
            $('#modalDlVerifyStatus').removeClass('text-success text-danger').text('');
            $('#modal_driving_license_number').prop('readonly', false);
            $('#modal_date_of_birth').prop('readonly', false);
            $('#modalVerifyDlBtn')
                .prop('disabled', false)
                .removeClass('btn-grd-success')
                .addClass('btn-grd-info')
                .text('Verify');
        }

        // Invalidate a previously verified payload if the user edits the
        // license number afterwards, so mismatched data never gets saved.
        $('#modal_driving_license_number').on('input', function() {
            if (modalDlVerified) return;
            $('#modal_dl_verification_data').val('');
            $('#modalDlVerifyStatus').removeClass('text-success text-danger').text('');
        });

        function normalizeModalDobForInput(value) {
            if (!value) return '';
            value = String(value).trim();
            if (/^\d{4}-\d{2}-\d{2}$/.test(value)) return value;
            let match = value.match(/^(\d{2})[\/\-](\d{2})[\/\-](\d{4})$/);
            if (match) return `${match[3]}-${match[2]}-${match[1]}`;
            return '';
        }

        $('#modalVerifyDlBtn').on('click', function() {
            if (modalDlVerified) return;

            let dlNumber = $('#modal_driving_license_number').val().trim();
            let dob = $('#modal_date_of_birth').val();
            let $btn = $(this);
            let $status = $('#modalDlVerifyStatus');

            $status.removeClass('text-success text-danger').text('');

            if (!dlNumber) {
                round_error_noti('Please enter the Driving License number first.');
                return;
            }
            if (!dob) {
                round_error_noti('Please select Date of Birth before verifying the license.');
                return;
            }

            function callVerify(lat, lng) {
                $.ajax({
                    url: '{{ route('driver.verifyLicense') }}',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        driving_license_number: dlNumber,
                        dob: dob,
                        latitude: lat,
                        longitude: lng,
                    },
                    beforeSend: function() {
                        $btn.prop('disabled', true).text('Verifying...');
                    },
                    success: function(response) {
                        if (response.success) {
                            let data = response.data || {};
                            console.log('BankU DL verify response:', data);

                            let details = data.details_of_driving_licence || {};

                            let fullName = details.name || data.name || '';
                            if (fullName) {
                                let parts = fullName.trim().split(/\s+/);
                                $('#modal_first_name').val(parts.shift());
                                $('#modal_last_name').val(parts.join(' '));
                            }

                            let address = details.address || data.address || '';
                            if (address) {
                                $('#modal_address').val(address);
                            }

                            let dobResp = normalizeModalDobForInput(data.dob || details.dob || '');
                            if (dobResp) {
                                $('#modal_date_of_birth').val(dobResp);
                            }

                            $('#modal_dl_verification_data').val(JSON.stringify(data));

                            $status.removeClass('text-danger').addClass('text-success')
                                .text('Driving license verified successfully.');
                            round_success_noti('Driving license verified successfully.');

                            lockModalDlFields();
                        } else {
                            $status.removeClass('text-success').addClass('text-danger')
                                .text(response.message || 'Verification failed.');
                            round_error_noti(response.message || 'Driving license verification failed.');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'An unexpected error occurred while verifying the license.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        $status.removeClass('text-success').addClass('text-danger').text(errorMessage);
                        round_error_noti(errorMessage);
                    },
                    complete: function() {
                        if (!modalDlVerified) {
                            $btn.prop('disabled', false).text('Verify');
                        }
                    }
                });
            }

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        callVerify(position.coords.latitude, position.coords.longitude);
                    },
                    function() {
                        callVerify(28.6139, 77.2090);
                    }, {
                        timeout: 3000
                    }
                );
            } else {
                callVerify(28.6139, 77.2090);
            }
        });
    </script>

    {{-- add new driver --}}
    <script>
       $(document).ready(function() {

    $("#driver_id").on("change", function() {
        if ($(this).val() === "add_new") {
            $("#addDriverModal").modal("show");
        }
    });

    $("#addDriverForm").on("submit", function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('driver.storeFromModal') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,

            success: function(response) {

                if (response.success) {

                    let newOption = $("<option>")
                        .val(response.driver.id)
                        .text(response.driver.name + " (" + response.driver.phone + ")");

                    $("#driver_id option[value='add_new']").before(newOption);

                    $("#driver_id").val(response.driver.id);

                    $("#addDriverModal").modal("hide");
                    $("#addDriverForm")[0].reset();
                    resetModalDlVerification();

                    round_success_noti(response.message);
                }
            },

            error: function(xhr) {

                if (xhr.status === 422) {

                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function(field, messages) {

                        $.each(messages, function(index, message) {
                            round_warning_noti(message);
                        });

                    });

                } else {

                    round_warning_noti(
                        xhr.responseJSON?.message ||
                        "Something went wrong!"
                    );

                }
            }
        });

    });

});
    </script>

    <script>
        const GOOGLE_MAPS_API_KEY = "{{ env('GOOGLE_MAPS_API_KEY') }}";
        $(document).ready(function() {
            // var apiKey = "AlzaSyC7RSr791vm_29LJiUOPJO-sLnBZg6qiGl";
            var apiKey = GOOGLE_MAPS_API_KEY;
            var $input = $('#shop_name');
            var $shop_suggestions = $('#shop_suggestions');

            $input.on('input', function() {
                var query = $(this).val().trim();
                if (query.length < 3) {
                    $shop_suggestions.empty().hide();
                    return;
                }

                $.get('https://maps.mapthrust.io/maps/api/place/textsearch/json', {
                    key: apiKey,
                    query: query
                }, function(response) {
                    $shop_suggestions.empty().show();

                    if (response.status === 'OK' && response.results.length > 0) {
                        response.results.forEach(function(item) {
                            var name = item.name || '';
                            var address = item.formatted_address || '';
                            var lat = item.geometry?.location?.lat || '';
                            var lng = item.geometry?.location?.lng || '';
                            var photoRef = item.photos?.[0]?.photo_reference || '';
                            var photoUrl = photoRef ?
                                `https://maps.mapthrust.io/maps/api/place/photo?maxwidth=400&photo_reference=${photoRef}&key=${apiKey}` :
                                '';

                            var $option = $(
                                '<a href="#" class="list-group-item list-group-item-action d-flex align-items-center"></a>'
                            );

                            if (photoUrl) {
                                $option.append(
                                    `<img src="${photoUrl}" class="me-2" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">`
                                );
                            }

                            $option.append(
                                `<div><div class="fw-bold">${name}</div><small>${address}</small></div>`
                            );
                            $option.data({
                                name,
                                address,
                                lat,
                                lng,
                                image: photoUrl
                            });
                            $shop_suggestions.append($option);
                        });
                    } else {
                        $shop_suggestions.append(
                            '<div class="list-group-item">No results found</div>');
                    }
                });
            });

            $shop_suggestions.on('click', 'a', function(e) {
                e.preventDefault();
                var data = $(this).data();

                $('#shop_name').val(data.name);
                $('#shop_address').val(data.address);
                $('#shop_latitude').val(data.lat);
                $('#shop_longitude').val(data.lng);

                // $('#shop_preview').attr('src', data.image || '').toggle(!!data.image);
                $('#blah').attr('src', data.image || '').toggle(!!data.image);
                $('#shop_image_from_google').val(data.image);
                $shop_suggestions.empty().hide();
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#shop_name, #suggestions').length) {
                    $shop_suggestions.empty().hide();
                }
            });


            var $shop_address = $('#shop_address');
            var $address_suggestions = $('#address_suggestions');

            $shop_address.on('input', function() {
                var query = $(this).val().trim();
                if (query.length < 3) {
                    $address_suggestions.empty().hide();
                    return;
                }

                $.get('https://maps.mapthrust.io/maps/api/place/textsearch/json', {
                    key: apiKey,
                    query: query
                }, function(response) {
                    console.log(response);

                    $address_suggestions.empty().show();

                    if (response.status === 'OK' && response.results.length > 0) {
                        response.results.forEach(function(item) {
                            var name = item.name || '';
                            var address = item.formatted_address || '';
                            var lat = item.geometry?.location?.lat || '';
                            var lng = item.geometry?.location?.lng || '';
                            var photoRef = item.photos?.[0]?.photo_reference || '';
                            var photoUrl = photoRef ?
                                `https://maps.mapthrust.io/maps/api/place/photo?maxwidth=400&photo_reference=${photoRef}&key=${apiKey}` :
                                '';

                            var $option = $(
                                '<a href="#" class="list-group-item list-group-item-action d-flex align-items-center"></a>'
                            );

                            if (photoUrl) {
                                $option.append(
                                    `<img src="${photoUrl}" class="me-2" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">`
                                );
                            }

                            $option.append(
                                `<div><div class="fw-bold">${name}</div><small>${address}</small></div>`
                            );
                            $option.data({
                                name,
                                address,
                                lat,
                                lng,
                                image: photoUrl
                            });
                            $address_suggestions.append($option);
                        });
                    } else {
                        $address_suggestions.append(
                            '<div class="list-group-item">No results found</div>');
                    }
                });
            });

            $address_suggestions.on('click', 'a', function(e) {
                e.preventDefault();
                var data = $(this).data();

                $('#shop_name').val(data.name);
                $('#shop_address').val(data.address);
                $('#shop_latitude').val(data.lat);
                $('#shop_longitude').val(data.lng);

                // $('#shop_preview').attr('src', data.image || '').toggle(!!data.image);
                $('#blah').attr('src', data.image || '').toggle(!!data.image);
                $('#shop_image_from_google').val(data.image);
                $address_suggestions.empty().hide();
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#shop_address, #address_suggestions').length) {
                    $address_suggestions.empty().hide();
                }
            });
        });
    </script>







    <script src="https://maps.mapthrust.io/maps/api/js?key=AlzaSyC7RSr791vm_29LJiUOPJO-sLnBZg6qiGl&libraries=places,geometry">
    </script>

    <script>
        let map;
        let routeLine;
        let selectedShops = [];
        let shopMarkers = [];
        let driverLat = 22.5059365;
        let driverLng = 88.3716779;

        @php
            $depotLat = null;
            $depotLng = null;

            if (auth()->user()->hasRole('Employee')) {
                $depotLat = auth()->user()->employee?->branch?->branch?->latitude;
                $depotLng = auth()->user()->employee?->branch?->branch?->longitude;
            }

            if (auth()->user()->hasRole('Branch')) {
                $depotLat = auth()->user()->branch?->latitude;
                $depotLng = auth()->user()->branch?->longitude;
            }

            $depotLat = is_numeric($depotLat) ? (float) $depotLat : 22.5059365;
            $depotLng = is_numeric($depotLng) ? (float) $depotLng : 88.3716779;
        @endphp

        driverLat = {{ $depotLat }};
        driverLng = {{ $depotLng }};

        @foreach($selectedShops as $item)
        selectedShops.push({
            id: {{ $item->shop->id }},
            name: @json($item->shop->shop_name),
            lat: parseFloat({{ $item->shop->shop_latitude }}),
            lng: parseFloat({{ $item->shop->shop_longitude }}),
            address: @json($item->shop->shop_address),// keep products so you can pre-fill items
        });
        @endforeach

        function initializeMap() {
            
            map = new google.maps.Map(document.getElementById("shopMap"), {
                center: {
                    lat: driverLat,
                    lng: driverLng
                },
                zoom: 12,
            });

            new google.maps.Marker({
                position: {
                    lat: driverLat,
                    lng: driverLng
                },
                map: map,
                title: "Driver",
                icon: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"
            });
        }

        async function drawMultiStopRoute() {
            // if (selectedShops.length === 0) return;
            if (selectedShops.length === 0) {
                // Clear existing route line if any
                if (routeLine) {
                    routeLine.setMap(null);
                    routeLine = null;
                }

                // Clear existing markers if any
                shopMarkers.forEach(marker => marker.setMap(null));
                shopMarkers = [];

                return; // nothing else to do
            }

            const body = {
                origin: {
                    location: {
                        latLng: {
                            latitude: driverLat,
                            longitude: driverLng
                        }
                    }
                },
                destination: {
                    location: {
                        latLng: {
                            latitude: selectedShops.at(-1).lat,
                            longitude: selectedShops.at(-1).lng
                        }
                    }
                },
                intermediates: selectedShops.slice(0, -1).map(shop => ({
                    location: {
                        latLng: {
                            latitude: shop.lat,
                            longitude: shop.lng
                        }
                    }
                })),
                travelMode: "DRIVE",
                routingPreference: "TRAFFIC_AWARE",
                computeAlternativeRoutes: false,
                polylineEncoding: "ENCODED_POLYLINE",
                languageCode: "en-US",
                units: "METRIC"
            };

            try {
                const response = await fetch("https://routes.mapthrust.io/directions/v2:computeRoutes", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Goog-Api-Key": "AlzaSyC7RSr791vm_29LJiUOPJO-sLnBZg6qiGl",
                        "X-Goog-FieldMask": "*"
                    },
                    body: JSON.stringify(body)
                });

                const data = await response.json();
                if (!data.routes || !data.routes.length) {
                    alert("No route found.");
                    return;
                }

                const polyline = data.routes[0].polyline.encodedPolyline;
                const decodedPath = google.maps.geometry.encoding.decodePath(polyline);

                if (routeLine) routeLine.setMap(null);
                routeLine = new google.maps.Polyline({
                    path: decodedPath,
                    geodesic: true,
                    strokeColor: "#000000",
                    strokeOpacity: 1.0,
                    strokeWeight: 4,
                });
                routeLine.setMap(map);

                const bounds = new google.maps.LatLngBounds();
                decodedPath.forEach(p => bounds.extend(p));
                map.fitBounds(bounds);

                google.maps.event.addListenerOnce(map, "bounds_changed", function () {
                    map.setZoom(12); // closer view
                });

                // Clear old markers
                shopMarkers.forEach(marker => marker.setMap(null));
                shopMarkers = [];

                const infoWindow = new google.maps.InfoWindow();

                const allPoints = [{
                        lat: driverLat,
                        lng: driverLng,
                        label: "Driver",
                        address: ""
                    },
                    ...selectedShops.map(shop => ({
                        lat: shop.lat,
                        lng: shop.lng,
                        label: shop.name,
                        address: shop.address
                    }))
                ];

                allPoints.forEach(point => {
                    const marker = new google.maps.Marker({
                        position: {
                            lat: point.lat,
                            lng: point.lng
                        },
                        map,
                        icon: {
                            path: google.maps.SymbolPath.CIRCLE,
                            scale: 8,
                            fillColor: "#000000",
                            fillOpacity: 1,
                            strokeWeight: 2,
                            strokeColor: "#fff"
                        },
                        title: point.label
                    });

                    // marker.addListener("click", () => {
                    //     infoWindow.setContent(`<strong>${point.label}</strong><br>${point.address || ''}`);
                    //     infoWindow.open(map, marker);
                    // });

                    // showing on google maps

                    marker.addListener("click", () => {
                        const mapsUrl =
                            `https://www.google.com/maps/search/?api=1&query=${point.lat},${point.lng}`;
                        const content = `
                            <strong>${point.label}</strong><br>
                            ${point.address || ''}<br>
                            <a href="${mapsUrl}" target="_blank" style="color: #007bff; text-decoration: underline;">
                                View on Google Maps
                            </a>
                        `;
                        infoWindow.setContent(content);
                        infoWindow.open(map, marker);
                    });

                    shopMarkers.push(marker);
                });
            } catch (err) {
                console.error("Route draw failed:", err);
            }
        }

        function showLocationsOnly() {
            if (selectedShops.length === 0) return;

            // Clear old markers
            shopMarkers.forEach(marker => marker.setMap(null));
            shopMarkers = [];

            const infoWindow = new google.maps.InfoWindow();

            // const allPoints = [
            //     {
            //         lat: driverLat,
            //         lng: driverLng,
            //         label: "Driver",
            //         address: ""
            //     },
            //     ...selectedShops.map(shop => ({
            //         lat: shop.lat,
            //         lng: shop.lng,
            //         label: shop.name,
            //         address: shop.address
            //     }))
            // ];

            const allPoints = selectedShops.map(shop => ({
                lat: shop.lat,
                lng: shop.lng,
                label: shop.name,
                address: shop.address
            }));

            allPoints.forEach(point => {
                const marker = new google.maps.Marker({
                    position: { lat: point.lat, lng: point.lng },
                    map,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 8,
                        fillColor: "#000000",
                        fillOpacity: 1,
                        strokeWeight: 2,
                        strokeColor: "#fff"
                    },
                    title: point.label
                });

                marker.addListener("click", () => {
                    const mapsUrl = `https://www.google.com/maps/search/?api=1&query=${point.lat},${point.lng}`;
                    const content = `
                        <strong>${point.label}</strong><br>
                        ${point.address || ''}<br>
                        <a href="${mapsUrl}" target="_blank" style="color: #007bff; text-decoration: underline;">
                            View on Google Maps
                        </a>
                    `;
                    infoWindow.setContent(content);
                    infoWindow.open(map, marker);
                });

                shopMarkers.push(marker);
            });

            // Fit map to show all points
            const bounds = new google.maps.LatLngBounds();
            allPoints.forEach(p => bounds.extend(new google.maps.LatLng(p.lat, p.lng)));
            map.fitBounds(bounds);

            google.maps.event.addListenerOnce(map, "bounds_changed", function () {
                map.setZoom(12); // closer view
            });
        }


        $(document).ready(function() {
            try {
                initializeMap(); // Always initialize with driver's location
            } catch (e) {
                console.error('initializeMap failed, continuing without the map:', e);
            }
            const $input = $('#search_shop_name');
            const $suggestions = $('#suggestions');
            const $sortableList = $('#sortable-shop-location');

            // ✅ Step 1: Get user's current location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    currentLat = position.coords.latitude;
                    currentLng = position.coords.longitude;
                    // console.log("User Location:", currentLat, currentLng);
                    // alert("Location captured: " + currentLat + ", " + currentLng);
                }, function() {
                    // alert("Failed to get location");
                });
            } else {
                // alert("Geolocation is not supported by this browser.");
            }

            drawMultiStopRoute();

            // ✅ Step 2: Shop search autocomplete
            $input.on('input', function() {
                const query = $(this).val().trim();
                if (query.length < 2) {
                    $suggestions.empty().hide();
                    return;
                }

                $.get("{{ route('shop.search') }}", {
                    search: query
                }, function(response) {
                    $suggestions.empty().show();

                    if (response.length > 0) {
                        response.forEach(function(item) {
                            const $option = $(`
                            <a href="#" class="list-group-item list-group-item-action d-flex flex-column">
                                <div class="fw-bold">${item.shop_name}</div>
                                <small>${item.shop_address}</small>
                            </a>
                        `);

                            $option.data({
                                id: item.id,
                                name: item.shop_name,
                                address: item.shop_address,
                                lat: item.shop_latitude,
                                lng: item.shop_longitude
                            });

                            $suggestions.append($option);
                        });
                    } else {
                        $suggestions.append('<div class="list-group-item">No results found</div>');
                    }
                });
            });

            // ✅ Step 3: Add selected shop to list
            $suggestions.on('click', 'a', function (e) {
                e.preventDefault();
                const data = $(this).data();
                $('#search_shop_name').val(data.name);
                $('#shop_id').val(data.id);
                $('#shop_name').val(data.name);
                $('#shop_addresse').val(data.address);
                $('#shop_latitude').val(data.lat);
                $('#shop_longitude').val(data.lng);

                    $('#productTotalSection').show();

                    selectedShops.push({
                        id: data.id,
                        name: data.name,
                        lat: parseFloat(data.lat),
                        lng: parseFloat(data.lng),
                        address: data.address
                    });

                    // showLocationsOnly();
                    drawMultiStopRoute();
                //     updateShopSerialNumbers();
                // }

                // $('#search_shop_name').val('');
                $suggestions.empty().hide();
            });

            


            // ✅ Step 4: Remove shop from list
            $(document).on('click', '.remove-shop', function() {
                const id = $(this).closest('li').data('id');
                $(this).closest('li').remove();
                selectedShops = selectedShops.filter(s => s.id !== id);
                updateShopSerialNumbers();
                drawMultiStopRoute();
            });

            $sortableList.sortable({
                handle: '.handle',
                update: function() {
                    // Wait a bit for DOM reordering to settle
                    setTimeout(function() {
                        updateShopSerialNumbers();

                        // 🟡 Rebuild selectedShops from new DOM order
                        selectedShops = $sortableList.children('li').map(function() {
                            const $li = $(this);
                            return {
                                id: $li.data('id'),
                                name: $li.find('input[name="shop_names[]"]').val(),
                                address: $li.find('input[name="shop_addresses[]"]')
                                .val(),
                                lat: parseFloat($li.find(
                                    'input[name="shop_latitudes[]"]').val()),
                                lng: parseFloat($li.find(
                                    'input[name="shop_longitudes[]"]').val())
                            };
                        }).get();

                        // ✅ Redraw route
                        drawMultiStopRoute();
                    }, 50);
                }
            }).disableSelection();


            function updateShopSerialNumbers() {
                // $sortableList.find('li').each(function(index) {
                //     $(this).find('.sl-no').text((index + 1) + ". ");
                // });

                $("#locations .location-item").each(function(index) {
                    $(this).find(".rounded-circle p").text(index + 1);
                });
            }

            // ✅ Step 6: Sort by Nearest Distance
            $('#sortByNearestBtn').on('click', function() {
                if (currentLat === null || currentLng === null) {
                    alert("User location not available yet.");
                    return;
                }

                function getDistance(lat1, lon1, lat2, lon2) {
                    const R = 6371; // km
                    const dLat = (lat2 - lat1) * Math.PI / 180;
                    const dLon = (lon2 - lon1) * Math.PI / 180;
                    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                        Math.sin(dLon / 2) * Math.sin(dLon / 2);
                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                    return R * c;
                }

                // Get all location items with distance
                const shopItems = $("#locations").find('.location-item').map(function() {
                    const $item = $(this);
                    const lat = parseFloat($item.find('input[name="shop_latitudes[]"]').val());
                    const lng = parseFloat($item.find('input[name="shop_longitudes[]"]').val());
                    const distance = getDistance(currentLat, currentLng, lat, lng);
                    return {
                        element: $item,
                        distance
                    };
                }).get();

                // Sort by distance
                shopItems.sort((a, b) => a.distance - b.distance);

                // Re-append in sorted order (keeps HTML intact)
                const $container = $("#locations").empty();
                shopItems.forEach(item => $container.append(item.element));

                // Update numbering
                $("#locations .location-item").each(function(index) {
                    $(this).find(".bg-primary p").text(index + 1);
                });

                // Update selectedShops based on new order
                selectedShops = $("#locations .location-item").map(function() {
                    const $item = $(this);
                    return {
                        id: $item.data('shop-id'),
                        name: $item.find('input[name="shop_names[]"]').val(),
                        address: $item.find('input[name="shop_addresses[]"]').val(),
                        lat: parseFloat($item.find('input[name="shop_latitudes[]"]').val()),
                        lng: parseFloat($item.find('input[name="shop_longitudes[]"]').val())
                    };
                }).get();

                // Redraw route
                drawMultiStopRoute();

                alert("Shop list sorted by nearest location.");
            });


            // Close suggestions when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#search_shop_name, #suggestions').length) {
                    $suggestions.empty().hide();
                }
            });

            $(document).on('click', '.toggle-details', function() {
                const $details = $(this).closest('li').find('.shop-details');
                $details.slideToggle(200);

                // Rotate icon for feedback
                $(this).find('i').toggleClass('fa-chevron-down fa-chevron-up');
            });
            
            $(document).on('click', function(e) {
                const $target = $(e.target);
                const isInsideRelevantBlock = $target.closest(
                        '.list-group-item, .toggle-details, .shop-details, .search-shop-name, #suggestions')
                    .length > 0;

                if (!isInsideRelevantBlock) {
                    $('.shop-details').slideUp(200);
                    $('.toggle-details i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
                    $('#suggestions').hide();
                }
            });

            $('#search_shop_name').on('click', function() {
                setTimeout(() => {
                    // Only close suggestions and collapse details if focus is truly lost
                    const suggestionsHovered = document.querySelector('#suggestions:hover, .list-group-item:hover');
                    if (!suggestionsHovered) {
                        $('.shop-details').slideUp(200);
                        $('.toggle-details i').removeClass('fa-chevron-up').addClass(
                            'fa-chevron-down');
                        $('#suggestions').hide(); // Hide suggestions too
                    }
                }, 200); // slight delay to allow click events (like suggestion click) to trigger
            });





        });
    </script>

    <script>
        $(document).ready(function () {

        // Add Delivery Task
        $("#task-form").on("submit", function (e) {
            e.preventDefault();

            let isValid = true;

            // Reset invalid states
            $("#task-form .form-control, #task-form .form-select").removeClass("is-invalid");

            // Get values
            let orderNo = $('input[name="invoice_no"]').val().trim();
            let consignee = $('#search_shop_name').val().trim();
            let paymentType = $('select[name="payment_type"]').val();
            let amount = $('input[name="amount"]').val().trim();
            let shop_id = $("#shop_id").val();
            let shop_name = $("#shop_name").val();
            let shop_addresse = $("#shop_addresse").val();
            let shop_latitude = $("#shop_latitude").val();
            let shop_longitude = $("#shop_longitude").val();
            let branch_id = $("#branch_id").val();

            // Validate fields
            if (orderNo === "") {
                $('input[name="invoice_no"]').addClass("is-invalid");
                isValid = false;
            }
            if (consignee === "") {
                $('#search_shop_name').addClass("is-invalid");
                isValid = false;
            }
            if (!paymentType) {
                $('select[name="payment_type"]').addClass("is-invalid");
                isValid = false;
            }
            if (amount === "") {
                $('input[name="amount"]').addClass("is-invalid");
                isValid = false;
            }

            $(".product-title").each(function () {
                if ($.trim($(this).val()) === "") {
                    $(this).addClass("is-invalid");
                    isValid = false;
                } else {
                    $(this).removeClass("is-invalid");
                }
            });

            // Validate product rows
            let hasValidProduct = false;
            $("#product-container .product-row").each(function () {
                let title = $(this).find(".product-title").val().trim();
                let qty = $(this).find(".product-qty").val();
                let unit = $(this).find(".product-unit").val();

                if (title !== "" && qty > 0 && unit !== "") {
                    hasValidProduct = true;
                } else {
                    if (title === "") $(this).find(".product-title").addClass("is-invalid");
                    if (qty <= 0 || qty === "") $(this).find(".product-qty").addClass("is-invalid");
                    if (unit === "") $(this).find(".product-unit").addClass("is-invalid");
                }
            });

            if (!hasValidProduct) {
                isValid = false;
            }

            // If invalid, stop here (don’t close offcanvas)
            if (!isValid) {
                return false;
            }

            // ✅ If valid → close offcanvas
            $(".btn-close[data-bs-dismiss='offcanvas']").trigger("click");

            // Count locations
            let locationCount = $("#locations .location-box").length + 1;

            // Products HTML
            let productsHTML = "";
            let productInputs = "";
            $("#product-container .product-row").each(function () {
                let title = $(this).find(".product-title").val().trim();
                let qty = $(this).find(".product-qty").val();
                let unit = $(this).find(".product-unit").val();

                productsHTML += `<div class="item-row">${title} - ${qty} ${unit}</div>`;
                productInputs += `
                    <input type="hidden" name="product_titles[${locationCount}][]" value="${title}">
                    <input type="hidden" name="product_qtys[${locationCount}][]" value="${qty}">
                    <input type="hidden" name="product_units[${locationCount}][]" value="${unit}">
                `;
            });

            // Location HTML
            let locationHTML = `
            <div class="d-flex w-100 align-items-start gap-3 mb-3 location-item" data-shop-id="${shop_id}">
                <div class="bg-primary d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                    style="width: 30px; height: 30px;">
                    <p class="text-white m-0 fw-bold">${locationCount}</p>
                </div>

                <div class="location-box flex-grow-1 w-100">
                    <div class="location-header p-2 d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse" data-bs-target="#location${locationCount}" style="cursor: pointer;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-geo-alt-fill me-2 text-primary"></i>
                            ${consignee || 'Location ' + locationCount}
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-chevron-down toggle-icon"></i>
                            <button type="button" class="btn-icon p-0 remove-location"><i class="bi bi-trash-fill"></i></button>
                            <i class="bi bi-pencil-square edit-location"></i>
                        </div>
                    </div>

                    <input type="hidden" name="shop_ids[]" value="${shop_id}">
                    <input type="hidden" name="shop_names[]" value="${shop_name}">
                    <input type="hidden" name="shop_addresses[]" value="${shop_addresse}">
                    <input type="hidden" name="shop_latitudes[]" value="${shop_latitude}">
                    <input type="hidden" name="shop_longitudes[]" value="${shop_longitude}">
                    <input type="hidden" name="invoice_nos[]" value="${orderNo}">
                    <input type="hidden" name="payment_types[]" value="${paymentType}">
                    <input type="hidden" name="amounts[]" value="${amount}">
                    <input type="hidden" name="branch_id[]" value="${branch_id}">
                    ${productInputs}
                    <div class="collapse" id="location${locationCount}">
                        <div class="location-content">
                            <div class="row g-2 mb-2">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" value="${orderNo}" placeholder="Enter Invoice">
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select">
                                        <option ${paymentType === 'Pre-Paid' ? 'selected' : ''}>Pre-Paid</option>
                                        <option ${paymentType === 'COD' ? 'selected' : ''}>COD</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" value="${amount}" placeholder="0.0">
                                </div>
                            </div>
                            ${productsHTML}
                        </div>
                    </div>
                </div>

                <a href="javascript:void(0);" data-bs-toggle="offcanvas" data-bs-target="#deliveryNote"
                    class="btn-add text-decoration-none">
                    <i class="bi bi-plus-circle me-1" style="font-size:30px;"></i>
                </a>
            </div>`;

            // Append to locations
            $("#locations").append(locationHTML);

            toggleSearchBar();

            // Reset form
            this.reset();
            $('#productTotalSection').hide();
            $("#product-container").html(`
                <div class="product-row">
                    <input type="text" placeholder="Title" class="product-title form-control custom-input">
                    <select class="product-unit form-select custom-input">
                        <option value="unit">Unit</option>
                        <option value="box">Box</option>
                    </select>
                    <input type="number" min="1" value="1" class="product-qty form-control custom-input">
                    <button type="button" class="remove-row btn btn-sm btn-danger" onclick="removeRow(this)">
                        <i class="material-icons-outlined">delete</i>
                    </button>
                    <button type="button" class="add-row btn btn-sm btn-success" onclick="addRow()">
                        <i class="material-icons-outlined">add</i>
                    </button>
                </div>
            `);
            bindEvents(document.querySelector('.product-row'));
        });

        // Remove invalid state on change
        $(document).on("input change", ".form-control, .form-select", function () {
            $(this).removeClass("is-invalid");
        });


        $(document).on("click", ".remove-location", function () {
            const id = $(this).closest(".d-flex.w-100.align-items-start")
                            .find("input[name='shop_ids[]']").val();
            
            $(this).closest(".d-flex.w-100.align-items-start").remove();
            
            selectedShops = selectedShops.filter(s => s.id != id);
            // updateShopSerialNumbers();
            drawMultiStopRoute();
        });


        $(document).on("click", ".edit-location", function () {
            let locationBox = $(this).closest(".location-box");

            // Get data from inside the location
            let orderNo = locationBox.find('input[placeholder="Enter Invoice"]').val();
            let paymentType = locationBox.find("select.form-select").val();
            let amount = locationBox.find('input[type="number"]').val();

            let consignor = locationBox.find(".location-header div:first").text().trim();
            let shop_id = locationBox.find('input[name="shop_ids[]"]').val();

            // Populate back to form
            $('input[name="invoice_no"]').val(orderNo);
            $("#search_shop_name").val(consignor);
            $('select[name="payment_type"]').val(paymentType);
            $('input[name="amount"]').val(amount);
            $("#shop_id").val(shop_id);

            // Products
            let productContainer = $("#product-container").empty();
            locationBox.find(".item-row").each(function () {
                let parts = $(this).text().trim().split(" - ");
                let title = parts[0];
                let qtyUnit = parts[1].split(" ");
                let qty = qtyUnit[0];
                let unit = qtyUnit[1];

                productContainer.append(`
                    <div class="product-row">
                        <input type="text" value="${title}" placeholder="Title" class="product-title form-control custom-input">
                        <select class="product-unit form-select custom-input">
                            <option value="unit" ${unit === 'Unit' ? 'selected' : ''}>Unit</option>
                            <option value="box" ${unit === 'Box' ? 'selected' : ''}>Box</option>
                        </select>
                        <input type="number" min="1" value="${qty}" class="product-qty form-control custom-input">
                        <button type="button" class="remove-row btn btn-sm btn-danger" onclick="removeRow(this)">
                            <i class="material-icons-outlined">delete</i>
                        </button>
                        <button type="button" class="add-row btn btn-sm btn-success" onclick="addRow()">
                            <i class="material-icons-outlined">add</i>
                        </button>
                    </div>
                `);
            });

            // Remove the old location (we'll re-add after form submit)
            locationBox.closest(".d-flex.w-100.align-items-start").remove();
            // ✅ Change button text
            $("#ofcanvus-submit-btn button[type='submit']").text("Edit Delivery Task");

            // ✅ Change close button to submit
            $(".btn-close[data-bs-dismiss='offcanvas']").hide();
                // .attr("data-bs-dismiss", "") // remove dismiss attribute
                // .attr("type", "submit")      // make it submit button
                // .attr("id", "editSubmitBtn") // optional: give ID for tracking
                // .off("click");               // remove default close behavior

            $("#edit-submit-close-btn").show();

            // ✅ Prevent closing on backdrop
            var deliveryNote = new bootstrap.Offcanvas('#deliveryNote', {
                backdrop: 'static',
                keyboard: false
            });
            deliveryNote.show();

            // var deliveryNote = new bootstrap.Offcanvas('#deliveryNote');
            // deliveryNote.show();
            $('#productTotalSection').show();
            updateTotal();
        });



        // Add Product Row
        // window.addRow = function () {
        //     $("#product-container").append(`
        //         <div class="product-row">
        //             <input type="text" placeholder="Product Title" class="product-title form-control custom-input">
        //             <select class="product-unit form-select custom-input">
        //                 <option value="unit">Unit</option>
        //                 <option value="box">Box</option>
        //             </select>
        //             <input type="number" min="1" value="1" class="product-qty form-control custom-input">
        //             <button type="button" class="remove-row btn btn-sm btn-danger" onclick="removeRow(this)">
        //                 <i class="material-icons-outlined">delete</i>
        //             </button>
        //             <button type="button" class="add-row btn btn-sm btn-success" onclick="addRow()">
        //                 <i class="material-icons-outlined">add</i>
        //             </button>
        //         </div>
        //     `);
        // };

        // Remove Location
        $(document).on("click", ".remove-location", function () {
            $(this).closest(".d-flex").remove();
        });

        $("#locations").sortable({
            items: ".location-item",                // Make only location-item draggable
            handle: ".location-header",             // Only drag from the header area
            placeholder: "sortable-placeholder",    // Placeholder style
            tolerance: "pointer",
            start: function (e, ui) {
                ui.placeholder.height(ui.item.height());
            },
            stop: function () {
                // Update the numbering after reorder
                $("#locations .location-item").each(function (index) {
                    $(this).find(".bg-primary p").text(index + 1);
                });

                // Rebuild selectedShops array based on new order
                let newOrder = [];
                $("#locations .location-item").each(function () {
                    const id = $(this).data("shop-id");
                    const shop = selectedShops.find(s => s.id == id);
                    if (shop) newOrder.push(shop);
                });
                selectedShops = newOrder;

                // Redraw route based on the new order
                drawMultiStopRoute();
            }
        });


    });
    </script>

    {{-- search in div id="locations" --}}
    <script>
        $(document).ready(function () {
            // Show search bar only if #locations is not empty
            toggleSearchBar();

            // When typing in the search box
            $("#searchShop").on("keyup", function () {
                let searchText = $(this).val().toLowerCase();
                // console.log(searchText);

                $("#locations > .d-flex").each(function () {
                    let shopName = $(this).find(".location-header div.d-flex:first").text().toLowerCase();

                    if (shopName.indexOf(searchText) > -1) {
                        // console.log(shopName);
                        $(this).removeClass("hide-important").addClass("show-important");
                    } else {
                        // console.log("hide" + shopName);
                        $(this).removeClass("show-important").addClass("hide-important");
                    }


                });
            });
        });

        // Function to toggle search visibility
        function toggleSearchBar() {
            if ($("#locations").children().length > 0) {
                $("#searchWrapper").show();
            } else {
                $("#searchWrapper").hide();
            }
        }

    </script>

@endsection
