@extends('layout.master')

@section('content')

<script type="text/javascript">
	$(document).ready(function() {
		$("body").on("click", ".delete", function(e){
			e.preventDefault();
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				$.post("/admin/resource/delete", {id: $curThis.closest("tr").attr("data-id")}, function(){
					$curThis.closest("tr").hide("slow");
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
			  <li class="active">Bestandsbeheer</li>
			</ol>
			<div>
			<br />

			<h2><strong>Bestandsbeheer</strong></h2>

			<div class="white-row">
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-3">Omschrijving</th>
						<th class="col-md-2">Grootte</th>
						<th class="col-md-2">Gebruiker</th>
						<th class="col-md-2">Project</th>
						<th class="col-md-2">Aangemaakt</th>
						<th class="col-md-1"></th>
					</tr>
				</thead>

				<tbody>
				@foreach (Resource::where('unlinked','=',false)->orderBy('created_at')->get() as $resource)
					<tr data-id="{{ $resource->id}}">
						<td class="col-md-3"><a target="blank" href="/{{ $resource->file_location }}">{{ $resource->description }}</a></td>
						<td class="col-md-2">{{ $resource->file_size }}</td>
						<td class="col-md-2">{{ ucfirst(User::find($resource->user_id)->username) }}</td>
						<td class="col-md-2">{{ $resource->project_id ? Project::find($resource->project_id)->project_name : 'Geen' }}</td>
						<td class="col-md-2">{{ date('d-m-Y H:i:s', strtotime(DB::table('resource')->select('created_at')->where('id','=',$resource->id)->get()[0]->created_at)) }}</td>
						<td class="col-md-1"><button class="btn btn-danger btn-xs fa fa-times delete"></button></td>
					</tr>
				@endforeach
				</tbody>
			</table>
			<div class="row">
				<div class="col-md-12">
					<a href="/admin/user/new" class="btn btn-primary"><i class="fa fa-pencil"></i> Nieuwe gebruiker</a>
				</div>
			</div>
			</div>
		</div>

	</section>

</div>
@stop
