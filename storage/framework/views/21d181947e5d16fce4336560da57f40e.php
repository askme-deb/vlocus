    <?php $__env->startSection('title'); ?> Dashboard <?php $__env->stopSection(); ?>
    
    <?php $__env->startSection('content'); ?>

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Role Permission</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="javascript:;">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Assign Permissions</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                <a class="btn btn-primary px-4" href="<?php echo e(route('roles')); ?>"><i class="fadeIn animated bx bx-arrow-back"></i>Back</a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card mt-4">
        <div class="card-body">
            <form class="custom-validation" action="<?php echo e(route('role.give-permissions', ['roleId' => $role->id])); ?>" method="post">
                <?php echo csrf_field(); ?>
                <div class="">
                    <h4 class="mb-3">Permissions Name</h4>
                    <div class="form-check mb-2">
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            id="selectAll" 
                            onclick="toggleSelectAll(this)"
                        />
                        <label class="form-check-label" for="selectAll">
                            Select All
                        </label>
                    </div>
                    <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="form-check form-check-inline">
                        <input 
                            class="form-check-input permission-checkbox" 
                            name="permission[]" 
                            id="<?php echo e($item->name); ?>" 
                            type="checkbox" 
                            value="<?php echo e($item->name); ?>" 
                            <?php echo e(in_array($item->id, $rolePermissions) ? 'checked': ''); ?>

                        />
                        <label class="form-check-label" for="<?php echo e($item->name); ?>"><?php echo e($item->name); ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="d-flex justify-content-center mt-5">
                    <button type="submit" class="btn text-light btn-grd-info">Save</button>
                </div>
            </form>
        </div>
    </div>
    
    <?php $__env->stopSection(); ?>

    <?php $__env->startSection('scripts'); ?>
    <script>
        function toggleSelectAll(source) {
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = source.checked;
            });
        }
    </script>
    <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\projects\vlocus\resources\views/admin/roles_permission/asign_permission.blade.php ENDPATH**/ ?>