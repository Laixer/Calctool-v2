@extends('layout.master')

@section('content')
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<div>
			<ol class="breadcrumb">
			  <li><a href="/">Dashboard</a></li>
			  <li>Relaties</li>
			</ol>
			<div>
			<br>

			<h2><strong>Relaties</strong></h2>

			<div class="white-row">

				<table class="table table-striped">
					<thead>
						<tr>
							<th class="col-md-4">(Bedrijfs)naam</th>
							<th class="col-md-2">Relatietype</th>
							<th class="col-md-2">Telefoon</th>
							<th class="col-md-2">Email</th>
							<th class="col-md-2">Plaats</th>
						</tr>
					</thead>

					<tbody>
					<?php
					$userid = Auth::user()->self_id;
					if(Auth::user()->self_id)
						$userid = Auth::user()->self_id;
					else
						$userid = -1;
					if (!\Calctool\Models\Relation::where('user_id','=', Auth::user()->id)->where('id','!=',$userid)->where('active',true)->count('id')) {
						echo '<tr><td colspan="6" style="text-align: center;">Er zijn nog geen relaties</td></tr>';
					}
					foreach (\Calctool\Models\Relation::where('user_id','=', Auth::user()->id)->where('id','!=',$userid)->where('active',true)->orderBy('created_at', 'desc')->get() as $relation) {
						$contact = \Calctool\Models\Contact::where('relation_id','=',$relation->id)->first();
					?>
						<tr>
							<td class="col-md-4"><a href="{{ 'relation-'.$relation->id.'/edit'}}">{{ $relation->company_name ? $relation->company_name : $contact->firstname .' '. $contact->lastname }}</td>
							<td class="col-md-2">{{ ucwords(\Calctool\Models\RelationKind::find($relation->kind_id)->kind_name) }}</td>
							<td class="col-md-2">{{ $relation->company_name ? $relation->phone : $contact->phone }}</td>
							<td class="col-md-2">{{ $relation->company_name ? $relation->email : $contact->email }}</td>
							<td class="col-md-2">{{ $relation->address_city }}</td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
				<div class="row">
					<div class="col-md-12">
						<a href="relation/new" class="btn btn-primary"><i class="fa fa-pencil"></i> Nieuwe relatie</a>
					</div>
				</div>
			</div>
		</div>

	</section>

</div>
@stop
