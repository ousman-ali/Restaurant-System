<script>
    $(document).ready(function () {
        <?php if(session('delete_error')): ?>
        $.Notification.notify('error',
                    'top right', 'Can not delete',
                    '<?php echo e(session('delete_error')); ?>'
        );
        <?php endif; ?>

        <?php if(session('delete_success')): ?>
         $.Notification.notify('success',
            'top right', 'Delete Success',
            '<?php echo e(session('delete_success')); ?>'
        );
        <?php endif; ?>

        <?php if(session('save_success')): ?>
         $.Notification.notify('success',
            'top right', 'Save Success',
            '<?php echo e(session('save_success')); ?>'
        );
        <?php endif; ?>

         <?php if(session('save_error')): ?>
         $.Notification.notify('error',
            'top right', 'Save Success',
            '<?php echo e(session('save_error')); ?>'
        );
        <?php endif; ?>
    })
</script>
<?php /**PATH C:\xampp\htdocs\Restaurant\resources\views/assets/show-session-message.blade.php ENDPATH**/ ?>