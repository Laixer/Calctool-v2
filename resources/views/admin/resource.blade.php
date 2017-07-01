<?php
use \BynqIO\Dynq\Models\Resource;
use \BynqIO\Dynq\Models\User;
use \BynqIO\Dynq\Models\Project;
?>

@extends('layout.master')

@section('title', 'Bestandsbeheer')

@section('content')
<div id="wrapper">

    <section class="container">

        <div class="col-md-12">

            <div>
            <ol class="breadcrumb">
              <li><a href="/">Dashboard</a></li>
              <li><a href="/admin">Admin Dashboard</a></li>
              <li class="active">Bestandsbeheer</li>
            </ol>
            <div>

            <h2><strong>Bestandsbeheer</strong></h2>

            <div class="white-row">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="col-md-3">Omschrijving</th>
                        <th class="col-md-2">Grootte</th>
                        <th class="col-md-2">Gebruiker</th>
                        <th class="col-md-2">Project</th>
                        <th class="col-md-2">Aangemaakt</th>
                        <th class="col-md-1"></th>
                    </tr>
                </thead>

                <tbody>
                @foreach (Resource::where('unlinked',false)->orderBy('created_at','desc')->limit(50)->get() as $resource)
                    <tr data-id="{{ $resource->id}}">
                        <td class="col-md-3"><a target="blank" href="/resource/{{ $resource->id }}/view/object">{{ $resource->description }} <i class="fa fa-external-link" aria-hidden="true"></i></a></td>
                        <td class="col-md-2">{{ $resource->file_size }}</td>
                        <td class="col-md-2">{{ ucfirst(User::find($resource->user_id)->username) }}</td>
                        <td class="col-md-2">{{ $resource->project_id ? Project::find($resource->project_id)->project_name : 'Geen' }}</td>
                        <td class="col-md-2">{{ date('d-m-Y H:i:s', strtotime(DB::table('resource')->select('created_at')->where('id',$resource->id)->get()[0]->created_at)) }}</td>
                        <td class="col-md-1"></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>
        </div>

    </section>

</div>
@stop
