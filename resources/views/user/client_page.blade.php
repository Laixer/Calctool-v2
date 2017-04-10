<?php

use \CalculatieTool\Calculus\CalculationEndresult;
use \CalculatieTool\Models\Relation;
use \CalculatieTool\Models\Resource;
use \CalculatieTool\Models\PurchaseKind;
use \CalculatieTool\Models\RelationKind;
use \CalculatieTool\Models\Contact;
use \CalculatieTool\Models\Country;
use \CalculatieTool\Models\Province;
use \CalculatieTool\Models\Project;
use \CalculatieTool\Models\Offer;
use \CalculatieTool\Models\Invoice;
use \CalculatieTool\Models\Wholesale;
use \CalculatieTool\Models\ProjectShare;
use \CalculatieTool\Models\User;

use \CalculatieTool\Models\RelationType;
use \CalculatieTool\Models\ContactFunction;


$common_access_error = false;
$share = ProjectShare::where('token', Route::Input('token'))->first();
$project = Project::find($share->project_id);
if (!$project)
	$common_access_error = true;
else {
	$offer_last = Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first();
	$relation_self = Relation::find(User::find($project->user_id)->self_id);
	if ($offer_last)
		$cntinv = Invoice::where('offer_id','=', $offer_last->id)->where('invoice_close',true)->count('id');
	else
		$cntinv = 0;
} 
?>

@extends('layout.master')

@section('title', 'Projectoverzicht')

@push('scripts')
<script src="/plugins/summernote/summernote.min.js"></script>
@endpush

<?php if($common_access_error){ ?>
@section('content')
<div id="wrapper">
	<section class="container">
		<div class="alert alert-danger">
			<i class="fa fa-frown-o"></i>
			<strong>Fout</strong>
			Dit project bestaat niet
		</div>
	</section>
</div>
@stop
<?php }else{ ?>

@section('header')

<header id="topNav" class="topHead">
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<a class="logo" href="/">
				{!! ($relation_self && $relation_self->logo_id) ? "<img src=\"/".Resource::find($relation_self->logo_id)->file_location."\" class=\"img-responsive\" style=\"height: 65px;\" />" : '' !!}
				</a>
			</div>
		</div>
	</div>
</header>
@endsection

@section('content')

<script type="text/javascript">
	$(document).ready(function() {
		$('#tab-status').click(function(e){
			sessionStorage.toggleTabProj{{Auth::id()}} = 'status';
		});
		$('#tab-project').click(function(e){
			sessionStorage.toggleTabProj{{Auth::id()}} = 'project';
		});
		$('#tab-desc').click(function(e){
			sessionStorage.toggleTabProj{{Auth::id()}} = 'desc';
		});
		if (sessionStorage.toggleTabProj{{Auth::id()}}){
			$toggleOpenTab = sessionStorage.toggleTabProj{{Auth::id()}};
			$('#tab-'+$toggleOpenTab).addClass('active');
			$('#'+$toggleOpenTab).addClass('active');
		} else {
			sessionStorage.toggleTabProj{{Auth::id()}} = 'status';
			$('#tab-status').addClass('active');
			$('#status').addClass('active');
		}

		$('.summernote').summernote({
            height: $(this).attr("data-height") || 200,
            toolbar: [
                ["style", ["bold", "italic", "underline", "strikethrough", "clear"]],
                ["para", ["ul", "ol", "paragraph"]],
                ["table", ["table"]],
                ["media", ["link", "picture", "video"]],
            ]
        })
	});
</script>
<div id="wrapper">

	<section class="container">

			@if (Session::has('success'))
			<div class="alert alert-success">
				<i class="fa fa-check-circle"></i>
				<strong>{{ Session::get('success') }}</strong>
			</div>
			@endif

			@if (count($errors) > 0)
			<div class="alert alert-danger">
				<i class="fa fa-frown-o"></i>
				<strong>Fout</strong>
				@foreach ($errors->all() as $error)
					{{ $error }}
				@endforeach
			</div>
			@endif

			<h2><strong>Project</strong> {{$project->project_name}}</h2>

				<div class="tabs nomargin-top">

					<ul class="nav nav-tabs">
						<li id="tab-status">
							<a href="#status" data-toggle="tab">Projectstatus</a>
						</li>
						<li id="tab-desc">
							<a href="#desc" data-toggle="tab">Communicatie met uw vakaman</a>
						</li>
					</ul>

					<div class="tab-content">

						<div id="status" class="tab-pane">
							<div class="row">
								<div class="col-md-12">
								<br>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="col-md-4">	
										<div class="row">
											<h4>Projectgegevens</h4>
										</div>
										<div class="row">
											<label for="name">Projectnaam</label>
											<span>{{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : $project->project_name) }}</span>
										</div>
										<div class="row">
											<label for="street">Straat </label>
											<span> {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : $project->address_street) }} {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : $project->address_number) }}</span>
										</div>
										<div class="row">
											<label for="zipcode">Postcode </label>
											<span> {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : $project->address_postal) }}</span>
										</div>
										<div class="row">
											<label for="city">Plaats </label>
											<span> {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : $project->address_city) }}</span>
										</div>
										<div class="row">
											<label for="province">Provincie </label>
											<span> {{ ucwords(Province::find($project->province_id)->province_name) }} </span>		
										</div>
										<div class="row">
											<label for="country">Land </label>
											<span> {{ ucwords(Country::find($project->country_id)->country_name) }} </span>
										</div>
									</div>

									<div class="col-md-4">
										<div class="row">
											<h4>Uw gegevens</h4>
										</div>
										<div class="row">
											<label>Opdrachtgever </label>
											<?php $relation = Relation::find($project->client_id); ?>
											@if (!$relation->isActive())
												<span> {{ RelationKind::find($relation->kind_id)->kind_name == 'zakelijk' ? ucwords($relation->company_name) : (Contact::where('relation_id','=',$relation->id)->first()['firstname'].' '.Contact::where('relation_id','=',$relation->id)->first()['lastname']) }}</span>
											@else
												<span> {{ RelationKind::find($relation->kind_id)->kind_name == 'zakelijk' ? ucwords($relation->company_name) : (Contact::where('relation_id','=',$relation->id)->first()['firstname'].' '.Contact::where('relation_id','=',$relation->id)->first()['lastname']) }}</span>
											@endif
										</div>

										<div class="row">
											<label for="name">Straat</label>
											<span>{{ $relation->address_street }} {{ $relation->address_number }}</span>
										</div>
										<div class="row">
											<label for="name">Postcode</label>
											<span>{{ $relation->address_postal }}</span>
										</div>
										<div class="row">
											<label for="name">Plaats</label>
											<span>{{ $relation->address_city }}</span>
										</div>
										<div class="row">
											<label for="name">Contactpersoon</label>
											<span>{{ (Contact::where('relation_id','=',$relation->id)->first()['firstname']) }}</span>
										</div>
										<div class="row">
											<label for="name">Telefoon</label>
											<span>{{ (Contact::where('relation_id','=',$relation->id)->first()['phone']) }}</span>
										</div>
										<div class="row">
											<label for="name">Mobiel</label>
											<span>{{ (Contact::where('relation_id','=',$relation->id)->first()['mobile']) }}</span>
										</div>									
									</div>

									<div class="col-md-4">	
										<div class="row">
											<h4>Uw vakman</h4>
										</div>

										<div class="row">
											<label for="name">Bedrijfsnaam</label>
											<span>{{ $relation_self->company_name }} </span>
										</div>
										<div class="row">
											<label for="name">Straat</label>
											<span>{{ $relation_self->address_street }} {{ $relation_self->address_number }}</span>
										</div>
										<div class="row">
											<label for="name">Postcode</label>
											<span>{{ $relation_self->address_postal }}</span>
										</div>
										<div class="row">
											<label for="name">Plaats</label>
											<span>{{ $relation_self->address_city }}</span>
										</div>
										
										<?php
											$contact=Contact::where('relation_id',$relation_self->id)->first();
										?>
										<div class="row">
											<label for="name">Contactpersoon</label>
											<span>{{ $contact->getFormalName() }}</span>
										</div>
										<div class="row">
											<label for="name">Telefoon</label>
											<span>{{ $contact->phone }}</span>
										</div>
										<div class="row">
											<label for="name">Mobiel</label>
											<span>{{ $contact->mobile }}</span>
										</div>		
									</div>
								</div>
							</div>

							<br>
					
							<div class="row">
								<div class="col-md-12">
								<h4>Projectacties</h4>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">Offerte opgesteld</div>
								<div class="col-md-2"><?php if ($offer_last) { echo date('d-m-Y', strtotime(DB::table('offer')->select('created_at')->where('id','=',$offer_last->id)->get()[0]->created_at)); } ?></div>
								<div class="col-md-3"><i><?php if ($offer_last) { echo 'Laatste wijziging: '.date('d-m-Y', strtotime(DB::table('offer')->select('updated_at')->where('id','=',$offer_last->id)->get()[0]->updated_at)); } ?></i></div>
							</div>

							<br>

							@if ($offer_last && !$offer_last->offer_finish)
							<div class="row">
								<div class="col-md-12">
									<a href="/ex-project-overview/{{ $share->token }}/done" id="offer-ok" class="btn btn-primary"><i class="fa fa-check"></i> Geef opdracht</a>
								</div>
							</div>
							@endif
						</div>
									
						<div id="desc" class="tab-pane">
							<form method="POST" action="/ex-project-overview/{{ $share->token }}/update" accept-charset="UTF-8">
                            {!! csrf_field() !!}

							<h4>Uw opmerkingen / vragen aan uw vakman</h4>
							<div class="row">
								<div class="form-group">
									<div class="col-md-12">
										<textarea name="client_note" id="client_note" rows="15" class="summernote form-control">{{ $share->client_note }}</textarea>
									</div>
								</div>
							</div>

							<h4>Opmerkingen van uw vakman</h4>
							<div class="row">
								<div class="form-group">
									<div class="col-md-12">
										<div class="white-row well">
											<span>{!! $share->user_note !!}</span>
										</div>										
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-primary"><i class="fa fa-check"></i> Verzenden</button>
								</div>
							</div>
						</form>
					</div>

				</div>
			</div>

		</div>

	</section>

</div>
@stop
<?php } ?>

