@extends('layout.master')

@section('content')

<?php
function userStatus($user)
{
	if (!$user->confirmed_mail)
		return "Emailactivatie";
	if ($user->banned)
		return "Geblokkeerd";
	if ($user->active)
		return "Actief";
	return "Inactief";
}
function userActive($user) {
	$time_updated = strtotime(DB::table('user_account')->select('updated_at')->where('id','=',$user->id)->get()[0]->updated_at);
	if (time()-$time_updated < 120)
		return 'Online';
	if (time()-$time_updated < 3600)
		return floor((time()-$time_updated)/60) .' minuten geleden';
	if (time()-$time_updated < 43200)
		return floor((time()-$time_updated)/3600) .' uur geleden';
	return date('d-m-Y H:i:s', $time_updated);
}
?>
<div id="wrapper">

	<section class="container">
		<div class="col-md-12">

			<div>
			<ol class="breadcrumb">
			  <li><a href="/">Home</a></li>
			  <li><a href="/admin">Admin CP</a></li>
			  <li class="active">Gebruikers</li>
			</ol>
			<div>
			<br />

			<div class="pull-right">
				<a class="btn btn-primary" href="?all=1">Alle gebruikers</a>
			</div>

			<h2><strong>Actieve gebruikers</strong></h2>

			<div class="white-row">
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-3">Gebruikersnaam</th>
						<th class="col-md-2">IP</th>
						<th class="col-md-2">Email</th>
						<th class="col-md-2">Status</th>
						<th class="col-md-1">Type</th>
						<th class="col-md-2">Actief</th>
					</tr>
				</thead>

				<tbody>
				<?php
				if (Input::get('all') == 1) {
					$selection = User::orderBy('created_at')->get();
				} else {
					$selection = User::where('active','=','true')->orderBy('created_at')->get();
				}
				?>
				@foreach ($selection as $users)
					<tr>
						<td class="col-md-3">{{ (UserType::find($users->user_type)->user_type=='system') ? $users->username : HTML::link('/admin/user-'.$users->id.'/edit', $users->username) . ' (' . $users->firstname . ($users->lastname ? (', ' . $users->lastname) : '') . ')' }}</td>
						<td class="col-md-2">{{ $users->ip }}</td>
						<td class="col-md-2">{{ $users->email }}</td>
						<td class="col-md-2">{{ userStatus($users) }}</td>
						<td class="col-md-1">{{ ucfirst(UserType::find($users->user_type)->user_type) }}</td>
						<td class="col-md-2">{{ userActive($users) }}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
			<div class="row">
				<div class="col-md-12">
					<a href="/admin/user/new" class="btn btn-primary"><i class="fa fa-pencil"></i> Nieuwe gebruiker</a>
				</div>
			</div>
			</div>
		</div>

	</section>

</div>
@stop
