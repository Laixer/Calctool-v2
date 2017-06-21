@extends('mail.layout.page')

@section('title', 'Uw vakman heeft gereageerd')

@section('page')
@include('mail.layout.elements.pretext', ['text' => [
    'Uw vakman voor project <strong>' . $project_name . '</strong> heeft gereageerd.',
    'De reactie van uw vakman:',
    $note
]])

{{-- Button --}}
@include('mail.layout.elements.button', [
    'url'   => url('ex-project-overview/' . $token),
    'title' => 'Bekijk project',
    'text'  => 'Bekijk project'
])
{{-- /Button --}}

@include('mail.layout.elements.textblock', ['text' => [
    'Met vriendelijke groet,',
    $user
]])
@stop
