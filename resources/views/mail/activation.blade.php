@extends('mail.layout.page')

@section('title', 'Account activatie')

@section('page')
@include('mail.layout.elements.pretext', ['text' => [
    'Je bent zojuist succesvol geregistreerd voor een gebruikersaccount van de <strong>CalculatieTool.com</strong>. Klik hieronder op <i>Activeer account</i> om uw gratis account te activeren.',
]])

{{-- Button --}}
@include('mail.layout.elements.button', [
    'url'   => url('auth/confirm/' . $token),
    'title' => 'Activeer account',
    'text'  => 'Activeer account'
])
{{-- /Button --}}

@include('mail.layout.elements.textblock', ['text' => [
    'Je wordt na de bevestiging direct doorgestuurd naar de <strong>CalculatieTool.com</strong> en kunt dan 30 dagen lang gebruikmaken van alles wat de <strong>CalculatieTool.com</strong> te bieden heeft. Zonder beperkingen, afgeschermde delen of verborgen kosten. Wij geloven in simpel en transparant. De <strong>CalculatieTool.com</strong> is voor de zzp\'er die snel en gemakkelijk gestructureerde offertes en facturen wilt kunnen maken.',
]])
@stop
