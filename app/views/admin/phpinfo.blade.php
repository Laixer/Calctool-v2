@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>

<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<h2><strong>PHP Info</strong></h2>

			<?php phpinfo(); ?>

		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop
