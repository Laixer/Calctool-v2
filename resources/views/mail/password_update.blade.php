@extends('mail.layout.page')

@section('title', 'Wachtwoord aangepast')

@section('page')
@include('mail.layout.elements.pretext', ['text' => [
    'Het wachtwoord van je account voor de <strong>CalculatieTool.com</strong> is aangepast.',
    'Indien je geen wijzigingen hebt doorgevoerd in je account adviseren wij dit te controleren of contact op te nemen met de <a href="http://www.calculatietool.com/over-ons/">helpdesk</strong></a>.'
]])
@stop