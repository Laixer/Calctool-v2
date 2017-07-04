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

use Carbon\Carbon;
use BynqIO\Dynq\Models\Invoice;
use BynqIO\Dynq\Models\DeliverTime;
use BynqIO\Dynq\Models\Valid;
use BynqIO\Dynq\Models\Relation;
use BynqIO\Dynq\Models\Contact;
use BynqIO\Dynq\ProjectManager\Contracts\Component;

use Encryptor;
use PDF;

/**
 * Class InvoiceReportComponent.
 */
class InvoiceReportComponent extends BaseComponent implements Component
{
    public function render()
    {
        $invoice = Invoice::findOrFail($this->request->get('id'));
        $relation = Relation::findOrFail($this->project->client_id);
        $relation_self = Relation::findOrFail($this->request->user()->self_id);

        $logo = null;
        if ($relation_self->logo) {
            $logo = Encryptor::base64($relation_self->logo->file_location);
        }

        $data = [
            'logo' => $logo,
            'company' => $relation_self->name(),
            'address' => $relation_self->fullAddress(),
            'phone' => $relation_self->phone_number,
            'email' => $relation_self->email,
            'overlay' => 'concept',
        ];

        $letter = [
            'document'         => 'Factuur',
            'document_number'  => $invoice->invoice_code,
            'document_date'    => Carbon::parse($this->request->get('date')),
            'project'          => $this->project,
            'relation'         => $relation,
            'relation_self'    => $relation_self,
            'contact_to'       => Contact::find($this->request->get('contact_to')),
            'contact_from'     => Contact::find($this->request->get('contact_from')),
            'reference'        => $this->request->get('our_reference'),
            'client_reference' => $this->request->get('client_reference'),
            'pretext'          => $this->request->get('pretext'),
            'posttext'         => $this->request->get('posttext'),
            'messages'         => ["Gaarne bij betaling factuurnummer vermelden."],
        ];

        $amount      = $this->request->get('amount');
        $condition   = $this->request->get('condition');
        $conditions  = $this->request->get('conditions');

        /* Delivery options */
        // if ($deliver == 1 || $deliver == 2) {
        //     $letter['messages'][] = "De werkzaamheden starten na uw opdrachtbevestiging.";
        // } else if (isset($deliver)) {
        //     $name = DeliverTime::findOrFail($deliver)->delivertime_name;
        //     $letter['messages'][] = "De werkzaamheden starten binnen $name.";
        // }

        /* Valid options */
        if (isset($condition)) {
            $letter['messages'][] = "Deze factuur dient betaald te worden binnen $condition dag(en) na dagtekening.";
        }

        /* Extra conditions */
        if (isset($conditions)) {
            foreach(explode(PHP_EOL, $conditions) as $condition) {
                $letter['messages'][] = $condition;
            }
        }

        if ($this->request->has('separate_subcon')) {
            $data['separate_subcon'] = true;
        }

        if ($invoice->isclose) {
            $data['pages'][] = 'total';
        } else {
            $data['pages'][] = 'term';
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

        $pdf = PDF::loadView('letter', array_merge($data, $letter));
        $pdf->setOption('footer-font-size', 8);
        $pdf->setOption('footer-left', $relation_self->name());
        $pdf->setOption('footer-right', 'Pagina [page]/[toPage]');
        $pdf->setOption('encoding', 'utf-8');
        $pdf->setOption('lowquality', false);

        return $pdf->inline();
    }
}
