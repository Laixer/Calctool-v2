@extends('mail.layout.page')

@section('title', 'Account verlengd')

@section('page')
@include('mail.layout.elements.pretext', ['text' => [
    'De betaling van <strong>' . $amount . '</strong> is in goede orde ontvangen en je account is verlengt tot <strong>' . $expdate . '</strong>.',
]])
@stop