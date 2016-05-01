<?php

use \Calctool\Models\Contact;
use \Calctool\Models\Project;

?>

@extends('layout.master')

@section('content')
<script type="text/javascript" src="/plugins/summernote/summernote.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.summernote').summernote({
        height: jQuery(this).attr("data-height") || 200,
        toolbar: [
            ["fontsize", ["fontsize"]],
            ["style", ["bold", "italic", "underline", "strikethrough", "clear"]],
            ["para", ["ul", "ol", "paragraph"]],
            ["table", ["table"]],
            ["media", ["link", "picture", "video"]],
            ["misc", ["codeview"]]
        ]
    });
    $("[name='tax_reverse']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
    $("[name='use_estimate']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
    $("[name='use_more']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
    $("[name='use_less']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
    $('#type').change(function(){
		if (this.value==1) { // regie
    		$('.hide-regie2').hide();
    	}else if (this.value==3) { // blanco 
    		$('.hide-regie').hide();
    	} else {
    		$('.hide-regie').show();
    		$('.hide-regie2').show();
    	}
    })


	var zipcode = $('#zipcode').val();
	var number = $('#address_number').val();
	$('.autoappend').blur(function(e){
		if (number == $('#address_number').val() && zipcode == $('#zipcode').val())
			return;
		zipcode = $('#zipcode').val();
		number = $('#address_number').val();
		if (number && zipcode) {

			$.post("/mycompany/quickstart/address", {
				zipcode: zipcode,
				number: number,
			}, function(data) {
				if (data) {
					var json = $.parseJSON(data);
					$('#street').val(json.street);
					$('#city').val(json.city);
					$("#province").find('option:selected').removeAttr("selected");
					$('#province option[value=' + json.province_id + ']').attr('selected','selected');
				}
			});
		}
	});
});
</script>
<div class="modal fade" id="tutModal" tabindex="-1" role="dialog" aria-labelledby="tutModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body" id="introform">
				<h4>Na de <strong>QuickStart</strong> kan je direct starten met je eerste calculatie & offerte.</h4>
				<hr>

				<div id="introerr" style="display:none;" class="alert alert-danger">
					<i class="fa fa-frown-o"></i>
					<strong>Fout</strong>
					<lu id="introerrlist"></lu>
				</div>

				<form id="frm-quick" action="/mycompany/quickstart" method="post">
				{!! csrf_field() !!}

				<h4 class="company">Jouw Bedrijfsgegevens</h4>
				<input type="hidden" name="id" id="id" value=""/>
				<div class="row">
					<div class="col-md-7">
						<div class="form-group">
							<label for="company_name">Bedrijfsnaam</label>
							<input name="company_name" id="company_name" type="text" value="" class="form-control" />
						</div>
					</div>
					<div class="col-md-5">
						<div class="form-group">
							<label for="company_type">Bedrijfstype</label>
							<select name="company_type" id="company_type" class="form-control pointer">
							@if (0)
							@foreach (RelationType::all() as $type)
								<option {{ $relation ? ($relation->type_id==$type->id ? 'selected' : '') : '' }} value="{{ $type->id }}">{{ ucwords($type->type_name) }}</option>
							@endforeach
							@endif
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<div class="form-group">
							<label for="address_number">Huis nr.</label>
							<input name="address_number" id="address_number" type="text" value="" class="form-control autoappend"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="zipcode">Postcode</label>
							<input name="zipcode" id="zipcode" maxlength="6" type="text" value="" class="form-control autoappend"/>
						</div>
					</div>

					<div class="col-md-7">
						<div class="form-group">
							<label for="street">Straat</label>
							<input name="street" id="street" type="text" value="" class="form-control"/>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="city">Plaats</label>
							<input name="city" id="city" type="text" value="" class="form-control"/>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="province">Provincie*</label>
							<select name="province" id="province" class="form-control pointer">
								@if (0)
								@foreach (Province::all() as $province)
									<option {{ $relation ? ($relation->province_id==$province->id ? 'selected' : '') : '' }} value="{{ $province->id }}">{{ ucwords($province->province_name) }}</option>
								@endforeach
								@endif
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="country">Land*</label>
							<select name="country" id="country" class="form-control pointer">
								@if (0)
								@foreach (Country::all() as $country)
									<option {{ $relation ? ($relation->country_id==$country->id ? 'selected' : '') : ($country->country_name=='nederland' ? 'selected' : '')}} value="{{ $country->id }}">{{ ucwords($country->country_name) }}</option>
								@endforeach
								@endif
							</select>
						</div>
					</div>
				</div>

				<h4>Jouw Contactgegevens</h4>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label for="contact_firstname">Voornaam</label>
							<input name="contact_firstname" id="contact_firstname" type="text" value="{{-- Input::old('contact_firstname') ? Input::old('contact_firstname') : ($relation ? Contact::where('relation_id', $relation->id)->first()['firstname'] : '') --}}" class="form-control"/>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="contact_name">Achternaam</label>
							<input name="contact_name" id="contact_name" type="text" value="{{-- Input::old('contact_name') ? Input::old('contact_name') : ($relation ? Contact::where('relation_id', $relation->id)->first()['lastname'] : '') --}}" class="form-control"/>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="email">Email</label>
							<input name="email" id="email" type="email" value="{{-- Auth::user()->email --}}" class="form-control"/>
						</div>
					</div>
					<div class="col-md-3 company">
						<div class="form-group">
							<label for="contactfunction">Functie</label>
							<select name="contactfunction" id="contactfunction" class="form-control pointer">
							@if (0)
							@foreach (ContactFunction::all() as $function)
								<option {{ $function->function_name=='directeur' ? 'selected' : '' }} value="{{ $function->id }}">{{ ucwords($function->function_name) }}</option>
							@endforeach
							@endif
							</select>
						</div>
					</div>
				</div>
				<span>Na het invullen van de QuickStart is het mogelijk meer bedrijfsgegevens op te geven onder "Mijn bedrijf".</span>
			</div>
			</form>

			<div class="modal-footer">
				<div class="col-md-6">
					<p>Scherm 1/2<p>
				</div>
				<div class="col-md-6">
					<button id="intrnext" class="btn btn-primary"><i class="fa fa-check"></i> Volgende</button>
				</div>
			</div>

		</div>
	</div>
</div>
<div id="wrapper">

	<section class="container">

		@include('calc.wizard', array('page' => 'calculation'))

			@if(Session::get('success'))
			<div class="alert alert-success">
				<i class="fa fa-check-circle"></i>
				<strong>Opgeslagen</strong>
			</div>
			@endif

			@if($errors->has())
			<div class="alert alert-danger">
				<i class="fa fa-frown-o"></i>
				<strong>Fouten in de invoer</strong>
				<ul>
					@foreach ($errors->all() as $error)
					<li><h5 class="nomargin">{{ $error }}</h5></li>
					@endforeach
				</ul>
			</div>
			@endif

			<h2><strong>Nieuw</strong> project</h2>

			@if(!Calctool\Models\Relation::where('user_id','=', Auth::user()->id)->count())
			<div class="alert alert-info">
				<i class="fa fa-info-circle"></i>
				<strong>Let Op!</strong> Maak eerst een opdrachtgever aan onder <a href="/relation/new">nieuwe relatie</a>.
			</div>
			@endif

			<div class="tabs nomargin-top">

				<ul class="nav nav-tabs">
					<li id="tab-project" class="active">
						<a href="#project" data-toggle="tab">Projectgegevens</a>
					</li>
				</ul>

				<div class="tab-content">

					<div id="project" class="tab-pane active">
						<form method="POST" action="/project/new" accept-charset="UTF-8">
						{!! csrf_field() !!}
							<h4>Projectgegevens</h4>
							<h5><strong>Gegevens</strong></h5>
							<div class="row">
								<div class="col-md-5">
									<div class="form-group">
										<label for="name">Projectnaam*</label>
										<input data-step="1" data-intro="Stap 1: Geef projectnaam." name="name" id="name" type="text" value="{{ Input::old('name') }}" class="form-control" />
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="contractor">Opdrachtgever*</label>
										<select data-step="2" data-intro="Stap 2: Selecteer j eopdrachtegver." name="contractor" id="contractor" class="form-control pointer">
											@foreach (Calctool\Models\Relation::where('user_id', Auth::user()->id)->where('active',true)  ->get() as $relation)
											<option value="{{ $relation->id }}">{!! Calctool\Models\RelationKind::find($relation->kind_id)->kind_name == 'zakelijk' ? ucwords($relation->company_name) : (Contact::where('relation_id','=',$relation->id)->first()['firstname'].' '.Contact::where('relation_id','=',$relation->id)->first()['lastname']); !!}</option>
											@endforeach
										</select>
										<!--<a href="/relation/new?redirect={{ Request::path() }}">+ Nieuwe opdrachtgever toevoegen</a>-->
										<a href="#" data-toggle="modal" data-target="#tutModal">+ Nieuwe opdrachtgever toevoegen</a>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="type">Soort project</label>
										<select data-step="3" data-intro="Stap 3: Geef het soort project aan. Zie XX voor meer uitleg." name="type" id="type" class="form-control pointer">
											@foreach (Calctool\Models\ProjectType::all() as $type)
											<option {{ $type->type_name=='calculatie' ? 'selected' : '' }} value="{{ $type->id }}">{{ ucwords($type->type_name) }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-2">
								<label for="tax_reverse">BTW verlegd</label>
									<div data-step="4" data-intro="Stap 4: Geef aan of je het project iwlt starten als BTW-verlegd (0% BTW) of  incl. BTW. Later in kan je dan de BTW-tarieven opgeven en nog bepalen of je incl. of excl. BTW wilt offreren en factureren. Zie voor meer informatie over BTW-verlegd XXX." class="form-group">
										<input name="tax_reverse" type="checkbox">
									</div>
								</div>

							</div>

							<h5><strong>Adresgegevens</strong></h5>
							<div data-step="5" data-intro="Stap 5: Geef de projectadres gegevens op."class="row">

								<div class="col-md-1">
									<div class="form-group">
										<label for="address_number">Huis nr.*</label>
										<input name="address_number" id="address_number" type="text" value="{{ Input::old('address_number') }}" class="form-control autoappend"/>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="zipcode">Postcode*</label>
										<input name="zipcode" id="zipcode" type="text" maxlength="6" value="{{ Input::old('zipcode') }}" class="form-control autoappend"/>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="street">Straat*</label>
										<input name="street" id="street" type="text" value="{{ Input::old('street') }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="city">Plaats*</label>
										<input name="city" id="city" type="text" value="{{ Input::old('city') }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="province">Provincie*</label>
										<select name="province" id="province" class="form-control pointer">
											@foreach (Calctool\Models\Province::all() as $province)
											<option {{ (old('province') == $province->id ? 'selected' : '') }} value="{{ $province->id }}">{{ ucwords($province->province_name) }}</option>
											@endforeach
										</select>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="country">Land*</label>
										<select name="country" id="country" class="form-control pointer">
											@foreach (Calctool\Models\Country::all() as $country)
											<option {{ (old('country') ? (old('country') == $country->id ? 'selected' : '') : $country->country_name=='nederland' ? 'selected' : '') }} value="{{ $country->id }}">{{ ucwords($country->country_name) }}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>		

<div data-step="6" data-intro="Stap 6: Noteer eventueel aantekenen van het project. Dit wordt anders weergegeven.">
							<h4>Kladblok van project <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit betreft een persoonlijk kladblok van dit project en wordt nergens anders weergegeven." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></h4>
					        <div class="row">
					          <div class="form-group">
					            <div class="col-md-12">
					              <textarea name="note" id="note" rows="5" class="form-control">{{ Input::old('note') }}</textarea>
					            </div>
					          </div>
					        </div>
</div>

							<div class="row">
								<div class="col-md-12">
									<button data-step="7" data-intro="Stap 7: Sla je project op." class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
								</div>
							</div>

						</form>
					</div>

				</div>
			</div>

	</section>

</div>
@stop
