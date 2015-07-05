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
?>
<?# -- WRAPPER -- ?>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<h2><strong>Gebruikers</strong></h2>

			<table class="table table-striped">
				<?# -- table head -- ?>
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

				<!-- table items -->
				<tbody>
				@foreach (User::orderBy('created_at')->get() as $users)
					<tr>
						<td class="col-md-3">{{ HTML::link('/user-'.$users->id.'/edit', $users->username) . ' (' . $users->firstname . ($users->lastname ? (', ' . $users->lastname) : '') . ')' }}</td>
						<td class="col-md-2">{{ $users->ip }}</td>
						<td class="col-md-2">{{ $users->email }}</td>
						<td class="col-md-2">{{ userStatus($users) }}</td>
						<td class="col-md-1">{{ ucfirst(UserType::find($users->user_type)->user_type) }}</td>
						<td class="col-md-2">{{ date('d-m-Y H:i:s', strtotime(DB::table('user_account')->select('updated_at')->where('id','=',$users->id)->get()[0]->updated_at)) }}</td>
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

	</section>

</div>
<!-- /WRAPPER -->
@stop
