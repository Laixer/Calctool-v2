@extends('mail.layout.page')

@section('title', 'Feedback/Vraag/Suggestie')

@section('page')
@include('mail.layout.elements.pretext', ['text' => [
    'Er is feedback/vraag/suggestie binnengekomen van een gebruiker <strong>' . $username .'</strong>',
    '<strong>Categorie: ' . $category .'</strong>',
    '<strong>Onderwerp: ' . $subject . '</strong>',
    '<strong>Bericht: </strong><br />' . $message,
]])
@stop
