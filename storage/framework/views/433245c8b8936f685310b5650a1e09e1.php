<?php
$dish = \App\Models\Dish::all();
$waiter = \App\Models\User::where('role', 4)->get();
$kitchen = \App\Models\User::where('role', 3)->get();

?>

<div class="row">
    <div class="col-md-6 col-lg-4">
        <div class="widget-bg-color-icon card-box fadeInDown animated">
            <div class="bg-icon bg-icon-info pull-left">
                <i class="md md-attach-money text-info"></i>
            </div>
            <div class="text-right">
                <h3 class="text-dark"><b class="counter">
                        <?php $orders = \App\Models\OrderDetails::where('created_at', 'like', \Carbon\Carbon::today()->format('Y-m-d') . '%')->get() ?>
                        <?php echo e(config('restaurant.currency.symbol')); ?>  <?php echo e(number_format($orders->sum('gross_price'),1)); ?> <?php echo e(config('restaurant.currency.currency')); ?>

                    </b></h3>
                <p class="text-muted">Today's Sell</p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="widget-bg-color-icon card-box">
            <div class="bg-icon bg-icon-pink pull-left">
                <i class="md md-add-shopping-cart text-pink"></i>
            </div>
            <div class="text-right">
                <h3 class="text-dark"><b class="counter">
                        <?php $purses = \App\Models\PursesProduct::where('created_at', 'like', \Carbon\Carbon::today()->format('Y-m-d') . '%')->get() ?>
                        <?php echo e(config('restaurant.currency.symbol')); ?> <?php echo e(number_format($purses->sum('gross_price'),1)); ?> <?php echo e(config('restaurant.currency.currency')); ?>

                    </b></h3>
                <p class="text-muted">Today's Purses</p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="widget-bg-color-icon card-box">
            <div class="bg-icon bg-icon-purple pull-left">
                <i class="md md-equalizer text-purple"></i>
            </div>
            <div class="text-right">
                <h3 class="text-dark"><b class="counter">
                        <?php $order = \App\Models\Order::where('created_at', 'like', \Carbon\Carbon::today()->format('Y-m-d') . '%')->get() ?>
                        <?php echo e(count($order)); ?>

                    </b></h3>
                <p class="text-muted">Today's Order</p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

</div>

<div class="col-lg-12">
    <div class="card-box">
        <h4 class="text-dark header-title m-t-0">Dish Sells today</h4>
        <div class="text-center">

        </div>
        <div id="myfirstchart" style="height: 303px;">

        </div>

    </div>
</div>

<div class="col-lg-12">
    <div class="card-box">
        <h4 class="text-dark header-title m-t-0">Daily Order by Waiter</h4>
        <div class="text-center"></div>
        <div id="dailyOrderByWaiter" style="height: 303px;">

        </div>

    </div>
</div>

<div class="col-lg-12">
    <div class="card-box">
        <h4 class="text-dark header-title m-t-0">Daily Order by Kitchen</h4>
        <div class="text-center"></div>
        <div id="dailyOrderByKitchen" style="height: 303px;">

        </div>

    </div>
</div>






<?php $__env->startSection('extra-js'); ?>

    <script src="<?php echo e(url('/dashboard/plugins/raphael/raphael-min.js')); ?>"></script>
    <script src="<?php echo e(url('/dashboard/plugins/morris/morris.min.js')); ?>"></script>
    <script>
        $(document).ready(function () {
            new Morris.Bar({
                // ID of the element in which to draw the chart.
                element: 'myfirstchart',
                // Chart data records -- each entry in this array corresponds to a point on
                // the chart.
                data:

                    [
                            <?php $__currentLoopData = $dish; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        {
                            year: '<?php echo e($d->dish); ?>' + '(<?php echo e(count($d->todaysOrderDish)); ?>)',
                            value: <?php echo e(count($d->todaysOrderDish)); ?>

                        },
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    ],
                goalLineColors: ['red', 'green', 'blue'],
                // The name of the data record attribute that contains x-values.
                xkey: 'year',
                // A list of names of data record attributes that contain y-values.
                ykeys: ['value'],
                // Labels for the ykeys -- will be displayed when you hover over the
                // chart.
                labels: ['Total Order'],
                barColors: ['orangered'],
                gridTextColor: '#000',
                gridTextSize: '15px',
                resize: true
            });

            new Morris.Bar({
                // ID of the element in which to draw the chart.
                element: 'dailyOrderByWaiter',
                // Chart data records -- each entry in this array corresponds to a point on
                // the chart.
                data:

                    [<?php $__currentLoopData = $waiter; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    {
                        year: '<?php echo e($w->name); ?>' + '(<?php echo e(count($w->waiterOrdersToday)); ?>)',
                        value: <?php echo e(count($w->waiterOrdersToday)); ?>

                    },
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    ],

                // The name of the data record attribute that contains x-values.
                xkey: 'year',
                // A list of names of data record attributes that contain y-values.
                ykeys: ['value'],
                // Labels for the ykeys -- will be displayed when you hover over the
                // chart.
                labels: ['Total Order'],
                barColors: ['blue'],
                gridTextColor: '#000',
                gridTextSize: '15px',
                resize: true
            });

            new Morris.Bar({
                // ID of the element in which to draw the chart.
                element: 'dailyOrderByKitchen',
                // Chart data records -- each entry in this array corresponds to a point on
                // the chart.
                data:

                    [<?php $__currentLoopData = $kitchen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    {
                        year: '<?php echo e($k->name); ?>' + '(<?php echo e(count($k->kitchenOrderToday)); ?>)',
                        value: <?php echo e(count($k->kitchenOrderToday)); ?>

                    },
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    ],

                // The name of the data record attribute that contains x-values.
                xkey: 'year',
                // A list of names of data record attributes that contain y-values.
                ykeys: ['value'],
                // Labels for the ykeys -- will be displayed when you hover over the
                // chart.
                labels: ['Total Order'],
                barColors: ['green'],
                gridTextColor: '#000',
                gridTextSize: '15px',
                resize: true
            });
        })
    </script>
<?php $__env->stopSection(); ?>
<?php /**PATH C:\xampp\htdocs\Restaurant\resources\views/user/admin/dashboard.blade.php ENDPATH**/ ?>