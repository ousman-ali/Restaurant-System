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
<script>
  $(document).ready(function() {
    $('.select2').select2({
      allowClear: true
    });
  });
</script>


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
        const senderRole = parseInt(data.sender_role);
        const userRole = parseInt(window.userRole);

        if (senderRole === 6) {
            if ([5].includes(userRole)) {
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

                if (userRole === 5) {
                    href = '/baker-status';
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
                console.log('🔕 Ignored: Not a target role for sender_role = 5');
            }
        } else {
            // Default logic for other sender roles
            if (userRole === 1 || userRole === 3) {
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

                if (userRole === 1) {
                    href = '/live-kitchen';
                } else if (userRole === 3) {
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
        }
    });

    // inhouse start cooking
    const bakerStartCooking = pusher.subscribe('start-inhouse-cooking');
    bakerStartCooking.bind('kitchen-inhouse-event', function(data) {
        if(window.userRole == data.sender_role){
            return;
        }
        if (window.userRole != 5) {
            console.log('🔕 Ignored: Not for barman');
            return;
        }

        const message = `Order #${data.order_no} started cooking.`;
        const time = data.updated_at
            ? new Date(data.updated_at.replace(' ', 'T')).toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            })
            : 'N/A';

        try {
            const audio = new Audio('{{ asset('sound/notification.mp3') }}');
            audio.volume = 1.0;
            audio.play().catch(error => {
                console.error('🔇 Audio playback failed:', error);
            });
        } catch (e) {
            console.error('❌ Error playing notification sound:', e);
        }
        const href = '/my-orders';
        handleNotification(message, time, href);
    });

    const bakerCompleteCooking = pusher.subscribe('complete-inhouse-cooking');
    bakerCompleteCooking.bind('complete-inhouse-cooking-event', function(data) {
        if (window.userRole != 5) {
            console.log('🔕 Ignored: Not for barman');
            return;
        }
        if(window.userRole == data.sender_role){
            return;
        }

        const message = `Order #${data.order_no} is ready for serving.`;
        const time = data.updated_at
            ? new Date(data.updated_at.replace(' ', 'T')).toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            })
            : 'N/A';

        try {
            const audio = new Audio('{{ asset('sound/notification.mp3') }}');
            audio.volume = 1.0;
            audio.play().catch(error => {
                console.error('🔇 Audio playback failed:', error);
            });
        } catch (e) {
            console.error('❌ Error playing notification sound:', e);
        }
        const href = '/my-orders';

        handleNotification(message, time, href);
    });


    const adminPurchased = pusher.subscribe('supplier-order-purchased');

    adminPurchased.bind('supplier-order-purchased-event', function(data) {
        console.log('✅ Event received from here:', data);
    if(window.userRole == data.sender_role){
                return;
            }
        if (![5].includes(window.userRole)) {
            console.log('🔕 Ignored: Not for admin or manager');
            return;
        }
        const message = `Order #${data.order_no} has been purchased.`;
        const time = data.purchase_time
            ? new Date(data.purchase_time.replace(' ', 'T')).toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            })
            : 'N/A';

        try {
            const audio = new Audio('{{ asset('sound/notification.mp3') }}');
            audio.volume = 1.0;
            audio.play().catch(error => {
                console.error('🔇 Audio playback failed:', error);
            });
        } catch (e) {
            console.error('❌ Error playing notification sound:', e);
        }
        const href = '/live-barman';
        handleNotification(message, time, href);
    });




    // complete cooking

    const completeCookingChannel = pusher.subscribe('complete-cooking');
    console.log('📡 Subscribing to complete-cooking channel...');

    completeCookingChannel.bind('complete-cooking-event', function (data) {
        const senderRole = parseInt(data.sender_role);
        const userRole = parseInt(window.userRole);

        console.log('roles', senderRole, userRole);

        // Check if sender_role is 5
        if (senderRole === 6) {
            if ([5].includes(userRole)) {
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

                if (userRole === 5) {
                    href = '/baker-status';
                }

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
                console.log('🔕 Ignored: Not a target role for sender_role = 5');
            }

        } else {
            // original logic
            if (userRole === 1 || userRole === 3) {
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

                if (userRole === 1) {
                    href = '/live-kitchen';
                } else if (userRole === 3) {
                    href = '/kitchen-status';
                }

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
        }
    });


    // new order notify for kitchen
    const newOrderChannel = pusher.subscribe('order');
    console.log('📡 Subscribing to new order channel...');

    newOrderChannel.bind('order-event', function(data) {
        const senderRole = data.sender_role;
        const orderTo = data.order_to;

        // 👇 Define role-based listening logic
        const roleNotificationMap = {
            4: 'kitchen',  // kitchen
            5: 'barman',   // barman
        };

        // 👇 Skip notification if not for current user
        if (window.userRole != orderTo) {
            console.log(`🔕 Ignored: Order not for this role (order_to: ${orderTo})`);
            return;
        }

        // 👇 Skip self-notifications
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

    servedOrderChannel.bind('order-served-event', function (data) {
        const senderRole = parseInt(data.sender_role);
        const userRole = parseInt(window.userRole);

        if (senderRole === 1) {
            // Notify only barman (userRole 5)
            if (userRole === 5) {
                try {
                    const audio = new Audio('{{ asset('sound/notification.mp3') }}');
                    audio.volume = 1.0;
                    audio.play().catch(error => {
                        console.error('🔇 Audio playback failed:', error);
                    });
                } catch (e) {
                    console.error('❌ Error playing notification sound:', e);
                }

                const message = `Order #${data.order_no} purchased`;
                let href = '/my-barman-orders';
                let time = 'N/A';

                if (data.purchase_time) {
                    const isoTime = data.purchase_time.replace(' ', 'T');
                    time = new Date(isoTime).toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    });
                }

                handleNotification(message, time, href);
            } else {
                console.log('🔕 Ignored: Sender role = 1, but not a barman.');
            }

        } else {
            // Default: notify only admin (role 1)
            if (userRole === 1) {
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
                console.log('🔕 Ignored: Not admin for default served order event.');
            }
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



    // new inhouse order

    const newInhouseOrder = pusher.subscribe('inhouse-order-submit');
    newInhouseOrder.bind('inhouse-order-submit-event', function(data){
        const senderRole = data.sender_role;
        if (senderRole === window.userRole) {
            console.log('🔕 Ignored: Notification from same role.');
            return;
        }
        if(window.userRole == 6){
            try {
            const audio = new Audio('{{ asset('sound/notification.mp3') }}');
            audio.volume = 1.0;
                audio.play().catch(error => {
                    console.error('🔇 Audio playback failed:', error);
                });
            } catch (e) {
                console.error('❌ Error playing notification sound:', e);
            }

            let message = `New order ${data.order_no} received.`;
            let time ='';
            if(data.created_at){
                const isoTime = data.created_at.replace(' ', 'T');
                time = new Date(isoTime).toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
            }
            href = '/home';
            handleNotification(message, time, href);

        }
        
    });


    const newSupplierOrder = pusher.subscribe('supplier-order-submit');
    newSupplierOrder.bind('supplier-order-submit-event', function(data){
        const senderRole = data.sender_role;
        if (senderRole === window.userRole) {
            console.log('🔕 Ignored: Notification from same role.');
            return;
        }
        if(window.userRole == 1){
            try {
            const audio = new Audio('{{ asset('sound/notification.mp3') }}');
            audio.volume = 1.0;
                audio.play().catch(error => {
                    console.error('🔇 Audio playback failed:', error);
                });
            } catch (e) {
                console.error('❌ Error playing notification sound:', e);
            }

            let message = `New order ${data.order_no} received.`;
            let time ='';
            if(data.created_at){
                const isoTime = data.created_at.replace(' ', 'T');
                time = new Date(isoTime).toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
            }
            href = '/live-barman';
            handleNotification(message, time, href);

        }
        
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

const materialRequest = pusher.subscribe('material-request');

materialRequest.bind('material-request-event', function(data) {
    const notify = data.notify ?? null;

    // ✅ Only notify the matching user
    if (parseInt(window.userRole) !== parseInt(notify)) {
        return; // ❌ Not this user's role — skip notification
    }

    const type = data.type ?? 'Unknown';
    const notType = data.not_type ?? 'Unknown';

    try {
        const audio = new Audio('{{ asset('sound/notification.mp3') }}');
        audio.volume = 1.0;
        audio.play().catch(error => {
            console.error('🔇 Audio playback failed:', error);
        });
    } catch (e) {
        console.error('❌ Error playing notification sound:', e);
    }

    // 📨 Build dynamic message
    let message = '';
    let time = 'N/A';
    let href = '';

    if (notType === 'approve') {
        message = `✔️ ${data.user} approved a request for ${data.requested} ${data.name}`;
    } else if (notType === 'reject') {
        message = `❌ ${data.user} rejected the request for ${data.name}`;
    } else {
        message = `🔔 ${data.user} made an update on a material request.`;
    }

    if  (data.time) {
        const rawTime = data.time;
        const isoTime = rawTime.replace(' ', 'T');
        time = new Date(isoTime).toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
    }

    switch (notify.toString()) {
        case '4':
            href = '/kitchen/all-stock';
            break;
        case '5':
            href = '/barman/all-stock';
            break;
        case '6':
            href = '/baker/all-stock';
            break;
        default:
            href = '/dashboard';
            break;
    }

    handleNotification(message, time, href);
});

                       
                       
const stockAlert = pusher.subscribe('stock-alert');
    stockAlert.bind('stock-alert-event', function(data) {
        if(window.userRole == 1 || window.userRole == 2){
            // location.reload(); 
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
        if(data.type == 'recipe_product')
        {
           href = '/kitchen/requests';
        }else{
            href = '/barman/requests';
        }
        message = `${data.user} requested ${data.requested} ${data.name}.`;
        if (data.time) {
            const isoTime = data.time.replace(' ', 'T');
            time = new Date(isoTime).toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
        }
        handleNotification(message, time, href);
        }
    });
                        






</script> 



