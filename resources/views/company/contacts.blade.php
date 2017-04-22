@extends('company.layout')

@section('company_section_name', 'Contactpersonen')

@section('company_content')
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
            <td class="col-md-2"><a href="/relation-{{ $relation->id }}/contact-{{ $contact->id }}/edit">{{ $contact->lastname }}</a></td>
            <td class="col-md-2"><a href="/relation-{{ $relation->id }}/contact-{{ $contact->id }}/edit">{{ $contact->firstname }}</a></td>
            <td class="col-md-2">{{ $contact->contactFunction->function_name }}</td>
            <td class="col-md-2">{{ $contact->phone }}</td>
            <td class="col-md-2">{{ $contact->mobile }}</td>
            <td class="col-md-2"><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="row">
    <div class="col-md-2">
        <a href="{{ url('relation-' . $relation->id . '/contact/new') }}" class="btn btn-primary"><i class="fa fa-pencil"></i> Nieuw contact</a>
    </div>
</div>
@endsection
