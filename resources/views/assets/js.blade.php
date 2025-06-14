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
        encrypted: {{config('broadcasting.connections.pusher.options.encrypted')}}
    });
</script>
