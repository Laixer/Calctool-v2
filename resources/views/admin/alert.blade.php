@extends('layout.master')

@section('title', 'Meldingen')

@section('content')
<script type="text/javascript">
	$(document).ready(function() {
		$('.arm').click(function(e) {
			e.preventDefault();

			var $curThis = $(this);
			$.post("/admin/alert/delete", {id: $curThis.attr("data-id")}, function(){
				$curThis.hide("slow");
				$curThis.closest('tr').find("td").eq(2).text('Nee');
			}).fail(function(e) { console.log(e); });

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
			  <li class="active">Alerts</li>
			</ol>
			<div>
			<br />

			<h2><strong>Meldingen</strong></h2>

			<div class="white-row">
			<h4>Meldingen</h4>
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-2">Titel</th>
						<th class="col-md-6">Bericht</th>
						<th class="col-md-1">Actief</th>
						<th class="col-md-2">Datum</th>
						<th class="col-md-1"></th>
					</tr>
				</thead>

				<tbody>
				@foreach (\CalculatieTool\Models\SysMessage::take(3)->orderBy('created_at', 'desc')->get() as $message)
					<tr>
						<td class="col-md-2">{{ $message->level==1 ? 'Warning' : 'Error' }}</td>
						<td class="col-md-6">{{ $message->content }}</td>
						<td class="col-md-1">{{ $message->active ? 'Ja' : 'Nee' }}</td>
						<td class="col-md-2">{{ DB::table('system_message')->select(DB::raw('created_at'))->first()->created_at }}</td>
						<td class="col-md-1">{!! $message->active ? '<button class="btn btn-danger btn-xxs arm" data-id="'.$message->id.'">Verberg</button>' : '' !!}</td>
					</tr>
				@endforeach
				</tbody>
			</table>

			<form method="POST" action="/admin/alert/new" accept-charset="UTF-8">
            {!! csrf_field() !!}
			<h4>Nieuwe melding</h4>
			<div class="row">

				<div class="col-md-2">
					<div class="form-group">
						<label for="title">Type</label>
						<select name="level" id="level" class="form-control">
							<option selected="selected" value="1">Warning</option>
							<option value="2">Error</option>
						</select>

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

			</form>
			</div>

		</div>

	</section>

</div>
@stop
