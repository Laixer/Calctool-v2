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

use BynqIO\Dynq\Models\Offer;
use BynqIO\Dynq\ProjectManager\Contracts\Component;

/**
 * Class QuotationDetailComponent.
 */
class QuotationDetailComponent extends BaseComponent implements Component
{
    protected $url;

    protected function buildUrl($id)
    {
        $this->url = "/res-{$id}/view";
    }

    public function render()
    {
        $offer = Offer::findOrFail($this->request->get('id'));

        $this->buildUrl($offer->resource_id);

        /* Show the report in the viewer */
        $data['url'] = $this->url;
        return view('component.reportviewer', $data);
    }
}
