<?php $__env->startSection('title'); ?>
    Company Wallet
<?php $__env->stopSection(); ?>

<?php
    $selfView = $selfView ?? false;
    $backRoute = $selfView ? route('dashboard') : route('company.index');
    $canFilterBranch = $canFilterBranch ?? true;
    $canFilterUser = $canFilterUser ?? true;
    $canViewBalance = $canViewBalance ?? true;
?>

<?php $__env->startSection('content'); ?>

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Company</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('dashboard')); ?>"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <?php if (! ($selfView)): ?>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('company.index')); ?>">Company</a></li>
                    <?php endif; ?>
                    <li class="breadcrumb-item active" aria-current="page">Wallet</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Wallet Settings Show')): ?>
                    <?php if (! ($selfView)): ?>
                        <a class="btn btn-grd-info text-light px-4"
                            href="<?php echo e(route('company.walletSettings.edit', $company->id)); ?>">
                            <i class="bx bx-cog"></i> API Rate Settings
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
                <a class="btn btn-primary px-4" href="<?php echo e($backRoute); ?>">
                    <i class="fadeIn animated bx bx-arrow-back"></i>Back
                </a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="row">
        <?php if($canViewBalance): ?>
            <div class="col-lg-4">
                <div class="card mt-4">
                    <div class="card-header text-center"><span class="fw-bold"><?php echo e($company->name); ?></span></div>
                    <div class="card-body text-center">
                        <p class="text-muted mb-1">Wallet Balance</p>
                        <h2 class="mb-3">&#8377;<?php echo e(number_format((float) $wallet->balance, 2)); ?></h2>
                    </div>
                </div>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Wallet Management Edit')): ?>
                    <div class="card mt-4">
                        <div class="card-header text-center"><span class="fw-bold">Top Up Wallet</span></div>
                        <div class="card-body">
                            <form class="needs-validation" action="<?php echo e(route('company.wallet.topUp', $company->id)); ?>"
                                method="post" novalidate>
                                <?php echo csrf_field(); ?>
                                <div class="mb-3">
                                    <label class="form-label">Amount <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0.01" class="form-control" name="amount"
                                        value="<?php echo e(old('amount')); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Note <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="note"
                                        placeholder="e.g. Manual recharge" value="<?php echo e(old('note')); ?>" required>
                                </div>
                                <button type="submit" class="btn btn-grd-primary px-4 text-light w-100">Add Balance</button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        
        <?php if (! ($selfView)): ?>
            <div class="col-lg-8">
                <div class="card mt-4">
                    <div class="card-header text-center"><span class="fw-bold">Transaction History</span></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Balance After</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e(format_datetime($transaction->created_at)); ?></td>
                                            <td>
                                                <?php if($transaction->type === 'credit'): ?>
                                                    <span class="badge bg-success text-light">Credit</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger text-light">Debit</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>&#8377;<?php echo e(number_format((float) $transaction->amount, 2)); ?></td>
                                            <td>&#8377;<?php echo e(number_format((float) $transaction->balance_after, 2)); ?></td>
                                            <td><?php echo e($transaction->description); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No transactions yet.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <?php echo e($transactions->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="<?php echo e($canViewBalance ? 'col-lg-8' : 'col-lg-12'); ?>">
                <div class="card mt-4">
                    <div class="card-header text-center"><span class="fw-bold">API Usage</span></div>
                    <div class="card-body">
                        <form method="get" action="<?php echo e(route('company.wallet.mine')); ?>" class="row g-2 mb-3">
                            <?php if($canFilterBranch): ?>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted mb-1">Branch</label>
                                    <select name="branch_user_id" class="form-select form-select-sm">
                                        <option value="">All Branches</option>
                                        <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($branch->id); ?>"
                                                <?php echo e((string) ($filters['branch_user_id'] ?? '') === (string) $branch->id ? 'selected' : ''); ?>>
                                                <?php echo e($branch->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                            <?php if($canFilterUser): ?>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted mb-1">User</label>
                                    <select name="actor_user_id" class="form-select form-select-sm">
                                        <option value="">All Users</option>
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($u->id); ?>"
                                                <?php echo e((string) ($filters['actor_user_id'] ?? '') === (string) $u->id ? 'selected' : ''); ?>>
                                                <?php echo e($u->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-2">
                                <label class="form-label small text-muted mb-1">API Type</label>
                                <select name="api_key" class="form-select form-select-sm">
                                    <option value="">All Types</option>
                                    <?php $__currentLoopData = \App\Models\CompanyApiRate::FILTER_TYPES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(($filters['api_key'] ?? '') === $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small text-muted mb-1">From</label>
                                <input type="date" name="date_from" class="form-control form-control-sm"
                                    value="<?php echo e($filters['date_from'] ?? ''); ?>">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small text-muted mb-1">To</label>
                                <input type="date" name="date_to" class="form-control form-control-sm"
                                    value="<?php echo e($filters['date_to'] ?? ''); ?>">
                            </div>
                            <div class="col-12 d-flex gap-2 mt-2">
                                <button type="submit" class="btn btn-grd-primary btn-sm px-3 text-light">Filter</button>
                                <a href="<?php echo e(route('company.wallet.mine')); ?>" class="btn btn-outline-secondary btn-sm px-3">Reset</a>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Branch</th>
                                        <th>User</th>
                                        <th>API Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $usage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e(format_datetime($entry->created_at)); ?></td>
                                            <td><?php echo e(optional($entry->branchUser)->name ?: '—'); ?></td>
                                            <td><?php echo e(optional($entry->actor)->name ?: '—'); ?></td>
                                            <td><?php echo e(\App\Models\CompanyApiRate::FILTER_TYPES[$entry->reference_type] ?? $entry->reference_type); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No API usage found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <?php echo e($usage->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if($selfView): ?>
        <div class="row">
            <div class="col-12">
                <div class="card mt-4">
                    <div class="card-header text-center"><span class="fw-bold">Usage Statement</span></div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">
                            How many verification calls each user has made (respects the Branch/date filters above).
                        </p>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <?php $__currentLoopData = \App\Models\CompanyApiRate::API_TYPES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <th class="text-center"><?php echo e($label); ?></th>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <th class="text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $grandTotal = 0; ?>
                                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php
                                            $row = $statement[$u->id] ?? ['counts' => [], 'total' => 0];
                                            $grandTotal += $row['total'];
                                        ?>
                                        <tr>
                                            <td><?php echo e($u->name); ?></td>
                                            <?php $__currentLoopData = \App\Models\CompanyApiRate::API_TYPES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <td class="text-center"><?php echo e($row['counts'][$key] ?? 0); ?></td>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <td class="text-center fw-semibold"><?php echo e($row['total']); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="<?php echo e(count(\App\Models\CompanyApiRate::API_TYPES) + 2); ?>"
                                                class="text-center text-muted">No users found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <?php if($users->isNotEmpty()): ?>
                                    <tfoot>
                                        <tr class="fw-bold">
                                            <td>Total</td>
                                            <?php $__currentLoopData = \App\Models\CompanyApiRate::API_TYPES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $colTotal = collect($statement)->sum(fn ($s) => $s['counts'][$key] ?? 0);
                                                ?>
                                                <td class="text-center"><?php echo e($colTotal); ?></td>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <td class="text-center"><?php echo e($grandTotal); ?></td>
                                        </tr>
                                    </tfoot>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\projects\vlocus\resources\views/admin/company/wallet.blade.php ENDPATH**/ ?>