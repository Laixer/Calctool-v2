@extends('layout.master')

@section('title', 'PHP Info')

@section('content')
<div id="wrapper">

    <section class="container">

        <div class="col-md-12">

            <div>
            <ol class="breadcrumb">
              <li><a href="/">Dashboard</a></li>
              <li><a href="/admin">Admin Dashboard</a></li>
              <li class="active">PHP configuratie</li>
            </ol>
            <div>

            <h2><strong>PHP Info</strong></h2>

            <?php phpinfo(); ?>

        </div>

    </section>

</div>
@stop
