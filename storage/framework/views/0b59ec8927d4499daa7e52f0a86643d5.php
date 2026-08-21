<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title'); ?> | <?php echo e(config('app.name', 'Laravel')); ?></title>
    <!--favicon-->
    <link rel="icon" href="<?php echo e(asset('assets/dashboard-assets/assets/images/favicon-32x32.png')); ?>" type="image/png">
    
    <!-- loader-->
    <link href="<?php echo e(asset('assets/dashboard-assets/assets/css/pace.min.css')); ?>" rel="stylesheet">
    <script src="<?php echo e(asset('assets/dashboard-assets/assets/js/pace.min.js')); ?>"></script>

    <!--plugins-->
    <link href="<?php echo e(asset('assets/dashboard-assets/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/dashboard-assets/assets/plugins/metismenu/metisMenu.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/dashboard-assets/assets/plugins/metismenu/mm-vertical.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/dashboard-assets/assets/plugins/simplebar/css/simplebar.css')); ?>">
    
    <link rel="stylesheet" href="<?php echo e(asset('assets/dashboard-assets/assets/plugins/notifications/css/lobibox.min.css')); ?>">
    
    <!--bootstrap css-->
    <link href="<?php echo e(asset('assets/dashboard-assets/assets/css/bootstrap.min.css')); ?>" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
    <!--main css-->
    <link href="<?php echo e(asset('assets/dashboard-assets/assets/css/bootstrap-extended.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/dashboard-assets/sass/main.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/dashboard-assets/assets/css/horizontal-menu.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/dashboard-assets/sass/dark-theme.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/dashboard-assets/sass/blue-theme.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/dashboard-assets/sass/semi-dark.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/dashboard-assets/sass/bordered-theme.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/dashboard-assets/sass/responsive.css')); ?>" rel="stylesheet">

    <link href="<?php echo e(asset('assets/dashboard-assets/assets/plugins/datatable/css/dataTables.bootstrap5.min.css')); ?>" rel="stylesheet" />

    <link rel="stylesheet" href="<?php echo e(asset('assets/dashboard-assets/vendors/sweetalert/sweetalert.css')); ?>" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <style>
        .nav-item a.nav-link .active{
            color: #ffffff !important;
            background-color: #008cff !important;
            
        } 
    </style>
    <style>
        /* Default state - show top-header and primary-menu */
        .top-header {
            display: block;
        }
    
        .primary-menu {
            display: block;
        }
    
        /* Hide top-header when screen width is 1366px or more (full screen) */
        @media screen and (min-width: 1366px) {
            .top-header {
                display: none;
            }
            .primary-menu .navbar {
                top: 0;
            }
            .main-wrapper {
                margin-top: 50px;
            }
        }
    </style>
    
    <?php echo $__env->yieldContent('css'); ?>
</head>


<body>

    <!--start header-->
    <?php echo $__env->make('layouts.admin-include.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!--end top header-->
  
  
    <!--navigation-->
    <?php echo $__env->make('layouts.admin-include.nav-menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!--end navigation-->
  
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <?php echo $__env->yieldContent('content'); ?>
            <?php if(isset($slot)): ?>
                <?php echo e($slot); ?>

            <?php endif; ?>
        </div>
    </main>
    <!--end main wrapper-->
  
    <!--start overlay-->
    <div class="overlay btn-toggle"></div>
    <!--end overlay-->
  
    <?php echo $__env->make('layouts.admin-include.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  
    <?php echo $__env->make('layouts.admin-include.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
  
</html>
<?php /**PATH D:\projects\vlocus\resources\views/layouts/app.blade.php ENDPATH**/ ?>