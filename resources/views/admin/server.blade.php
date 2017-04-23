@extends('layout.master')

@section('title', 'Omgeving')

@section('content')

<?php
$envvars = array($_ENV);
$laravel = app();
$version = $laravel::VERSION;
$db_version = DB::select(DB::raw("SELECT version()"));
if ($db_version)
    $db_version = $db_version[0]->version;
else
    $db_version = '-';

function convert($size) {
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}

?>
<div id="wrapper">

    <section class="container">

        <div class="col-md-12">

            <div>
            <ol class="breadcrumb">
              <li><a href="/">Home</a></li>
              <li><a href="/admin">Admin CP</a></li>
              <li class="active">Server & Config</li>
            </ol>
            <div>
            <br />

            @if (Session::has('success'))
            <div class="alert alert-success">
                <i class="fa fa-check-circle"></i>
                <strong>{{ Session::get('success') }}</strong>
            </div>
            @endif

            <h2><strong>Omgeving {{ app()->environment() }}</strong></h2>

            <div class="white-row">

                <div class="pull-right">
                    <?php // @if (Auth::user()->isSystem()) ?>
                    <a href="/admin/environment/clearcaches" class="btn btn-primary">Clear cache</a>
                    <?php // @endif ?>
                </div>

                <h4>Serverstatus</h4>
                <div class="row">
                    <div class="col-md-2"><strong>Servernaam</strong></div>
                    <div class="col-md-10">{{ gethostname() }}</div>
                </div>
                <div class="row">
                    <div class="col-md-2"><strong>Memory used</strong></div>
                    <div class="col-md-10">{{ convert(memory_get_usage(true)) }}</div>
                </div>
                <div class="row">
                    <div class="col-md-2"><strong>Load Averages</strong></div>
                    <div class="col-md-10">{{ sys_getloadavg()[0] . ' ' . sys_getloadavg()[1] . ' ' . sys_getloadavg()[2] }}</div>
                </div>
                <div class="row">
                    <div class="col-md-2"><strong>Date offset</strong></div>
                    <div class="col-md-10">{{ date('T') }}</div>
                </div>
                <div class="row">
                    <div class="col-md-2"><strong>Debug</strong></div>
                    <div class="col-md-10">{{ config('app.debug') ? "Yes" : "No" }}</div>
                </div>
                <br />
                <h4>Software</h4>
                <div class="row">
                    <div class="col-md-2">Versie</div>
                    <div class="col-md-10">{{ config('app.version') }}</div>
                </div>
                <div class="row">
                    <div class="col-md-2">Framework</div>
                    <div class="col-md-10">{{ $version }}</div>
                </div>
                <div class="row">
                    <div class="col-md-2">PHP</div>
                    <div class="col-md-10">{{ phpversion() }}</div>
                </div>
                <div class="row">
                    <div class="col-md-2">Database</div>
                    <div class="col-md-10">{{ $db_version }}</div>
                </div>
            </div>

        </div>

    </section>

</div>
@stop
