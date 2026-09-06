<?php $__env->startSection('title', 'Driver Bank Account'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Driver Bank Account</h4>
        <a class="btn btn-primary" href="<?php echo e(route('driver.index')); ?>">Back to Drivers</a>
    </div>
    <div class="alert alert-info">
        Step 3 of 3: Add bank details for <strong><?php echo e($driver->user->name); ?></strong>.
    </div>
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger" role="alert">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div><?php echo e($error); ?></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
    <?php
        $submitted = $bank && in_array($bank->status, ['submitting', 'pending', 'verified']);
        $retryOnly = $bank && $bank->status === 'unknown';
    ?>
    <?php if($bank): ?>
        <div class="card mb-3">
            <div class="card-body">
                <p class="mb-1"><strong>Account:</strong> <?php echo e($bank->maskedAccountNumber()); ?></p>
                <p class="mb-0"><strong>Verification:</strong> <?php echo e(ucfirst($bank->status)); ?></p>
                <?php if($bank->status === 'pending'): ?>
                    <p class="text-muted mt-2 mb-0">Your request has been accepted. The account is awaiting final verification.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if(! $submitted): ?>
        <form action="<?php echo e(route('driver.bank.store', $driver)); ?>" method="post" id="bankAccountForm">
            <?php echo csrf_field(); ?>
            <div class="card">
                <div class="card-header">Bank Details</div>
                <div class="card-body">
                    <div class="row g-3">
                        <?php $__currentLoopData = ['bank_name' => 'Bank Name', 'branch_name' => 'Branch Name', 'account_number' => 'A/c Number', 'account_holder_name' => 'A/c Holder Name', 'ifsc' => 'IFSC Code']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6">
                                <label class="form-label" for="<?php echo e($field); ?>"><?php echo e($label); ?> <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="<?php echo e($field); ?>" name="<?php echo e($field); ?>"
                                    value="<?php echo e(old($field, $bank?->$field)); ?>" required
                                    maxlength="<?php echo e($field === 'ifsc' ? 11 : ($field === 'account_number' ? 34 : 255)); ?>"
                                    <?php if($field === 'account_number'): ?> inputmode="numeric" pattern="[0-9]{6,34}" autocomplete="off" <?php endif; ?>
                                    <?php if($field === 'ifsc'): ?> style="text-transform: uppercase" pattern="[A-Za-z]{4}0[A-Za-z0-9]{6}" <?php endif; ?>
                                    <?php if($retryOnly): echo 'readonly'; endif; ?>>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <p class="text-muted mt-3">The IFSC code is checked before submitting the account for verification.</p>
                    <button class="btn btn-primary" type="submit" id="bankSubmit"><?php echo e($retryOnly ? 'Retry Verification' : 'Save & Verify Bank Account'); ?></button>
                    <a class="btn btn-outline-secondary" href="<?php echo e(route('driver.kyc', $driver->id)); ?>">Back to KYC</a>
                </div>
            </div>
        </form>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.getElementById('bankAccountForm')?.addEventListener('submit', function () {
        const button = document.getElementById('bankSubmit');
        button.disabled = true;
        button.textContent = 'Submitting verification...';
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\projects\vlocus\resources\views/admin/driver/bank.blade.php ENDPATH**/ ?>