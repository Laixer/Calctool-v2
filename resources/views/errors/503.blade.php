@extends('layout.master')

@section('header')
<header id="topNav" class="topHead">
    <div class="container">

        <button class="btn btn-mobile" data-toggle="collapse" data-target=".nav-main-collapse">
            <i class="fa fa-bars"></i>
        </button>

        <a class="logo" href="/">
            <img src="/images/logo.png" width="229px" alt="CalculatieTool.com" />
        </a>

    </div>
</header>
@endsection

@section('content')
<script type="text/javascript">
$(document).ready(function(){
    function _lpolupdate() {
        $.ajax({
            url: "/support",
            error: function(XMLHttpRequest, textStatus, errorThrown) {},
            success: function() {
                location.reload();
            }
        });
        setTimeout(_lpolupdate, 1000);
    }
    _lpolupdate();
});
</script>
<div id="wrapper">

    <section class="container">

        <div class="row">

            <div class="col-md-12 text-center">
                <div class="e404">
                <i class="fa fa-refresh fa-spin"></i></div>
                <h2>
                    De applicatie wordt bijgewerkt. Dit kan enkele minuten duren.
                    <span class="subtitle">Dit is een automatisch process, u hoeft niets te doen.</span>
                </h2>
            </div>

        </div>

    </section>

</div>
@stop
