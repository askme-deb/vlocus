<?php $__env->startSection('title'); ?>
    Driver Details
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $user = $driver->user;
    ?>

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Driver</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('dashboard')); ?>"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('driver.index')); ?>">Drivers</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo e(optional($user)->name ?: $driver->driving_license_number); ?></li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Driver Show')): ?>
                    <a class="btn btn-grd-success text-light px-4" href="<?php echo e(route('driver.download', $driver->id)); ?>">
                        <i class="bx bx-download"></i> Download
                    </a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Driver Edit')): ?>
                    <?php if($user): ?>
                        <a class="btn btn-grd-primary text-light px-4" href="<?php echo e(route('driver.edit', $user->id)); ?>">
                            <i class="bx bx-edit"></i> Edit
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
                <a class="btn btn-primary px-4" href="<?php echo e(route('driver.index')); ?>">
                    <i class="fadeIn animated bx bx-arrow-back"></i>Back
                </a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Driver Create')): ?>
        <a class="btn btn-outline-primary mb-3" href="<?php echo e(route('driver.bank.edit', $driver)); ?>">Bank Account</a>
    <?php endif; ?>
    <div class="row">
        <div class="col-lg-4">
            <div class="card mt-4">
                <div class="card-header text-center"><span class="fw-bold">Driver</span></div>
                <div class="card-body text-center">
                    <img src="<?php echo e(optional($user)->getFirstMediaUrl('system-user-image')); ?>" alt=""
                        width="110" height="110" style="object-fit: cover;"
                        class="img-thumbnail mb-3">
                    <h6 class="mb-1"><?php echo e(optional($user)->name ?: '—'); ?></h6>
                    <p class="text-muted mb-1"><?php echo e(optional($user)->email); ?></p>
                    <p class="text-muted mb-2"><?php echo e(optional($user)->phone); ?></p>
                    <?php if($user): ?>
                        <?php echo check_status($user->status); ?>

                    <?php endif; ?>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header"><span class="fw-bold">Driver KYC</span></div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold">Aadhaar</span>
                            <?php if(optional($user)->aadhaar_verified_at): ?>
                                <span class="badge bg-success text-light">
                                    <i class="bx bx-check-shield"></i> Verified &middot;
                                    <?php echo e($user->aadhaar_verified_at->format('d M Y')); ?>

                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary text-light">Not verified</span>
                            <?php endif; ?>
                        </div>
                        <p class="text-muted mb-2">Number: <span class="fw-semibold"><?php echo e(optional($user)->aadhar_card_number ?: '—'); ?></span></p>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Driver Create')): ?>
                            <?php if(!optional($user)->aadhaar_verified_at): ?>
                                <a class="btn btn-sm btn-outline-primary mb-2" href="<?php echo e(route('driver.kyc', $driver->id)); ?>#aadhaar_number">Reverify</a>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if(!empty(optional($user)->aadhaar_verification_data)): ?>
                            
                        <?php endif; ?>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold">PAN</span>
                            <?php if(optional($user)->pan_verified_at): ?>
                                <span class="badge bg-success text-light">
                                    <i class="bx bx-check-shield"></i> Verified &middot;
                                    <?php echo e($user->pan_verified_at->format('d M Y')); ?>

                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary text-light">Not verified</span>
                            <?php endif; ?>
                        </div>
                        <p class="text-muted mb-2">Number: <span class="fw-semibold"><?php echo e(optional($user)->pan_card_number ?: '—'); ?></span></p>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Driver Create')): ?>
                            <?php if(!optional($user)->pan_verified_at): ?>
                                <a class="btn btn-sm btn-outline-primary mb-2" href="<?php echo e(route('driver.kyc', $driver->id)); ?>#pan_card_number">Reverify</a>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if(!empty(optional($user)->pan_verification_data)): ?>
                            
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header"><span class="fw-bold">Bank Details</span></div>
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success" role="status"><?php echo e(session('success')); ?></div>
                    <?php endif; ?>
                    <?php $__errorArgs = ['bank'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="alert alert-danger" role="alert"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php if($bank = $driver->bankAccount): ?>
                        <dl class="row mb-0">
                            <?php $__currentLoopData = [
                                'Bank Name' => $bank->bank_name,
                                'Branch Name' => $bank->branch_name,
                                'A/c Number' => $bank->account_number,
                                'A/c Holder Name' => $bank->account_holder_name,
                                'IFSC Code' => $bank->ifsc,
                                'Verification Status' => ucfirst($bank->status),
                                'Submitted At' => $bank->submitted_at?->format('d M Y, h:i A'),
                                'Verified At' => $bank->verified_at?->format('d M Y, h:i A'),
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <dt class="col-sm-5"><?php echo e($label); ?></dt>
                                <dd class="col-sm-7 text-break"><?php echo e($value ?: '-'); ?></dd>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </dl>
                    <?php else: ?>
                        <p class="text-muted mb-0">No bank account details added yet.</p>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Driver Create')): ?>
                        <?php if($bank && in_array($bank->status, ['pending', 'unknown', 'submitting'])): ?>
                            <form action="<?php echo e(route('driver.bank.status', $driver)); ?>" method="post" class="mt-3">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-sm btn-outline-primary">Check Verification Status</button>
                            </form>
                        <?php elseif(!$bank || ($bank->status !== 'verified' && !$bank->verified_at)): ?>
                            <a class="btn btn-sm btn-outline-primary mt-3" href="<?php echo e(route('driver.bank.edit', $driver)); ?>">Reverify</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold">DRIVING LICENSE DETAILS</span>
                    <?php if($driver->driving_license_verified_at): ?>
                        <span class="badge bg-success text-light">
                            <i class="bx bx-check-shield"></i> DL Verified &middot;
                            <?php echo e($driver->driving_license_verified_at->format('d M Y')); ?>

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
                                    $rows = ['D.L Number' => $driver->driving_license_number];
                                    foreach (\App\Models\Driver::DL_FIELDS as $key => $label) {
                                        $rows[$label] = $driver->{$key};
                                    }
                                ?>
                                <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td class="text-uppercase text-muted"><?php echo e($label); ?></td>
                                        <td class="fw-semibold"><?php echo e($value === null || $value === '' ? '—' : $value); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if(!empty($driver->driving_license_verification_data)): ?>
                        
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\projects\vlocus\resources\views/admin/driver/show.blade.php ENDPATH**/ ?>