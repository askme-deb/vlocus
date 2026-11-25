<?php $__env->startSection('title'); ?>
    Vehicle Details
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
                    <li class="breadcrumb-item"><a href="<?php echo e(route('vehicle.index')); ?>">Vehicle</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo e($data->vehicle_number); ?></li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Vehicle Show')): ?>
                    <a class="btn btn-grd-success text-light px-4" href="<?php echo e(route('vehicle.download', $data->id)); ?>">
                        <i class="bx bx-download"></i> Download
                    </a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Vehicle Edit')): ?>
                    <a class="btn btn-grd-primary text-light px-4" href="<?php echo e(route('vehicle.edit', $data->id)); ?>">
                        <i class="bx bx-edit"></i> Edit
                    </a>
                <?php endif; ?>
                <a class="btn btn-primary px-4" href="<?php echo e(route('vehicle.index')); ?>">
                    <i class="fadeIn animated bx bx-arrow-back"></i>Back
                </a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-bold">Vehicle Registration Details</span>
            <?php if($data->rc_verified_at): ?>
                <span class="badge bg-success text-light">
                    <i class="bx bx-check-shield"></i> RC Verified &middot;
                    <?php echo e($data->rc_verified_at->format('d M Y')); ?>

                </span>
            <?php else: ?>
                <span class="badge bg-secondary text-light">Manually entered</span>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:60px;">SL</th>
                            <th style="width:35%;">Particulars</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $rows = ['Vehicle RC Number' => $data->vehicle_number];
                            foreach (\App\Models\Vehicle::RC_FIELDS as $key => $label) {
                                if ($key === 'is_commercial') {
                                    $rows[$label] = is_null($data->is_commercial)
                                        ? null
                                        : ($data->is_commercial ? 'TRUE = YES' : 'FALSE = NO');
                                } elseif (in_array($key, \App\Models\Vehicle::RC_DATE_FIELDS, true)) {
                                    $rows[$label] = $data->{$key} ? safe_format_date($data->{$key}) : null;
                                } else {
                                    $rows[$label] = $data->{$key};
                                }
                            }
                        ?>
                        <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td class="text-uppercase text-muted"><?php echo e($label); ?></td>
                                <td class="fw-semibold"><?php echo e(($value === null || $value === '') ? '—' : $value); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e(count($rows) + 1); ?></td>
                            <td class="text-uppercase text-muted">Status</td>
                            <td><?php echo check_status($data->is_visible); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <?php if(!empty($data->rc_verification_data)): ?>
                
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rqodasyj/app.vlocus.in/resources/views/admin/vehicle/show.blade.php ENDPATH**/ ?>