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

use BynqIO\Dynq\ProjectManager\Contracts\Component;
use Illuminate\Http\Request;

/**
 * Class QuotationComponent.
 */
class QuotationNewComponent extends BaseComponent implements Component
{
    protected $url;

    protected function buildUrl()
    {
        $this->url = "/project/{$this->project->id}-{$this->project->slug()}/quotations/report?" . $this->request->getQueryString();
    }

    public function render()
    {
        $this->buildUrl();

        return $this->builderLayout($this->url, 'pdfoptions');
    }
}
