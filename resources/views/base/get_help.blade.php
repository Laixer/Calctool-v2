<?php
use \CalculatieTool\Models\Relation;
use \CalculatieTool\Models\Project;
use \CalculatieTool\Models\RelationKind;
use \CalculatieTool\Models\RelationType;
use \CalculatieTool\Models\Province;
use \CalculatieTool\Models\Country;
use \CalculatieTool\Models\Contact;
use \CalculatieTool\Models\ContactFunction;
use \CalculatieTool\Models\SysMessage;
use \Jenssegers\Agent\Agent;
?>

@extends('layout.master')

@section('title', 'Hulp nodig')

@section('content')
<div id="wrapper">

	<div id="shop">
		<section class="container">

			<div class="pull-right" style="margin: 10px 0 20px 0">
				<a href="/support" class="btn btn-default" type="button"><i class="fa fa-user"></i> Persoonlijk contact</a>
			</div>

			<h2 style="margin: 10px 0 20px 0;"><strong>Ik ...</strong></h2>

			<div class="row">

				<div class="col-md-4">
					<div class="white-row">
						<h2>Wil <strong>direct</strong> aan de slag</h2>
						
						<ul style="list-style-type: decimal;">
							<li><a href="/project/new">Nieuw Project</a></li>
							<li><a href="/relation/new">Nieuwe Relatie</a></li>
							<li><a href="/mycompany">Mijn Bedrijf</a></li>
							<li><a href="/timesheet">Algemene Urenregistratie</a></li>
							<li><a href="/purchase">Algemene Inkoopfacturen</a></li>
						</ul>

					</div>
				</div>

				<div class="col-md-4">
					<div class="white-row">
						<h2>Zoek een <strong>pagina</strong></h2>
						
						<h5>Account</h5>
						<ul>
							<li><a href="/myaccount">Wachtwoord veranderen</a></li>
							<li><a href="/myaccount">Email adres aanpassen</a></li>
							<li><a href="/myaccount">Uw betalingsgsoverzicht</a></li>
							<li><a href="/myaccount">Account verlengen</a></li>
							<li><a href="/myaccount">Account opzeggen</a></li>
							
						</ul>

						<h5>Bedrijf</h5>
						<ul>
							<li><a href="/mycompany">Bedrijfsgegevens wijzingen</a></li>
							<li><a href="/mycompany">Bedrijfsadres wijzigen</a></li>
							<li><a href="/mycompany">Contacten toeovegen aan uw bedrijf</a></li>
							<li><a href="/mycompany">Logo & voorwaarden uploaden</a></li>
							<li><a href="/mycompany">Voorkeuren voor op brieven beheren</a></li>
							<li><a href="/mycompany">Betalingsgegevens voor op factuur</a></li>
							<li><a href="/finance/overview">Uw financieel overzicht</a></li>
						</ul>

						<h5>Projecten</h5>
						<ul>
							<li><a href="/">Overzicht van mijn open projecten</a></li>
							<li><a href="/">Overzicht van mijn gesloten projecten</a></li>
							<li><a href="/finance/overview">Openstande offertes</a></li>
							<li><a href="/finance/overview">Openstande Facturen</a></li>
							<li><a href="/mycompany">Contacten toeovegen aan uw bedrijf</a></li>
	
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
