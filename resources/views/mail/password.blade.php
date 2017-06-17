@extends('mail.layout.page')

@section('title', 'Wachtwoord herstellen')

@section('page')
@include('mail.layout.elements.pretext', ['text' => [
    'Er is zojuist een verzoek ingediend om je wachtwoord te herstellen van je gebruikersaccount bij de <strong>CalculatieTool.com</strong>. Klik hieronder op <i>wachtwoord herstellen</i> om opnieuw toegang te krijgen tot account.',
]])

{{-- Button --}}
@include('mail.layout.elements.button', [
    'url'   => url('/auth/password/' . $token),
    'title' => 'Wachtwoord herstellen',
    'text'  => 'Wachtwoord herstellen'
])
{{-- /Button --}}

@include('mail.layout.elements.textblock', ['text' => [
    'Indien je geen verzoek hebt ingediend voor het resetten van je wachtwoord kan je deze mail beschouwen als niet verzonden.',
]])
@stop
