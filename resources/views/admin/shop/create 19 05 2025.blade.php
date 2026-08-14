@extends('layouts.app')

@section('title')
    Create Shop
@endsection

@section('css')
    <style>
        #suggestions {
            position: absolute;
            z-index: 1050;
            max-height: 250px;
            overflow-y: auto;
        }
    </style>
@endsection

@section('content')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Vehicle Brand</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Add New Brand</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                <a class="btn btn-primary px-4" href="{{ route('shop.index') }}"><i
                        class="fadeIn animated bx bx-arrow-back"></i>Back</a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->

    <form class="needs-validation" action="{{ route('shop.store') }}" method="post" novalidate
        enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header text-center">Add Shop Details</div>
                    <div class="card-body">

                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="shop_name">Shop Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="{{ old('shop_name') }}" name="shop_name"
                                    id="shop_name" placeholder="Enter shop name" required>
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Please enter shop name.</div>
                                <div id="suggestions" class="list-group position-absolute w-100 z-index-3"></div>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="shop_contact_person_name">Shop Contact Person Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="{{ old('shop_contact_person_name') }}"
                                    name="shop_contact_person_name" id="shop_contact_person_name"
                                    placeholder="Enter shop contact person name" required>
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Please enter shop contact person name.</div>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="shop_address">Shop Address <span
                                        class="text-danger">*</span></label>
                                <textarea name="shop_address" class="form-control" placeholder="Enter shop address" id="shop_address" required>{{ old('shop_address') }}</textarea>
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Please enter a valid shop address.</div>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="shop_contact_person_phone">Shop Contact Person Phone No.
                                    <span class="text-danger">*</span></label>
                                <input type="tel" minlength="10" maxlength="10" pattern="[0-9]{10}" class="form-control"
                                    value="{{ old('shop_contact_person_phone') }}" name="shop_contact_person_phone"
                                    id="shop_contact_person_phone" placeholder="Enter shop contact person phone no."
                                    required>
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Please enter shop contact person phone no.</div>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="shop_latitude">Latitude</label>
                                <input type="text" class="form-control" value="{{ old('shop_latitude') }}"
                                    name="shop_latitude" id="shop_latitude" placeholder="Enter latitude">
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Please enter latitude.</div>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="shop_longitude">Longitude</label>
                                <input type="text" class="form-control" value="{{ old('shop_longitude') }}"
                                    name="shop_longitude" id="shop_longitude" placeholder="Enter longitude">
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Please enter longitude.</div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="row">

                    <div class="card">
                        <div class="card-header text-center">Add Shop Image</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <img class="img-thumbnail rounded me-2" id="blah" alt="" width="200"
                                    src="" data-holder-rendered="true" style="display: none;">
                            </div>
                            <div class="mb-0">
                                <input class="form-control" name="image" type="file" id="imgInp">

                                <input type="hidden" name="shop_image_from_google" id="shop_image_from_google">

                                {{-- <img id="shop_preview" src="" alt="Shop Preview" class="img-thumbnail mt-2" style="max-width: 200px; display: none;"> --}}

                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header text-center">Publish</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label mb-3 d-flex">Status</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="customRadioInline1" name="is_visible"
                                        class="form-check-input" value="1" checked>
                                    <label class="form-check-label" for="customRadioInline1">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="customRadioInline2" name="is_visible"
                                        class="form-check-input" value="0">
                                    <label class="form-check-label" for="customRadioInline2">Inactive</label>
                                </div>
                            </div>
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
    <script>
        const GOOGLE_MAPS_API_KEY = "{{ env('GOOGLE_MAPS_API_KEY') }}";
        $(document).ready(function() {
            // var apiKey = "AlzaSyC7RSr791vm_29LJiUOPJO-sLnBZg6qiGl";
            var apiKey = GOOGLE_MAPS_API_KEY;
            var $input = $('#shop_name');
            var $suggestions = $('#suggestions');

            $input.on('input', function() {
                var query = $(this).val().trim();
                if (query.length < 3) {
                    $suggestions.empty().hide();
                    return;
                }

                $.get('https://maps.gomaps.pro/maps/api/place/textsearch/json', {
                    key: apiKey,
                    query: query
                }, function(response) {
                    $suggestions.empty().show();

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

                $('#shop_name').val(data.name);
                $('#shop_address').val(data.address);
                $('#shop_latitude').val(data.lat);
                $('#shop_longitude').val(data.lng);

                // $('#shop_preview').attr('src', data.image || '').toggle(!!data.image);
                $('#blah').attr('src', data.image || '').toggle(!!data.image);
                $('#shop_image_from_google').val(data.image);
                $suggestions.empty().hide();
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#shop_name, #suggestions').length) {
                    $suggestions.empty().hide();
                }
            });
        });
    </script>
@endsection
