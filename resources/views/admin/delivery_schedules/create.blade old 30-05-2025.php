@extends('layouts.app')

@section('title')
    Delivery Schedule
@endsection

@section('css')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
    <style>
        #suggestions {
            position: absolute;
            z-index: 1050;
            max-height: 250px;
            overflow-y: auto;
        }
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
                    <li class="breadcrumb-item active" aria-current="page">Add New Delivery Schedule</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                <a class="btn btn-primary px-4" href="{{ route('delivery-schedule.index') }}"><i
                        class="fadeIn animated bx bx-arrow-back"></i>Back</a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->

    <form class="needs-validation" action="{{ route('delivery-schedule.store') }}" method="post" novalidate
        enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="shop_ids" id="shop_ids">
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header text-center">Add Delivery Schedule</div>
                    <div class="card-body">

                        <div class="row">
                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="delivery_date">Delivery Date <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" value="{{ old('delivery_date') }}"
                                    name="delivery_date" id="delivery_date" placeholder="Enter date" required>
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Please enter shop name.</div>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="driver_id">Choose Driver <span
                                        class="text-danger">*</span></label>
                                <select class="form-select single-select-field" name="driver_id" id="driver_id" required>
                                    <option value="" selected disabled>Select Driver</option>
                                    @foreach ($drivers as $driver)
                                        <option value="{{ $driver->id }}"
                                            {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                            {{ $driver->name }}</option>
                                    @endforeach
                                </select>
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Please enter shop contact person name.</div>
                            </div>

                            <!--<div class="mb-3 col-md-4">-->
                            <!--    <label class="form-label" for="vehicle_id">Choose Vehicle <span-->
                            <!--            class="text-danger">*</span></label>-->
                            <!--    <select class="form-select single-select-field" name="vehicle_id" id="vehicle_id" required>-->
                            <!--        <option value="" selected disabled>Select Vehicle</option>-->
                            <!--        @foreach ($vehicles as $vehicle)-->
                            <!--            <option value="{{ $vehicle->id }}"-->
                            <!--                {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>-->
                            <!--                {{ $vehicle->name }}( {{ $vehicle->vehicle_number }} )</option>-->
                            <!--        @endforeach-->
                            <!--    </select>-->
                            <!--    <div class="valid-feedback">Looks good!</div>-->
                            <!--    <div class="invalid-feedback">Please enter a valid shop address.</div>-->
                            <!--</div>-->

                            {{-- <div class="mb-3">
                                <label class="form-label" for="shop">Choose Shops<span
                                        class="text-danger">*</span></label>
                                <select class="form-select single-select-field" id="shop" name="shop"
                                    data-placeholder="Choose anything">
                                    <option value="" selected>Select Shops</option>
                                    @foreach ($shops as $shop)
                                        <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
                                    @endforeach
                                </select>
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Please enter start point.</div>
                            </div> --}}

                            {{-- <div class="mb-3">
                                <label class="form-label">Search Shop Name </label>
                                <input type="text" class="form-control" value="{{ old('shop_name') }}"
                                    name="search_shop_name" id="search_shop_name" placeholder="Enter shop name" required>
                                <div id="suggestions" class="list-group position-absolute w-100 z-index-3"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Selected Shops</label>
                                <ul id="sortable-bus-stops" class="list-group"></ul>
                            </div>


                            <div class="mb-3">
                                <label class="form-label">Sorting Shops Location</label>
                                <ul id="sortable-shop-location" class="list-group"></ul>
                            </div> --}}


                            <div class="mb-3">
                                <label class="form-label">Search Shop Name</label>
                                <input type="text" class="form-control" id="search_shop_name"
                                    placeholder="Enter shop name">
                                <div id="suggestions" class="list-group position-absolute w-100 z-index-3"></div>
                            </div>
                            {{-- <label class="form-label">Sorting Shops Manualy</label>
                            <ul id="sortable-shops" class="list-group mt-2"></ul> --}}

                            <div class="mb-3">
                                <label class="form-label">Sorting Shops Location</label>
                                <ul id="sortable-shop-location" class="list-group"></ul>
                            </div>
                            {{-- <button type="button" class="btn btn-outline-primary mb-3" onclick="sortShopsByDistance()">Sort
                                by Nearest Shop</button> --}}
                            <button type="button" class="btn btn-success mb-3" id="sortByNearestBtn">
                                Sort by Nearest Shop
                            </button>


              

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
                                    <input type="radio" id="customRadioInline1" name="is_visible" class="form-check-input"
                                        value="1" checked>
                                    <label class="form-check-label" for="customRadioInline1">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="customRadioInline2" name="is_visible" class="form-check-input"
                                        value="0">
                                    <label class="form-check-label" for="customRadioInline2">Inactive</label>
                                </div>
                            </div> --}}
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-grd-primary px-4 text-light">Submit</button>
                                <button type="reset" class="btn btn-grd-info px-4 text-light">Reset</button>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets/dashboard-assets/assets/plugins/select2/js/select2-custom.js') }}"></script>

    {{-- <script>
        // $(document).ready(function() {
        //     var $input = $('#search_shop_name');
        //     var $suggestions = $('#suggestions');

        //     $input.on('input', function() {
        //         var query = $(this).val().trim();
        //         if (query.length < 2) {
        //             $suggestions.empty().hide();
        //             return;
        //         }

        //         $.get('search', {
        //             search: query
        //         }, function(response) {
        //             $suggestions.empty().show();

        //             if (response.length > 0) {
        //                 response.forEach(function(item) {
        //                     var name = item.shop_name || '';
        //                     var address = item.shop_address || '';
        //                     var lat = item.shop_latitude || '';
        //                     var lng = item.shop_longitude || '';

        //                     var $option = $(
        //                         '<a href="#" class="list-group-item list-group-item-action d-flex flex-column"></a>'
        //                     );
        //                     $option.append(
        //                         `<div class="fw-bold">${name}</div><small>${address}</small>`
        //                     );

        //                     $option.data({
        //                         name,
        //                         address,
        //                         lat,
        //                         lng
        //                     });

        //                     $suggestions.append($option);
        //                 });
        //             } else {
        //                 $suggestions.append('<div class="list-group-item">No results found</div>');
        //             }
        //         });
        //     });

        //     $suggestions.on('click', 'a', function(e) {
        //         e.preventDefault();
        //         var data = $(this).data();

        //         $('#shop_name').val(data.name);
        //         $('#shop_address').val(data.address);
        //         $('#shop_latitude').val(data.lat);
        //         $('#shop_longitude').val(data.lng);

        //         $suggestions.empty().hide();
        //     });

        //     $(document).on('click', function(e) {
        //         if (!$(e.target).closest('#search_shop_name, #suggestions').length) {
        //             $suggestions.empty().hide();
        //         }
        //     });
        // });

        $(document).ready(function() {
            var $input = $('#search_shop_name');
            var $suggestions = $('#suggestions');

            $input.on('input', function() {
                var query = $(this).val().trim();
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
                            var name = item.shop_name || '';
                            var address = item.shop_address || '';
                            var lat = item.shop_latitude || '';
                            var lng = item.shop_longitude || '';

                            var $option = $(`
                        <a href="#" class="list-group-item list-group-item-action d-flex flex-column">
                            <div class="fw-bold">${name}</div>
                            <small>${address}</small>
                        </a>
                    `);

                            $option.data({
                                id: item.id,
                                name,
                                address,
                                lat,
                                lng
                            });

                            $suggestions.append($option);
                        });
                    } else {
                        $suggestions.append('<div class="list-group-item">No results found</div>');
                    }
                });
            });

            $suggestions.on('click', 'a', function(e) {
                e.preventDefault();
                var data = $(this).data();

                if ($('#sortable-shops li[data-id="' + data.id + '"]').length === 0) {
                    $('#sortable-shops').append(`
                        <li class="list-group-item d-flex align-items-center" data-id="${data.id}">
                            <span class="sl-no"></span>  
                            <span class="ms-2 flex-grow-1">${data.name} - ${data.address}</span>
                            <input type="hidden" name="shop_ids[]" value="${data.id}">
                            <input type="hidden" name="shop_names[]" value="${data.name}">
                            <input type="hidden" name="shop_addresses[]" value="${data.address}">
                            <input type="hidden" name="shop_latitudes[]" value="${data.lat}">
                            <input type="hidden" name="shop_longitudes[]" value="${data.lng}">
                            <button type="button" class="btn btn-danger btn-sm ms-2 remove-shop">❌</button>
                            <span class="handle text-secondary ms-auto" style="cursor: grab;">☰</span>
                        </li>
                    `);
                    updateShopSerialNumbers();
                }

                if ($('#sortable-shop-location li[data-id="' + data.id + '"]').length === 0) {
                    $('#sortable-shop-location').append(`
                        <li class="list-group-item d-flex align-items-center" data-id="${data.id}">
                            <span class="sl-no"></span>  
                            <span class="ms-2 flex-grow-1">${data.name} - ${data.address}</span>
                            <input type="hidden" name="shop_ids[]" value="${data.id}">
                            <input type="hidden" name="shop_names[]" value="${data.name}">
                            <input type="hidden" name="shop_addresses[]" value="${data.address}">
                            <input type="hidden" name="shop_latitudes[]" value="${data.lat}">
                            <input type="hidden" name="shop_longitudes[]" value="${data.lng}">
                            
                      
                        </li>
                    `);
                    updateShopSerialNumbers();
                }

            
                $input.val(''); 

                $suggestions.empty().hide();
            });

            $(document).on('click', '.remove-shop', function() {
                $(this).closest('li').remove();
                updateShopSerialNumbers();
            });

            $('#sortable-shops').sortable({
                handle: '.handle',
                update: function() {
                    setTimeout(updateShopSerialNumbers, 50);
                }
            }).disableSelection();

            function updateShopSerialNumbers() {
                $('#sortable-shops li').each(function(index) {
                    $(this).find('.sl-no').text((index + 1) + ". ");
                });
            }

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#search_shop_name, #suggestions').length) {
                    $suggestions.empty().hide();
                }
            });
        });
    </script> --}}




    <script>
        let currentLat = null;
        let currentLng = null;

        $(document).ready(function() {
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
                    <li class="list-group-item d-flex align-items-center" data-id="${data.id}">
                        <span class="sl-no"></span>  
                        <span class="ms-2 flex-grow-1">${data.name} - ${data.address}</span>
                        <input type="hidden" name="shop_ids[]" value="${data.id}">
                        <input type="hidden" name="shop_names[]" value="${data.name}">
                        <input type="hidden" name="shop_addresses[]" value="${data.address}">
                        <input type="hidden" name="shop_latitudes[]" value="${data.lat}">
                        <input type="hidden" name="shop_longitudes[]" value="${data.lng}">
                        <button type="button" class="btn btn-danger btn-sm ms-2 remove-shop">❌</button>
                        <span class="handle text-secondary ms-auto" style="cursor: grab;">☰</span>
                    </li>
                `);

                    $sortableList.append($li);
                    updateShopSerialNumbers();
                }
                $input.val(''); 
                
                $suggestions.empty().hide();
            });

            // ✅ Step 4: Remove shop from list
            $(document).on('click', '.remove-shop', function() {
                $(this).closest('li').remove();
                updateShopSerialNumbers();
            });

            // ✅ Step 5: Sortable init
            $sortableList.sortable({
                handle: '.handle',
                update: function() {
                    setTimeout(updateShopSerialNumbers, 50);
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
                updateShopSerialNumbers();
                alert("Shop list sorted by nearest location.");
            });

            // Close suggestions when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#search_shop_name, #suggestions').length) {
                    $suggestions.empty().hide();
                }
            });
        });
    </script>







    {{-- <script>
        $(document).ready(function() {
            // Initialize sortable list
            $("#sortable-bus-stops").sortable({
                update: function(event, ui) {
                    updateShopIds();
                }
            });

            $("#shop").change(function() {
                var shopId = $(this).val();
                var shopName = $("#shop option:selected").text();

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

            // Remove item on button click
            $(document).on("click", ".remove-shop", function() {
                $(this).parent().remove();
                updateShopIds();
            });

            // Function to update hidden input with sorted shop IDs
            function updateShopIds() {
                var shopIds = [];
                $("#sortable-bus-stops li").each(function() {
                    shopIds.push($(this).data("id"));
                });
                $("#shop_ids").val(shopIds.join(",")); // Store as comma-separated values
            }
        });
    </script> --}}
@endsection
