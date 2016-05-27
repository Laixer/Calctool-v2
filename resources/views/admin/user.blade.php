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
$all = false;
if (Input::get('all') == 1) {
	$all = true;
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
				@if ($all)
					<a class="btn btn-primary" href="/admin/user">Actieve gebruikers</a>
				@else
					<a class="btn btn-primary" href="?all=1">Alle gebruikers</a>
				@endif
			</div>

			<h2><strong>{{ ($all ? 'Alle' : 'Actieve') }} gebruikers</strong></h2>

			<div class="white-row">
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-1">ID</th>
						<th class="col-md-3">Gebruikersnaam</th>
						<th class="col-md-2">Actief</th>
						<th class="col-md-3">Email</th>
						<th class="col-md-1">Status</th>
						<th class="col-md-1">Type</th>
					</tr>
				</thead>

				<tbody>
				<?php
				if ($all) {
					$selection = \Calctool\Models\User::orderBy('updated_at','desc')->get();
				} else {
					$selection = \Calctool\Models\User::where('active','=','true')->orderBy('updated_at','desc')->get();
				}
				?>
				@foreach ($selection as $users)
					<tr>
						<td class="col-md-1"><a href="{{ '/admin/user-'.$users->id.'/edit' }}">{{ $users->id }}</a></td>
						<td class="col-md-3"><a href="{{ '/admin/user-'.$users->id.'/edit' }}"><?php
							echo $users->username;
							if ($users->firstname != $users->username) {
								echo ' (' . $users->firstname . ($users->lastname ? (', ' . $users->lastname) : '') . ')';
							}
						?></a></td>
						<td class="col-md-2">{{ userActive($users) }}</td>
						<td class="col-md-3">{{ $users->email }}</td>
						<td class="col-md-1">{{ userStatus($users) }}</td>
						<td class="col-md-1">{{ ucfirst(\Calctool\Models\UserType::find($users->user_type)->user_type) }}</td>
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
