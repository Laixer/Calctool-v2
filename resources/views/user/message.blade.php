<?php
use \Calctool\Models\User;
use \Calctool\Models\MessageBox;

$common_access_error = false;
$message = MessageBox::find(Route::Input('message'));
if (!$message || !$message->isOwner()) {
	$common_access_error = true;
}
?>
@extends('layout.master')

<?php if($common_access_error){ ?>
@section('content')
<div id="wrapper">
	<section class="container">
		<div class="alert alert-danger">
			<i class="fa fa-frown-o"></i>
			<strong>Fout</strong>
			Dit bericht bestaat niet
		</div>
	</section>
</div>
@stop
<?php }else{ ?>

@section('content')

<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<div>
				<ol class="breadcrumb">
				  <li><a href="/">Home</a></li>
				  <li><a href="/messagebox">Notificaties</a></li>
				  <li class="active">{{ $message->subject }}</li>
				</ol>
			<div>
			<br>

			<div class="pull-right">
				<a href="/messagebox/message-{{ $message->id }}/delete" class="btn btn-primary">Verwijderen</a>
			</div>

			<h2><strong>{{ $message->subject }}</strong></h2>
			<div class="white-row">
				{!! $message->message !!}
			</div>
		</div>

	</section>

</div>
@stop

<?php } ?>