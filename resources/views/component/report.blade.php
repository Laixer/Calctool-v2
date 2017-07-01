<?php

use BynqIO\Dynq\Models\Relation;
use BynqIO\Dynq\Models\Offer;
use BynqIO\Dynq\Models\Contact;
use BynqIO\Dynq\Models\Resource;
use BynqIO\Dynq\Http\Controllers\OfferController;

$relation = Relation::find($project->client_id);
$relation_self = Relation::find(Auth::user()->self_id);
if ($relation_self)
    $contact_self = Contact::where('relation_id', $relation_self->id);
$offer_last = Offer::where('project_id', $project->id)->orderBy('created_at', 'desc')->first();
?>

@extends('component.fullscreen', ['title' => $page])

@section('component_content')
<div class="white-row printable">

    {{-- Page header --}}
    <div class="row">
        <div class="col-sm-7">
            @if ($relation_self && $relation_self->logo_id)
            <img src="/resource/{{ $relation_self->logo_id }}/view/logo.img" width="150px" class="img-responsive" />
            @endif
        </div>
        <div class="col-sm-5">
            <h4><strong>{{ $relation_self->company_name }}</strong></h4>
            <p>
                <ul class="list-unstyled">
                    <li>{{ $relation_self->address_street . ' ' . $relation_self->address_number }}</li>
                    <li>{{ $relation_self->address_postal . ', ' . $relation_self->address_city }}</li>
                    <li>Telefoon: {{ $relation_self->phone }}</li>
                    <li>{{ $relation_self->email }}</li>
                    <li>KVK: {{ $relation_self->kvk }}</li>
                </ul>
            </p>
        </div>
    </div>
    {{-- /Page header --}}

    <hr class="margin-top10 margin-bottom10" />

    {{-- Addressing --}}
    <div class="row">
        <div class="col-sm-7">
            <ul class="list-unstyled">
                <li>{{ $relation->company_name }}</li>
                <li>T.a.v.
                @if ($offer_last && $offer_last->offer_finish)
                    {{ Contact::find($offer_last->to_contact_id)->getFormalName() }}
                    @else
                <select name="to_contact" id="to_contact">
                    @foreach (Contact::where('relation_id',$relation->id)->get() as $contact)
                    <option {{ $offer_last ? ($offer_last->to_contact_id==$contact->id ? 'selected' : '') : '' }} value="{{ $contact->id }}">{{ Contact::find($contact->id)->getFormalName() }}</option>
                    @endforeach
                </select>
                @endif
                </li>
                <li>{{ $relation->address_street . ' ' . $relation->address_number }}<br /> {{ $relation->address_postal . ', ' . $relation->address_city }}</li>
            </ul>
        </div>
    </div>
    {{-- /Addressing --}}

    {{-- Description --}}
    <div class="row margin-bottom10">
        <div class="col-sm-6">
        Geachte
        @if ($offer_last && $offer_last->offer_finish)
        {{ Contact::find($offer_last->to_contact_id)->getFormalName() }}
        @else
        <span id="adressing"></span>
        @endif
        ,
        </div>
    </div>
    <div class="row margin-bottom10">
        <div class="col-sm-12">
        @if ($offer_last && $offer_last->offer_finish)
        {{ $offer_last->description }}
        @else
        <textarea name="description" id="description" rows="5" maxlength="500" class="form-control">{{ ($offer_last ? $offer_last->description : Auth::user()->pref_offer_description) }}</textarea>
        @endif
        </div>
    </div>
    {{-- /Description --}}

    @yield('report_body')

    {{-- Page footer --}}
    <div class="row">
        <div class="col-sm-12">

            @if ($offer_last && $offer_last->offer_finish)
            {{ $offer_last->closure }}
            @else
            <textarea name="closure" id="closure" rows="5" class="form-control">{{ ($offer_last ? $offer_last->closure : Auth::user()->pref_closure_offer) }}</textarea>
            @endif

            <p>Met vriendelijke groet,
                <br>
                @if ($offer_last && $offer_last->offer_finish)
                {{ Contact::find($offer_last->from_contact_id)->firstname . ' ' . Contact::find($offer_last->from_contact_id)->lastname }}
                @else
                <select name="from_contact" id="from_contact">
                    @foreach (Contact::where('relation_id','=',$relation_self->id)->get() as $contact)
                    <option {{ $offer_last ? ($offer_last->from_contact_id==$contact->id ? 'selected' : '') : '' }} value="{{ $contact->id }}">{{ $contact->firstname . ' ' . $contact->lastname }}</option>
                    @endforeach
                </select>
                @endif
            </p>
        </div>
    </div>
    {{-- /Page footer --}}

</div>

@if (0)
<div class="white-row">

    {{-- Page header --}}
    <div class="row">
        <div class="col-sm-6">
            @if ($relation_self && $relation_self->logo_id)
            <img src="/resource/{{ $relation_self->logo_id }}/view/logo.img" class="img-responsive" />
            @endif
        </div>
        <div class="col-sm-6 text-right">
            <p>
                <h4><strong>{{ $project->project_name }}</strong></h4>
                <ul class="list-unstyled">
                    <li><strong>Offertedatum:</strong> <a href="#" class="offdate">Bewerk</a></li>
                    <li><strong>Offertenummer:</strong> {{ OfferController::getOfferCode($project->id) }}</li>
                </ul>
            </p>
            </div>
    </div>
    {{-- /Page header --}}

    <hr class="margin-top10 margin-bottom10" />

    {{-- << Concent here >> --}}

</div>

<div class="white-row">

    {{-- Page header --}}
    <div class="row">
        <div class="col-sm-6">
            @if ($relation_self && $relation_self->logo_id)
            <img src="/resource/{{ $relation_self->logo_id }}/view/logo.img" class="img-responsive" />
            @endif
        </div>
        <div class="col-sm-6 text-right">
            <p>
                <h4><strong>{{ $project->project_name }}</strong></h4>
                <ul class="list-unstyled">
                    <li><strong>Offertedatum:</strong> <a href="#" class="offdate">Bewerk</a></li>
                    <li><strong>Offertenummer:</strong> {{ OfferController::getOfferCode($project->id) }}</li>
                </ul>
            </p>
        </div>
    </div>
    {{-- /Page header --}}

    <hr class="margin-top10 margin-bottom10" />

    {{-- << Concent here >> --}}

</div>
@endif
@stop
