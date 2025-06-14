<?php $__env->startSection('title'); ?>
    Dashboard
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php if (\Illuminate\Support\Facades\Blade::check('kitchen')): ?>
    <?php echo $__env->make('user.kitchen.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

    <?php if (\Illuminate\Support\Facades\Blade::check('waiter')): ?>
    <?php echo $__env->make('user.waiter.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

    <?php if (\Illuminate\Support\Facades\Blade::check('admin')): ?>
    <?php echo $__env->make('user.admin.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

    <?php if (\Illuminate\Support\Facades\Blade::check('manager')): ?>
    <?php echo $__env->make('user.admin.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Restaurant\resources\views/home.blade.php ENDPATH**/ ?>