<script>
    var resizefunc = [];
</script>

<script src="https://js.pusher.com/4.1/pusher.min.js"></script>

<script src="<?php echo e(url('/dashboard/js/jquery.min.js')); ?>"></script>
<script src="<?php echo e(url('/dashboard/js/bootstrap.min.js')); ?>"></script>
<script src="<?php echo e(url('/dashboard/js/detect.js')); ?>"></script>
<script src="<?php echo e(url('/dashboard/js/fastclick.js')); ?>"></script>
<script src="<?php echo e(url('/dashboard/js/jquery.slimscroll.js')); ?>"></script>
<script src="<?php echo e(url('/dashboard/js/jquery.blockUI.js')); ?>"></script>

<script src="<?php echo e(url('/dashboard/js/wow.min.js')); ?>"></script>
<script src="<?php echo e(url('/dashboard/js/jquery.nicescroll.js')); ?>"></script>
<script src="<?php echo e(url('/dashboard/js/jquery.scrollTo.min.js')); ?>"></script>



<script src="<?php echo e(url('/dashboard/plugins/select2/js/select2.min.js')); ?>"></script>



<script src="<?php echo e(url('/dashboard/plugins/notifyjs/js/notify.js')); ?>"></script>
<script src="<?php echo e(url('/dashboard/plugins/notifications/notify-metro.js')); ?>"></script>


<script src="<?php echo e(url('/dashboard/plugins/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(url('/dashboard/plugins/datatables/dataTables.bootstrap.js')); ?>"></script>
<script src="<?php echo e(url('/dashboard/plugins/datatables/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(url('/dashboard/plugins/datatables/buttons.bootstrap.min.js')); ?>"></script>
<script src="<?php echo e(url('/dashboard/plugins/datatables/dataTables.responsive.min.js')); ?>"></script>
<script src="<?php echo e(url('/dashboard/plugins/datatables/responsive.bootstrap.min.js')); ?>"></script>


<script src="<?php echo e(url('/dashboard/plugins/ladda-buttons/js/spin.min.js')); ?>"></script>
<script src="<?php echo e(url('/dashboard/plugins/ladda-buttons/js/ladda.min.js')); ?>"></script>
<script src="<?php echo e(url('/dashboard/plugins/ladda-buttons/js/ladda.jquery.min.js')); ?>"></script>




<script src="<?php echo e(url('/dashboard/js/dashboard.js')); ?>"></script>
<script src="<?php echo e(url('/dashboard/js/jquery.core.js')); ?>"></script>
<script src="<?php echo e(url('/dashboard/js/jquery.app.js')); ?>"></script>
<script src="<?php echo e(url('/dashboard/js/jquery.uploadPreview.js')); ?>"></script>


<script src="<?php echo e(url('/dashboard/plugins/parsleyjs/parsley.min.js')); ?>"></script>


<script>
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('<?php echo e(config('broadcasting.connections.pusher.key')); ?>', {
        cluster: '<?php echo e(config('broadcasting.connections.pusher.options.cluster')); ?>',
        encrypted: <?php echo e(config('broadcasting.connections.pusher.options.encrypted')); ?>

    });
</script>
<?php /**PATH C:\xampp\htdocs\Restaurant\resources\views/assets/js.blade.php ENDPATH**/ ?>