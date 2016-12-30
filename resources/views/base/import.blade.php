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
?>

@extends('layout.master')

@section('title', 'Dashboard')

@push('style')
@endpush

@push('scripts')
@endpush

@section('content')
<script type="text/javascript">
$(document).ready(function() {
	$(document).on('change', '.btn-file :file', function() {
	  $('#frm-save').submit();
	});
});
</script>

<div id="wrapper">

	<div id="shop">
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
				<strong>Fouten in de invoer</strong>
				<ul>
					@foreach ($errors->all() as $error)
					<li><h5 class="nomargin">{{ $error }}</h5></li>
					@endforeach
				</ul>
			</div>
			@endif

			<div class="row">

				<div id="wrapper" ng-app="projectApp" class="nopadding-top">

					<div class="col-md-12">
						<br>
						<h2><strong>Relaties</strong> en <strong>contacten</strong> importeren</h2>
						
						<div class="row">

							<div class="col-md-12">

								<div class="white-row">
									<div class="pull-right">
										<a href="/docs/voorbeeld_relatie_bestand_import.csv" class="btn btn-primary" type="button"><i class="fa fa-download"></i> Download voorbeeld</a>
									</div>

								<h2>Bestandsindeling</h2>
								De applicatie verwacht een CSV bestand met velden gescheiden door een <strong>;</strong> (puntcomma). De eerste regel van het bestand wordt overgeslagen. Regels die niet voldoen aan de opmaak of niet leeg mogen zijn worden overgeslagen.<br /><br />
								Na het importeren kan overige informatie worden ingevoerd via <a href="/relation">relaties</a>.
								</div>

							</div>

						</div>

						<div class="bs-callout text-center whiteBg">
							<h3>

								<form id="frm-save" action="import/save" method="post" enctype="multipart/form-data">
								{!! csrf_field() !!}
									<div class="form-group">
										<label for="image">Bestand Uploaden</label><br /><br />
										<div class="input-group col-md-12">
							                <span class="">
							                    <span class="btn btn-primary btn-lg btn-file">
							                        Selecteer bestand <input name="csvfile" type="file" multiple>
							                    </span>
							                    <i class="fa fa-arrow-right" aria-hidden="true"></i>
							                    <input type="submit" class="btn btn-primary btn-lg" value="Importeer" />
							                </span>
							            </div>
						            </div>
					            </form>

							</h3>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div> 

</div>
@stop
