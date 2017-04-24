<script type="text/javascript">
    $.ajaxSetup({headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    if (localStorage._prescnt) localStorage._prescnt++; else localStorage._prescnt = 1;
    if (!localStorage.lastPageTag) localStorage.lastPageTag = '/';
    $(document).ready(function(){
        @if (Auth::check())
        function _lpolupdate() {
            $.post('/api/v1/update', {location:window.location.href,prescount:localStorage._prescnt}, function() {
                setTimeout(_lpolupdate, 30000);
            }).fail(function(e) {
                if (e.status == 503) location.reload();
            })
        }
        _lpolupdate();
        @endif
    });
</script>
