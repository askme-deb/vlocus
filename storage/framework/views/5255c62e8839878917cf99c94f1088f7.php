

<?php $__env->startSection('title', 'Run & Idle Report'); ?>

<?php $__env->startSection('content'); ?>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Run & Idle Report</div>
    <div class="ps-3">
        <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><i class="bx bx-home-alt"></i></a></li>
            <li class="breadcrumb-item active" aria-current="page">Run & Idle</li>
        </ol>
    </div>
</div>
<!--end breadcrumb-->

<div class="card mt-4">
    <div class="card-body">

        <!-- Filter Form -->
        <form method="GET" action="<?php echo e(route('reports.runIdle')); ?>" class="row g-2 mb-3">
            <div class="col-md-3">
                <label>Start Date</label>
                <input type="date" name="start_date" class="form-control" value="<?php echo e(request('start_date')); ?>">
            </div>
            <div class="col-md-3">
                <label>End Date</label>
                <input type="date" name="end_date" class="form-control" value="<?php echo e(request('end_date')); ?>">
            </div>
            <div class="col-md-2 align-self-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table id="example2" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>SL No.</th>
                        <th>Date</th>
                        <th>Vehicle</th>
                        <th>Driver Name</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Run Time</th>
                        <th>Idle Time</th>
                        <th>Total Stops</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e(formated_date($report['date']) ?? '-'); ?></td>
                            <td><?php echo e($report['vehicle'] ?? '-'); ?></td>
                            <td><?php echo e($report['driver']); ?></td>
                            <td><?php echo e(format_time($report['start_time']) ?? '-'); ?></td>
                            <td><?php echo e(format_time($report['end_time']) ?? '-'); ?></td>
                            <td><span class="badge bg-success"><?php echo e($report['run_time']); ?></span></td>
                            <td><span class="badge bg-warning text-dark"><?php echo e($report['idle_time']); ?></span></td>
                            <td><?php echo e($report['stops']); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="text-center">No data available for this period</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\projects\vlocus\resources\views/admin/reports/run_idle.blade.php ENDPATH**/ ?>