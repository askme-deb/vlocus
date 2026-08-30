<style>
  /* ==========================================================
     ULTIMATE MOBILE RESPONSIVE FIX FOR DELIVERY SCHEDULE
     ========================================================== */

  @media (max-width: 991px) {
    /* Main Content Spacing */
    main.main-wrapper {
      margin-top: 70px !important;
      padding: 10px !important;
    }

    .main-wrapper .main-content {
      padding: 0 !important;
    }

    /* DataTable Top Section (Buttons & Search Stack) */
    #example2_wrapper .row:first-child {
      display: flex;
      flex-direction: column;
      gap: 12px;
      align-items: stretch !important;
      margin: 0 !important;
    }

    #example2_wrapper .col-sm-12,
    #example2_wrapper .col-md-6 {
      width: 100% !important;
      max-width: 100% !important;
      padding: 0 !important;
      display: flex;
      justify-content: center;
    }

    /* Export Buttons Center & Wrap */
    .dt-buttons {
      display: flex;
      justify-content: center !important;
      gap: 6px;
      width: 100%;
    }

    .dt-buttons .btn {
      padding: 6px 12px !important;
      font-size: 12px !important;
      border-radius: 8px !important;
    }

    /* Search Input Full Width */
    .dataTables_filter {
      text-align: center !important;
      width: 100%;
    }

    .dataTables_filter input {
      width: 100% !important;
      margin-left: 0 !important;
      margin-top: 5px;
      height: 40px !important;
    }

    /* BULK UPLOAD FORM RESPONSIVE STACK */
    .card-body form .row {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .card-body form .row .col-md-8,
    .card-body form .row .col-md-4 {
      width: 100% !important;
      text-align: left !important;
    }

    .card-body form .row .col-md-4 {
      text-align: center !important;
    }

    .card-body form .row .col-md-4 button {
      width: 100%;
    }
  }

  @media (max-width: 767px) {
    /* TABLE SCROLL CONTAINER FIX */
    .table-responsive {
      display: block !important;
      width: 100% !important;
      overflow-x: auto !important;
      -webkit-overflow-scrolling: touch !important;
      border-radius: 12px;
      border: 1px solid #edf2f7;
    }

    #example2 {
      width: 100% !important;
    }

    /* Table Font & Cell Spacing */
    #example2 th, 
    #example2 td {
      padding: 10px 12px !important;
      font-size: 12px !important;
      white-space: nowrap !important;
    }

    /* Action Buttons Inside Table */
    #example2 td.d-flex {
      display: flex !important;
      gap: 6px !important;
      align-items: center !important;
      justify-content: flex-start !important;
    }

    #example2 td.d-flex .btn {
      width: 32px !important;
      height: 32px !important;
    }

    /* Breadcrumb Header Alignment */
    .page-breadcrumb {
      flex-direction: column;
      align-items: flex-start !important;
      gap: 10px;
    }

    .page-breadcrumb .ms-auto {
      margin-left: 0 !important;
      width: 100%;
    }

    .page-breadcrumb .ms-auto .btn {
      width: 100%;
      justify-content: center;
    }

    /* Pagination & Info Stack */
    #example2_wrapper .row:last-child {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 10px;
      text-align: center;
      margin: 0 !important;
    }

    #example2_wrapper .col-sm-12.col-md-5,
    #example2_wrapper .col-sm-12.col-md-7 {
      width: 100% !important;
      display: flex;
      justify-content: center;
      padding: 0 !important;
    }

    .dataTables_info {
      padding-top: 0 !important;
    }
    
   .page-breadcrumb.d-none.d-sm-flex.align-items-center.mb-3 {display: block !important;}

   .main-wrapper .main-content .breadcrumb-title {font-size: 14px;border: none;display: none;}

   .page-breadcrumb nav {padding-bottom: 12px;}

   main.main-wrapper {margin-top: 41px !important;
}
    
  }
</style>



<?php $__env->startSection('title'); ?> Delivery Schedule <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Delivery Schedule</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('dashboard')); ?>">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Delivery Schedule</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delivery Schedule Create')): ?>
                <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                    <a class="btn btn-primary px-4" href="<?php echo e(route('delivery-schedule.create')); ?>"><i class="bi bi-plus-lg me-2"></i>Add New</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!--end breadcrumb-->


  <div class="accordion" id="faqAccordion">

         <!-- Item 2 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo"
                        aria-expanded="false"
                        aria-controls="collapseTwo">
                    <i class="bi bi-info-circle"></i> &nbsp;  Instructions for Filling the Excel Template:
                </button>
            </h2>

            <div id="collapseTwo"
                 class="accordion-collapse collapse"
                 aria-labelledby="headingTwo"
                 data-bs-parent="#faqAccordion">
                <div class="accordion-body">
          
            <div class="alert alert-info mt-3">
                <ul class="mb-0 ps-3">
                    <li>Enter the <strong>Delivery Date</strong> in <code>YYYY-MM-DD</code> format (e.g., <code>2025-11-10</code>).</li>
                    <li>Enter valid <strong>Driver</strong> Mobile Number.</li>
                    <li>Enter valid <strong>Vehicle</strong> Number.</li>
                    <li>Enter valid <strong>Shop</strong> number where the delivery is scheduled.</li>
                    <li><strong>Branch/Consignor</strong> will be auto-assigned based on your login (Employee or Branch user).</li>
                    <li class="text-danger">Do not rename, remove, or reorder any columns in the Excel sheet.</li>
                    <li class="text-danger">Once filled, save the file and upload it here in <strong>.xlsx</strong> format only.</li>
                </ul>
            </div>
            
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delivery Schedule Create')): ?>
             <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center" style="border: none;">
            <h5>Bulk Upload Delivery Schedule</h5>
            <a href="<?php echo e(route('delivery-schedule.template.download')); ?>" class="btn btn-sm btn-success">
                <i class="bi bi-download"></i> Download Template
            </a>
        </div>

        <div class="card-body">
            <form action="<?php echo e(route('delivery-schedule.bulk.upload')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <input type="file" name="bulk_file" class="form-control" required>
                    </div>
                    <div class="col-md-4 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload"></i> Upload File
                        </button>
                    </div>
                </div>
            </form>

            <!-- Instruction Section -->
           
            <!--<div class="alert alert-info mt-3">-->
            <!--    <h6 class="mb-2"><i class="bi bi-info-circle"></i> Instructions for Filling the Excel Template:</h6>-->
            <!--    <ul class="mb-0 ps-3">-->
            <!--        <li>Enter the <strong>Delivery Date</strong> in <code>YYYY-MM-DD</code> format (e.g., <code>2025-11-10</code>).</li>-->
            <!--        <li>Enter valid <strong>Driver</strong> Mobile Number.</li>-->
            <!--        <li>Enter valid <strong>Vehicle</strong> Number.</li>-->
            <!--        <li>Enter valid <strong>Shop</strong> number where the delivery is scheduled.</li>-->
            <!--        <li><strong>Branch/Consignor</strong> will be auto-assigned based on your login (Employee or Branch user).</li>-->
            <!--        <li class="text-danger">Do not rename, remove, or reorder any columns in the Excel sheet.</li>-->
            <!--        <li class="text-danger">Once filled, save the file and upload it here in <strong>.xlsx</strong> format only.</li>-->
            <!--    </ul>-->
            <!--</div>-->
        </div>
    </div>
             <?php endif; ?>

                </div>
            </div>
        </div>
        </div>





    <div class="card mt-4">
        <div class="card-body">
            <div class="product-table">
                <div class="table-responsive">
                    <table id="example2" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.l</th>
                                <th>Delevery Date</th>
                                <th>Order Id</th>
                                <th>Driver</th>
                                <th>Vehicle</th>
                                <th>Total Shop</th>
                                <th>Status</th>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['Delivery Schedule Edit', 'Delivery Schedule Delete'])): ?>
                                    <th>Action</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($delivery_schedules->isNotEmpty()): ?>
                                <?php $__currentLoopData = $delivery_schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td><?php echo e($item->delivery_date); ?></td>
                                    <td><?php echo e($item->order_id); ?></td>
                                    <td><?php echo e($item->driver?->name); ?></td>
                                    <td><?php echo e($item->vehicle?->vehicle_number); ?></td>
                                    <td><?php echo e($item->deliveryScheduleShops?->count()); ?></td>
                                    <td>
                                        <?php if($item->is_completed): ?>
                                            <span class="badge bg-success">Completed</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php endif; ?>
                                    </td>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['Delivery Schedule Edit', 'Delivery Schedule Delete'])): ?>
                                        <td class="d-flex">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delivery Schedule Edit')): ?>
                                                <a class="btn" href="<?php echo e(route('delivery-schedule.edit', $item->id)); ?>" alt="edit"><i
                                                        class="text-primary" data-feather="edit"></i></a>
                                            <?php endif; ?>
                                            
                                            <a class="btn" href="<?php echo e(route('track.delivery', ['delivery_id' => $item->id])); ?>" alt="edit"><i class="text-primary" data-feather="eye"></i></a>
                                            
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delivery Schedule Delete')): ?>
                                                <a class="btn" href="javascript:void(0);" onclick="deleteItem(this)"
                                                    data-url="<?php echo e(route('delivery-schedule.destroy',$item->id)); ?>" data-item="Delivery Schedule"
                                                    alt="delete"><i
                                                    class="text-danger" data-feather="trash-2"></i></a>
                                            <?php endif; ?>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                           
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\projects\vlocus\resources\views/admin/delivery_schedules/index.blade.php ENDPATH**/ ?>