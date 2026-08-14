

<?php $__env->startSection('title'); ?>
    Driver
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
    
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Driver</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('dashboard')); ?>">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Add New Driver</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Driver Create')): ?>
                <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                    <a class="btn btn-primary px-4" href="<?php echo e(route('driver.index')); ?>"><i
                            class="fadeIn animated bx bx-arrow-back"></i>Back</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!--end breadcrumb-->

    <form class="needs-validation" action="<?php echo e(route('driver.store')); ?>" method="post" novalidate
        enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="row">
            <div class="col-md-9">
                <div class="card mt-4">
                    <div class="card-header text-center">Basic Information</div>
                    <div class="card-body">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Date Of Birth <span class="text-danger"></span></label>
                            <div class="col-md-9">
                                <input type="date" class="form-control" name="date_of_birth" id="date_of_birth"
                                    value="<?php echo e(old('date_of_birth')); ?>">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Driving License Number <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="driving_license_number"
                                        id="driving_license_number" value="<?php echo e(old('driving_license_number')); ?>">
                                    <button type="button" class="btn btn-grd-info text-light" id="verifyDlBtn">Verify</button>
                                </div>
                                <small id="dlVerifyStatus" class="d-block mt-1"></small>
                                <input type="hidden" name="driving_license_verification_data" id="dl_verification_data" value="<?php echo e(old('driving_license_verification_data')); ?>">
                            </div>
                        </div>
                        <div class="form-group row mb-3" id="dlVerifiedFieldsWrap" style="display: none;">
                            <label class="col-md-3 col-form-label">Verified License Details</label>
                            <div class="col-md-9">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">Issuing State</small>
                                        <input type="text" class="form-control form-control-sm" id="dl_state" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">Valid Till</small>
                                        <input type="text" class="form-control form-control-sm" id="dl_valid_till" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">Vehicle Class</small>
                                        <input type="text" class="form-control form-control-sm" id="dl_vehicle_class" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">Father / Husband Name</small>
                                        <input type="text" class="form-control form-control-sm" id="dl_guardian_name" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Driving License Image</label>
                            <div class="col-md-9">
                                <div class="mb-3">
                                    <img class="img-thumbnail rounded me-2" id="blah4" alt="" width="200"
                                        src="" data-holder-rendered="true" style="display: none;">
                                </div>
                                <div class="mb-0">
                                    <input class="form-control" name="driver_license_image" type="file"
                                        id="imgInp4">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">First Name <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Enter First name" name="first_name"
                                    id="first_name" value="<?php echo e(old('first_name')); ?>" required>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Last Name <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Enter Last name" name="last_name"
                                    id="last_name" value="<?php echo e(old('last_name')); ?>" required>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Email <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="email" class="form-control" name="email" id="email"
                                    value="<?php echo e(old('email')); ?>" required>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Gender <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control input-height" name="gender" required>
                                    <option value selected disabled>Select...</option>
                                    <option value="male" <?php if(old('gender') == 'male'): ?> selected <?php endif; ?>>Male</option>
                                    <option value="female" <?php if(old('gender') == 'female'): ?> selected <?php endif; ?>>Female</option>
                                    <option value="others" <?php if(old('gender') == 'others'): ?> selected <?php endif; ?>>Others</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Mobile No. <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="phone" value="<?php echo e(old('phone')); ?>"
                                    required>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Alternative Mobile No. <span
                                    class="text-danger"></span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="opt_mobile_no"
                                    value="<?php echo e(old('opt_mobile_no')); ?>">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Address <span class="text-danger"></span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="address" id="address" value="<?php echo e(old('address')); ?>">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Profile Picture</label>
                            <div class="col-md-9">
                                <div class="mb-3">
                                    <img class="img-thumbnail rounded me-2" id="blah" alt="" width="200"
                                        src="" data-holder-rendered="true" style="display: none;">
                                </div>
                                <div class="mb-0">
                                    <input class="form-control" name="profile_image" type="file" id="imgInp">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Aadhar Card Number <span
                                    class="text-danger"></span></label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="aadhaar_number"
                                        id="aadhaar_number" value="<?php echo e(old('aadhaar_number')); ?>">
                                    <button type="button" class="btn btn-grd-info text-light" id="sendAadhaarOtpBtn">Send OTP</button>
                                </div>
                                <small id="aadhaarVerifyStatus" class="d-block mt-1"></small>
                                <input type="hidden" name="aadhaar_verification_data" id="aadhaar_verification_data" value="<?php echo e(old('aadhaar_verification_data')); ?>">
                                <input type="hidden" id="aadhaar_ref_id">
                            </div>
                        </div>
                        <div class="form-group row mb-3" id="aadhaarOtpWrap" style="display: none;">
                            <label class="col-md-3 col-form-label">Aadhaar OTP</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="aadhaar_otp" maxlength="6"
                                        placeholder="Enter 6-digit OTP">
                                    <button type="button" class="btn btn-grd-info text-light" id="verifyAadhaarOtpBtn">Verify OTP</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Aadhar Card Image</label>
                            <div class="col-md-9">
                                <div class="mb-3">
                                    <img class="img-thumbnail rounded me-2" id="blah2" alt="" width="200"
                                        src="" data-holder-rendered="true" style="display: none;">
                                </div>
                                <div class="mb-0">
                                    <input class="form-control" name="aadhar_image" type="file" id="imgInp2">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Pan Card Number <span
                                    class="text-danger"></span></label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="pan_card_number"
                                        id="pan_card_number" value="<?php echo e(old('pan_card_number')); ?>">
                                    <button type="button" class="btn btn-grd-info text-light" id="verifyPanBtn">Verify</button>
                                </div>
                                <small id="panVerifyStatus" class="d-block mt-1"></small>
                                <input type="hidden" name="pan_verification_data" id="pan_verification_data" value="<?php echo e(old('pan_verification_data')); ?>">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Pan Card Image</label>
                            <div class="col-md-9">
                                <div class="mb-3">
                                    <img class="img-thumbnail rounded me-2" id="blah3" alt="" width="200"
                                        src="" data-holder-rendered="true" style="display: none;">
                                </div>
                                <div class="mb-0">
                                    <input class="form-control" name="pan_image" type="file" id="imgInp3">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Driving Exprience <span
                                    class="text-danger"></span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="driving_exprience"
                                    value="<?php echo e(old('driving_exprience')); ?>">
                            </div>
                        </div>
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
                                            value="<?php echo e(old('email')); ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-12 mb-3">
                                    <div class="form-group">
                                        <label class="col-form-label">Password <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="password"
                                            value="<?php echo e(old('password')); ?>" required>
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
                                    <input type="radio" id="customRadioInline1" name="status"
                                        class="form-check-input" value="1" checked>
                                    <label class="form-check-label" for="customRadioInline1">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="customRadioInline2" name="status"
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
                                <?php echo csrf_field(); ?>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label" for="name">Company Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" placeholder="Enter Name"
                                            name="company_name" value="<?php echo e(old('company_name')); ?>" required>
                                        <div class="valid-feedback">Looks good!</div>
                                        <div class="invalid-feedback">Please enter company name.</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label" for="description">Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" id="email"
                                                value="<?php echo e(old('email')); ?>"placeholder="Enter Email" required>
                                            <div class="valid-feedback">Looks good!</div>
                                            <div class="invalid-feedback">Please enter a valid email.</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="phone">Phone <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" value="<?php echo e(old('phone')); ?>"
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
                                                value="<?php echo e(old('trade_license')); ?>" name="trade_license"
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
                                                        id="generated-password" value="<?php echo e(old('password')); ?>" required>
                                                    <button type="button" class="btn btn-secondary"
                                                        id="generate-password">Generate</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label" for="address">Address</label>
                                            <textarea name="address" class="form-control" placeholder="Enter Address" id="address"><?php echo e(old('address')); ?></textarea>
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

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="<?php echo e(asset('assets/dashboard-assets/assets/plugins/select2/js/select2-custom.js')); ?>"></script>
    <script>
        $('#email').on('keyup', function() {
            $('#login-email').val($(this).val());
        });
    </script>
    <script>
        $('#imgInp2').on('change', function() {
            var input = this;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#blah2').attr('src', e.target.result).css('display', 'block');
                }
                reader.readAsDataURL(input.files[0]);
            }
        });
        $('#imgInp3').on('change', function() {
            var input = this;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#blah3').attr('src', e.target.result).css('display', 'block');
                }
                reader.readAsDataURL(input.files[0]);
            }
        });
        $('#imgInp4').on('change', function() {
            var input = this;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#blah4').attr('src', e.target.result).css('display', 'block');
                }
                reader.readAsDataURL(input.files[0]);
            }
        });
        document.getElementById('generate-password').addEventListener('click', function() {
            const length = 8;
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
            let password = '';

            for (let i = 0; i < length; i++) {
                password += chars.charAt(Math.floor(Math.random() * chars.length));
            }

            document.getElementById('generated-password').value = password;
        });
    </script>
        <script>
        $(document).ready(function() {

            $('#companyCreateForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: '<?php echo e(route('driver.add_company')); ?>',
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
    <script>
        function normalizeDobForInput(value) {
            if (!value) return '';
            value = String(value).trim();

            // Already in the yyyy-mm-dd format the <input type="date"> expects.
            if (/^\d{4}-\d{2}-\d{2}$/.test(value)) {
                return value;
            }

            // Common dd/mm/yyyy or dd-mm-yyyy format.
            let match = value.match(/^(\d{2})[\/\-](\d{2})[\/\-](\d{4})$/);
            if (match) {
                return `${match[3]}-${match[2]}-${match[1]}`;
            }

            return '';
        }

        // Gender isn't guaranteed from every verification source (BankU's DL
        // API doesn't return it at all), so whichever of DL / Aadhaar / PAN
        // responds with a recognizable value first fills the field.
        function applyGenderFromApi(rawValue) {
            if (!rawValue) return;

            let genderMap = {
                m: 'male',
                male: 'male',
                f: 'female',
                female: 'female',
                o: 'others',
                others: 'others',
                other: 'others',
                transgender: 'others'
            };

            let mapped = genderMap[String(rawValue).trim().toLowerCase()];
            if (mapped) {
                $('select[name="gender"]').val(mapped);
            }
        }

        let dlVerified = false;

        function lockDlFields() {
            dlVerified = true;
            $('#driving_license_number').prop('readonly', true);
            $('#date_of_birth').prop('readonly', true);
            $('#verifyDlBtn')
                .prop('disabled', true)
                .removeClass('btn-grd-info')
                .addClass('btn-grd-success')
                .html('<i class="bx bx-check"></i> Verified');
        }

        // Invalidate a previously verified payload if the user edits the
        // license number afterwards, so mismatched data never gets saved.
        $('#driving_license_number').on('input', function() {
            if (dlVerified) return;
            $('#dl_verification_data').val('');
            $('#dlVerifiedFieldsWrap').hide();
            $('#dlVerifyStatus').removeClass('text-success text-danger').text('');
        });

        // Populate the read-only "Verified License Details" summary panel.
        // BankU nests most identity fields under details_of_driving_licence.
        function populateVerifiedDetailsPanel(data) {
            let details = data.details_of_driving_licence || {};

            let guardianName = details.father_or_husband_name || '';
            if (guardianName) {
                $('#dl_guardian_name').val(guardianName);
                $('#dlVerifiedFieldsWrap').show();
            }

            // split_address.state is an array of [fullName, abbreviation] pairs.
            let stateEntry = details.split_address &&
                details.split_address.state &&
                details.split_address.state[0];
            let stateLabel = Array.isArray(stateEntry) ? stateEntry.join(' - ') : (stateEntry || '');
            if (stateLabel) {
                $('#dl_state').val(stateLabel);
                $('#dlVerifiedFieldsWrap').show();
            }

            let validTill = data.dl_validity &&
                data.dl_validity.non_transport &&
                data.dl_validity.non_transport.to;
            if (validTill) {
                $('#dl_valid_till').val(validTill);
                $('#dlVerifiedFieldsWrap').show();
            }

            let firstBadge = Array.isArray(data.badge_details) ? data.badge_details[0] : null;
            let vehicleClasses = firstBadge && Array.isArray(firstBadge.class_of_vehicle)
                ? firstBadge.class_of_vehicle
                : [];
            if (vehicleClasses.length) {
                $('#dl_vehicle_class').val(vehicleClasses.join(', '));
                $('#dlVerifiedFieldsWrap').show();
            }
        }

        // Restore the locked state after a full-page reload (e.g. a
        // validation error on submit brought the user back with old input).
        $(function() {
            if ($('#dl_verification_data').val()) {
                lockDlFields();
                $('#dlVerifyStatus').removeClass('text-danger').addClass('text-success')
                    .text('Driving license verified successfully.');

                try {
                    populateVerifiedDetailsPanel(JSON.parse($('#dl_verification_data').val()));
                } catch (e) {
                    // Ignore malformed stored payload; fields stay locked either way.
                }
            }
        });

        $('#verifyDlBtn').on('click', function() {
            if (dlVerified) {
                return;
            }

            let dlNumber = $('#driving_license_number').val().trim();
            let dob = $('#date_of_birth').val();
            let $btn = $(this);
            let $status = $('#dlVerifyStatus');

            $status.removeClass('text-success text-danger').text('');
            $('#dl_verification_data').val('');
            $('#dlVerifiedFieldsWrap').hide();

            if (!dlNumber) {
                round_error_noti('Please enter the Driving License number first.');
                return;
            }
            if (!dob) {
                round_error_noti('Please enter Date of Birth before verifying the license.');
                return;
            }

            function callVerify(lat, lng) {
                $.ajax({
                    url: '<?php echo e(route('driver.verifyLicense')); ?>',
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

                            // BankU nests most identity fields under details_of_driving_licence.
                            let details = data.details_of_driving_licence || {};

                            let fullName = details.name || data.name || '';
                            if (fullName) {
                                let parts = fullName.trim().split(/\s+/);
                                $('#first_name').val(parts.shift());
                                $('#last_name').val(parts.join(' '));
                            }

                            let address = details.address || data.address || '';
                            if (address) {
                                $('#address').val(address);
                            }

                            let dobResp = normalizeDobForInput(data.dob || details.dob || '');
                            if (dobResp) {
                                $('#date_of_birth').val(dobResp);
                            }

                            applyGenderFromApi(data.gender || data.sex || details.gender);

                            populateVerifiedDetailsPanel(data);

                            // Keep the full verified payload so it's persisted with the
                            // driver record on submit, even for fields with no matching input.
                            $('#dl_verification_data').val(JSON.stringify(data));

                            $status.removeClass('text-danger').addClass('text-success')
                                .text('Driving license verified successfully.');
                            round_success_noti('Driving license verified successfully.');

                            // Lock the license number & DOB so a verified license can't
                            // be re-verified or swapped out after the fact.
                            lockDlFields();
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
                        if (!dlVerified) {
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
    <script>
        let panVerified = false;

        function lockPanField() {
            panVerified = true;
            $('#pan_card_number').prop('readonly', true);
            $('#verifyPanBtn')
                .prop('disabled', true)
                .removeClass('btn-grd-info')
                .addClass('btn-grd-success')
                .html('<i class="bx bx-check"></i> Verified');
        }

        $('#pan_card_number').on('input', function() {
            if (panVerified) return;
            $('#pan_verification_data').val('');
            $('#panVerifyStatus').removeClass('text-success text-danger').text('');
        });

        $(function() {
            if ($('#pan_verification_data').val()) {
                lockPanField();
                $('#panVerifyStatus').removeClass('text-danger').addClass('text-success')
                    .text('PAN verified successfully.');
            }
        });

        $('#verifyPanBtn').on('click', function() {
            if (panVerified) return;

            let panNumber = $('#pan_card_number').val().trim().toUpperCase();
            let $btn = $(this);
            let $status = $('#panVerifyStatus');

            $status.removeClass('text-success text-danger').text('');
            $('#pan_verification_data').val('');

            if (!panNumber) {
                round_error_noti('Please enter the PAN number first.');
                return;
            }
            if (!/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/.test(panNumber)) {
                round_error_noti('Please enter a valid PAN number (e.g. ABCDE1234F).');
                return;
            }

            $('#pan_card_number').val(panNumber);

            $.ajax({
                url: '<?php echo e(route('driver.verifyPan')); ?>',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    pan_card_number: panNumber,
                },
                beforeSend: function() {
                    $btn.prop('disabled', true).text('Verifying...');
                },
                success: function(response) {
                    if (response.success) {
                        let data = response.data || {};
                        console.log('BankU PAN verify response:', data);

                        let fullName = data.name || data.registered_name || data.full_name || '';
                        if (fullName && !$('#first_name').val()) {
                            let parts = fullName.trim().split(/\s+/);
                            $('#first_name').val(parts.shift());
                            $('#last_name').val(parts.join(' '));
                        }

                        applyGenderFromApi(data.gender || data.sex);

                        // Keep the full verified payload so it's persisted with the
                        // user record on submit, even for fields with no matching input.
                        $('#pan_verification_data').val(JSON.stringify(data));

                        $status.removeClass('text-danger').addClass('text-success')
                            .text('PAN verified successfully.');
                        round_success_noti('PAN verified successfully.');

                        // Lock the PAN number so a verified PAN can't be
                        // re-verified or swapped out after the fact.
                        lockPanField();
                    } else {
                        $status.removeClass('text-success').addClass('text-danger')
                            .text(response.message || 'PAN verification failed.');
                        round_error_noti(response.message || 'PAN verification failed.');
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'An unexpected error occurred while verifying the PAN.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    $status.removeClass('text-success').addClass('text-danger').text(errorMessage);
                    round_error_noti(errorMessage);
                },
                complete: function() {
                    if (!panVerified) {
                        $btn.prop('disabled', false).text('Verify');
                    }
                }
            });
        });
    </script>
    <script>
        let aadhaarVerified = false;

        function lockAadhaarFields() {
            aadhaarVerified = true;
            $('#aadhaar_number').prop('readonly', true);
            $('#aadhaar_otp').prop('readonly', true);
            $('#sendAadhaarOtpBtn')
                .prop('disabled', true)
                .removeClass('btn-grd-info')
                .addClass('btn-grd-success')
                .html('<i class="bx bx-check"></i> Verified');
            $('#verifyAadhaarOtpBtn')
                .prop('disabled', true)
                .removeClass('btn-grd-info')
                .addClass('btn-grd-success')
                .html('<i class="bx bx-check"></i> Verified');
            $('#aadhaarOtpWrap').hide();
        }

        $('#aadhaar_number').on('input', function() {
            if (aadhaarVerified) return;
            $('#aadhaar_verification_data').val('');
            $('#aadhaar_ref_id').val('');
            $('#aadhaarOtpWrap').hide();
            $('#aadhaarVerifyStatus').removeClass('text-success text-danger').text('');
        });

        $(function() {
            if ($('#aadhaar_verification_data').val()) {
                lockAadhaarFields();
                $('#aadhaarVerifyStatus').removeClass('text-danger').addClass('text-success')
                    .text('Aadhaar verified successfully.');
            }
        });

        $('#sendAadhaarOtpBtn').on('click', function() {
            if (aadhaarVerified) return;

            let aadhaarNumber = $('#aadhaar_number').val().trim();
            let $btn = $(this);
            let $status = $('#aadhaarVerifyStatus');

            $status.removeClass('text-success text-danger').text('');
            $('#aadhaar_verification_data').val('');
            $('#aadhaar_ref_id').val('');
            $('#aadhaarOtpWrap').hide();

            if (!/^\d{12}$/.test(aadhaarNumber)) {
                round_error_noti('Please enter a valid 12-digit Aadhaar number.');
                return;
            }

            $.ajax({
                url: '<?php echo e(route('driver.aadhaarSendOtp')); ?>',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    aadhaar_number: aadhaarNumber,
                },
                beforeSend: function() {
                    $btn.prop('disabled', true).text('Sending...');
                },
                success: function(response) {
                    if (response.success) {
                        let data = response.data || {};
                        console.log('BankU Aadhaar send-otp response:', data);

                        let refId = data.ref_id || data.refId || data.reference_id || data.transaction_id || '';
                        $('#aadhaar_ref_id').val(refId);

                        $('#aadhaarOtpWrap').show();
                        $('#aadhaar_otp').val('').focus();

                        $status.removeClass('text-danger').addClass('text-success')
                            .text('OTP sent to the Aadhaar-registered mobile number.');
                        round_success_noti('OTP sent to the Aadhaar-registered mobile number.');
                    } else {
                        $status.removeClass('text-success').addClass('text-danger')
                            .text(response.message || 'Unable to send OTP.');
                        round_error_noti(response.message || 'Unable to send Aadhaar OTP.');
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'An unexpected error occurred while sending the OTP.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    $status.removeClass('text-success').addClass('text-danger').text(errorMessage);
                    round_error_noti(errorMessage);
                },
                complete: function() {
                    $btn.prop('disabled', false).text('Send OTP');
                }
            });
        });

        $('#verifyAadhaarOtpBtn').on('click', function() {
            if (aadhaarVerified) return;

            let otp = $('#aadhaar_otp').val().trim();
            let refId = $('#aadhaar_ref_id').val();
            let $btn = $(this);
            let $status = $('#aadhaarVerifyStatus');

            if (!/^\d{6}$/.test(otp)) {
                round_error_noti('Please enter the 6-digit OTP.');
                return;
            }
            if (!refId) {
                round_error_noti('OTP session expired. Please resend the OTP.');
                return;
            }

            $.ajax({
                url: '<?php echo e(route('driver.aadhaarVerifyOtp')); ?>',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    otp: otp,
                    ref_id: refId,
                },
                beforeSend: function() {
                    $btn.prop('disabled', true).text('Verifying...');
                },
                success: function(response) {
                    if (response.success) {
                        let data = response.data || {};
                        console.log('BankU Aadhaar verify-otp response:', data);

                        let fullName = data.name || data.full_name || '';
                        if (fullName && !$('#first_name').val()) {
                            let parts = fullName.trim().split(/\s+/);
                            $('#first_name').val(parts.shift());
                            $('#last_name').val(parts.join(' '));
                        }

                        let address = data.address || data.full_address || '';
                        if (address && !$('#address').val()) {
                            $('#address').val(address);
                        }

                        let dobResp = normalizeDobForInput(data.dob || data.date_of_birth || '');
                        if (dobResp && !$('#date_of_birth').val()) {
                            $('#date_of_birth').val(dobResp);
                        }

                        applyGenderFromApi(data.gender || data.sex);

                        // Keep the full verified payload so it's persisted with the
                        // user record on submit, even for fields with no matching input.
                        $('#aadhaar_verification_data').val(JSON.stringify(data));

                        $status.removeClass('text-danger').addClass('text-success')
                            .text('Aadhaar verified successfully.');
                        round_success_noti('Aadhaar verified successfully.');

                        // Lock the Aadhaar number & OTP so a verified Aadhaar
                        // can't be re-verified or swapped out after the fact.
                        lockAadhaarFields();
                    } else {
                        $status.removeClass('text-success').addClass('text-danger')
                            .text(response.message || 'OTP verification failed.');
                        round_error_noti(response.message || 'Aadhaar OTP verification failed.');
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'An unexpected error occurred while verifying the OTP.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    $status.removeClass('text-success').addClass('text-danger').text(errorMessage);
                    round_error_noti(errorMessage);
                },
                complete: function() {
                    if (!aadhaarVerified) {
                        $btn.prop('disabled', false).text('Verify OTP');
                    }
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\projects\vlocus\resources\views/admin/driver/create.blade.php ENDPATH**/ ?>