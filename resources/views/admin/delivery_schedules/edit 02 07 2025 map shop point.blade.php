@extends('layouts.app')

@section('title')
    Delivery Schedule
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">

    <style>
        #suggestions {
            position: absolute;
            z-index: 1050;
            max-height: 250px;
            overflow-y: auto;
        }

        #shop_suggestions {
            position: absolute;
            z-index: 1050;
            max-height: 250px;
            overflow-y: auto;
        }

        #shop_address_suggestions {
            position: absolute;
            z-index: 1050;
            max-height: 250px;
            overflow-y: auto;
        }

        <style>#suggestions {
            position: absolute;
            z-index: 1050;
            max-height: 188px;
            overflow-y: auto;
            max-width: 350px;
        }

        .card {
            background: #fff;
            box-shadow: 0px 0px 5px 0px #9999999e;
            margin: 20px 25px 0px 0px;
            padding: 20px;
        }

        .deliveryScheduleDashboardRight {
            background: #e7e3e3;
            padding: 20px;
        }

        .form-label {
            margin-bottom: .5rem;
            color: #000;
        }

        .dashboardleft img {
            width: 100%;
        }

        .dashboardleft {
            /* padding: 75px 0px 0px 25px; */
            padding: 0px 0px 0px 20px;
            margin: 20px 0px;
            box-shadow: none;
        }

        .dashboardleft .card-header {
            background: #e7e3e3;
            margin-bottom: 20px;
            padding: 25px 0px;



        }

        .scrollable-list {
            max-height: 70px;
            overflow-y: auto;
        }


        .scrollable-list {
            max-height: 260px;
            overflow-y: auto;
        }

        #sortable-shops {
            /*position: absolute;*/
            /*       z-index: 1050;*/
            /*       max-height: 250px;*/
            overflow-y: auto;
        }

        .form-control {

            color: #000;
        }

        .select2-container--bootstrap-5.select2-container--focus .select2-selection,
        .select2-container--bootstrap-5.select2-container--open .select2-selection {

            border: 1px solid #000;
        }

        .form-control:focus {

            border: 1px solid #000;
            color: #000;
        }

        .select2-selection .select2-selection--single {
            background: #e7e3e3;
            border: 1px solid #000;
        }

        .select2 .select2-container .select2-container--bootstrap-5 {
            background: #e7e3e3 !important;
            border: 1px solid #000 !important;
        }

        .calender {
            position: relative;
        }

        .fa-calendar {
            position: absolute;
            top: 12px;
            right: 16px;
        }

        .card-color {
            background: #e7e3e3;
        }

        span.select2-selection.select2-selection--single {
            /* background: blue; */

            background-repeat: no-repeat !important;
            background-position: right !important;
        }


        #shop_suggestions {
            position: absolute;
            z-index: 1050;
            max-height: 250px;
            overflow-y: auto;
        }

        #shop_address_suggestions {
            position: absolute;
            z-index: 1050;
            max-height: 250px;
            overflow-y: auto;
        }
    </style>
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
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

    <form class="needs-validation" action="{{ route('delivery-schedule.update', $delivery_Schedule->id) }}" method="post"
        novalidate enctype="multipart/form-data">
        @csrf
        @method('PUT')


        {{-- <input type="hidden" name="shop_ids" id="shop_ids" value="{{ implode(',', $selectedShops) }}"> --}}
        <div class="row">
            <!-- <div class="col-md-3 bg-white">-->

            <!--</div>-->
            <div class="col-md-12">
                <div class="deliveryScheduleDashboardRight">
                    <div class="bg-white">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="dashboardleft card">
                                    <div class="card-header text-center text-black">Easy Task Creation to manage deliveries
                                    </div>
                                    {{-- <img src="{{ asset('assets/dashboard-assets/assets/images/map new.jpg') }}"> --}}
                                    <div id="shopMap" style="height: 300px;"></div>
                                </div>


                            </div>

                            <div class="col-lg-6">

                                <div class="card card-color" style="height: 623px">
                                    <div class="card-header text-center text-black" style="padding-bottom:20px;">Edit
                                        Delivery Schedule</div>
                                    <div class="card-body">

                                        <div class="row">


                                            <div style="display: flex; gap:20px; width:470px">
                                                <div class="mb-3 col-md-12" style="width: 30%">
                                                    <label class="form-label" for="delivery_date">Delivery Date <span
                                                            class="text-danger">*</span></label>

                                                    <input style="font-size: 15px" type="date" class="form-control"
                                                        value="{{ old('delivery_date', date('Y-m-d')) }}"
                                                        name="delivery_date" id="delivery_date" placeholder="Enter date"
                                                        required>



                                                    <div class="valid-feedback">Looks good!</div>
                                                    <div class="invalid-feedback">Please enter shop name.</div>
                                                </div>

                                                <div class="mb-3 col-md-12" style="width:70%">
                                                    <label class="form-label" for="driver_id">Choose Driver <span
                                                            class="text-danger">*</span>
                                                    </label>

                                                    <select class="form-select single-select-field" name="driver_id"
                                                        id="driver_id" required>
                                                        <option value="" selected disabled>Select Driver</option>
                                                        @foreach ($drivers as $driver)
                                                            <option value="{{ $driver->id }}"
                                                                {{ old('driver_id', $delivery_Schedule->driver_id) == $driver->id ? 'selected' : '' }}>
                                                                {{ $driver->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="valid-feedback">Looks good!</div>
                                                    <div class="invalid-feedback">Please enter shop contact person name.
                                                    </div>
                                                </div>
                                            </div>


                                            <!--<div style="display: flex; gap:20px; width:470px">-->
                                            <!--    <div class="mb-3 col-md-12" style="width: 30%">-->
                                            <!--        <label class="form-label" for="payment_type">Payment Type </label>-->
                                            <!--        <select class="form-select " name="payment_type"-->
                                            <!--            id="payment_type" required>-->
                                            <!--            <option value="" selected disabled>Select Type</option>-->

                                            <!--            <option value="Pre-Paid"-->
                                            <!--                {{ old('payment_type', $delivery_Schedule->payment_type) == 'Pre-Paid' ? 'selected' : '' }}>-->
                                            <!--                Pre-Paid-->

                                            <!--            </option>-->
                                            <!--            <option value="Pre-Paid"-->
                                            <!--                {{ old('payment_type', $delivery_Schedule->payment_type) == 'COD' ? 'selected' : '' }}>COD-->

                                            <!--            </option>-->

                                            <!--        </select>-->
                                            <!--        <div class="valid-feedback">Looks good!</div>-->
                                            <!--        <div class="invalid-feedback">Please payment type.</div>-->
                                            <!--    </div>-->

                                            <!--    <div class="mb-3 col-md-12" style="width:70%">-->
                                            <!--    <label class="form-label" for="amount">Amount <span-->
                                            <!--            class="text-danger">*</span>-->
                                            <!--    </label>-->

                                            <!--    <input style="font-size: 15px" type="text" class="form-control"-->
                                            <!--        value="{{ old('amount', $delivery_Schedule->amount) }}"-->
                                            <!--        name="amount" id="amount" placeholder="Enter amount"-->
                                            <!--        >-->
                                            <!--    <div class="valid-feedback">Looks good!</div>-->
                                            <!--    <div class="invalid-feedback">Please enter amount.-->
                                            <!--    </div>-->
                                            <!--</div>-->
                                            <!--</div>-->

                                            <!--<div class="mb-3 col-md-12" >-->
                                            <!--    <label class="form-label" for="delivery_date">Delivery Note <span-->
                                            <!--            class="text-danger">*</span></label>-->
                                            <!--    <textarea name="delivery_note" id="delivery_note" class="form-control">{{ old('delivery_note', $delivery_Schedule->delivery_note) }}</textarea>-->
                                            <!--    <div class="valid-feedback">Looks good!</div>-->
                                            <!--    <div class="invalid-feedback">Please delivery note.</div>-->
                                            <!--</div>-->







                                            <div class="mb-3">
                                                <label class="form-label">Add Shops</label>
                                                <a class="btn view-map border" data-bs-toggle="modal"
                                                    data-bs-target="#quickShopAddModal">
                                                    <i class="text-primary" data-feather="plus"></i>
                                                </a>
                                                <input type="text" class="form-control" id="search_shop_name"
                                                    placeholder="Enter shop name">
                                                <div id="suggestions"
                                                    class="list-group position-absolute w-100 z-index-3"></div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Sorting Shops Location</label>


                                                <ul id="sortable-shop-location" class="list-group scrollable-list"
                                                    style="height: 1800px">
                                                    @foreach ($selectedShops as $index => $item)
                                                        @php
                                                            $shop = $item->shop;
                                                        @endphp

                                                        @if ($shop)
                                                            <li class="list-group-item d-flex flex-column"
                                                                data-id="{{ $shop->id }}">

                                                                <div class="handle" style="cursor: grab;">
                                                                    <div
                                                                        class="d-flex align-items-center justify-content-between">

                                                                        <div>
                                                                            <span
                                                                                class="sl-no">{{ $loop->iteration }}.</span>
                                                                            <span
                                                                                class="ms-2 flex-grow-1">{{ $shop->shop_name }}
                                                                                L.R / Challan No - {{ $item->lr_no }}</span>
                                                                        </div>

                                                                        <div class="handle text-secondary"
                                                                            style="cursor: grab;">
                                                                               <button type="button"
                                                                                class="btn btn-sm btn-outline-secondary toggle-details">
                                                                                <i class="fa fa-chevron-down"></i>
                                                                            </button>
                                                                            <button type="button"
                                                                                class="btn btn-danger btn-sm remove-shop"><i
                                                                                    class="fa-solid fa-trash"></i></button>
                                                                        </div>


                                                                    </div>

                                                                    {{-- Hidden basic shop info --}}
                                                                    <input type="hidden" name="shop_ids[]"
                                                                        value="{{ $shop->id }}">
                                                                    <input type="hidden" name="shop_names[]"
                                                                        value="{{ $shop->shop_name }}">
                                                                    <input type="hidden" name="shop_addresses[]"
                                                                        value="{{ $shop->shop_address }}">
                                                                    <input type="hidden" name="shop_latitudes[]"
                                                                        value="{{ $shop->shop_latitude }}">
                                                                    <input type="hidden" name="shop_longitudes[]"
                                                                        value="{{ $shop->shop_longitude }}">

                                                                    <div class="shop-details mt-3" style="display: none;">
                                                                        {{-- Invoice --}}
                                                                        <div class="d-flex gap-3 mb-2">
                                                                            <div style="width: 33%;">
                                                                                <input type="text" class="form-control"
                                                                                    name="invoice_no[]"
                                                                                    id="invoice_no_{{ $shop->id }}"
                                                                                    value="{{ $item->invoice_no }}"
                                                                                    placeholder="Enter invoice number">
                                                                            </div>

                                                                            {{-- Payment & Amount --}}

                                                                            <div style="width: 33%;">
                                                                                <select class="form-select"
                                                                                    name="payment_types[]"
                                                                                    id="payment_type_{{ $shop->id }}"
                                                                                    required>
                                                                                    <option value="" disabled
                                                                                        {{ $item->payment_type == null ? 'selected' : '' }}>
                                                                                        Select Type</option>
                                                                                    <option value="Pre-Paid"
                                                                                        {{ $item->payment_type == 'Pre-Paid' ? 'selected' : '' }}>
                                                                                        Pre-Paid</option>
                                                                                    <option value="COD"
                                                                                        {{ $item->payment_type == 'COD' ? 'selected' : '' }}>
                                                                                        COD</option>
                                                                                </select>
                                                                            </div>

                                                                            <div style="width: 33%;">
                                                                                <input type="number" class="form-control"
                                                                                    name="amounts[]"
                                                                                    id="amount_{{ $shop->id }}"
                                                                                    value="{{ $item->amount }}"
                                                                                    placeholder="Enter amount" required>
                                                                            </div>
                                                                        </div>

                                                                        {{-- Delivery Note --}}
                                                                        <div class="mb-3">
                                                                            <textarea class="form-control" name="delivery_notes[]" id="delivery_note_{{ $shop->id }}" rows="2"
                                                                                required>{{ $item->delivery_note }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>

                                            </div>




                                            <div class="position-absolute bottom-0 end-0 start-0 p-3  border-top">
                                                <div class="d-md-flex d-grid justify-content-end align-items-center gap-3">
                                                    <button type="submit"
                                                        class="btn btn-grd-primary px-4 text-light">Submit</button>
                                                    <button type="button" class="btn btn-success px-4"
                                                        id="sortByNearestBtn">Sort</button>
                                                    <button type="reset"
                                                        class="btn btn-grd-info px-4 text-light">Reset</button>
                                                </div>


                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>


    <div class="modal fade" id="quickShopAddModal">
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
@endsection


@section('scripts')
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets/dashboard-assets/assets/plugins/select2/js/select2-custom.js') }}"></script>




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

                $.get('https://maps.gomaps.pro/maps/api/place/textsearch/json', {
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
                                `https://maps.gomaps.pro/maps/api/place/photo?maxwidth=400&photo_reference=${photoRef}&key=${apiKey}` :
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

                $.get('https://maps.gomaps.pro/maps/api/place/textsearch/json', {
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
                                `https://maps.gomaps.pro/maps/api/place/photo?maxwidth=400&photo_reference=${photoRef}&key=${apiKey}` :
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

    
    
    
    <script src="https://maps.gomaps.pro/maps/api/js?key=AlzaSyC7RSr791vm_29LJiUOPJO-sLnBZg6qiGl&libraries=places,geometry">
    </script>
    <script>
        let map;
        let routeLine;
        let selectedShops = [];
        let driverLat = 22.5059365;
        let driverLng = 88.3716779;

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

        // ✅ Call this every time shops are added, removed, or reordered
        async function drawMultiStopRoute() {
            if (selectedShops.length === 0) return;

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
                const response = await fetch("https://routes.gomaps.pro/directions/v2:computeRoute", {
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
                    strokeColor: "#28a745",
                    strokeOpacity: 1.0,
                    strokeWeight: 4,
                });
                routeLine.setMap(map);

                const bounds = new google.maps.LatLngBounds();
                decodedPath.forEach(p => bounds.extend(p));
                map.fitBounds(bounds);
            } catch (err) {
                console.error("Route draw failed:", err);
            }
        }

        $(document).ready(function() {
            initializeMap(); // Always initialize with driver's location
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
            
            // ✅ Extract shops from edit DOM into selectedShops
            $sortableList.children('li').each(function() {
                const $li = $(this);
                selectedShops.push({
                    id: $li.data('id'),
                    name: $li.find('input[name="shop_names[]"]').val(),
                    lat: parseFloat($li.find('input[name="shop_latitudes[]"]').val()),
                    lng: parseFloat($li.find('input[name="shop_longitudes[]"]').val())
                });
            });

            // ✅ Draw initial map route
            drawMultiStopRoute();

            // ✅ Expand first shop details on load
            $sortableList.find('li:first .shop-details').slideDown(200);
            $sortableList.find('li:first .toggle-details i').removeClass('fa-chevron-down').addClass('fa-chevron-up');

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
            $suggestions.on('click', 'a', function(e) {
                e.preventDefault();
                const data = $(this).data();

                // Prevent duplicate
                if ($sortableList.find(`li[data-id="${data.id}"]`).length === 0) {
                    const $li = $(`
                        <li class="list-group-item d-flex flex-column" data-id="${data.id}">
                            <div class="handle" style="cursor: grab;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="sl-no"></span>
                                        <span class="ms-2 fw-bold">${data.name}</span>
                                    </div>
                                    <div class="handle text-secondary" style="cursor: grab;">
                                        <button type="button" class="btn btn-sm btn-outline-secondary toggle-details">
                                            <i class="fa fa-chevron-down"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm ms-2 remove-shop">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                <input type="hidden" name="shop_ids[]" value="${data.id}">
                                <input type="hidden" name="shop_names[]" value="${data.name}">
                                <input type="hidden" name="shop_addresses[]" value="${data.address}">
                                <input type="hidden" name="shop_latitudes[]" value="${data.lat}">
                                <input type="hidden" name="shop_longitudes[]" value="${data.lng}">

                                <div class="shop-details mt-3" style="display: none;">
                                    <div class="d-flex gap-3 mb-2">
                                        <div style="width: 33%;">
                                            <input type="text" class="form-control" name="invoice_no[]" placeholder="Enter invoice number">
                                        </div>
                                        <div style="width: 33%;">
                                            <select class="form-select" name="payment_types[]">
                                                <option value="" selected disabled>Select Type</option>
                                                <option value="Pre-Paid">Pre-Paid</option>
                                                <option value="COD">COD</option>
                                            </select>
                                        </div>
                                        <div style="width: 33%;">
                                            <input type="text" class="form-control" name="amounts[]" placeholder="Enter amount">
                                        </div>
                                    </div>

                                    <div>
                                        <textarea class="form-control" name="delivery_notes[]" rows="2" placeholder="Enter delivery note"></textarea>
                                    </div>
                                </div>
                            </div>
                        </li>
                    `);
                    
                    //  Collapse all existing details
                    $sortableList.find('.shop-details').slideUp(200);
                    $sortableList.find('.toggle-details i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
                        
                    // Expand new one
                    $li.find('.shop-details').show();
                    $li.find('.toggle-details i').removeClass('fa-chevron-down').addClass('fa-chevron-up');


                    $sortableList.append($li);
                    selectedShops.push({
                        id: data.id,
                        name: data.name,
                        lat: parseFloat(data.lat),
                        lng: parseFloat(data.lng)
                    });
                    drawMultiStopRoute();
                    updateShopSerialNumbers();
                    // drawRouteToShop(parseFloat(data.lat), parseFloat(data.lng));
                }
                const input = $(`
                        <input type="text" class="form-control" id="search_shop_name"
                                                    placeholder="Enter shop name">
                `);
                $sortableList.append($input);
                $input.val('');

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

            // // ✅ Step 5: Sortable init
            // $sortableList.sortable({
            //     handle: '.handle',
            //     update: function() {
            //         setTimeout(updateShopSerialNumbers, 50);
            //     }
            // }).disableSelection();

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
                $sortableList.find('li').each(function(index) {
                    $(this).find('.sl-no').text((index + 1) + ". ");
                });
            }

            // ✅ Step 6: Sort by Nearest Distance
            $('#sortByNearestBtn').on('click', function() {
                if (currentLat === null || currentLng === null) {
                    alert("User location not available yet.");
                    return;
                }

                function getDistance(lat1, lon1, lat2, lon2) {
                    const R = 6371;
                    const dLat = (lat2 - lat1) * Math.PI / 180;
                    const dLon = (lon2 - lon1) * Math.PI / 180;
                    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                        Math.sin(dLon / 2) * Math.sin(dLon / 2);
                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                    return R * c;
                }

                const shopItems = $sortableList.children('li').map(function() {
                    const $li = $(this);
                    const lat = parseFloat($li.find('input[name="shop_latitudes[]"]').val());
                    const lng = parseFloat($li.find('input[name="shop_longitudes[]"]').val());
                    const distance = getDistance(currentLat, currentLng, lat, lng);
                    return {
                        element: $li,
                        distance
                    };
                }).get();

                shopItems.sort((a, b) => a.distance - b.distance);
                $sortableList.empty();
                shopItems.forEach(item => $sortableList.append(item.element));
                // 🟡 Update selectedShops array based on new DOM order
                selectedShops = $sortableList.children('li').map(function() {
                    const $li = $(this);
                    return {
                        id: $li.data('id'),
                        name: $li.find('input[name="shop_names[]"]').val(),
                        lat: parseFloat($li.find('input[name="shop_latitudes[]"]').val()),
                        lng: parseFloat($li.find('input[name="shop_longitudes[]"]').val())
                    };
                }).get();

                // ✅ Redraw route with new order
                drawMultiStopRoute();
                updateShopSerialNumbers();
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
            
             // Collapse all shop details if user clicks outside any shop block
            $(document).on('click', function(e) {
                const $target = $(e.target);
                const clickedInsideShop = $target.closest(
                    '.list-group-item, #suggestions, #search_shop_name').length > 0;

                if (!clickedInsideShop) {
                    $('.shop-details').slideUp(200);
                    $('.toggle-details i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
                }
            });

        });
    </script>
@endsection
