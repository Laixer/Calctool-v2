@extends('mail.layout.page')

@section('title', 'Notificatie')

@section('page')
@include('mail.layout.elements.pretext', ['text' => [
    'De volgende notificatie kwam binnen op uw account:',
    $body
]])
@stop
