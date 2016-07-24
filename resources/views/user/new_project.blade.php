<?php

use \Calctool\Models\Contact;
use \Calctool\Models\Project;
use \Calctool\Models\RelationKind;
use \Calctool\Models\RelationType;

?>

@extends('layout.master')

@section('title', 'Nieuw project')

@push('style')
<link media="all" type="text/css" rel="stylesheet" href="/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">
<link media="all" type="text/css" rel="stylesheet" href="/components/intro.js/introjs.css">
@endpush

@push('scripts')
<script src="/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script src="/plugins/summernote/summernote.min.js"></script>
<script src="/components/intro.js/intro.js"></script>
@endpush

@section('content')
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

	$('#relationkind').change(function(e) {
		if ($(this).val() == 2)
			$('.company').hide('slow');
		else
			$('.company').show('slow');
	});

	$('#btn-new-relation').click(function(){
		$.post("/relation/new", {
			relationkind: $('#relationkind').val(),
			debtor: $('#debtor').val(),
			company_name: $('#company_name').val(),
			company_type: $('#company_type').val(),
			email_comp: $('#email_comp').val(),
			address_number: $('#address_number2').val(),
			zipcode: $('#zipcode2').val(),
			street: $('#street2').val(),
			city: $('#city2').val(),
			province: $('#province2').val(),
			country: $('#country2').val(),
			contact_name: $('#contact_name').val(),
			email: $('#email').val(),
			contactfunction: $('#contactfunction').val(),
			gender: $('#gender').val(),
		}, function(data) {
			if (data) {
				$('#tutModal').modal('toggle');
     			$('#contractor')
     				.append($("<option selected></option>")
                    .attr("value", data.id)
                    .text(data.name));
			}
		}).error(function(data) {
			$('#introerr').show();
			$.each(data.responseJSON, function(i, val) {
				$('#introerrlist').append("<li>" + val + "</li>")
			});
		});
	});

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
					var json = data;
					$('#street').val(json.street);
					$('#city').val(json.city);
					$("#province").find('option:selected').removeAttr("selected");
					$('#province option[value=' + json.province_id + ']').attr('selected','selected');
				}
			});
		}
	});

	var zipcode = $('#zipcode2').val();
	var number = $('#address_number2').val();
	$('.autoappend2').blur(function(e){
		if (number == $('#address_number2').val() && zipcode == $('#zipcode2').val())
			return;
		zipcode = $('#zipcode2').val();
		number = $('#address_number2').val();
		if (number && zipcode) {

			$.post("/mycompany/quickstart/address", {
				zipcode: zipcode,
				number: number,
			}, function(data) {
				if (data) {
					var json = data;
					$('#street2').val(json.street);
					$('#city2').val(json.city);
					$("#province2").find('option:selected').removeAttr("selected");
					$('#province2 option[value=' + json.province_id + ']').attr('selected','selected');
				}
			});
		}
	});

	function isPrivateContractor(val) {
		if (!$('#contractor :selected').data('business')) {

			$.get("/project/relation/" + val.val(), function(data) {
				if (data.success == 1) {
					$('#street').val(data.address_street);
					$('#address_number').val(data.address_number);
					$('#zipcode').val(data.address_postal);
					$('#city').val(data.address_city);
					
					$("#province").find('option:selected').removeAttr("selected");
					$('#province option[value=' + data.province_id + ']').attr('selected','selected');
				}
			});
		}
	}

	$('#contractor').change(function(){
		isPrivateContractor($(this));
	});

	isPrivateContractor($('#contractor'));

	if (sessionStorage.introDemo) {
		var demo = introJs().
			setOption('nextLabel', 'Volgende').
			setOption('prevLabel', 'Vorige').
			setOption('skipLabel', 'Overslaan').
			setOption('doneLabel', 'Klaar').
			setOption('showBullets', false).
			onexit(function(){
				sessionStorage.removeItem('introDemo');
			}).onbeforechange(function(){
				sessionStorage.introDemo = this._currentStep;
			}).onafterchange(function(){
				var done = this._currentStep;
				if (this._currentStep == 5) {
					sessionStorage.introDemo = 999
				}
				if (done == 1 || done == 2 || done == 3) {
					$('.introjs-prevbutton').css("visibility","initial");
				} else {
					$('.introjs-prevbutton').css("visibility","hidden");
				}
			});

		if (sessionStorage.introDemo == 999) {
			sessionStorage.clear();
			sessionStorage.introDemo = 0;

			sessionStorage.toggleTabProj{{Auth::id()}} = 'calc';

			demo.start();
		} else {
			demo.goToStep(sessionStorage.introDemo).start();
		}

	}	
});
</script>
<div class="modal fade" id="tutModal" tabindex="-1" role="dialog" aria-labelledby="tutModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel2">Nieuwe relatie</h4>
			</div>

			<div class="modal-body" id="introform">
				<div id="introerr" style="display:none;" class="alert alert-danger">
					<i class="fa fa-frown-o"></i>
					<strong>Fout</strong>
					<lu id="introerrlist"></lu>
				</div>

				{!! csrf_field() !!}
				<div class="row">

					<div class="col-md-2">
						<div class="form-group">
							<label for="relationkind">Relatiesoort*</label>
							<select name="relationkind" id="relationkind" class="form-control pointer">
								@foreach (RelationKind::all() as $kind)
								<option {{ old('relationkind') && old('relationkind') == $kind->id ? 'selected' : '' }} value="{{ $kind->id }}">{{ ucwords($kind->kind_name) }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="debtor">Debiteurennummer*</label>&nbsp;<a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit nummer is gegenereerd door de CalculatieTool.com. Je kunt dit vervangen door je eigen boekhoudkundige nummering." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
							<input name="debtor" id="debtor" type="text" value="{{ Input::old('debtor') ? Input::old('debtor') : $debtor_code }}" class="form-control"/>
						</div>
					</div>

				</div>

				<h4 class="company">Bedrijfsgegevens</h4>
				<div class="row company">

					<div class="col-md-5">
						<div class="form-group">
							<label for="company_name">Bedrijfsnaam*</label>
							<input name="company_name" id="company_name" type="text" value="{{ Input::old('company_name') }}" class="form-control" />
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="company_type">Bedrijfstype*</label>
							<select name="company_type" id="company_type" class="form-control pointer">
								@foreach (RelationType::all() as $type)
								<option {{ (old('company_type') == $type->id ? 'selected' : '') }} value="{{ $type->id }}">{{ ucwords($type->type_name) }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="email_comp">Email*</label>
							<input name="email_comp" id="email_comp" type="email" value="{{ Input::old('email_comp') }}" class="form-control"/>
						</div>
					</div>

				</div>

				<h4>Adresgegevens</h4>
				<div class="row">

					<div class="col-md-2">
						<div class="form-group">
							<label for="address_number">Huis nr.*</label>
							<input name="address_number" id="address_number2" type="text" value="{{ old('address_number') }}" class="form-control autoappend2"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="zipcode">Postcode*</label>
							<input name="zipcode" id="zipcode2" maxlength="6" type="text" value="{{ old('zipcode') }}" class="form-control autoappend2"/>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="street">Straat*</label>
							<input name="street" id="street2" type="text" value="{{ Input::old('street') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="city">Plaats*</label>
							<input name="city" id="city2" type="text" value="{{ Input::old('city') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="province">Provincie*</label>
							<select name="province" id="province2" class="form-control pointer">
								@foreach (Calctool\Models\Province::all() as $province)
								<option  {{ (old('province') == $province->id ? 'selected' : '') }} value="{{ $province->id }}">{{ ucwords($province->province_name) }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="country">Land*</label>
							<select name="country" id="country2" class="form-control pointer">
								@foreach (Calctool\Models\Country::all() as $country)
								<option {{ (old('country') ? (old('country') == $country->id ? 'selected' : '') : $country->country_name=='nederland' ? 'selected' : '') }} value="{{ $country->id }}">{{ ucwords($country->country_name) }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>

				<h4>Contactpersoon</h4>
				<div class="row">

					<div class="col-md-3">
						<div class="form-group">
							<label for="contact_name">Achternaam*</label>
							<input name="contact_name" id="contact_name" type="text" value="{{ Input::old('contact_name') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="email">Email*</label>
							<input name="email" id="email" type="email" value="{{ Input::old('email') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3 company">
						<div class="form-group">
							<label for="contactfunction">Functie*</label>
							<select name="contactfunction" id="contactfunction" class="form-control pointer">
								@foreach (Calctool\Models\ContactFunction::all() as $function)
								<option {{ (old('contactfunction') ? (old('contactfunction') == $function->id ? 'selected' : '') : $function->function_name=='directeur' ? 'selected' : '') }} value="{{ $function->id }}">{{ ucwords($function->function_name) }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="gender" style="display:block;">Geslacht</label>
							<select name="gender" id="gender" class="form-control pointer">
								<option value="-1">Selecteer</option>
								<option {{ (old('gender') == 'M' ? 'selected' : '') }} value="M">Man</option>
								<option {{ (old('gender') == 'V' ? 'selected' : '') }} value="V">Vrouw</option>
							</select>
						</div>
					</div>

				</div>

			</div>

			<div class="modal-footer">
				<div class="col-md-6"></div>
				<div class="col-md-6">
					<button class="btn btn-primary" id="btn-new-relation"><i class="fa fa-check"></i> Opslaan</button>
				</div>
			</div>

		</div>
	</div>
</div>

<div class="modal fade" id="myYouTube" tabindex="-1" role="dialog" aria-labelledby="mYouTubeLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

		<iframe width="1280" height="720" src="https://www.youtube.com/embed/TH13Vg12kcM?rel=0;" frameborder="0" allowfullscreen></iframe>

			<div class="modal-body">
				<div class="form-horizontal">


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

			@if (count($errors) > 0)
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

			<h2><strong>Nieuw</strong> project&nbsp;&nbsp;<a class="fa fa-youtube-play" href="javascript:void(0);" data-toggle="modal" data-target="#myYouTube"></a></h2>

			@if(!Calctool\Models\Relation::where('user_id','=', Auth::user()->id)->count())
			<div class="alert alert-info">
				<i class="fa fa-info-circle"></i>
				<strong>Let Op!</strong> Maak eerst een opdrachtgever aan onder <a href="/relation/new">nieuwe relatie</a>
			</div>
			@endif

			<div class="white-row">

				<div id="project">
					<form method="POST" action="/project/new" accept-charset="UTF-8">
					{!! csrf_field() !!}
						<h4>Projectgegevens</h4>
						<h5><strong>Gegevens</strong></h5>
						<div class="row" data-step="1" data-intro="Geef de projectnaam op, selecteer de opdrachtgever en geef aan of je wilt calculeren zonder BTW (BTW-verlegd).">
			
							<div class="col-md-5">
								<div class="form-group">
									<label for="name">Projectnaam*</label>
									<input name="name" id="name" type="text" value="{{ Input::old('name') }}" class="form-control" />
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="contractor">Opdrachtgever*</label>
									<select name="contractor" id="contractor" class="form-control pointer">
										@foreach (Calctool\Models\Relation::where('user_id', Auth::user()->id)->where('active',true)  ->get() as $relation)
										<option data-business="{{ $relation->isBusiness() ? 1 : 0 }}" value="{{ $relation->id }}">{{ $relation->name() }}</option>
										@endforeach
									</select>
									<a href="#" data-toggle="modal" data-target="#tutModal">+ Nieuwe opdrachtgever toevoegen</a>
								</div>
							</div>
							@if (Auth::user()->isAdmin())
							<div class="col-md-2">
								<div class="form-group">
									<label for="type">Soort project</label>
									<select name="type" id="type" class="form-control pointer">
										@foreach (Calctool\Models\ProjectType::all() as $type)
										<option {{ $type->type_name=='calculatie' ? 'selected' : '' }} value="{{ $type->id }}">{{ ucwords($type->type_name) }}</option>
										@endforeach
									</select>										
								</div>
							</div>
							@endif
							<div class="col-md-2">
							<label for="tax_reverse">BTW verlegd</label>
								<div class="form-group">
									<input name="tax_reverse" type="checkbox">
								</div>
							</div>

						</div>

						<h5><strong>Adresgegevens</strong></h5>
						<div data-step="2" data-intro="Geef de adresgegevens van het project op." class="row">

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

						<div class="hidden-xs" data-step="3" data-intro="Noteer eventueel aantekeningen bestemd voor het project. (Dit is alleen zichtbaar voor jou)">
						<h4 class="hidden-xs" >Kladblok van project <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit betreft een persoonlijk kladblok van dit project en wordt nergens anders weergegeven." href="javascript:void(0);" ><i class="fa fa-info-circle"></i></a></h4>
				        <div class="row hidden-xs">
				          <div class="form-group">
				            <div class="col-md-12">
				              <textarea name="note" id="note" rows="5" class="form-control summernote">{{ Input::old('note') }}</textarea>
				            </div>
				          </div>
				        </div>
						</div>

						<div class="row">
							<div class="col-md-12 item-full">
								<button data-step="4" name="save-project" class="btn btn-primary item-full"><i class="fa fa-check"></i> Opslaan</button>
							</div>
						</div>

					</form>
				</div>

			</div>

	</section>

</div>
@stop
