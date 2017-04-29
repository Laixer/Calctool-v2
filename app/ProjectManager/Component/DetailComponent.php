<?php

/**
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the BynqIO\CalculatieTool.com.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 *
 * @package  CalculatieTool
 * @author   Yorick de Wid <y.dewid@calculatietool.com>
 */

namespace BynqIO\CalculatieTool\ProjectManager\Component;

use BynqIO\CalculatieTool\Models\ProjectShare;
use BynqIO\CalculatieTool\ProjectManager\Contracts\Component;

/**
 * Class DetailComponent.
 */
class DetailComponent extends BaseComponent implements Component
{
    public function render()
    {
        $data = [
            'tabs' => [
                ['name' => 'settings', 'title' => 'Projectgegevens', 'icon' => 'fa-info'],
                ['name' => 'options',  'title' => 'Opties',          'icon' => 'fa-sliders'],
            ]
        ];

        /* Hide some options for quick invoice */
        if ($this->type != 'quickinvoice') {
            array_push($data['tabs'], ['name' => 'financial', 'title' => 'Financieel', 'icon' => 'fa-percent']);
            array_push($data['tabs'], ['name' => 'documents', 'title' => 'Documenten', 'icon' => 'fa-cloud']);
        }

        /* Show communication when project is shared with customer */
        $share = ProjectShare::where('project_id', $this->project->id)->first();
        if ($share && $share->client_note) {
            array_push($data['tabs'], ['name' => 'communication', 'title' => 'Communicatie opdrachtgever', 'icon' => 'fa-comments']);
        }

        return view("component.tabs", $data);
    }
}
