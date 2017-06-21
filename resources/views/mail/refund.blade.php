@extends('mail.layout.page')

@section('title', 'Terugstorting')

@section('page')
@include('mail.layout.elements.pretext', ['text' => [
    'Het volledige bedrag van <strong>' . $amount . '</strong> is teruggestort, uw account is hierop aangepast.',
]])
@stop
