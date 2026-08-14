@extends('layouts.app')

@section('title')
Delivery Schedule
@endsection

@section('css')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
@endsection

@section('content')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Delivery Schedule</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Delivery Schedule</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                <a class="btn btn-primary px-4" href="{{ route('delivery-schedule.index') }}">
                    <i class="fadeIn animated bx bx-arrow-back"></i>Back
                </a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->

    <form class="needs-validation" action="{{ route('delivery-schedule.update',$delivery_Schedule->id) }}" method="post" novalidate enctype="multipart/form-data">
        @csrf
        @method('PUT')


        <input type="hidden" name="shop_ids" id="shop_ids" value="{{ implode(',', $selectedShops) }}">
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header text-center">Edit Delivery Schedule</div>
                    <div class="card-body">

                        <div class="row">
                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="delivery_date">Delivery Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" value="{{ old('delivery_date',$delivery_Schedule->delivery_date) }}" name="delivery_date" id="delivery_date" placeholder="Enter date" required>
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Please enter shop name.</div>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="driver_id">Choose Driver <span class="text-danger">*</span></label>
                                <select class="form-select" name="driver_id" id="driver_id" required>
                                    <option value="" selected disabled>Select Driver</option>
                                    @foreach ($drivers as $driver)
                                        <option value="{{ $driver->id }}"
                                            {{ old('driver_id',$delivery_Schedule->driver_id) == $driver->id ? 'selected' : '' }}>
                                            {{ $driver->name }}</option>
                                    @endforeach
                                </select>
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Please enter shop contact person name.</div>
                            </div>
                        
                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="vehicle_id">Choose Vehicle <span class="text-danger">*</span></label>
                                <select class="form-select" name="vehicle_id" id="vehicle_id" required>
                                    <option value="" selected disabled>Select Vehicle</option>
                                    @foreach ($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}"
                                            {{ old('vehicle_id',$delivery_Schedule->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                                            {{ $vehicle->name }}( {{ $vehicle->vehicle_number }} )</option>
                                    @endforeach
                                </select>
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Please enter a valid shop address.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="shop">Choose Shops<span class="text-danger">*</span></label>
                                <select class="form-select single-select-field" id="shop" name="shop" data-placeholder="Choose anything">
                                    <option value="" selected>Select Shops</option>
                                    @foreach ($shops as $shop)
                                        <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
                                    @endforeach
                                </select>
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Please enter start point.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Selected Shops</label>
                                <ul id="sortable-bus-stops" class="list-group"></ul>
                            </div>
                        </div>
              
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="card">
                        <div class="card-header text-center">Publish</div>
                        <div class="card-body">
                            {{-- <div class="mb-3">
                                <label class="form-label mb-3 d-flex">Status</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="customRadioInline1" name="is_visible" class="form-check-input" value="1" {{ check_uncheck($data->is_visible,1) }}>
                                    <label class="form-check-label" for="customRadioInline1">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="customRadioInline2" name="is_visible" class="form-check-input" value="0" {{ check_uncheck($data->is_visible,0) }}>
                                    <label class="form-check-label" for="customRadioInline2">Inactive</label>
                                </div>
                            </div> --}}
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-grd-primary px-4 text-light">Submit</button>
                                {{-- <button type="reset" class="btn btn-grd-info px-4 text-light">Reset</button> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection


@section('scripts')
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            let selectedShopIds = $("#shop_ids").val().split(",");

            // Populate selected shops on page load
            selectedShopIds.forEach(function(shopId) {
                if (shopId) {
                    let shopName = $("#shop option[value='" + shopId + "']").text();
                    if (shopName) {
                        $("#sortable-bus-stops").append(`
                            <li class="list-group-item d-flex justify-content-between align-items-center" data-id="${shopId}">
                                ${shopName}
                                <button class="btn btn-sm btn-danger remove-shop">Remove</button>
                            </li>
                        `);
                    }
                }
            });

            // Initialize sortable list
            $("#sortable-bus-stops").sortable({
                update: function(event, ui) {
                    updateShopIds();
                }
            });

            $("#shop").change(function() {
                let shopId = $(this).val();
                let shopName = $("#shop option:selected").text();

                if (shopId && $("#sortable-bus-stops li[data-id='" + shopId + "']").length === 0) {
                    $("#sortable-bus-stops").append(`
                        <li class="list-group-item d-flex justify-content-between align-items-center" data-id="${shopId}">
                            ${shopName}
                            <button class="btn btn-sm btn-danger remove-shop">Remove</button>
                        </li>
                    `);
                    updateShopIds();
                }
            });

            // Remove shop from list
            $(document).on("click", ".remove-shop", function() {
                $(this).parent().remove();
                updateShopIds();
            });

            // Update hidden input with sorted shop IDs
            function updateShopIds() {
                let shopIds = [];
                $("#sortable-bus-stops li").each(function() {
                    shopIds.push($(this).data("id"));
                });
                $("#shop_ids").val(shopIds.join(",")); 
            }
        });

    </script>
@endsection
