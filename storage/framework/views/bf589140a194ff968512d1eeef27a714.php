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
                        <a href="<?php echo e(route('dashboard')); ?>"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Driver</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                <?php if(optional($user->driver)->id): ?>
                    <a class="btn btn-grd-info text-light px-4" href="<?php echo e(route('driver.show', $user->driver->id)); ?>">
                        <i class="bx bx-show"></i> View
                    </a>
                <?php endif; ?>
                <a class="btn btn-primary px-4" href="<?php echo e(route('driver.index')); ?>"><i
                        class="fadeIn animated bx bx-arrow-back"></i>Back</a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->

    <form class="needs-validation" action="<?php echo e(route('driver.update')); ?>" method="post" novalidate
        enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <input type="hidden" id="user_id" name="user_id" value="<?php echo e($user->id); ?>">

        <div class="row">
            <div class="col-md-9">

                <div class="card mt-4">
                    <div class="card-header text-center">Driving Licence</div>
                    <div class="card-body">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Date Of Birth</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control" name="date_of_birth" id="date_of_birth"
                                    value="<?php echo e(old('date_of_birth', $user->date_of_birth)); ?>">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Driving Licence Number</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="driving_license_number"
                                        id="driving_license_number"
                                        value="<?php echo e(old('driving_license_number', optional($user->driver)->driving_license_number)); ?>">
                                    <button type="button" class="btn btn-grd-info text-light" id="verifyDlBtn">
                                        Fetch DL Details
                                    </button>
                                </div>
                                <small id="dlVerifyStatus" class="d-block mt-1"></small>
                                <input type="hidden" name="driving_license_verification_data"
                                    id="driving_license_verification_data"
                                    value="<?php echo e(old('driving_license_verification_data', optional($user->driver)->driving_license_verification_data ? json_encode($user->driver->driving_license_verification_data) : '')); ?>">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Driving Licence Image</label>
                            <div class="col-md-9">
                                <div class="mb-3">
                                    <img class="img-thumbnail rounded me-2" id="blah4" alt="" width="200"
                                        src="<?php echo e(optional($user->driver)->getFirstMediaUrl('driver-license')); ?>"
                                        data-holder-rendered="true"
                                        style="display: <?php echo e(is_have_image(optional($user->driver)->getFirstMediaUrl('driver-license'))); ?>;">
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

                        <?php echo $__env->make('admin.driver._dl_fields', ['data' => $user->driver], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header text-center">Basic Information</div>
                    <div class="card-body">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">First Name <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Enter First name" name="first_name"
                                    id="first_name" value="<?php echo e(old('first_name', $user->first_name)); ?>" required>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Last Name <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Enter Last name" name="last_name"
                                    id="last_name" value="<?php echo e(old('last_name', $user->last_name)); ?>" required>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Email <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="email" class="form-control" name="email" id="email"
                                    value="<?php echo e(old('email', $user->email)); ?>" required>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Gender <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control input-height" name="gender" required>
                                    <option value selected disabled>Select...</option>
                                    <option value="male" <?php if($user->gender == 'male'): ?> selected <?php endif; ?>>Male</option>
                                    <option value="female" <?php if($user->gender == 'female'): ?> selected <?php endif; ?>>Female</option>
                                    <option value="others" <?php if($user->gender == 'others'): ?> selected <?php endif; ?>>Others</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Mobile No. <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="phone" value="<?php echo e(old('phone', $user->phone)); ?>"
                                    required>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Alternative Mobile No.</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="opt_mobile_no"
                                    value="<?php echo e(old('opt_mobile_no', $user->opt_mobile_no)); ?>">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Address</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="address" id="address"
                                    value="<?php echo e(old('address', $user->address)); ?>">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Profile Picture</label>
                            <div class="col-md-9">
                                <div class="mb-3">
                                    <img class="img-thumbnail rounded me-2" id="blah" alt="" width="200"
                                        src="<?php echo e($user->getFirstMediaUrl('system-user-image')); ?>"
                                        data-holder-rendered="true"
                                        style="display: <?php echo e(is_have_image($user->getFirstMediaUrl('system-user-image'))); ?>;">
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
                                    <?php $__currentLoopData = $vehicle_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"
                                            <?php if(old('vehicle_type', optional($user->driver)->vehicle_type ?? '') == $item->id): ?> selected <?php endif; ?>>
                                            <?php echo e($item->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Vehicle <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control input-height" name="vehicle_id" required>
                                    <option value selected disabled>Select...</option>
                                    <?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"
                                            <?php if(old('vehicle_id', optional($user->driver)->vehicle_id) == $item->id): ?> selected <?php endif; ?>>
                                            <?php echo e($item->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Driving Exprience</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="driving_exprience"
                                    value="<?php echo e(old('driving_exprience', optional($user->driver)->driving_exprience)); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header text-center">Driver KYC</div>
                    <div class="card-body">
                        <?php echo $__env->make('admin.driver._kyc_fields', ['data' => $user], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
                                            value="<?php echo e($user->email); ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-12 mb-3">
                                    <div class="form-group">
                                        <label class="col-form-label">Password</label>
                                        <input type="text" class="form-control" name="password"
                                            value="<?php echo e(old('password')); ?>">
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
                                        value="1" <?php echo e(check_uncheck($user->status, 1)); ?>>
                                    <label class="form-check-label" for="customRadioInline1">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="customRadioInline2" name="status" class="form-check-input"
                                        value="0" <?php echo e(check_uncheck($user->status, 0)); ?>>
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

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="<?php echo e(asset('assets/dashboard-assets/assets/plugins/select2/js/select2-custom.js')); ?>"></script>

    <?php echo $__env->make('admin.driver._dl_scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('admin.driver._kyc_scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\projects\vlocus\resources\views/admin/driver/edit.blade.php ENDPATH**/ ?>