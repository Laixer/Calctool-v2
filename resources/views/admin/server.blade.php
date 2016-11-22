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

$loadresult = @exec('uptime');
preg_match("/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/", $loadresult, $avgs);

$uptime = explode(' up ', $loadresult);
$uptime = explode(',', $uptime[1]);
$uptime = $uptime[0].', '.$uptime[1];

function convert($size) {
	$unit=array('b','kb','mb','gb','tb','pb');
	return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}

$rev_timestamp = '-';
$rev = '-';
if (File::exists('../.revision')) {
	$rev_timestamp = File::lastModified('../.revision');
	$rev = substr(File::get('../.revision'), 0, 7);
}

$redis_info = Redis::command('info');
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
				<strong>@if (Session::get('success'))</strong>
			</div>
			@endif

			<h2><strong>Omgeving {{ app()->environment() }}</strong></h2>

			<div class="white-row">

				<div class="pull-right">
					<a href="/admin/environment/clearcaches" class="btn btn-primary">Clear cache</a>
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
					<div class="col-md-10">{{ $avgs[1] . ' ' . $avgs[2] . ' ' . $avgs[2] }}</div>
				</div>
				<div class="row">
					<div class="col-md-2"><strong>Uptime</strong></div>
					<div class="col-md-10">{{ $uptime }}</div>
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
					<div class="col-md-2">Upstream</div>
					<div class="col-md-10">{{ $rev_timestamp == '-' ? '-' : date('Y-m-d H:i:s', $rev_timestamp) }}</div>
				</div>
				<div class="row">
					<div class="col-md-2">Versie</div>
					<div class="col-md-10">{{ config('app.version') }}</div>
				</div>
				<div class="row">
					<div class="col-md-2">Revisie</div>
					<div class="col-md-10">{{ $rev }}</div>
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
					<div class="col-md-2">Cache</div>
					<div class="col-md-10">{{ $redis_info['Server']['redis_version'] }}</div>
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
