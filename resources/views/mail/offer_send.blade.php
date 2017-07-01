@extends('mail.layout.page')

@section('title', 'Offerte ' . $project_name)

@section('page')
@include('mail.layout.elements.pretext', ['text' => [Auth::User()->pref_email_offer]])

{{-- Button --}}
@isset($token)
@include('mail.layout.elements.button', [
    'url'   => url('auth/confirm/' . $token),
    'title' => $project_name,
    'text'  => $project_name
])
@endif
{{-- /Button --}}

@include('mail.layout.elements.textblock', ['text' => [
    'U wordt na het klikken op de link naar een beveiligde omgeving van de <strong>' . config('app.name') . '</strong> doorgestuurd.',
    'Hier kunt u opmerkingen achterlaten en eventueel direct de offerte goedkeuren en opdracht verstrekken.<br />',
    'Met vriendelijke groet,',
    $user
]])
@stop
