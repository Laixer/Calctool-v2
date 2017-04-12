<?php
use \CalculatieTool\Models\User;
?>

@extends('layout.master')

@section('title', 'Sessies')

@section('content')
<div id="wrapper">

    <section class="container">

        <div class="col-md-12">

            <div>
            <ol class="breadcrumb">
              <li><a href="/">Home</a></li>
              <li><a href="/admin">Admin CP</a></li>
              <li class="active">Sessies</li>
            </ol>
            <div>
            <br />

            <h2><strong>Sessies</strong></h2>

            <div class="white-row">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="col-md-2">Gebruiker</th>
                        <th class="col-md-2">Remote</th>
                        <th class="col-md-2">Instance</th>
                        <th class="col-md-2">Laatste Update</th>
                        <th class="col-md-1"></th>
                    </tr>
                </thead>

                <tbody>
                @foreach (\DB::table('sessions')->get() as $session)
                    <tr data-id="{{ $session->id }}">
                        <td class="col-md-2"><a href="/admin/user-{{ $session->user_id }}/edit">{{ $session->user_id ? User::findOrFail($session->user_id)->username : '-' }}</a></td>
                        <td class="col-md-2">{{ $session->ip_address }}</td>
                        <td class="col-md-2">{{ $session->instance }}</td>
                        <td class="col-md-2">{{ date('d-m-Y H:i:s', $session->last_activity) }}</td>
                        <td class="col-md-1"><a class="btn btn-xs btn-danger" href="/admin/session/{{ $session->id }}/kill">Verwijderen</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>
        </div>

    </section>

</div>
@stop
