<script>
    var resizefunc = [];
</script>

<script src="https://js.pusher.com/4.1/pusher.min.js"></script>

<script src="{{ url('/dashboard/js/jquery.min.js') }}"></script>
<script src="{{ url('/dashboard/js/bootstrap.min.js') }}"></script>
<script src="{{ url('/dashboard/js/detect.js') }}"></script>
<script src="{{ url('/dashboard/js/fastclick.js') }}"></script>
<script src="{{ url('/dashboard/js/jquery.slimscroll.js') }}"></script>
<script src="{{ url('/dashboard/js/jquery.blockUI.js') }}"></script>
{{--<script src="{{ url('/dashboard/js/waves.js') }}"></script>--}}
<script src="{{ url('/dashboard/js/wow.min.js') }}"></script>
<script src="{{ url('/dashboard/js/jquery.nicescroll.js') }}"></script>
<script src="{{ url('/dashboard/js/jquery.scrollTo.min.js') }}"></script>


{{--Select 2 Plugins--}}
<script src="{{ url('/dashboard/plugins/select2/js/select2.min.js') }}"></script>


{{--Notification Plugins--}}
<script src="{{ url('/dashboard/plugins/notifyjs/js/notify.js') }}"></script>
<script src="{{ url('/dashboard/plugins/notifications/notify-metro.js') }}"></script>

{{--Data table plugins--}}
<script src="{{ url('/dashboard/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('/dashboard/plugins/datatables/dataTables.bootstrap.js') }}"></script>
<script src="{{ url('/dashboard/plugins/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ url('/dashboard/plugins/datatables/buttons.bootstrap.min.js') }}"></script>
<script src="{{ url('/dashboard/plugins/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ url('/dashboard/plugins/datatables/responsive.bootstrap.min.js') }}"></script>

{{--Ladda js plugs--}}
<script src="{{ url('/dashboard/plugins/ladda-buttons/js/spin.min.js') }}"></script>
<script src="{{ url('/dashboard/plugins/ladda-buttons/js/ladda.min.js') }}"></script>
<script src="{{ url('/dashboard/plugins/ladda-buttons/js/ladda.jquery.min.js') }}"></script>

{{-- sweet alert --}}
<script src="{{ asset('dashboard/js/sweetalert.min.js') }}"></script>

{{--Custom plugins--}}
<script src="{{ url('/dashboard/js/dashboard.js') }}"></script>
<script src="{{ url('/dashboard/js/jquery.core.js') }}"></script>
<script src="{{ url('/dashboard/js/jquery.app.js') }}"></script>
<script src="{{ url('/dashboard/js/jquery.uploadPreview.js') }}"></script>

{{--Form validation--}}
<script src="{{ url('/dashboard/plugins/parsleyjs/parsley.min.js') }}"></script>

{{--Pusher Setup--}}
<script>
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('{{config('broadcasting.connections.pusher.key')}}', {
        cluster: '{{config('broadcasting.connections.pusher.options.cluster')}}',
        encrypted: true,
    });


    function handleNotification(message, time, href) {
        console.log('📦 Received message:', message);
        const notificationDropdownMenu = $('.notificationDropdown');
        const notificationBadge = $('.notif-count');

        if (notificationBadge.length) {
            const currentCount = parseInt(notificationBadge.text()) || 0;
            const newCount = currentCount + 1;
            notificationBadge.text(newCount);
            notificationBadge.removeAttr('style');
            notificationBadge.removeClass('d-none');
        }
        const notificationHtml = `
        <a href="${href}">
            <li style="padding: 10px; border-bottom: 1px solid #eee;">
                <strong>${message}</strong><br>
                <small class="text-muted">${time}</small>
            </li>
        </a>
        `;
        const notificationList = $('.notif-menu');
        if (notificationList.length) {
            notificationList.prepend(notificationHtml);
        }
    }

    console.log('user role', window.userRole);

    // start cooking
    const channel = pusher.subscribe('start-cooking');
    console.log('📡 Subscribing to start-cooking channel...');
    channel.bind('kitchen-event', function(data) {
        if (window.userRole === 1 || window.userRole === 3) {
             try {
                const audio = new Audio('{{ asset('sound/notification.mp3') }}');
                audio.volume = 1.0;
                audio.play().catch(error => {
                    console.error('🔇 Audio playback failed:', error);
                });
            } catch (e) {
                console.error('❌ Error playing notification sound:', e);
            }
            const message = `Order #${data.order_no} has started cooking`;
            let time = 'N/A';
            let href = '';
            if(window.userRole == 1){
                href = '/live-kitchen';
            }else if(window.userRole == 3){
                href = '/kitchen-status';
            }
            if (data.cook_start_time) {
                const isoTime = data.cook_start_time.replace(' ', 'T');
                time = new Date(isoTime).toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
            }
            handleNotification(message, time, href);
        } else {
            console.log('🔕 Ignored: Not an admin or waiter.');
        }
    });

    // complete cooking

    const completeCookingChannel = pusher.subscribe('complete-cooking');
    console.log('📡 Subscribing to complete-cooking channel...');
    completeCookingChannel.bind('complete-cooking-event', function(data) {
        if (window.userRole === 1 || window.userRole === 3) {
             try {
                const audio = new Audio('{{ asset('sound/notification.mp3') }}');
                audio.volume = 1.0;
                audio.play().catch(error => {
                    console.error('🔇 Audio playback failed:', error);
                });
            } catch (e) {
                console.error('❌ Error playing notification sound:', e);
            }
            const message = `Order #${data.order_no} has completed cooking`;
            let time = 'N/A';
            let href = '';
            if(window.userRole == 1){
                href = '/live-kitchen';
            }else if(window.userRole == 3){
                href = '/kitchen-status';
            }
            console.log('href', href);
            if (data.cook_complete_time) {
                const isoTime = data.cook_complete_time.replace(' ', 'T');
                time = new Date(isoTime).toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
            }
            handleNotification(message, time, href);
        } else {
            console.log('🔕 Ignored: Not an admin or waiter.');
        }
    });

    // new order notify for kitchen
    const newOrderChannel = pusher.subscribe('order');
    console.log('📡 Subscribing to new order channel...');

    newOrderChannel.bind('order-event', function(data) {
        const senderRole = data.sender_role;

        // Ignore if sender and current user share the same role
        if (senderRole === window.userRole) {
            console.log('🔕 Ignored: Notification from same role.');
            return;
        }

        try {
            const audio = new Audio('{{ asset('sound/notification.mp3') }}');
            audio.volume = 1.0;
            audio.play().catch(error => {
                console.error('🔇 Audio playback failed:', error);
            });
        } catch (e) {
            console.error('❌ Error playing notification sound:', e);
        }

        let message = '';
        let time = 'N/A';

        let href = '';
        if(window.userRole == 1){
            href = '/all-order';
        }else if(window.userRole == 3){
            href = '/my-orders';
        }
        if (data.type === 'update') {
            message = `Order #${data.order_no} updated`;
            if (data.updated_at) {
                const isoTime = data.updated_at.replace(' ', 'T');
                time = new Date(isoTime).toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
            }
        } else {
            message = `New order #${data.order_no} received`;
            if (data.created_at) {
                const isoTime = data.created_at.replace(' ', 'T');
                time = new Date(isoTime).toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
            }
        }

        handleNotification(message, time, href);
    });


    // order served
    const servedOrderChannel = pusher.subscribe('order-served');
    console.log('📡 Subscribing to serve order channel...');
    servedOrderChannel.bind('order-served-event', function(data) {
        if (window.userRole === 1) {
             try {
                const audio = new Audio('{{ asset('sound/notification.mp3') }}');
                audio.volume = 1.0;
                audio.play().catch(error => {
                    console.error('🔇 Audio playback failed:', error);
                });
            } catch (e) {
                console.error('❌ Error playing notification sound:', e);
            }
            const message = `Order #${data.order_no} has served`;
            let href = '/all-order';
            let time = 'N/A';
            if (data.updated_at) {
                const isoTime = data.updated_at.replace(' ', 'T');
                time = new Date(isoTime).toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
            }
            handleNotification(message, time, href);
        } else {
            console.log('🔕 Ignored: Not an admin or waiter.');
        }
    });

    // cancel order

    const cancelOrderChannel = pusher.subscribe('cancel-order');
    console.log('📡 Subscribing to cancel order channel...');
    cancelOrderChannel.bind('order-cancel-event', function(data) {
        const senderRole = data.sender_role;

        // Ignore if sender and current user share the same role
        if (senderRole === window.userRole) {
            console.log('🔕 Ignored: Notification from same role.');
            return;
        }

        try {
            const audio = new Audio('{{ asset('sound/notification.mp3') }}');
            audio.volume = 1.0;
            audio.play().catch(error => {
                console.error('🔇 Audio playback failed:', error);
            });
        } catch (e) {
            console.error('❌ Error playing notification sound:', e);
        }

        let message = '';
        let time = 'N/A';
        let href = '';
        message = `Order #${data.order_no} canceled`;
        if (data.updated_at) {
            const isoTime = data.updated_at.replace(' ', 'T');
            time = new Date(isoTime).toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
        }
        handleNotification(message, time, href);
    });

    

    // delete popup
$(document).on('click', '.deletebtn', function (e) {
    e.preventDefault();

    let form = $(this).closest('form');

    swal({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        buttons: {
            confirm: {
                text: 'Yes, delete it!',
                className: 'btn btn-success'
            },
            cancel: {
                visible: true,
                className: 'btn btn-danger'
            }
        }
    }).then((Delete) => {
        if (Delete) {
            form.submit();
        } else {
            swal.close();
        }
    });
});







</script> 
