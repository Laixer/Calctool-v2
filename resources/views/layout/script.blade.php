<script type="text/javascript">
    $.ajaxSetup({headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    $(document).ready(function(){
        @if (Auth::check())
        function polling_update() {
            $uri = '/api/v1/update?token={{ csrf_token() }}&ts=' + Date.now();
            $.post($uri, {location:window.location.href}, function() {
                setTimeout(polling_update, 20000);
            }).fail(function(e) { if (e.status == 503) location.reload(); });
        }
        polling_update();
        @endif
    });
</script>
