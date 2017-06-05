@extends('layout.master')

@section('content')

@section('title', 'Audit log')

@push('style')
@endpush

@push('scripts')
@endpush

<?php
$allevents = false;
if (Input::get('allevents') == 1) {
    $allevents = true;
}
?>
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
                    <br />

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

                        <div class="pull-right">
                            @if ($allevents)
                            <a class="btn btn-primary" href="/admin/auditlog" >Laatste events</a>
                            @else
                            <a class="btn btn-primary" href="/admin/auditlog?allevents=1" >Alle events</a>
                            @endif
                        </div>

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
                                <?php
                                if ($allevents) {
                                    $selection = \BynqIO\Dynq\Models\Audit::orderBy('created_at','desc')->get();
                                } else {
                                    $selection = \BynqIO\Dynq\Models\Audit::orderBy('created_at','desc')->limit(50)->get();
                                }
                                ?>
                                @foreach ($selection as $rec)
                                <tr>
                                    <td class="col-md-2 hidden-sm hidden-xs">{{ date('d-m-Y H:i:s', strtotime(DB::table('audit')->select('created_at')->where('id',$rec->id)->get()[0]->created_at)) }}</td>
                                    <td class="col-md-2 hidden-sm hidden-xs"><a href="/admin/user-{{ $rec->user_id }}/edit">{{ \BynqIO\Dynq\Models\User::find($rec->user_id)->username }}</a></td>
                                    <td class="col-md-2 hidden-sm hidden-xs">{{ $rec->ip }}</td>
                                    <td class="col-md-6">{!! nl2br($rec->event) !!}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </section>

            </div>
@stop
