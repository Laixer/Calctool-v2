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

use BynqIO\Dynq\Models\ProjectShare;
use BynqIO\Dynq\ProjectManager\Contracts\Component;

/**
 * Class DetailComponent.
 */
class DetailComponent extends BaseComponent implements Component
{
    public function render()
    {
        $tabs = [
            ['name' => 'overview', 'title' => 'Overzicht',       'icon' => 'fa-info'],
            ['name' => 'settings', 'title' => 'Projectgegevens',   'icon' => 'fa-map-marker'],
            ['name' => 'options',  'title' => 'Opties',          'icon' => 'fa-sliders'],
        ];

        /* Hide some options for quick invoice */
        if ($this->type != 'quickinvoice') {
            $tabs[] = ['name' => 'financial', 'title' => 'Financieel', 'icon' => 'fa-percent'];
            $tabs[] = ['name' => 'documents', 'title' => 'Documenten', 'icon' => 'fa-cloud'];
        }

        /* Show communication when project is shared with customer */
        $share = ProjectShare::where('project_id', $this->project->id)->first();
        if ($share && $share->client_note) {
            $tabs[] = ['name' => 'communication', 'title' => 'Communicatie opdrachtgever', 'icon' => 'fa-comments'];
        }

        return $this->tabLayout($tabs);
    }
}
