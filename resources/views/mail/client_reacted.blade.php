@extends('mail.layout.page')

@section('title', 'Account activatie')

@section('page')
@include('mail.layout.elements.pretext', ['text' => [
    'Uw opdrachtgever voor project ' . $project_name . ' heeft gereageerd.',
    'De reactie van uw opdrachtgever:',
    $note,
]])

@include('mail.layout.elements.textblock', ['text' => [
    'Login op uw Calculatietool.com account om een reactie te plaatsen.',
]])
@stop
