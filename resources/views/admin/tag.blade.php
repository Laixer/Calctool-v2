@extends('layout.master')

@section('title', 'Tags')
@section('content')
<div id="wrapper">

    <section class="container">
        <div class="col-md-12">

            <div>
            <ol class="breadcrumb">
              <li><a href="/">Dashboard</a></li>
              <li><a href="/admin">Admin CP</a></li>
              <li><a href="/admin/user">Gebruikers</a></li>
              <li class="active">Gebruikerstags</li>
            </ol>
            <div>
            <br />

            <h2><strong>Gebruikerstags</strong></h2>

            <div class="white-row">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="col-md-1 hidden-sm hidden-xs">ID</th>
                        <th class="col-md-3">Naam</th>
                        <th class="col-md-3">Gebruikers</th>
                        <th class="col-md-1"></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($selection = \BynqIO\Dynq\Models\UserTag::orderBy('id')->get() as $tag)
                    <tr>
                        <td class="col-md-1 hidden-sm hidden-xs">{{ $tag->id }}</td>
                        <td class="col-md-3">{{ $tag->name }}</td>
                        <td class="col-md-2">{{ \BynqIO\Dynq\Models\User::where('user_tag_id', $tag->id)->count() }}</td>
                        <td class="col-md-1">
                        @if (!\BynqIO\Dynq\Models\User::where('user_tag_id', $tag->id)->count())
                        <a class="btn btn-xs btn-danger" href="/admin/user/tag-{{ $tag->id }}/delete">Verwijderen</a>
                        @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-12">
                    <a href="/admin/user/tags/new" class="btn btn-primary"><i class="fa fa-pencil"></i> Nieuwe tag</a>
                </div>
            </div>
            </div>
        </div>

    </section>

</div>
@stop
