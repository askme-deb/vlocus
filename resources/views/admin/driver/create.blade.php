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
                    <li class="breadcrumb-item active" aria-current="page">Add New Driver</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            @can('Driver Create')
                <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                    <a class="btn btn-primary px-4" href="{{ route('driver.index') }}"><i
                            class="fadeIn animated bx bx-arrow-back"></i>Back</a>
                </div>
            @endcan
        </div>
    </div>
    <!--end breadcrumb-->

    <form class="needs-validation" action="{{ route('driver.store') }}" method="post" novalidate>
        @csrf
        <div class="row">
            <div class="col-md-12">

                <div class="card mt-4">
                    <div class="card-header text-center">Driving Licence</div>
                    <div class="card-body">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Date Of Birth <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="date" class="form-control" name="date_of_birth" id="date_of_birth"
                                    value="{{ old('date_of_birth') }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Driving Licence Number <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="driving_license_number"
                                        id="driving_license_number" value="{{ old('driving_license_number') }}">
                                    <button type="button" class="btn btn-grd-info text-light" id="verifyDlBtn">
                                        Fetch DL Details
                                    </button>
                                </div>
                                <small id="dlVerifyStatus" class="d-block mt-1"></small>
                                <input type="hidden" name="driving_license_verification_data"
                                    id="driving_license_verification_data"
                                    value="{{ old('driving_license_verification_data') }}">
                            </div>
                        </div>
                        <hr>
                        <p class="text-muted small mb-3">
                            Enter the Date of Birth and Driving Licence Number, then click
                            <strong>Fetch DL Details</strong>. 
                        </p>

                        @include('admin.driver._dl_fields', ['data' => null])
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header text-center">Basic Information</div>
                    <div class="card-body">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">First Name <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Enter First name" name="first_name"
                                    id="first_name" value="{{ old('first_name') }}" required>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Last Name <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Enter Last name" name="last_name"
                                    id="last_name" value="{{ old('last_name') }}" required>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Gender</label>
                            <div class="col-md-9">
                                <select class="form-control input-height" name="gender">
                                    <option value="" @if (! old('gender')) selected @endif>Select... (defaults to Male)</option>
                                    <option value="male" @if (old('gender') == 'male') selected @endif>Male</option>
                                    <option value="female" @if (old('gender') == 'female') selected @endif>Female</option>
                                    <option value="others" @if (old('gender') == 'others') selected @endif>Others</option>
                                </select>
                                <small class="d-block text-muted mt-1">
                                    Auto-filled after <strong>Fetch DL Details</strong> when BankU returns it; not every
                                    licence response includes gender. Can be corrected here or on the KYC page.
                                </small>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Address</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="address" id="address"
                                    value="{{ old('address') }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Profile Picture</label>
                            <div class="col-md-9">
                                <img class="img-thumbnail rounded" id="blah" alt="" width="200" src=""
                                    data-holder-rendered="true" style="display: none;">
                                <small class="d-block text-muted mt-1">
                                    Auto-filled from the driving licence photo after <strong>Fetch DL Details</strong>.
                                </small>
                                <input type="hidden" name="profile_photo_source" id="profile_photo_source"
                                    value="{{ old('profile_photo_source') }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Driving Exprience</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="driving_exprience"
                                    value="{{ old('driving_exprience') }}">
                            </div>
                        </div>

                        <p class="text-muted small mb-3">
                            Email, mobile number, status and Aadhaar/PAN verification are completed on the
                            <strong>Driver KYC</strong> page after this step.
                        </p>
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-grd-primary px-4 text-light">Submit</button>
                            <button type="reset" class="btn btn-grd-info px-4 text-light">Reset</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <div class="modal fade" id="quickCompanyAddModal">
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
                            <form action="" id="companyCreateForm" class="needs-validation" novalidate>
                                @csrf
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label" for="name">Company Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" placeholder="Enter Name"
                                            name="company_name" value="{{ old('company_name') }}" required>
                                        <div class="valid-feedback">Looks good!</div>
                                        <div class="invalid-feedback">Please enter company name.</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label" for="description">Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" id="email"
                                                value="{{ old('email') }}" placeholder="Enter Email" required>
                                            <div class="valid-feedback">Looks good!</div>
                                            <div class="invalid-feedback">Please enter a valid email.</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="phone">Phone <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" value="{{ old('phone') }}"
                                                name="phone" id="phone" placeholder="Enter phone" required>
                                            <div class="valid-feedback">Looks good!</div>
                                            <div class="invalid-feedback">Please enter phone.</div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label" for="trade_license">Registrations Number <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control"
                                                value="{{ old('trade_license') }}" name="trade_license"
                                                id="trade_license" placeholder="Enter trade_license" required>
                                            <div class="valid-feedback">Looks good!</div>
                                            <div class="invalid-feedback">Please enter trade license.</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Password <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="password"
                                                        id="generated-password" value="{{ old('password') }}" required>
                                                    <button type="button" class="btn btn-secondary"
                                                        id="generate-password">Generate</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label" for="address">Address</label>
                                            <textarea name="address" class="form-control" placeholder="Enter Address" id="address">{{ old('address') }}</textarea>
                                            <div class="valid-feedback">Looks good!</div>
                                            <div class="invalid-feedback">Please enter a valid address.</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label mb-3 d-flex">Status</label>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" id="customRadioInline1" name="is_verified"
                                                    class="form-check-input" value="1">
                                                <label class="form-check-label" for="customRadioInline1">Verified</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" id="customRadioInline2" name="is_verified"
                                                    class="form-check-input" value="0">
                                                <label class="form-check-label" for="customRadioInline2">Not
                                                    Verified</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <button type="button" class="btn btn-grd-danger text-light"
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

    <style>
        #dlFetchOverlay {
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, .85);
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 1rem;
        }
        #dlFetchOverlay .spinner-border {
            width: 3rem;
            height: 3rem;
        }
        #dlFetchOverlay span {
            font-weight: 600;
            color: #475569;
        }
    </style>
    <div id="dlFetchOverlay">
        <div class="spinner-border text-primary" role="status"></div>
        <span id="dlFetchOverlayText">Fetching driving licence details&hellip;</span>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets/dashboard-assets/assets/plugins/select2/js/select2-custom.js') }}"></script>

    @include('admin.driver._dl_scripts')

    <script>
        // Blocks the whole page behind #dlFetchOverlay for the DL fetch and
        // auto-submits this (DL-only) form once it succeeds, so the driver
        // is created immediately -- no separate manual Submit step. Events
        // are dispatched by _dl_scripts.blade.php around the fetch AJAX.
        $(document).on('banku:dl-fetch-start', function () {
            $('#dlFetchOverlayText').text('Fetching driving licence details…');
            $('#dlFetchOverlay').css('display', 'flex');
        });

        $(document).on('banku:dl-fetch-failed', function () {
            $('#dlFetchOverlay').hide();
        });

        $(document).on('banku:dl-fetched', function () {
            let $form = $('#verifyDlBtn').closest('form');
            if (!$form.length) return;

            $('#dlFetchOverlayText').text('Details fetched. Submitting…');

            if ($form[0].checkValidity() === false) {
                $form.addClass('was-validated');
                $('#dlFetchOverlay').hide();
                if (typeof round_error_noti === 'function') {
                    round_error_noti('Please complete the required fields before submitting.');
                }
                return;
            }

            $form.trigger('submit');
        });

        var genBtn = document.getElementById('generate-password');
        if (genBtn) {
            genBtn.addEventListener('click', function() {
                const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
                let password = '';
                for (let i = 0; i < 8; i++) {
                    password += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                document.getElementById('generated-password').value = password;
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#companyCreateForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: '{{ route('driver.add_company') }}',
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
                            $('#companyCreateForm')[0].reset();
                            $('#quickCompanyAddModal').modal('hide');
                            let newCompany =
                                `<option value="${response.company.id}">${response.company.name}</option>`;
                            $('#company_id').append(newCompany);
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
@endsection
