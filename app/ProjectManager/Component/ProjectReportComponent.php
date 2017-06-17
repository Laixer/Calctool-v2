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
use BynqIO\Dynq\Models\DeliverTime;
use BynqIO\Dynq\Models\Valid;
use BynqIO\Dynq\Models\Relation;
use BynqIO\Dynq\Models\Contact;
use BynqIO\Dynq\ProjectManager\Contracts\Component;

use Encryptor;
use PDF;

/**
 * Class ProjectReportComponent.
 */
class ProjectReportComponent extends BaseComponent implements Component
{
    public function render()
    {
        $relation_self = Relation::findOrFail($this->request->user()->self_id);
        $document_number = sprintf("%s%05d-%03d-%s", $this->request->user()->offernumber_prefix, $this->project->id, $this->request->user()->offer_counter, date('y'));

        $logo = null;
        if ($relation_self->logo) {
            $logo = Encryptor::base64($relation_self->logo->file_location);
        }

        $data = [
            'logo'     => $logo,
            'company'  => $relation_self->name(),
            'address'  => $relation_self->fullAddress(),
            'phone'    => $relation_self->phone_number,
            'email'    => $relation_self->email,
            'pages'    => ['main'],
        ];

        $letter = [
            'document'         => 'Projectoverzicht',
            'document_date'    => Carbon::now(),
            'project'          => $this->project,
            'relation_self'    => $relation_self,
            'reference'        => $this->project->id,
        ];

        $data['pages'][] = 'overview_contracting';

        $pdf = PDF::loadView('letter', array_merge($data, $letter));
        $pdf->setOption('footer-font-size', 8);
        $pdf->setOption('footer-left', $relation_self->name());
        $pdf->setOption('footer-right', 'Pagina [page]/[toPage]');
        $pdf->setOption('encoding', 'utf-8');
        $pdf->setOption('lowquality', false);

        return $pdf->inline();
    }
}
