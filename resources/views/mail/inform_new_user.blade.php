@extends('mail.layout.page')

@section('title', 'Account activatie')

@section('page')
@include('mail.layout.elements.pretext', ['text' => [
    'Gebruiker heeft zich aangemeld met volgende gegevens:',
    '<strong>Gebruiker: ' . $username . '</strong>',
    '<strong>Email: ' . $email . '</strong>',
]])
@stop
