@extends('layout.master')

@section('content')

<script type="text/javascript">
	$(document).ready(function() {
		$(document).ready(function(){
		    $('#log').scrollTop($('#log')[0].scrollHeight);
		});
	});
</script>

<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<div>
			<ol class="breadcrumb">
			  <li><a href="/">Home</a></li>
			  <li><a href="/admin">Admin CP</a></li>
			  <li class="active">Logviewer</li>
			</ol>
			<div>
			<br />

			<div class="pull-right">
				<a class="btn btn-primary" href="/admin/log/truncate">Log wissen</a>
			</div>

			<h2><strong>Logviewer</strong></h2>

			<div class="row">
				<div class="form-group">
					<div class="col-md-12">
						<textarea name="log" id="log" rows="25" class="form-control">{{ file_get_contents('../storage/logs/laravel.log') }}</textarea>
					</div>
				</div>
			</div>

		</div>

	</section>

</div>
@stop
