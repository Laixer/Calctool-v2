@extends('layout.master')

@section('content')

@section('title', 'Actieve gebruikers')

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
$all = false;
if (Input::get('all') == 1) {
	$all = true;
}

$group = null;
if (Input::has('group')) {
	$group = Input::get('group');
}
?>

<script type="text/javascript">
$(document).ready(function() {
	setInterval(function() {
		location.reload();
	}, 60000);
})
</script>
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
				<a href="/admin/user/new" class="btn btn-primary"><i class="fa fa-pencil"></i> Nieuwe gebruiker</a>
			</div>

			<?php
			if ($all) {
				$selection_today = \Calctool\Models\User::whereRaw("\"updated_at\" > NOW() - '1 day'::INTERVAL")->orderBy('updated_at','desc')->get();
				$selection_week = \Calctool\Models\User::whereRaw("\"updated_at\" < NOW() - '1 day'::INTERVAL")->whereRaw("\"updated_at\" > NOW() - '1 week'::INTERVAL")->orderBy('updated_at','desc')->get();
				$selection_other = \Calctool\Models\User::whereRaw("\"updated_at\" < NOW() - '1 week'::INTERVAL")->orderBy('updated_at','desc')->get();
			} else if (!empty($group)) {
				$selection_today = \Calctool\Models\User::where('user_group', $group)->whereRaw("\"updated_at\" > NOW() - '1 day'::INTERVAL")->orderBy('updated_at','desc')->get();
				$selection_week = \Calctool\Models\User::where('user_group', $group)->whereRaw("\"updated_at\" < NOW() - '1 day'::INTERVAL")->whereRaw("\"updated_at\" > NOW() - '1 week'::INTERVAL")->orderBy('updated_at','desc')->get();
				$selection_other = \Calctool\Models\User::where('user_group', $group)->whereRaw("\"updated_at\" < NOW() - '1 week'::INTERVAL")->orderBy('updated_at','desc')->get();
			} else {
				$selection_today = \Calctool\Models\User::where('active','true')->whereRaw("\"updated_at\" > NOW() - '1 day'::INTERVAL")->orderBy('updated_at','desc')->get();
				$selection_week = \Calctool\Models\User::where('active','true')->whereRaw("\"updated_at\" < NOW() - '1 day'::INTERVAL")->whereRaw("\"updated_at\" > NOW() - '1 week'::INTERVAL")->orderBy('updated_at','desc')->get();
				$selection_other = \Calctool\Models\User::where('active','true')->whereRaw("\"updated_at\" < NOW() - '1 week'::INTERVAL")->orderBy('updated_at','desc')->get();
			}
			?>

			<h2><strong>{{ ($all ? 'Alle' : ($group ? 'Groep' : 'Actieve')) }} gebruikers ({{ count($selection_today) + count($selection_week) + count($selection_other) }})</strong></h2>

			<div class="white-row">
			<h4>Vandaag</h4>
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-1 hidden-xs">ID</th>
						<th class="col-md-2">Gebruikersnaam</th>
						<th class="col-md-2">Actief</th>
						<th class="col-md-3 hidden-xs">Email</th>
						<th class="col-md-1 hidden-sm hidden-xs">Status</th>
						<th class="col-md-1 hidden-sm hidden-xs">Type</th>
						<th class="col-md-2 hidden-sm hidden-xs">Group</th>
					</tr>
				</thead>

				<tbody>
				@foreach ($selection_today as $users)
					<tr>
						<td class="col-md-1 hidden-xs"><a href="{{ '/admin/user-'.$users->id.'/edit' }}">{{ $users->id }}</a></td>
						<td class="col-md-2"><a href="{{ '/admin/user-'.$users->id.'/edit' }}"><?php
							echo $users->username;
							if ($users->firstname != $users->username) {
								echo ' (' . $users->firstname . ($users->lastname ? (', ' . $users->lastname) : '') . ')';
							}
						?></a></td>
						<td class="col-md-2">{{ $users->currentStatus() }}</td>
						<td class="col-md-3 hidden-xs">{{ $users->email }}</td>
						<td class="col-md-1 hidden-sm hidden-xs">{{ userStatus($users) }}</td>
						<td class="col-md-1 hidden-sm hidden-xs">{{ ucfirst(\Calctool\Models\UserType::find($users->user_type)->user_type) }}</td>
						<td class="col-md-1 hidden-sm hidden-xs">{{ ucfirst(\Calctool\Models\UserGroup::find($users->user_group)->name) }}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
			<h4>Deze week</h4>
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-1 hidden-xs">ID</th>
						<th class="col-md-2">Gebruikersnaam</th>
						<th class="col-md-2">Actief</th>
						<th class="col-md-3 hidden-xs">Email</th>
						<th class="col-md-1 hidden-sm hidden-xs">Status</th>
						<th class="col-md-1 hidden-sm hidden-xs">Type</th>
						<th class="col-md-2 hidden-sm hidden-xs">Group</th>
					</tr>
				</thead>

				<tbody>
				@foreach ($selection_week as $users)
					<tr>
						<td class="col-md-1 hidden-xs"><a href="{{ '/admin/user-'.$users->id.'/edit' }}">{{ $users->id }}</a></td>
						<td class="col-md-2"><a href="{{ '/admin/user-'.$users->id.'/edit' }}"><?php
							echo $users->username;
							if ($users->firstname != $users->username) {
								echo ' (' . $users->firstname . ($users->lastname ? (', ' . $users->lastname) : '') . ')';
							}
						?></a></td>
						<td class="col-md-2">{{ $users->currentStatus() }}</td>
						<td class="col-md-3 hidden-xs">{{ $users->email }}</td>
						<td class="col-md-1 hidden-sm hidden-xs">{{ userStatus($users) }}</td>
						<td class="col-md-1 hidden-sm hidden-xs">{{ ucfirst(\Calctool\Models\UserType::find($users->user_type)->user_type) }}</td>
						<td class="col-md-1 hidden-sm hidden-xs">{{ ucfirst(\Calctool\Models\UserGroup::find($users->user_group)->name) }}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
			<h4>Eerder</h4>
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-1 hidden-xs">ID</th>
						<th class="col-md-2">Gebruikersnaam</th>
						<th class="col-md-2">Actief</th>
						<th class="col-md-3 hidden-xs">Email</th>
						<th class="col-md-1 hidden-sm hidden-xs">Status</th>
						<th class="col-md-1 hidden-sm hidden-xs">Type</th>
						<th class="col-md-2 hidden-sm hidden-xs">Group</th>
					</tr>
				</thead>

				<tbody>
				@foreach ($selection_other as $users)
					<tr>
						<td class="col-md-1 hidden-xs"><a href="{{ '/admin/user-'.$users->id.'/edit' }}">{{ $users->id }}</a></td>
						<td class="col-md-2"><a href="{{ '/admin/user-'.$users->id.'/edit' }}"><?php
							echo $users->username;
							if ($users->firstname != $users->username) {
								echo ' (' . $users->firstname . ($users->lastname ? (', ' . $users->lastname) : '') . ')';
							}
						?></a></td>
						<td class="col-md-2">{{ $users->currentStatus() }}</td>
						<td class="col-md-3 hidden-xs">{{ $users->email }}</td>
						<td class="col-md-1 hidden-sm hidden-xs">{{ userStatus($users) }}</td>
						<td class="col-md-1 hidden-sm hidden-xs">{{ ucfirst(\Calctool\Models\UserType::find($users->user_type)->user_type) }}</td>
						<td class="col-md-1 hidden-sm hidden-xs">{{ ucfirst(\Calctool\Models\UserGroup::find($users->user_group)->name) }}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
			</div>
		</div>

	</section>

</div>
@stop
