@extends('mail.layout.page')

@section('title', 'Betalingsgegevens aangepast')

@section('page')
@include('mail.layout.elements.pretext', ['text' => [
    'Het IBAN rekeningnummer en/of de tenaamstelling is aangepast op <strong>CalculatieTool.com</strong>.',
    'Indien je geen wijzigingen hebt doorgevoerd in je betalingsgegevens adviseren wij dit te controleren of contact op te nemen met de <a href="http://www.calculatietool.com/over-ons/">helpdesk</strong></a>.'
]])
@stop
