    <?php $__env->startSection('title'); ?> Dashboard <?php $__env->stopSection(); ?>
    
    <?php $__env->startSection('content'); ?>

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Role Permission</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('dashboard')); ?>">
                                <i class="bx bx-home-alt"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Roles</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Role Create')): ?>
                <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                    <button class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="bi bi-plus-lg me-2"></i>Add New Role</button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    <!--end breadcrumb-->

    <div class="card mt-4">
        <div class="card-body">
            <div class="product-table">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Asign Permission')): ?>
                                <th>Asign Permission to Role</th>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['Role Edit','Role Delete'])): ?>
                                <th>Action</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($role->name); ?></td>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Asign Permission')): ?>
                                <td><a href="<?php echo e(route('role.addPermissionToRole', ['roleId' => $role->id])); ?>" class="btn btn-outline-success">Asign Permission</a></td>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['Role Edit','Role Delete'])): ?>
                                <td class="d-flex">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Role Edit')): ?>
                                    <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#staticBackdropedit<?php echo e($role->id); ?>" style="margin-right: 10px;"> 
                                        <i class="text-primary" data-feather="edit"></i>
                                    </button>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Role Delete')): ?>
                                    <a class="btn" href="javascript:void(0);" onclick="deleteItem(this)"
                                                    data-url="<?php echo e(route('role.destroy', ['roleId' => $role->id])); ?>" data-item="Route"
                                                    alt="delete"><i
                                                    class="text-danger" data-feather="trash-2"></i></a>
                                    <?php endif; ?>
                                </td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 py-2">
                    <h5 class="modal-title">Add New Role</h5>
                    <a href="javascript:;" class="primaery-menu-close" data-bs-dismiss="modal">
                        <i class="material-icons-outlined">close</i>
                    </a>
                </div>
                <form action="<?php echo e(route('role.create')); ?>" method="post">
                <?php echo csrf_field(); ?>

                <div class="modal-body">
                    <div class="position-relative">
                        <label for="role-name" class="form-label">Role Name</label>
                        <input type="text" name="name" class="form-control" id="role-name" placeholder="Write Role name here..." required="">
                        <div class="valid-tooltip">
                            Looks good!
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top-0">
                    <button type="submit" class="btn btn-grd-info">Save Role</button>
                </div>                                          
                </form>
            </div>
        </div>
    </div>

    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="staticBackdropedit<?php echo e($role->id); ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add New Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo e(route('role.update', ['roleId' => $role->id])); ?>" method="post">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="position-relative">
                        <label for="role-name" class="form-label">Role Name</label>
                        <input type="text" name="name" class="form-control" id="role-name" placeholder="Write role name here..." value="<?php echo e($role->name); ?>" required="">
                        <div class="valid-tooltip">
                            Looks good!
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\projects\vlocus\resources\views/admin/roles_permission/roles.blade.php ENDPATH**/ ?>