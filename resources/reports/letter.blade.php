{{--
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the Dynq project.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 *
 * @package  Dynq
 * @author   Yorick de Wid <y.dewid@calculatietool.com>
--}}

@extends('report')

@section('footer')
<div>
    @isset($relation_self->kvk)
    KVK: {{ $relation_self->kvk }} <span class="divider">|</span>
    @endisset
    @isset($relation_self->btw)
    BTW: {{ $relation_self->btw }} <span class="divider">|</span>
    @endisset
    IBAN: {{ $relation_self->iban }}
    TNV: {{ $relation_self->iban_name }}
</div>
@endsection

@section('topright')
<h1>OFFERTE {{ $document_number }}</h1>
<div class="date">Project: {{ $project->project_name }}</div>
<div class="date">Date of Invoice: 01/06/2014</div>
<div class="date">Due Date: 30/06/2014</div>
@isset($reference)
<div class="date">Reference: {{ $reference }}</div>
@endisset
@endsection

@section('topleft')
@isset($contact_to)
<div class="to">FACTUUR AAN:</div>
<h2 class="name">{{ $contact_to->getFormalName() }}</h2>
<div class="address">{{ $relation->address_street . ' ' . $relation->address_number . ', ' . $relation->address_postal . ', ' . $relation->address_city }}</div>
<div class="email"><a href="mailto:{{ $contact_to->email }}">{{ $contact_to->email }}</a></div>
<div class="address">Klant Nummer: {{ $relation->debtor_code }}</div>
<div class="address">BTW Nummer: {{ $relation->btw }}</div>
@endisset
@endsection


{{-- First page --}}

@section('body_main')
<div id="details" class="clearfix">
    <div id="client">@yield('topleft')</div>
    <div id="invoice">@yield('topright')</div>
</div>

@isset($pretext)
@isset($contact_to)
<div style="font-size: 16px;padding-bottom: 10px;">Geachte {{ $contact_to->getFormalName() }},</div>
@endisset
<div style="font-size: 16px;padding-bottom: 30px;">{{ $pretext }}</div>
@endisset

@include('partials.total')

@isset($posttext)
<div style="font-size: 16px;padding-bottom: 10px;">{{ $posttext }}</div>
@isset($contact_from)
<div style="font-size: 16px;padding-bottom: 30px;">Met vriendelijke groet, <br />{{ $contact_from->getFormalName() }}</div>
@endisset
@endisset

@isset($messages)
<h4>Opmerkingen</h4>
<div id="notices">
    @foreach($messages as $message)
    <div class="notice">&#8226; {{ $message }}</div>
    @endforeach
</div>
@endisset

@endsection


{{-- Additional pages --}}

@foreach(array_slice($pages, 1) as $page)
@section("body_$page")
<div id="details" class="clearfix">
    <div id="invoice">@yield('topright')</div>
</div>

@include("partials.$page")

@endsection
@endforeach
