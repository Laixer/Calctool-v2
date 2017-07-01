@extends('relation.layout', ['page' => 'contacts'])

@section('relation_section_name', 'Contactpersonen')

@section('relation_content')

<?php
use \BynqIO\Dynq\Models\Relation;
$relation = Relation::find(Route::Input('relation_id'));
?>

<div class="white-row">
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="col-md-2">Achternaam</th>
                <th class="col-md-2">Voornaam</th>
                <th class="col-md-2">Functie</th>
                <th class="col-md-2">Telefoon</th>
                <th class="col-md-2">Mobiel</th>
                <th class="col-md-2">Email</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($relation->contacts as $contact)
            <tr>
                <td class="col-md-2"><a href="/relation/{{ $relation->id }}-{{ $relation->slug() }}/contact-{{ $contact->id }}/edit">{{ $contact->lastname }}</a></td>
                <td class="col-md-2">{{ $contact->firstname }}</a></td>
                <td class="col-md-2">{{ ucfirst($contact->contactFunction->function_name) }}</td>
                <td class="col-md-2">{{ $contact->phone }}</td>
                <td class="col-md-2">{{ $contact->mobile }}</td>
                <td class="col-md-2"><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="row">
        <div class="col-md-12">
            <a href="/relation/{{ $relation->id }}-{{ $relation->slug() }}/contact/new" class="btn btn-primary"><i class="fa fa-pencil"></i> Nieuw contact</a>
        </div>
    </div>
</div>
@endsection
