<?php
use \Calctool\Models\Relation;
use \Calctool\Models\Project;
use \Calctool\Models\RelationKind;
use \Calctool\Models\RelationType;
use \Calctool\Models\Province;
use \Calctool\Models\Country;
use \Calctool\Models\Contact;
use \Calctool\Models\ContactFunction;
?>

@extends('layout.master')

<?php
$next_step = Cookie::get('nstep');
if (Input::get('nstep') == 'intro')
	$next_step = 'intro_'.Auth::id();

$relation = Relation::find(Auth::user()->self_id);
?>

@section('content')

<script src="/plugins/jquery-ui/jquery-ui.min.js"></script>
<link media="all" type="text/css" rel="stylesheet" href="/plugins/jquery-ui/jquery-ui.css" />
<script type="text/javascript">
/*$(document).ready(function() {
	var myPlayer = videojs('intro_vid');
	$('#tutModal').modal('toggle');
	$('button[data-action="hide"]').click(function(){
		$.get("/hidenextstep").fail(function(e) { console.log(e); });
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
					var json = $.parseJSON(data);
					$('#street').val(json.street);
					$('#city').val(json.city);
					$("#province").find('option:selected').removeAttr("selected");
					$('#province option[value=' + json.province_id + ']').attr('selected','selected');
				}
			});
		}
	});
	$('#intrnext').click(function(e){
		$.post("/mycompany/quickstart", {
			company_type: $('#company_type').val(),
			company_name: $('#company_name').val(),
			street: $('#street').val(),
			address_number: $('#address_number').val(),
			zipcode: $('#zipcode').val(),
			city: $('#city').val(),
			province: $('#province').val(),
			country: $('#country').val(),
			contact_name: $('#contact_name').val(),
			contact_firstname: $('#contact_firstname').val(),
			email: $('#email').val(),
			contactfunction: $('#contactfunction').val(),
		}, function(data) {
			$('#introform').hide('slide', function(){
				$('#introvid').show('slide', {direction: "right"});
				$('.modal-footer').hide('slide', {direction: "up"});
			});
		}).error(function(data) {
			$('#introerr').show();
			$.each(data.responseJSON, function(i, val) {
				$('#introerrlist').append("<li>" + val + "</li>")
			});
		});
	});
	$('#tutModal').on('hidden.bs.modal', function () {
		myPlayer.pause();
	});
});*/
$(document).ready(function() {
	$('.starttour').click(function(){
		sessionStorage.introDemo = 0;
		$('#introModal').modal('hide')

		introJs().
		setOption('nextLabel', 'Volgende').
		setOption('prevLabel', 'Vorige').
		setOption('skipLabel', 'Overslaan').
		setOption('doneLabel', 'Volgende pagina').
		setOption('showBullets', false).
		setOption('exitOnOverlayClick', false).
		start().oncomplete(function(){
			window.location.href = '/mycompany';
			sessionStorage.introDemo = 0;
		});
	});
	<?php if ($next_step && $next_step=='intro_'.Auth::id()){ ?>
		$('#introModal').modal('toggle');
	<?php } ?>
});
</script>
<!--<div class="modal fade" id="tutModal" tabindex="-1" role="dialog" aria-labelledby="tutModalLabel" aria-hidden="true">
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
				<input type="hidden" name="id" id="id" value="{{ $relation ? $relation->id : '' }}"/>
				<div class="row">
					<div class="col-md-7">
						<div class="form-group">
							<label for="company_name">Bedrijfsnaam</label>
							<input name="company_name" id="company_name" type="text" value="{{ Input::old('company_name') ? Input::old('company_name') : ($relation ? $relation->company_name : '') }}" class="form-control" />
						</div>
					</div>
					<div class="col-md-5">
						<div class="form-group">
							<label for="company_type">Bedrijfstype</label>
							<select name="company_type" id="company_type" class="form-control pointer">
							@foreach (RelationType::all() as $type)
								<option {{ $relation ? ($relation->type_id==$type->id ? 'selected' : '') : '' }} value="{{ $type->id }}">{{ ucwords($type->type_name) }}</option>
							@endforeach
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<div class="form-group">
							<label for="address_number">Huis nr.</label>
							<input name="address_number" id="address_number" type="text" value="{{ Input::old('address_number') ? Input::old('address_number') : ($relation ? $relation->address_number : '') }}" class="form-control autoappend"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="zipcode">Postcode</label>
							<input name="zipcode" id="zipcode" maxlength="6" type="text" value="{{ Input::old('zipcode') ? Input::old('zipcode') : ($relation ? $relation->address_postal : '') }}" class="form-control autoappend"/>
						</div>
					</div>

					<div class="col-md-7">
						<div class="form-group">
							<label for="street">Straat</label>
							<input name="street" id="street" type="text" value="{{ Input::old('street') ? Input::old('street') : ($relation ? $relation->address_street : '') }}" class="form-control"/>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="city">Plaats</label>
							<input name="city" id="city" type="text" value="{{ Input::old('city') ? Input::old('city') : ($relation ? $relation->address_city : '') }}" class="form-control"/>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="province">Provincie*</label>
							<select name="province" id="province" class="form-control pointer">
								@foreach (Province::all() as $province)
									<option {{ $relation ? ($relation->province_id==$province->id ? 'selected' : '') : '' }} value="{{ $province->id }}">{{ ucwords($province->province_name) }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="country">Land*</label>
							<select name="country" id="country" class="form-control pointer">
								@foreach (Country::all() as $country)
									<option {{ $relation ? ($relation->country_id==$country->id ? 'selected' : '') : ($country->country_name=='nederland' ? 'selected' : '')}} value="{{ $country->id }}">{{ ucwords($country->country_name) }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>

				<h4>Jouw Contactgegevens</h4>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label for="contact_firstname">Voornaam</label>
							<input name="contact_firstname" id="contact_firstname" type="text" value="{{ Input::old('contact_firstname') ? Input::old('contact_firstname') : ($relation ? Contact::where('relation_id', $relation->id)->first()['firstname'] : '') }}" class="form-control"/>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="contact_name">Achternaam</label>
							<input name="contact_name" id="contact_name" type="text" value="{{ Input::old('contact_name') ? Input::old('contact_name') : ($relation ? Contact::where('relation_id', $relation->id)->first()['lastname'] : '') }}" class="form-control"/>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="email">Email</label>
							<input name="email" id="email" type="email" value="{{ Auth::user()->email }}" class="form-control"/>
						</div>
					</div>
					<div class="col-md-3 company">
						<div class="form-group">
							<label for="contactfunction">Functie</label>
							<select name="contactfunction" id="contactfunction" class="form-control pointer">
							@foreach (ContactFunction::all() as $function)
								<option {{ $function->function_name=='directeur' ? 'selected' : '' }} value="{{ $function->id }}">{{ ucwords($function->function_name) }}</option>
							@endforeach
							</select>
						</div>
					</div>
				</div>
				<span>Na het invullen van de QuickStart is het mogelijk meer bedrijfsgegevens op te geven onder "Mijn bedrijf".</span>
			</div>
			</form>

			<div class="modal-body" id="introvid" style="display:none;padding:0px;">
			  <video id="intro_vid" class="video-js vjs-sublime-skin" controls preload="none" width="900" height="540" data-setup="{}">
			    <source src="/video/vid_intro_1.mp4" type='video/mp4' />
			    <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
			  </video>
			</div>

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
</div>-->
<div class="modal fade" id="introModal" tabindex="-1" role="dialog" aria-labelledby="introModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body" id="introform">
				<div class="row">
					<div class="col-md-8">
						<h4>Welkom bij de<strong> CalculatieTool.com</strong></h4>
					</div>
					<div class="col-md-4">
						<a class="logo" href="/">
						<img src="/images/logo2.png" width="229px" alt="Calctool">
						</a>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-8">
						<h4>Voor een juiste werking van de CalculatieTool.com moeten er eerst een aantal gegevens van je bedrijf bekend zijn. Deze Quick-SetUp helpt je daarbij dit binnen 1 minuut in te stellen.</h4>
					</div>
					<div class="col-md-4">
						<a class="logo2" href="/">
							<img src="/images/cal_bouwen.png" width="229px" alt="Calctool">
						</a>
					</div>
				</div>
			</div>
			</form>
			<div class="modal-footer">
				<div class="col-md-6"></div>
				<div class="col-md-6">
					<button class="starttour btn btn-primary"><i class="fa fa-check"></i> Begin Quick-SetUp</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="wrapper">

	<div id="shop">
		<section class="container">

			@if (Calctool\Models\SysMessage::where('active','=',true)->count()>0)
			@if (Calctool\Models\SysMessage::where('active','=',true)->orderBy('created_at', 'desc')->first()->level==1)
			<div class="alert alert-warning">
				<i class="fa fa-fa fa-info-circle"></i>
				{{ Calctool\Models\SysMessage::where('active','=',true)->orderBy('created_at', 'desc')->first()->content }}
			</div>
			@else
			<div class="alert alert-danger">
				<i class="fa fa-warning"></i>
				<strong>{{ Calctool\Models\SysMessage::where('active','=',true)->orderBy('created_at', 'desc')->first()->content }}</strong>
			</div>
			@endif
			@endif

			<h2 style="margin: 10px 0 20px 0;"><strong>Welkom</strong>, {{ Auth::user()->firstname }}</h2>
			<div class="row">

				<div class="col-sm-6 col-md-2" data-step="1" data-intro="Stap 1: Klik op 'Mijn Bedrijf' om je bedrijfsgegevens in te vullen.">
					<div class="item-box item-box-show fixed-box">
						<figure>
							<a class="item-hover" href="/mycompany">
								<span class="overlay color2"></span>
								<span class="inner" style="top:40%;">
									<span class="block fa fa-home fsize60"></span>
								</span>
							</a>
							<a href="/mycompany" class="btn btn-primary add_to_cart"><strong> Mijn Bedrijf</strong></a>

						</figure>
					</div>
				</div>



				<div class="col-sm-6 col-md-2">
					<div class="item-box item-box-show fixed-box">
						<figure>
							<a class="item-hover" href="/material">
								<span class="overlay color2"></span>
								<span class="inner" style="top:40%;">
									<span class="block fa fa-wrench fsize60"></span>
								</span>
							</a>
							<a href="/material" class="btn btn-primary add_to_cart"><strong> Materialen</strong></a>
						</figure>
					</div>
				</div>

				<div class="col-sm-6 col-md-2">
					<div class="item-box item-box-show fixed-box">
						<figure>
							<a class="item-hover" href="/timesheet">
								<span class="overlay color2"></span>
								<span class="inner" style="top:40%;">
									<span class="block fa fa-clock-o fsize60"></span>
								</span>
							</a>
							<a href="/timesheet" class="btn btn-primary add_to_cart"><strong> Urenregistratie</strong></a>
						</figure>
					</div>
				</div>

				<div class="col-sm-6 col-md-2">
					<div class="item-box item-box-show fixed-box">
						<figure>
							<a class="item-hover" href="/purchase">
								<span class="overlay color2"></span>
								<span class="inner" style="top:40%;">
									<span class="block fa fa-shopping-cart fsize60"></span>
								</span>
							</a>
							<a href="/purchase" class="btn btn-primary add_to_cart"><strong> Inkoopfacturen</strong></a>
						</figure>
					</div>
				</div>

				<div class="col-sm-6 col-md-2">
					<div class="item-box item-box-show fixed-box">
						<figure>
							<a class="item-hover" href="/relation">
								<span class="overlay color2"></span>
								<span class="inner" style="top:40%;">
									<span class="block fa fa-users fsize60"></span>
								</span>
							</a>
							<a href="/relation" class="btn btn-primary add_to_cart"><strong> Relaties</strong></a>
						</figure>
					</div>
				</div>

				<div class="col-sm-6 col-md-2">
					<div class="item-box item-box-show fixed-box">
						<figure>
							<a class="item-hover" href="javascript:void(0);" ui-href="/apps">
								<span class="overlay color2" style="background-color: rgba(0,0,0, 0.2) !important;"></span>
								<span class="inner" style="top:40%;">
									<span class="block fa fa-gears fsize60"></span> coming soon
								</span>
							</a>
							<a style="opacity: 0.4;" href="javascript:void(0);" ui-href="/apps" class="btn btn-primary add_to_cart"><strong> Apps</strong></a>
						</figure>
					</div>
				</div>
				
			</div>

			<div class="row">

				<div id="wrapper" ng-app="projectApp" class="nopadding-top">

					<div class="col-md-12">
						<br>
						@if (Project::where('user_id','=', Auth::user()->id)->count('id')>0)
						<h2><strong>Jouw</strong> Projecten</h2>
						<div class="white-row" ng-controller="projectController">
							<div class="row">
								<div class="form-group col-md-8">
									<input type="text" ng-model="query" class="form-control" placeholder="Zoek in projecten">
								</div>
								<div class="form-group col-md-4" STYLE="text-align: right;" >
									<span><strong>Projectstatus: &nbsp;</strong></span>
									<input name="toggle-close" type="checkbox">
								</div>
							</div>
							<table class="table table-striped">
								<thead>
									<tr>
										<th class="col-md-5" ng-click="orderByField='project_name'; reverseSort = !reverseSort">Projectnaam</th>
										<th class="col-md-3" ng-click="orderByField='relation'; reverseSort = !reverseSort">Opdrachtgever</th>
										<th class="col-md-2" ng-click="orderByField='type_name'; reverseSort = !reverseSort">Type</th>
										<th class="col-md-2" ng-click="orderByField='address_city'; reverseSort = !reverseSort">Plaats</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="project in projects | filter: query | orderBy: orderByField:reverseSort as results">
										<td class="col-md-5"><a href="/project-@{{ project.id }}/edit">@{{ project.project_name }}</a></td>
										<td class="col-md-3">@{{ project.relation }}</td>
										<td class="col-md-2">@{{ project.type.type_name }}</td>
										<td class="col-md-2">@{{ project.address_city }}</td>
									</tr>
									<tr ng-show="results == 0">
										<td colspan="6" style="text-align: center;">Geen projecten beschikbaar</td>
									</tr>
								</tbody>
							</table>
							<div class="row">
								<div class="col-md-3">
									
									<div class="btn-group">
								  		<a href="/project/new" class="btn btn-primary"><i class="fa fa-pencil"></i> Nieuw project</a>
								 		
									</div>
								</div>
							</div>

						</div>
						@else
						<h2><strong>De eerste</strong> stappen...</h2>
						<div class="bs-callout text-center whiteBg">
							<h3><button class="starttour btn btn-primary btn-lg">Quick-SetUp</button> <strong>OF</strong> <a href="/project/new" class="btn btn-primary btn-lg" kaas-intro="Stap 9: Maak nu je eerste prject aan.">Start nieuw project</a></h3>
						</div>
						@endif
					</div>

				</div>
				<script type="text/javascript">
				angular.module('projectApp', []).controller('projectController', function($scope, $http) {
					$http.get('/api/v1/projects').then(function(response){
						$scope._projects = response.data;
						$scope.projects = [];
						angular.forEach($scope._projects, function(value, key) {
						  if (value.project_close == null) {
							$scope.projects.push(value);
						  }
						});
					});
					$("[name='toggle-close']").bootstrapSwitch({onText: 'Gesloten', offText: 'Open'});
					$("[name='toggle-close']").on('switchChange.bootstrapSwitch', function (event, state) {
				        if (state == false) {
							$scope.projects = [];
							angular.forEach($scope._projects, function(value, key) {
							  if (value.project_close == null) {
								$scope.projects.push(value);
							  }
							});
							$scope.$apply();
				        } else {
							$scope.projects = [];
							angular.forEach($scope._projects, function(value, key) {
							  if (value.project_close != null) {
								$scope.projects.push(value);
							  }
							});
							$scope.$apply();
				        }
					});

				});
				</script>
			</div>
		</div>
	</div> 

</div>
@stop
