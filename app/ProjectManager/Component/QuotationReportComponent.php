<?php

/**
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
 */

namespace BynqIO\Dynq\ProjectManager\Component;

use BynqIO\Dynq\Models\DeliverTime;
use BynqIO\Dynq\Models\Valid;
use BynqIO\Dynq\Models\Relation;
use BynqIO\Dynq\Models\Contact;
use BynqIO\Dynq\ProjectManager\Contracts\Component;

use PDF;

/**
 * Class QuotationReportComponent.
 */
class QuotationReportComponent extends BaseComponent implements Component
{
    public function render()
    {
        $logo = 'data:image/png;base64,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';

        $relation = Relation::findOrFail($this->project->client_id);
        $relation_self = Relation::findOrFail($this->request->user()->self_id);
        $document_number = sprintf("%s%05d-%03d-%s", $this->request->user()->offernumber_prefix, $this->project->id, $this->request->user()->offer_counter, date('y'));

        $data = [
            'logo' => $logo,
            'company' => $relation_self->name(),
            'address' => $relation_self->address_street . ' ' . $relation_self->address_number . ', ' . $relation_self->address_postal . ', ' . $relation_self->address_city,
            'phone' => $relation_self->phone_number,
            'email' => $relation_self->email,
            'overlay' => 'concept',
            'pages' => ['main'],
        ];

        $letter = [
            'document_number' => $document_number,
            'project' => $this->project,
            'relation' => $relation,
            'relation_self' => $relation_self,
            'contact_to' => Contact::find($this->request->get('contact_to')),
            'contact_from' => Contact::find($this->request->get('contact_from')),
            'reference' => '394969#11',
            'pretext' => 'Bij deze doe ik u toekomen mijn prijsopgaaf betreffende het uit te voeren werk. Onderstaand zal ik het werk en de uit te voeren werkzaamheden specificeren zoals afgesproken.',
            'posttext' => 'Hopende u hiermee een passende aanbieding gedaan te hebben, zie ik uw reactie met genoegen tegemoet.',
        ];

        $terms   = $this->request->get('terms');
        $amount  = $this->request->get('amount');
        $deliver = $this->request->get('deliver');
        $valid   = $this->request->get('valid');

        /* Terms and amount */
        if ($terms > 1 && $amount > 1) {
            $letter['messages'][] = "Indien opdracht gegund wordt, ontvangt u $terms termijnen waarvan de eerste termijn een aanbetaling betreft á € $amount";
        } else if ($terms > 1) {
            $letter['messages'][] = "Indien opdracht gegund wordt, ontvangt u $terms termijnen waarvan de laatste een eindfactuur.";
        } else {
            $letter['messages'][] = "Indien opdracht gegund wordt, ontvangt u één eindfactuur.";
        }

        /* Delivery options */
        if ($deliver == 1 || $deliver == 2) {
            $letter['messages'][] = "De werkzaamheden starten na uw opdrachtbevestiging.";
        } else if (isset($deliver)) {
            $name = DeliverTime::findOrFail($deliver)->delivertime_name;
            $letter['messages'][] = "De werkzaamheden starten binnen $name.";
        }

        /* Valid options */
        if (isset($valid)) {
            $name = Valid::findOrFail($valid)->valid_name;
            $letter['messages'][] = "Deze offerte is geldig tot $name na dagtekening.";
        }

        /* Additional pages */
        if ($this->request->has('display_specification')) {
            $data['pages'][] = 'specification';
        }
        if ($this->request->has('display_worktotals')) {
            $data['pages'][] = 'levelcost';
        }
        if ($this->request->has('display_description')) {
            $data['pages'][] = 'description';
        }

        // $data['pages'][] = 'appendix';

        $pdf = PDF::loadView('letter', array_merge($data, $letter));
        $pdf->setOption('footer-font-size', 8);
        $pdf->setOption('footer-left', $relation_self->name());
        $pdf->setOption('footer-right', 'Pagina [page]/[toPage]');
        $pdf->setOption('lowquality', false);

        return $pdf->inline();

        return view('letter', array_merge($data, $letter));
    }
}
