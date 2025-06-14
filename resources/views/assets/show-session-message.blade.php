<script>
    $(document).ready(function () {
        @if (session('delete_error'))
        $.Notification.notify('error',
                    'top right', 'Can not delete',
                    '{{ session('delete_error') }}'
        );
        @endif

        @if(session('delete_success'))
         $.Notification.notify('success',
            'top right', 'Delete Success',
            '{{ session('delete_success') }}'
        );
        @endif

        @if(session('save_success'))
         $.Notification.notify('success',
            'top right', 'Save Success',
            '{{ session('save_success') }}'
        );
        @endif

         @if(session('save_error'))
         $.Notification.notify('error',
            'top right', 'Save Success',
            '{{ session('save_error') }}'
        );
        @endif
    })
</script>
