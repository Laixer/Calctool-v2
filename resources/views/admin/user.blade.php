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

<div id="wrapper">

    <section class="container">
        <div class="col-md-12">

            <div>
                <ol class="breadcrumb">
                    <li><a href="/">Dashboard</a></li>
                    <li><a href="/admin">Admin Dashboard</a></li>
                    <li class="active">Gebruikers</li>
                </ol>
            <div>

            <div class="pull-right">
                @if ($all)
                    <a class="btn btn-primary" href="/admin/user">Actieve gebruikers</a>
                @else
                    <a class="btn btn-primary" href="?all=1">Alle gebruikers</a>
                @endif

                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fa fa-ellipsis-h" aria-hidden="true"></span></button>
                    <ul class="dropdown-menu">
                        <li><a href="/admin/user/new">Nieuwe gebruiker</a></li>
                        <li><a href="/admin/user/tags">Tags</a></li>
                    </ul>
                </div>

            </div>

            <?php
            if ($all) {
                $selection_today = \BynqIO\Dynq\Models\User::whereRaw("DATE(online_at) = current_date")->orderBy('online_at','desc')->get();
                $selection_week = \BynqIO\Dynq\Models\User::whereRaw("DATE(online_at) < current_date")->whereRaw("\"online_at\" > NOW() - '1 week'::INTERVAL")->orderBy('online_at','desc')->get();
                $selection_other = \BynqIO\Dynq\Models\User::whereRaw("\"online_at\" < NOW() - '1 week'::INTERVAL")->orWhereNull('online_at')->orderBy('online_at','desc')->get();
            } else if (!empty($group)) {
                $selection_today = \BynqIO\Dynq\Models\User::where('user_group', $group)->whereRaw("DATE(online_at) = current_date")->orderBy('online_at','desc')->get();
                $selection_week = \BynqIO\Dynq\Models\User::where('user_group', $group)->whereRaw("DATE(online_at) < current_date")->whereRaw("\"online_at\" > NOW() - '1 week'::INTERVAL")->orderBy('online_at','desc')->get();
                $selection_other = \BynqIO\Dynq\Models\User::where('user_group', $group)->Where(function($query) {
                    $query->whereRaw("\"online_at\" < NOW() - '1 week'::INTERVAL")->orWhereNull('online_at');
                })->orderBy('online_at','desc')->get();
            } else {
                $selection_today = \BynqIO\Dynq\Models\User::where('active','true')->whereRaw("DATE(online_at) = current_date")->orderBy('online_at','desc')->get();
                $selection_week = \BynqIO\Dynq\Models\User::where('active','true')->whereRaw("DATE(online_at) < current_date")->whereRaw("\"online_at\" > NOW() - '1 week'::INTERVAL")->orderBy('online_at','desc')->get();
                $selection_other = \BynqIO\Dynq\Models\User::where('active','true')->Where(function($query) {
                    $query->whereRaw("\"online_at\" < NOW() - '1 week'::INTERVAL")->orWhereNull('online_at');
                })->where('login_count','>',1)->orderBy('online_at','desc')->get();
            }
            ?>

            <h2><strong>{{ ($all ? 'Alle' : ($group ? 'Groep' : 'Actieve')) }} gebruikers ({{ count($selection_today) + count($selection_week) + count($selection_other) }})</strong></h2>

            <div class="white-row">
            @if (count($selection_today))
            <h4>Vandaag ({{ count($selection_today) }})</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="col-md-4">Gebruikersnaam</th>
                        <th class="col-md-2">Actief</th>
                        <th class="col-md-2 hidden-sm hidden-xs">Status</th>
                        <th class="col-md-1 hidden-sm hidden-xs">Type</th>
                        <th class="col-md-2 hidden-sm hidden-xs">Groep</th>
                        <th class="col-md-1 hidden-xs">Tag</th>
                    </tr>
                </thead>

                <tbody>
                @foreach ($selection_today as $users)
                    <tr>
                        <td class="col-md-4"><a href="{{ '/admin/user-'.$users->id.'/edit' }}"><?php
                            echo $users->username;
                            if ($users->firstname != $users->username) {
                                echo ' (' . $users->firstname . ($users->lastname ? (', ' . $users->lastname) : '') . ')';
                            }
                        ?></a></td>
                        <td class="col-md-2">{{ $users->currentStatus() }}</td>
                        <td class="col-md-2 hidden-sm hidden-xs">
                        {{ userStatus($users) }}
                        @if ($users->isAdmin())
                        <i class="fa fa-bolt" aria-hidden="true"></i>
                        @elseif ($users->isTryPeriod())
                        <i class="fa fa-flask" aria-hidden="true"></i>
                        @elseif ($users->hasPayed())
                        <i class="fa fa-heart" aria-hidden="true"></i>
                        @else
                        <i class="fa fa-exclamation" aria-hidden="true"></i>
                        @endif
                        </td>
                        <td class="col-md-1 hidden-sm hidden-xs">{{ ucfirst(\BynqIO\Dynq\Models\UserType::find($users->user_type)->user_type) }}</td>
                        <td class="col-md-1 hidden-sm hidden-xs">{{ ucfirst(\BynqIO\Dynq\Models\UserGroup::find($users->user_group)->name) }}</td>
                        <td class="col-md-1 hidden-xs">{{ $users->tag ? $users->tag->name : '-' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @endif

            @if (count($selection_week))
            <h4>Deze week ({{ count($selection_week) }})</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="col-md-4">Gebruikersnaam</th>
                        <th class="col-md-2">Actief</th>
                        <th class="col-md-2 hidden-sm hidden-xs">Status</th>
                        <th class="col-md-1 hidden-sm hidden-xs">Type</th>
                        <th class="col-md-2 hidden-sm hidden-xs">Groep</th>
                        <th class="col-md-1 hidden-xs">Tag</th>
                    </tr>
                </thead>

                <tbody>
                @foreach ($selection_week as $users)
                    <tr>
                        <td class="col-md-4"><a href="{{ '/admin/user-'.$users->id.'/edit' }}"><?php
                            echo $users->username;
                            if ($users->firstname != $users->username) {
                                echo ' (' . $users->firstname . ($users->lastname ? (', ' . $users->lastname) : '') . ')';
                            }
                        ?></a></td>
                        <td class="col-md-2">{{ $users->currentStatus() }}</td>
                        <td class="col-md-2 hidden-sm hidden-xs">
                        {{ userStatus($users) }}
                        @if ($users->isAdmin())
                        <i class="fa fa-bolt" aria-hidden="true"></i>
                        @elseif ($users->isTryPeriod())
                        <i class="fa fa-flask" aria-hidden="true"></i>
                        @elseif ($users->hasPayed())
                        <i class="fa fa-heart" aria-hidden="true"></i>
                        @else
                        <i class="fa fa-exclamation" aria-hidden="true"></i>
                        @endif
                        </td>
                        <td class="col-md-1 hidden-sm hidden-xs">{{ ucfirst(\BynqIO\Dynq\Models\UserType::find($users->user_type)->user_type) }}</td>
                        <td class="col-md-1 hidden-sm hidden-xs">{{ ucfirst(\BynqIO\Dynq\Models\UserGroup::find($users->user_group)->name) }}</td>
                        <td class="col-md-1 hidden-xs">{{ $users->tag ? $users->tag->name : '-' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @endif

            @if (count($selection_other))
            <h4>Eerder ({{ count($selection_other) }})</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="col-md-4">Gebruikersnaam</th>
                        <th class="col-md-2">Actief</th>
                        <th class="col-md-2 hidden-sm hidden-xs">Status</th>
                        <th class="col-md-1 hidden-sm hidden-xs">Type</th>
                        <th class="col-md-2 hidden-sm hidden-xs">Groep</th>
                        <th class="col-md-1 hidden-xs">Tag</th>
                    </tr>
                </thead>

                <tbody>
                @foreach ($selection_other as $users)
                    <tr>
                        <td class="col-md-4"><a href="{{ '/admin/user-'.$users->id.'/edit' }}"><?php
                            echo $users->username;
                            if ($users->firstname != $users->username) {
                                echo ' (' . $users->firstname . ($users->lastname ? (', ' . $users->lastname) : '') . ')';
                            }
                        ?></a></td>
                        <td class="col-md-2">{{ $users->currentStatus() }}</td>
                        <td class="col-md-2 hidden-sm hidden-xs">
                        {{ userStatus($users) }}
                        @if ($users->isAdmin())
                        <i class="fa fa-bolt" aria-hidden="true"></i>
                        @elseif ($users->isTryPeriod())
                        <i class="fa fa-flask" aria-hidden="true"></i>
                        @elseif ($users->hasPayed())
                        <i class="fa fa-heart" aria-hidden="true"></i>
                        @else
                        <i class="fa fa-exclamation" aria-hidden="true"></i>
                        @endif
                        </td>
                        <td class="col-md-1 hidden-sm hidden-xs">{{ ucfirst(\BynqIO\Dynq\Models\UserType::find($users->user_type)->user_type) }}</td>
                        <td class="col-md-1 hidden-sm hidden-xs">{{ ucfirst(\BynqIO\Dynq\Models\UserGroup::find($users->user_group)->name) }}</td>
                        <td class="col-md-1 hidden-xs">{{ $users->tag ? $users->tag->name : '-' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @endif

            </div>
        </div>

    </section>

</div>
@stop
