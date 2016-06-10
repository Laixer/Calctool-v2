<?php
use \Calctool\Models\User;
use \Calctool\Models\MessageBox;
?>

@extends('layout.master')

@section('title', 'Notificaties')

@section('content')

<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<div>
				<ol class="breadcrumb">
				  <li><a href="/">Dashboard</a></li>
				  <li class="active">Notificaties</li>
				</ol>
			<div>
			<br>

			<h2><strong>Notificaties</strong></h2>
			<div class="white-row">

				<table class="table table-striped">
					<thead>
						<tr>
							<th class="col-md-2">Datum</th>
							<th class="col-md-2">Van</th>
							<th class="col-md-5">Betreft</th>
							<th class="col-md-2"></th>
							<th class="col-md-1">Acties</th>
						</tr>
					</thead>

					<tbody>
					@if (!MessageBox::where('user_id','=', Auth::id())->where('active', true)->count('id'))
					<tr>
						<td colspan="6" style="text-align: center;">Er zijn geen berichten</td>
					</tr>
					@endif
					@foreach (MessageBox::where('user_id','=', Auth::id())->where('active', true)->orderBy('created_at', 'desc')->get() as $message)
					@if (!$message->read)
						<tr>
							<td class="col-md-2"><a href="/messagebox/message-{{ $message->id }}"><strong>{{ date('d-m-Y', strtotime(DB::table('messagebox')->select(DB::raw('created_at'))->first()->created_at)) }}</strong></a></td>
							<td class="col-md-2"><strong>{{ User::find($message->from_user)->username }}</strong></td>
							<td class="col-md-5"><strong>{{ $message->subject }}</strong></td>
							<td class="col-md-2"></td>
							<td class="col-md-1">

							  <div class="btn-group" role="group">
							    <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							      Opties
							      <span class="caret"></span>
							    </button>
							    <ul class="dropdown-menu">
							      <li><a href="/messagebox/message-{{ $message->id }}/read">Gelezen</a></li>
							      <li><a href="/messagebox/message-{{ $message->id }}/delete">Verwijderen</a></li>
							    </ul>
						 	 </div>

							</td>
						</tr>
						@else
						<tr>
							<td class="col-md-2"><a href="/messagebox/message-{{ $message->id }}">{{ date('d-m-Y', strtotime(DB::table('messagebox')->select(DB::raw('created_at'))->first()->created_at)) }}</a></td>
							<td class="col-md-2">{{ User::find($message->from_user)->username }}</td>
							<td class="col-md-5">{{ $message->subject }}</td>
							<td class="col-md-2"></td>
							<td class="col-md-1">

							  <div class="btn-group" role="group">
							    <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							      Opties
							      <span class="caret"></span>
							    </button>
							    <ul class="dropdown-menu">
							      <li><a href="/messagebox/message-{{ $message->id }}/read">Gelezen</a></li>
							      <li><a href="/messagebox/message-{{ $message->id }}/delete">Verwijderen</a></li>
							    </ul>
						 	 </div>

							</td>
						</tr>						
						@endif
					@endforeach
					</tbody>
				</table>
			</div>
		</div>

	</section>

</div>
@stop
