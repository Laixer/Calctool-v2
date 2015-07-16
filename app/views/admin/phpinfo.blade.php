@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>

<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<div>
			<ol class="breadcrumb">
			  <li><a href="/">Home</a></li>
			  <li><a href="/admin">Admin CP</a></li>
			  <li class="active">PHP configuratie</li>
			</ol>
			<div>
			<br />

			<h2><strong>PHP Info</strong></h2>

			<?php phpinfo(); ?>

		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop
