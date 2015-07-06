@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<script type="text/javascript">
	$(document).ready(function() {
		$('.arm').click(function(e) {
			e.preventDefault();

			var $curThis = $(this);
			$.post("/admin/alert/delete", {id: $curThis.attr("data-id")}, function(){
				$curThis.closest("tr").hide("slow");
			}).fail(function(e) { console.log(e); });

		});
	});
</script>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<h2><strong>Meldingen</strong></h2>

			<table class="table table-striped">
				<?# -- table head -- ?>
				<thead>
					<tr>
						<th class="col-md-2">Titel</th>
						<th class="col-md-6">Bericht</th>
						<th class="col-md-1">Actief</th>
						<th class="col-md-2">Datum</th>
						<th class="col-md-1"></th>
					</tr>
				</thead>

				<!-- table items -->
				<tbody>
				@foreach (SystemMessage::take(3)->orderBy('created_at', 'desc')->get() as $message)
					<tr>
						<td class="col-md-2">{{ $message->title }}</td>
						<td class="col-md-6">{{ $message->content }}</td>
						<td class="col-md-1">{{ $message->active ? 'Ja' : 'Nee' }}</td>
						<td class="col-md-2">{{ DB::table('system_message')->select(DB::raw('created_at'))->first()->created_at; }}</td>
						<td class="col-md-1">{{ $message->active ? '<button class="btn btn-danger btn-xxs arm" data-id="'.$message->id.'">Verberg</button>' : '' }}</td>
					</tr>
				@endforeach
				</tbody>
			</table>

			{{ Form::open(array('url' => 'admin/alert/new')) }}
			<h4>Nieuwe melding</h4>
			<div class="row">

				<div class="col-md-6">
					<div class="form-group">
						<label for="title">Titel</label>
						<input name="title" id="title" type="text" value="{{ Input::old('title') }}" class="form-control" />
					</div>
				</div>

			</div>

			<div class="row">
				<div class="form-group">
					<div class="col-md-12">
						<label for="message">Bericht</label>
						<textarea name="message" id="message" rows="3" class="form-control">{{ Input::old('message') }}</textarea>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
				</div>
			</div>

			{{ Form::close() }}

		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop