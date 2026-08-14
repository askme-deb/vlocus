
<script src="{{ asset('assets/dashboard-assets/assets/plugins/notifications/js/lobibox.min.js') }}"></script>
<script src="{{ asset('assets/dashboard-assets/assets/plugins/notifications/js/notifications.min.js') }}"></script>
<script src="{{ asset('assets/dashboard-assets/assets/plugins/notifications/js/notification-custom-script.js') }}">
</script>

@if ($errors->any())
    @foreach ($errors->all() as $error)
    <script>
        $(document).ready(function(){
            round_warning_noti('{{ $error }}');
        });
    </script>
    @endforeach
@endif

@if(Session::has("error"))
    <script>
        $(document).ready(function(){
            round_error_noti('{{Session::get("error")}}');
        });
    </script>
@endif

@if(Session::has("success"))
    <script>
        $(document).ready(function(){
            round_success_noti('{{Session::get("success")}}');
        });
    </script>
@endif
