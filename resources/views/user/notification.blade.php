<?php
use \BynqIO\Dynq\Models\User;
use \BynqIO\Dynq\Models\MessageBox;
?>

@extends('layout.master')

@section('title', 'Notificaties')

@section('content')
<div id="wrapper">

    <section class="container">

        <div class="col-md-12">

            <div>
                <ol class="breadcrumb">
                  <li><a href="/">Dashboard</a></li>
                  <li class="active">Notificaties</li>
                </ol>
            <div>
            <br>

            <h2><strong>Notificaties</strong></h2>
            <div class="white-row">

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="col-md-2">Datum</th>
                            <th class="col-md-2">Van</th>
                            <th class="col-md-5">Betreft</th>
                            <th class="col-md-2"></th>
                            <th class="col-md-1">&nbsp;</th>
                        </tr>
                    </thead>

                    <tbody>
                    @if (!MessageBox::where('user_id', Auth::id())->where('active', true)->count('id'))
                    <tr>
                        <td colspan="6" style="text-align: center;">Er zijn geen berichten</td>
                    </tr>
                    @endif
                    @foreach (MessageBox::where('user_id','=', Auth::id())->where('active', true)->orderBy('created_at', 'desc')->get() as $message)
                    @if (!$message->read)
                        <tr>
                            <td class="col-md-2"><strong>{{ $message->created_at->toDateString() }}</strong></td>
                            <td class="col-md-2"><strong>{{ User::find($message->from_user)->username }}</strong></td>
                            <td class="col-md-5"><a href="/notification/message-{{ $message->id }}"><strong>{{ $message->subject }}</strong></a></td>
                            <td class="col-md-2"></td>
                            <td class="col-md-1 text-right">
                                <a href="/notification/message-{{ $message->id }}/delete" class="btn btn-danger btn-xs fa fa-times" role="button"></a>
                            </td>
                        </tr>
                        @else
                        <tr>
                            <td class="col-md-2">{{ $message->created_at->toDateString() }}</td>
                            <td class="col-md-2">{{ User::find($message->from_user)->username }}</td>
                            <td class="col-md-5"><a href="/notification/message-{{ $message->id }}">{{ $message->subject }}</a></td>
                            <td class="col-md-2"></td>
                            <td class="col-md-1 text-right">
                                <a href="/notification/message-{{ $message->id }}/delete" class="btn btn-danger btn-xs fa fa-times" role="button"></a>
                            </td>
                        </tr>						
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </section>

</div>
@stop
