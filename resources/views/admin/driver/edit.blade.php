@extends('layouts.app')

@section('title')
    Driver
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
@endsection

@section('content')

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Driver</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Driver</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                @if (optional($user->driver)->id)
                    <a class="btn btn-grd-info text-light px-4" href="{{ route('driver.show', $user->driver->id) }}">
                        <i class="bx bx-show"></i> View
                    </a>
                @endif
                <a class="btn btn-primary px-4" href="{{ route('driver.index') }}"><i
                        class="fadeIn animated bx bx-arrow-back"></i>Back</a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->

    <form class="needs-validation" action="{{ route('driver.update') }}" method="post" novalidate
        enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="user_id" name="user_id" value="{{ $user->id }}">

        <div class="row">
            <div class="col-md-9">

                <div class="card mt-4">
                    <div class="card-header text-center">Driving Licence</div>
                    <div class="card-body">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Date Of Birth</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control" name="date_of_birth" id="date_of_birth"
                                    value="{{ old('date_of_birth', $user->date_of_birth) }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Driving Licence Number</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="driving_license_number"
                                        id="driving_license_number"
                                        value="{{ old('driving_license_number', optional($user->driver)->driving_license_number) }}">
                                    <button type="button" class="btn btn-grd-info text-light" id="verifyDlBtn">
                                        Fetch DL Details
                                    </button>
                                </div>
                                <small id="dlVerifyStatus" class="d-block mt-1"></small>
                                <input type="hidden" name="driving_license_verification_data"
                                    id="driving_license_verification_data"
                                    value="{{ old('driving_license_verification_data', optional($user->driver)->driving_license_verification_data ? json_encode($user->driver->driving_license_verification_data) : '') }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Driving Licence Image</label>
                            <div class="col-md-9">
                                <div class="mb-3">
                                    <img class="img-thumbnail rounded me-2" id="blah4" alt="" width="200"
                                        src="{{ optional($user->driver)->getFirstMediaUrl('driver-license') }}"
                                        data-holder-rendered="true"
                                        style="display: {{ is_have_image(optional($user->driver)->getFirstMediaUrl('driver-license')) }};">
                                </div>
                                <div class="mb-0">
                                    <input class="form-control" name="driver_license_image" type="file" id="imgInp4">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <p class="text-muted small mb-3">
                            Edit any field below, or click <strong>Fetch DL Details</strong> to refresh from BankU.
                        </p>

                        @include('admin.driver._dl_fields', ['data' => $user->driver])
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header text-center">Basic Information</div>
                    <div class="card-body">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">First Name <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Enter First name" name="first_name"
                                    id="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Last Name <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Enter Last name" name="last_name"
                                    id="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Email <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="email" class="form-control" name="email" id="email"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Gender <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control input-height" name="gender" required>
                                    <option value selected disabled>Select...</option>
                                    <option value="male" @if ($user->gender == 'male') selected @endif>Male</option>
                                    <option value="female" @if ($user->gender == 'female') selected @endif>Female</option>
                                    <option value="others" @if ($user->gender == 'others') selected @endif>Others</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Mobile No. <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="phone" value="{{ old('phone', $user->phone) }}"
                                    required>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Alternative Mobile No.</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="opt_mobile_no"
                                    value="{{ old('opt_mobile_no', $user->opt_mobile_no) }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Address</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="address" id="address"
                                    value="{{ old('address', $user->address) }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Profile Picture</label>
                            <div class="col-md-9">
                                <div class="mb-3">
                                    <img class="img-thumbnail rounded me-2" id="blah" alt="" width="200"
                                        src="{{ $user->getFirstMediaUrl('system-user-image') }}"
                                        data-holder-rendered="true"
                                        style="display: {{ is_have_image($user->getFirstMediaUrl('system-user-image')) }};">
                                </div>
                                <div class="mb-0">
                                    <input class="form-control" name="profile_image" type="file" id="imgInp">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Vehicle Types <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control input-height" name="vehicle_type" required>
                                    <option value selected disabled>Select...</option>
                                    @foreach ($vehicle_types as $item)
                                        <option value="{{ $item->id }}"
                                            @if (old('vehicle_type', optional($user->driver)->vehicle_type ?? '') == $item->id) selected @endif>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Vehicle <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control input-height" name="vehicle_id" required>
                                    <option value selected disabled>Select...</option>
                                    @foreach ($vehicles as $item)
                                        <option value="{{ $item->id }}"
                                            @if (old('vehicle_id', optional($user->driver)->vehicle_id) == $item->id) selected @endif>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Driving Exprience</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="driving_exprience"
                                    value="{{ old('driving_exprience', optional($user->driver)->driving_exprience) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header text-center">Driver KYC</div>
                    <div class="card-body">
                        @include('admin.driver._kyc_fields', ['data' => $user])
                    </div>
                </div>

            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="card mt-4">
                        <div class="card-header text-center">Account Information</div>
                        <div class="card-body">
                            <div class="row clearfix">
                                <div class="col-sm-12 mb-3">
                                    <div class="form-group">
                                        <label class="col-form-label">Email</label>
                                        <input type="text" id="login-email" class="form-control"
                                            value="{{ $user->email }}" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-12 mb-3">
                                    <div class="form-group">
                                        <label class="col-form-label">Password</label>
                                        <input type="text" class="form-control" name="password"
                                            value="{{ old('password') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header text-center">Publish</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label mb-3 d-flex">Status</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="customRadioInline1" name="status" class="form-check-input"
                                        value="1" {{ check_uncheck($user->status, 1) }}>
                                    <label class="form-check-label" for="customRadioInline1">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="customRadioInline2" name="status" class="form-check-input"
                                        value="0" {{ check_uncheck($user->status, 0) }}>
                                    <label class="form-check-label" for="customRadioInline2">Inactive</label>
                                </div>
                            </div>
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-grd-primary px-4 text-light">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets/dashboard-assets/assets/plugins/select2/js/select2-custom.js') }}"></script>

    @include('admin.driver._dl_scripts')
    @include('admin.driver._kyc_scripts')

    <script>
        $('#email').on('keyup', function() {
            $('#login-email').val($(this).val());
        });

        [
            ['#imgInp', '#blah'],
            ['#imgInp4', '#blah4']
        ].forEach(function(pair) {
            $(pair[0]).on('change', function() {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $(pair[1]).attr('src', e.target.result).css('display', 'block');
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
    </script>
@endsection
