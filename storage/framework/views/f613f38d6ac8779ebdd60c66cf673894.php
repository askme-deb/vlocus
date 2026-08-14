<style>
  /* ==========================================================
     ULTRA-PREMIUM SETTINGS PAGE REDESIGN (ONLY CSS)
     ========================================================== */

  /* Main Wrapper Background */
  main.main-wrapper {
    background-color: #f8fafc !important;
    min-height: calc(100vh - 100px);
  }

  /* Form Container Spacing */
  form {
    max-width: 1400px;
    margin: 0 auto;
    padding-bottom: 40px;
  }

  /* Premium Card Design */
  .card.shadow-sm {
    border: none !important;
    border-radius: 20px !important;
    background: #ffffff !important;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04) !important;
    overflow: hidden !important;
    transition: all 0.3s ease;
    height: 100%;
  }

  .card.shadow-sm:hover {
    box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08) !important;
    transform: translateY(-2px);
  }

  /* Modern Card Header */
  .card-header.bg-primary {
    background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
    color: #fff !important;
    font-weight: 600 !important;
    font-size: 0.95rem !important;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 16px 24px !important;
    border: none !important;
  }

  /* Card Body Padding */
  .card-body {
    padding: 24px !important;
  }

  /* Form Labels */
  .form-label {
    font-size: 0.825rem !important;
    font-weight: 600 !important;
    color: #475569 !important;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-bottom: 8px !important;
  }

  /* Modern Input Fields & Textareas */
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

  textarea.form-control {
    height: auto !important;
    padding: 14px 16px !important;
  }

  .form-control:focus {
    background-color: #fff !important;
    border-color: #2563eb !important;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1) !important;
  }

  /* File Input Styling Enhancement */
  input[type="file"].form-control {
    padding: 10px 16px !important;
    line-height: 1.5;
  }

  /* Margin for form groups */
  .mb-3 {
    margin-bottom: 20px !important;
  }

  /* Premium Submit Button */
  .btn-success {
    background: linear-gradient(135deg, #10b981, #059669) !important;
    border: none !important;
    border-radius: 14px !important;
    padding: 14px 40px !important;
    font-weight: 600 !important;
    font-size: 1rem !important;
    letter-spacing: 0.025em;
    color: #fff !important;
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.25) !important;
    transition: all 0.3s ease !important;
  }

  .btn-success:hover {
    background: linear-gradient(135deg, #059669, #047857) !important;
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(16, 185, 129, 0.35) !important;
  }

  /* Mobile Responsive Spacing */
  @media (max-width: 768px) {
    .card-body {
      padding: 18px !important;
    }
    .card.shadow-sm {
      border-radius: 16px !important;
    }
  }

</style>


<?php $__env->startSection('title'); ?>
    Settings
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Settings</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('dashboard')); ?>">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Settings</li>
                </ol>
            </nav>
        </div>

    </div>
    <!--end breadcrumb-->

    <form action="<?php echo e(route('settings.update')); ?>" method="POST" enctype="multipart/form-data" class="needs-validation"
        novalidate>
        <?php echo csrf_field(); ?>
        <div class="row">
            <!-- General Info -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">General Information</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="site_name" class="form-label">Site Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="site_name" name="site_name"
                                value="<?php echo e(old('site_name', $setting->site_name ?? '')); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Site Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?php echo e(old('description', $setting->description ?? '')); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Branding -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">Branding</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="logo" class="form-label">Site Logo</label>
                            <input type="file" class="form-control" id="logo" name="logo">



                            <?php if($setting && $setting->hasMedia('logo')): ?>
                                <img src="<?php echo e($setting->getFirstMediaUrl('logo')); ?>" alt="Logo" height="60"
                                    class="mt-2">
                            <?php endif; ?>



                        </div>
                        <div class="mb-3">
                            <label for="favicon" class="form-label">Favicon</label>
                            <input type="file" class="form-control" id="favicon" name="favicon">

                
                            <?php if($setting && $setting->hasMedia('favicon')): ?>
                                <img src="<?php echo e($setting->getFirstMediaUrl('favicon')); ?>" alt="Logo" height="60"
                                    class="mt-2">
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">Contact Information</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="contact_email" class="form-label">Contact Email</label>
                            <input type="email" class="form-control" id="contact_email" name="contact_email"
                                value="<?php echo e(old('contact_email', $setting->contact_email ?? '')); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="contact_phone" class="form-label">Contact Phone</label>
                            <input type="text" class="form-control" id="contact_phone" name="contact_phone"
                                value="<?php echo e(old('contact_phone', $setting->contact_phone ?? '')); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="2"><?php echo e(old('address', $setting->address ?? '')); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Links -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">Social Media Links</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="facebook_link" class="form-label">Facebook</label>
                            <input type="url" class="form-control" id="facebook_link" name="facebook_link"
                                value="<?php echo e(old('facebook_link', $setting->facebook_link ?? '')); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="twitter_link" class="form-label">Twitter</label>
                            <input type="url" class="form-control" id="twitter_link" name="twitter_link"
                                value="<?php echo e(old('twitter_link', $setting->twitter_link ?? '')); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="instagram_link" class="form-label">Instagram</label>
                            <input type="url" class="form-control" id="instagram_link" name="instagram_link"
                                value="<?php echo e(old('instagram_link', $setting->instagram_link ?? '')); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">Radius</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="cab_search_radius" class="form-label">Cab Searh Radius (K.M)</label>
                            <input type="text" class="form-control" id="cab_search_radius" name="cab_search_radius"
                                value="<?php echo e(old('cab_search_radius', $setting->cab_search_radius ?? '')); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="nearby_search_radius" class="form-label">NearBy Searh Radius (K.M)</label>
                            <input type="text" class="form-control" id="nearby_search_radius"
                                name="nearby_search_radius"
                                value="<?php echo e(old('nearby_search_radius', $setting->nearby_search_radius ?? '')); ?>">
                        </div>

                    </div>
                </div>
            </div>

            <!-- Submit -->
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Settings Edit')): ?>
            <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-success px-5">Save Settings</button>
            </div>
            <?php endif; ?>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\projects\vlocus\resources\views/admin/settings/settings.blade.php ENDPATH**/ ?>