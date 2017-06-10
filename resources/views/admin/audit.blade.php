@extends('layout.master')

@section('title', 'Audit log')

@section('content')
<div id="wrapper">

    <section class="container">

        <div class="col-md-12">

            <div>
                <ol class="breadcrumb">
                    <li><a href="/">Dashboard</a></li>
                    <li><a href="/admin">Admin CP</a></li>
                    <li class="active">Auditlog</li>
                </ol>
                <div>

                    @if (Session::has('success'))
                    <div class="alert alert-success">
                        <i class="fa fa-check-circle"></i>
                        <strong>{{ Session::get('success') }}</strong>
                    </div>
                    @endif

                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <i class="fa fa-frown-o"></i>
                        <strong>Fout</strong>
                        @foreach ($errors->all() as $error)
                        {{ $error }}
                        @endforeach
                    </div>
                    @endif

                    <h2><strong>Auditlog</strong> gebruikers</h2>

                    <div class="white-row">

                        <h4>Meest recente event bovenaan</h4>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="col-md-2 hidden-sm hidden-xs">Timestamp</th>
                                    <th class="col-md-2 hidden-sm hidden-xs">Gebruiker</th>
                                    <th class="col-md-2 hidden-sm hidden-xs">IP</th>
                                    <th class="col-md-6">Event</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($records as $rec)
                                <tr>
                                    <td class="col-md-2 hidden-sm hidden-xs">{{ $rec->created_at->toDateTimeString() }}</td>
                                    <td class="col-md-2 hidden-sm hidden-xs"><a href="/admin/user-{{ $rec->user_id }}/edit">{{ \BynqIO\Dynq\Models\User::find($rec->user_id)->username }}</a></td>
                                    <td class="col-md-2 hidden-sm hidden-xs">{{ $rec->ip }}</td>
                                    <td class="col-md-6">{!! nl2br($rec->event) !!}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-md-6">
                                @if ($page > 1)
                                <a href="?page={{ $page - 1 }}" class="btn btn-primary">&laquo; Vorige</a>
                                @endif
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="?page={{ $page + 1 }}" class="btn btn-primary">Volgende &raquo;</a>
                            </div>
                        </div>

                    </div>

                </section>

            </div>
@stop
