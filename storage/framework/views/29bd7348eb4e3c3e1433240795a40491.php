<!-- ========== Left Sidebar Start ========== -->

<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>

                <li class="text-muted menu-title">Navigation</li>

                <li class="has_sub">
                    <a href="<?php echo e(url('/home')); ?>" class="waves-effect"><i class="ti-home"></i> <span> Dashboard </span> </a>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="icon icon-chart"></i> <span> Reports </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo e(url('/kitchen-stat')); ?>">Kitchen</a></li>
                        <li><a href="<?php echo e(url('/waiter-stat')); ?>">Waiter</a></li>
                        <li><a href="<?php echo e(url('/dish-stat')); ?>">Dish</a></li>
                    </ul>
                </li>


                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="icon icon-fire"></i> <span> Kitchen </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo e(url('/live-kitchen')); ?>">Live Kitchen</a></li>
                        
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-notepad"></i> <span> Orders </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo e(url('/new-order')); ?>">New Order</a></li>
                        <li><a href="<?php echo e(url('/all-order')); ?>">All Order</a></li>
                        <li><a href="<?php echo e(url('/non-paid-order')); ?>">Non paid Order</a></li>
                    </ul>
                </li>



                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-truck"></i> <span> Supplier </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo e(url('/add-supplier')); ?>">Add Supplier</a></li>
                        <li><a href="<?php echo e(url('/all-supplier')); ?>">All Supplier</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect <?php echo e(isset($account_menu) ? 'active' : ''); ?>"><i class="icon icon-calculator"></i><span> Accounting </span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><span>Expense</span>  <span class="menu-arrow"></span></a>
                            <ul style="">
                                
                                <li><a href="<?php echo e(url('/add-expense')); ?>"><span>Add Expense</span></a></li>
                                <li><a href="<?php echo e(url('/all-expanse')); ?>"><span>All Expense</span></a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="<?php echo e(url('/all-income')); ?>"><span>Income</span></a>
                        </li>

                    </ul>

                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-menu-alt"></i><span>Tables Management</span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo e(url('/add-table')); ?>">Add Tables</a></li>
                        <li><a href="<?php echo e(url('/all-table')); ?>">All Table</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-package"></i><span> Stock Management </span><span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo e(url('/new-purses')); ?>">New Purses</a></li>
                        <li><a href="<?php echo e(url('/all-purses')); ?>">All Purses</a></li>
                        <li><a href="<?php echo e(url('/add-item')); ?>">Add Item</a></li>
                        <li><a href="<?php echo e(url('/all-item')); ?>">All Stock</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-cutlery"></i><span> Dish </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo e(url('/add-dish')); ?>">Add Dish</a></li>
                        <li><a href="<?php echo e(url('/all-dish')); ?>">All Dish</a></li>
                        <li><a href="<?php echo e(url('/all-dish-type')); ?>">Dish Categories</a></li>
                    </ul>
                </li>

                <li class="text-muted menu-title">More</li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="icon icon-people"></i><span> Employee </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo e(url('/add-employee')); ?>">Add Employee</a></li>
                        <li><a href="<?php echo e(url('/all-employee')); ?>">All Employee</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="icon icon-settings"></i><span> Settings </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo e(url('/all-product-type')); ?>">Product Type Setting</a></li>
                        <li><a href="<?php echo e(url('/all-unit')); ?>">Unit Setting</a></li>
                        <li><a href="<?php echo e(url('/app-settings')); ?>">App Setting</a></li>
                        <li><a href="<?php echo e(url('/website')); ?>">Website</a></li>
                    </ul>
                </li>



            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<!-- Left Sidebar End -->
<?php /**PATH C:\xampp\htdocs\Restaurant\resources\views/assets/sidebar/admin.blade.php ENDPATH**/ ?>