@extends('mail.layout.page')

@section('title', 'Account activatie')

@section('page')
@include('mail.layout.elements.pretext', ['text' => [
    'Gebruiker heeft zijn account gedeactiveerd.',
    '<strong>Gebruiker: ' . $username . '</strong>',
    '<strong>Email: ' . $email . '</strong>',
    '<strong>Reden: ' . $reason . '</strong>',
]])
@stop
