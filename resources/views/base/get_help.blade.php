<?php
use \Calctool\Models\Relation;
use \Calctool\Models\Project;
use \Calctool\Models\RelationKind;
use \Calctool\Models\RelationType;
use \Calctool\Models\Province;
use \Calctool\Models\Country;
use \Calctool\Models\Contact;
use \Calctool\Models\ContactFunction;
use \Calctool\Models\SysMessage;
use \Jenssegers\Agent\Agent;
?>

@extends('layout.master')

@section('title', 'Hulp nodig')

@section('content')
<div id="wrapper">

	<div id="shop">
		<section class="container">

			<div class="pull-right" style="margin: 10px 0 20px 0">
				<a href="/support" class="btn btn-default" type="button">Persoonlijk contact</a>
			</div>

			<h2 style="margin: 10px 0 20px 0;"><strong>Ik ...</strong></h2>

			<div class="row">

				<div class="col-md-4">
					<div class="white-row">
						<h2>Wil <strong>direct</strong> aan de slag</h2>
						
						<ul style="list-style-type: decimal;">
							<li><a href="shop-full-width.html">Nieuw Project</a></li>
							<li><a href="shop-sidebar.html">Nieuwe Relatie</a></li>
							<li><a href="shop-product-full-width.html">Mijn Bedrijf</a></li>
							<li><a href="shop-product-sidebar.html">Algemene Urenregistratie</a></li>
						</ul>

					</div>
				</div>

				<div class="col-md-4">
					<div class="white-row">
						<h2>Zoek een <strong>pagina</strong></h2>
						
						<h5>Account</h5>
						<ul>
							<li><a href="shop-full-width.html">Wachtwoord veranderen</a></li>
							<li><a href="shop-full-width.html">Account verlengen</a></li>
							<li><a href="shop-full-width.html">Account opzeggen</a></li>
							<li><a href="shop-full-width.html">Email adres aanpassen</a></li>
						</ul>

						<h5>Bedrijf</h5>
						<ul>
							<li><a href="shop-full-width.html">Gegevens wijzingen</a></li>
							<li><a href="shop-full-width.html">Betalingsgegevens</a></li>
							<li><a href="shop-full-width.html">Account opzeggen</a></li>
						</ul>

					</div>
				</div>

				<div class="col-md-4">
					<div class="white-row">
						<h2>Zoek naar <strong>hulp</strong></h2>

						<ul>
							<li><a href="https://www.calculatietool.com/faq/"  target="new">Veelgestelde vragen</a></li>
							<li><a href="https://www.calculatietool.com/video-tutorial/" target="new">Video tutorial</a></li>
							<li><a href="/support">Neem contact op</a></li>
							<li><a href="https://demo.calculatietool.com/login?dauth=bWl0Y2g6ZGV1cnplbg==" target="new">Demo omgeving</a></li>
							<li><a href="/support">Probleem melden</a></li>
						</ul>

					</div>
				</div>

			</div>


		</div> 

	</div>
	@stop
