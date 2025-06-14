<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title><?php echo $__env->yieldContent('title',config('app.name')); ?></title>


    
    <?php echo app('Illuminate\Foundation\Vite')('resources/assets/js/app.js'); ?>
    <?php echo $__env->make('assets.css', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldContent('extra-css'); ?>
</head>
<body class="fixed-left">
<div id="wrapper">
    
    <?php echo $__env->make('assets.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


    <?php if(auth()->guard()->guest()): ?>
    <?php echo $__env->yieldContent('content'); ?>
    <?php else: ?>

        
        <?php if (\Illuminate\Support\Facades\Blade::check('admin')): ?>
        <?php echo $__env->make('assets.sidebar.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>

        <?php if (\Illuminate\Support\Facades\Blade::check('kitchen')): ?>
        <?php echo $__env->make('assets.sidebar.kitchen', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>

        <?php if (\Illuminate\Support\Facades\Blade::check('waiter')): ?>
        <?php echo $__env->make('assets.sidebar.waiter', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>

        <?php if (\Illuminate\Support\Facades\Blade::check('manager')): ?>
        <?php echo $__env->make('assets.sidebar.manager', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>

        <div class="content-page">
            <div class="content">
                <div class="container">
                    
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>


        
        <?php echo $__env->make('assets.js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->yieldContent('extra-js'); ?>
        <?php echo $__env->make('assets.show-session-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</div>

<footer class="footer">
    Restulator © 2025. All rights reserved <a href="https://binarycastle.net" target="_blank">BinaryCastle</a>
</footer>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\Restaurant\resources\views/layouts/app.blade.php ENDPATH**/ ?>