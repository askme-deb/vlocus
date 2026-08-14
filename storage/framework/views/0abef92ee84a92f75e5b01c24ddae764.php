<?php if($errors->any()): ?>
    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <script>
        $(document).ready(function(){
            round_warning_noti('<?php echo e($error); ?>');
        });
    </script>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>

<?php if(Session::has("error")): ?>
    <script>
        $(document).ready(function(){
            round_error_noti('<?php echo e(Session::get("error")); ?>');
        });
    </script>
<?php endif; ?>

<?php if(Session::has("success")): ?>
    <script>
        $(document).ready(function(){
            round_success_noti('<?php echo e(Session::get("success")); ?>');
        });
    </script>
<?php endif; ?>
<?php /**PATH D:\projects\vlocus\resources\views/layouts/_notification.blade.php ENDPATH**/ ?>