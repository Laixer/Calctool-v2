@extends('mail.layout.page')

@section('title', 'Automatische incasso gestopt')

@section('page')
@include('mail.layout.elements.pretext', ['text' => [
    'De automatische incasso <strong>' . $subscription . '</strong> is door gebruiker <strong>' . $username . '</strong> gestopt.',
]])
@stop
