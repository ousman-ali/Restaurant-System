<?php $__env->startSection('content'); ?>

    <style>
        .btn-orange{
            background-color: orangered!important;
        }
        .text-orange{
            color: orangered!important;
        }
    </style>

<div class="account-pages"></div>
<div class="clearfix"></div>
<div class="wrapper-page">
    <div class=" card-box">
        <div class="panel-heading">
            <h3 class="text-center"> Sign In to <strong class="text-orange my-logo">Restulator</strong> </h3>
            
            
        </div>


        <div class="panel-body">
            <form class="form-horizontal m-t-20" action="<?php echo e(route('login')); ?>" method="post" data-parsley-validate novalidate>
                <?php echo e(csrf_field()); ?>

                <div class="form-group ">
                    <div class="col-xs-12">
                        <input class="form-control" type="email" required placeholder="Email Address" name="email" value="<?php echo e(old('email')); ?>">
                        <?php if($errors->has('email')): ?>
                        <span class="help-block">
                        <strong class="text-danger"><?php echo e($errors->first('email')); ?></strong>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-12">
                        <input class="form-control" type="password" required name="password" placeholder="Password">
                        <?php if($errors->has('password')): ?>
                        <span class="help-block">
                        <strong class="text-danger"><?php echo e($errors->first('password')); ?></strong>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group ">
                    <div class="col-xs-12">
                        <div class="checkbox checkbox-primary">
                            <input id="checkbox-signup" type="checkbox"  name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                            <label for="checkbox-signup">
                                Remember me
                            </label>
                        </div>

                    </div>
                </div>

                <div class="form-group text-center m-t-40">
                    <div class="col-xs-12">
                        <button class="btn btn-pink btn-orange btn-block text-uppercase waves-effect waves-light" type="submit">Log In</button>
                    </div>
                </div>

                <div class="form-group m-t-30 m-b-0">
                    <div class="col-sm-12">
                        <a href="<?php echo e(route('password.request')); ?>" class="text-dark"><i class="fa fa-lock m-r-5"></i> Forgot your password?</a>
                    </div>
                </div>
            </form>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-center">
            <p>Don't have an account? <a href="<?php echo e(route('register')); ?>" class="text-primary m-l-5"><b>Sign Up</b></a></p>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Restaurant\resources\views/auth/login.blade.php ENDPATH**/ ?>