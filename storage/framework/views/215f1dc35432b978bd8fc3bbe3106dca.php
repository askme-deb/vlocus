<style>
  /* ==========================================================
     PREMIUM PROFILE PAGE REDESIGN (ONLY CSS)
     ========================================================== */

  /* Page Wrapper Background & Spacing */
  main.main-wrapper {
    background-color: #f8fafc !important;
    min-height: calc(100vh - 120px);
  }

  /* Profile Sections Container */
  .py-12 {
    padding: 2rem 1rem !important;
    max-width: 900px;
    margin: 0 auto;
  }

  /* Premium Card Styles */
  .py-12 .shadow {
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 20px !important;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04) !important;
    padding: 30px !important;
    margin-bottom: 25px !important;
    transition: all 0.3s ease;
  }

  .py-12 .shadow:hover {
    box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08) !important;
  }

  /* Section Headings */
  section header h2 {
    font-size: 1.25rem !important;
    font-weight: 700 !important;
    color: #1e293b !important;
    letter-spacing: -0.025em;
    margin-bottom: 4px;
  }

  section header p {
    font-size: 0.875rem !important;
    color: #64748b !important;
    margin-bottom: 20px;
  }

  /* Form Labels */
  .form-group label, 
  section label.block {
    font-size: 0.85rem !important;
    font-weight: 600 !important;
    color: #475569 !important;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 6px;
    display: block;
  }

  /* Modern Input Fields */
  .form-control {
    height: 48px !important;
    border-radius: 12px !important;
    border: 1.5px solid #cbd5e1 !important;
    background-color: #f8fafc !important;
    padding: 0 16px !important;
    font-size: 0.95rem !important;
    color: #334155 !important;
    transition: all 0.25s ease !important;
  }

  .form-control:focus {
    background-color: #fff !important;
    border-color: #2563eb !important;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1) !important;
  }

  /* Premium Buttons */
  .btn {
    border-radius: 12px !important;
    padding: 10px 24px !important;
    font-weight: 600 !important;
    font-size: 0.875rem !important;
    letter-spacing: 0.025em;
    transition: all 0.25s ease !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  }

  .btn-grd-info {
    background: linear-gradient(135deg, #2563eb, #3b82f6) !important;
    border: none !important;
    color: #fff !important;
  }

  .btn-grd-info:hover {
    background: linear-gradient(135deg, #1d4ed8, #2563eb) !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.25) !important;
  }

  .btn-grd-warning {
    background: linear-gradient(135deg, #0f172a, #334155) !important;
    border: none !important;
    color: #fff !important;
  }

  .btn-grd-warning:hover {
    background: linear-gradient(135deg, #000000, #0f172a) !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.25) !important;
  }

  /* Spacing between inputs */
  .form-group.mb-3, .form-group {
    margin-bottom: 20px !important;
  }

  /* Mobile Devices Responsiveness */
  @media (max-width: 576px) {
    .py-12 {
      padding: 1rem 0.5rem !important;
    }
    .py-12 .shadow {
      padding: 20px !important;
      border-radius: 16px !important;
    }
  }

/* ==========================================================
     SIDE-BY-SIDE RESPONSIVE LAYOUT FOR PROFILE SECTIONS
     ========================================================== */

  /* Main container-ke flex row banano */
  .py-12 .max-w-7xl.mx-auto.space-y-6 {
    display: flex;
    flex-wrap: wrap;
    gap: 25px;
    max-width: 1200px !important;
  }

  /* Both profile cards take full width on mobile, half on desktop */
  .py-12 .shadow {
    flex: 1 1 calc(50% - 13px) !important;
    min-width: 300px;
    margin-bottom: 0 !important;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  /* Inner content width 100% fix */
  .py-12 .max-w-xl {
    max-width: 100% !important;
    width: 100% !important;
  }

  /* Mobile view-te upore-niche (stack) ashar jonno */
  @media (max-width: 991px) {
    .py-12 .max-w-7xl.mx-auto.space-y-6 {
      flex-direction: column;
    }
    .py-12 .shadow {
      flex: 1 1 100% !important;
    }
  }

</style>
<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php $__env->startSection('title','Profile'); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Profile')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <?php echo $__env->make('profile.partials.update-profile-information-form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>

            <div class="p-4 sm:p-8 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <?php echo $__env->make('profile.partials.update-password-form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>

            
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH /home/rqodasyj/app.vlocus.in/resources/views/profile/edit.blade.php ENDPATH**/ ?>