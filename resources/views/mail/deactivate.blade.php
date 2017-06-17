@extends('mail.layout.page')

@section('title', 'Account opgezegd')

@section('page')
@include('mail.layout.elements.pretext', ['text' => [
    'Je CalculatieTool.com account is zojuist <strong>gedeactiveerd</strong>. Mocht dit niet bedoeling zijn neem dan contact met ons op.',
]])
@stop
