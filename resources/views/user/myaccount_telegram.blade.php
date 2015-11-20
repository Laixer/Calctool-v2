<?php

use \Calctool\Models\Telegram;

$tgram = Telegram::where('user_id','=',Auth::id())->first();
if ($tgram) {
	$text = "Telegram messenger is momenteel gekoppeld aan dit account. Om Telegram toegang te weigeren kan de koppleling worden verbroken.";
} else {
	$text = "Telegram messenger is niet momenteel gekoppeld aan dit account. Om Telegram toegang te geven moet eerst de Teleram applicatie worden geinstalleerd.";
}
?>

@extends('layout.master')

@section('content')

<script type="text/javascript">
$(document).ready(function() {
	$("[name='toggle-alert']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
});
</script>

<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			@if(Session::get('success'))
			<div class="alert alert-success">
				<i class="fa fa-check-circle"></i>
				<strong>{{ Session::get('success') }}</strong>
			</div>
			@endif

			@if($errors->has())
			<div class="alert alert-danger">
				<i class="fa fa-frown-o"></i>
				<strong>Fout</strong>
				@foreach ($errors->all() as $error)
					{{ $error }}
				@endforeach
			</div>
			@endif

			<h2><strong>Telegram</strong></h2>

				<div class="white-row">

					<div class="pull-right">
						<img src="https://telegram.org/img/t_logo.png" heigh="150px" style="height: 150px;">
					</div>

					<form method="POST" action="/myaccount/telegram/update" accept-charset="UTF-8">
					{!! csrf_field() !!}

					<h4 class="company">Telegram koppeling</h4>
					<div class="row">
						<div class="col-md-6">
							<div>{{ $text }}
							Alerts kunnen worden ingeschakeld om accountwijzigingen te pushen naar Telegram.</div><br />
							<p>Klik <a target="blank" href="https://telegram.org/">hier</a> voor meer informatie over Telegram<br />
							Klik <a target="blank" href="https://telegram.me/calctool_bot">hier</a> voor de telegram bot</p>
						</div>
					</div>
					<div class="row">

						<div class="col-md-2">
							<div class="form-group">
								<label for="toggle-alert" style="display:block;">Ontvang alerts</label>
								<input name="toggle-alert" type="checkbox" {{ $tgram ? $tgram->alert ? 'checked' : '' : 'disabled' }}>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="uid">UID</label>
								<input name="uid" id="uid" type="text" readonly="readonly" value="{{ $tgram ? 'tg:'.$tgram->uid : '' }}" class="form-control"/>
							</div>
						</div>

					</div>

					<div class="row">
						<div class="col-md-12">
							<a href="/myaccount/telegram/unchain" class="btn btn-danger {{ $tgram ? '' : 'disabled' }} "><i class="fa fa-remove"></i> Ontkopellen</a>
							<button class="btn btn-primary {{ $tgram ? '' : 'disabled' }}"><i class="fa fa-check"></i> Opslaan</button>
						</div>
					</div>
				</form>

				</div>

		</div>

	</section>

</div>

@stop
