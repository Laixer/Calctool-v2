<?php

use BynqIO\CalculatieTool\Models\Relation;
use BynqIO\CalculatieTool\Models\Contact;
use BynqIO\CalculatieTool\Models\RelationKind;

?>

<div class="form-group">
    <div class="col-md-9">
        <form method="POST" action="/project/update/communication" accept-charset="UTF-8">
            {!! csrf_field() !!}
            <input type="hidden" name="project" value="{{ $project->id }}"/>

            <h5><strong>Vraag opmerkingen van je opdrachtgever </strong><a data-toggle="tooltip" data-placement="bottom" data-original-title="Alleen mogelijk wanneer een offerte verzonden is per e-mail op de offerte pagina." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></h5>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="white-row well">
                            {!!  $share ? $share->client_note : ''!!}
                        </div>
                    </div>
                </div>
            </div>
            <h5><strong>Jouw reactie</strong></h5>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <textarea name="user_note" id="user_note" rows="10" class="summernote form-control">{{ $share ? $share->user_note : ''}}</textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary"><i class="fa fa-check"></i> Verzenden</button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-3">
        <div class="row">
            <h5><strong>Gegevens van uw relatie</strong></h5>
        </div>
        <div class="row">
            <label>Opdrachtgever </label>
            <?php $relation = Relation::find($project->client_id); ?>
            @if (!$relation->isActive())
            <span> {{ RelationKind::find($relation->kind_id)->kind_name == 'zakelijk' ? ucwords($relation->company_name) : (Contact::where('relation_id','=',$relation->id)->first()['firstname'].' '.Contact::where('relation_id','=',$relation->id)->first()['lastname']) }}</span>
            @else
            <span> {{ RelationKind::find($relation->kind_id)->kind_name == 'zakelijk' ? ucwords($relation->company_name) : (Contact::where('relation_id','=',$relation->id)->first()['firstname'].' '.Contact::where('relation_id','=',$relation->id)->first()['lastname']) }}</span>
            @endif
        </div>
        <div class="row">
            <label for="name">Straat</label>
            <span>{{ $relation->address_street }} {{ $relation->address_number }}</span>
        </div>
        <div class="row">
            <label for="name">Postcode</label>
            <span>{{ $relation->address_postal }}</span>
        </div>
        <div class="row">
            <label for="name">Plaats</label>
            <span>{{ $relation->address_city }}</span>
        </div>

        <?php
        $contact=Contact::where('relation_id',$relation->id)->first();
        ?>
        <div class="row">
            <label for="name">Contactpersoon</label>
            <span>{{ $contact->getFormalName() }}</span>
        </div>
        <div class="row">
            <label for="name">Telefoon</label>
            <span>{{ $contact->mobile }}</span>
        </div>
    </div>
</div>
