<!-- Top Bar Start -->
<div class="topbar">

    <!-- LOGO -->
    <div class="topbar-left">
        <div class="text-center">
            <a href="<?php echo e(url('/')); ?>" class="logo">
                <i class="icon-c-logo my-logo">R</i>
                <span class="my-logo">Restulator</span>
            </a>
            <!-- Image Logo here -->
            
            
            
            
        </div>
    </div>

    <!-- Button mobile view to collapse sidebar menu -->
    <div class="navbar navbar-default" role="navigation">
        <div class="container">
            <div class="">
                <div class="pull-left">
                    <button class="button-menu-mobile open-left waves-effect waves-light">
                        <i class="md md-menu"></i>
                    </button>
                    <span class="clearfix"></span>
                </div>
                <?php if(auth()->guard()->guest()): ?>
                    <?php if(config('restaurant.hasInstall') == true): ?>
                        <ul class="nav navbar-nav navbar-right pull-right">
                            <li><a href="<?php echo e(route('login')); ?>" class="waves-effect waves-light">Login</a></li>
                            <?php if(\App\Models\User::first()): ?>

                            <?php else: ?>
                                <li><a href="<?php echo e(route('register')); ?>" class="waves-effect waves-light">Join</a></li>
                            <?php endif; ?>
                        </ul>
                    <?php else: ?>
                        <ul class="nav navbar-nav navbar-right pull-right">
                            <li><a href="<?php echo e(url('/install')); ?>" class="waves-eefect waves-light">Install</a></li>
                        </ul>
                    <?php endif; ?>
                <?php else: ?>

                    <?php if (\Illuminate\Support\Facades\Blade::check('admin')): ?>
                    <ul class="nav navbar-nav hidden-xs">
                        <li><a href="<?php echo e(url('/new-order')); ?>" class="waves-effect waves-light">New Order</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown"
                               role="button" aria-haspopup="true" aria-expanded="false">Shortcut menu <span
                                    class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo e(url('/live-kitchen')); ?>">Live Kitchen</a></li>
                                <li><a href="<?php echo e(url('/kitchen-stat')); ?>">Kitchen report</a></li>
                                <li><a href="<?php echo e(url('/waiter-stat')); ?>">Waiter report</a></li>
                                <li><a href="<?php echo e(url('/dish-stat')); ?>">Dish report</a></li>
                            </ul>
                        </li>
                    </ul>
                    <?php endif; ?>

                    <?php if (\Illuminate\Support\Facades\Blade::check('manager')): ?>
                    <ul class="nav navbar-nav hidden-xs">
                        <li><a href="<?php echo e(url('/new-order')); ?>" class="waves-effect waves-light">New Order</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown"
                               role="button" aria-haspopup="true" aria-expanded="false">Shortcut menu <span
                                    class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo e(url('/live-kitchen')); ?>">Live Kitchen</a></li>
                                <li><a href="<?php echo e(url('/kitchen-stat')); ?>">Kitchen report</a></li>
                                <li><a href="<?php echo e(url('/waiter-stat')); ?>">Waiter report</a></li>
                                <li><a href="<?php echo e(url('/dish-stat')); ?>">Dish report</a></li>
                            </ul>
                        </li>
                    </ul>
                    <?php endif; ?>


                    <ul class="nav navbar-nav navbar-right pull-right">
                        <li class="dropdown top-menu-item-xs">
                            <a href="" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown"
                               aria-expanded="true"><img
                                    src="<?php echo e(auth()->user()->image ? auth()->user()->image : url('/img_assets/default-thumbnail.jpg')); ?>"
                                    alt="user-img"
                                    class="img-circle"> </a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo e(url('/profile')); ?>"><i class="ti-user m-r-10 text-custom"></i> Profile</a>
                                </li>
                                <li><a href="<?php echo e(url('/profile-edit')); ?>"><i class="ti-settings m-r-10 text-custom"></i>
                                        Settings</a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="<?php echo e(route('logout')); ?>"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="ti-power-off m-r-10 text-danger"></i> Logout
                                    </a>

                                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST"
                                          style="display: none;">
                                        <?php echo e(csrf_field()); ?>

                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
            <!--/.nav-collapse -->
        </div>
    </div>
</div>
<!-- Top Bar End -->
<?php /**PATH C:\xampp\htdocs\Restaurant\resources\views/assets/header.blade.php ENDPATH**/ ?>