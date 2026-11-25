<?php $__env->startSection('title'); ?>
    Vehicle
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Vehicle</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('dashboard')); ?>"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Add New Vehicle</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                <a class="btn btn-primary px-4" href="<?php echo e(route('vehicle.index')); ?>"><i
                        class="fadeIn animated bx bx-arrow-back"></i>Back</a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->

    <form method="POST" action="<?php echo e(route('vehicle.store')); ?>" class="needs-validation" novalidate>
        <?php echo csrf_field(); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-header text-center">Vehicle Registration Details</div>
                    <div class="card-body">

                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="vehicle_number" class="form-label">
                                    Vehicle RC Number <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control text-uppercase" name="vehicle_number"
                                        id="vehicle_number" placeholder="e.g. WB23J7636"
                                        value="<?php echo e(old('vehicle_number')); ?>" required>
                                    <button type="button" class="btn btn-grd-info text-light" id="verifyRcBtn">
                                        Fetch RC Details
                                    </button>
                                </div>
                                <small id="rcVerifyStatus" class="d-block mt-1"></small>
                                <div class="invalid-feedback">Please enter the vehicle RC number.</div>
                                <input type="hidden" name="rc_verification_data" id="rc_verification_data"
                                    value="<?php echo e(old('rc_verification_data')); ?>">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label d-block">Status</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="active" name="is_visible" class="form-check-input"
                                        value="1" <?php echo e(old('is_visible', '1') == '1' ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="active">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="inactive" name="is_visible" class="form-check-input"
                                        value="0" <?php echo e(old('is_visible') === '0' ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="inactive">Inactive</label>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <p class="text-muted small mb-3">
                            Enter the RC number and click <strong>Fetch RC Details</strong>. The fields below are
                            populated from the BankU response and can be edited before saving.
                        </p>

                        <?php echo $__env->make('admin.vehicle._rc_fields', ['data' => null], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                        <div class="d-md-flex d-grid align-items-center gap-3 mt-3">
                            <button type="submit" class="btn btn-grd-primary px-4 text-light">Submit</button>
                            <button type="reset" class="btn btn-grd-info px-4 text-light">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <?php echo $__env->make('admin.vehicle._rc_scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rqodasyj/app.vlocus.in/resources/views/admin/vehicle/create.blade.php ENDPATH**/ ?>