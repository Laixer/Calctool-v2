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

use BynqIO\Dynq\Models\Invoice;
use BynqIO\Dynq\ProjectManager\Contracts\Component;

/**
 * Class InvoiceDetailComponent.
 */
class InvoiceDetailComponent extends BaseComponent implements Component
{
    protected $url;

    protected function buildUrl()
    {
        $this->url = "/project/{$this->project->id}-{$this->project->slug()}/invoices/report?" . $this->request->getQueryString();
    }

    public function render()
    {
        $this->buildUrl();

        $invoice = Invoice::findOrFail($this->request->get('id'));

        /* Show the report in the viewer */
        if ($invoice->invoice_close) {
            $data['url'] = "/resource/{$invoice->resource_id}/view/invoice.pdf";
            return view('component.reportviewer', $data);
        }

        return $this->builderLayout($this->url, 'pdfoptions', compact('invoice'));
    }
}
