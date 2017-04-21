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

namespace BynqIO\CalculatieTool\Http\Controllers\Project;

use BynqIO\CalculatieTool\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function packingSlip(Request $request, $project_id)
    {
        $pdf = \PDF::loadView('calc.packslip_pdf', [
            'project_id' => $project_id,
            'relation_self' => $relation_self = Relation::find(Auth::user()->self_id),
            'list_id' => $project_id . date('Y') . mt_rand(10,99),
        ]);

        $pdf->setOption('zoom', 1.1);
        $pdf->setOption('footer-font-size', 8);
        $pdf->setOption('footer-left', Project::find($project_id)->project_name);
        $pdf->setOption('footer-right', 'Pagina [page]/[toPage]');
        $pdf->setOption('lowquality', false);

        return $pdf->inline();
    }

    // public function packList(Request $request, $project_id)
    // {
    //     $pdf = \PDF::loadView('user.packlist_pdf', [
    //         'project_id' => $project_id,
    //         'user_id' => Auth::id(),
    //         'relation_self' => $relation_self = Relation::find(Auth::user()->self_id),
    //         'list_id' => $project_id . date('Y') . mt_rand(10,99),
    //     ]);

    //     $pdf->setOption('zoom', 1.1);
    //     $pdf->setOption('footer-font-size', 8);
    //     $pdf->setOption('footer-left', Project::find($project_id)->project_name);
    //     $pdf->setOption('footer-right', 'Pagina [page]/[toPage]');
    //     $pdf->setOption('lowquality', false);

    //     return $pdf->inline();
    // }

    public function printOverview(Request $request, $project_id)
    {
        $pdf = \PDF::loadView('calc.print_overview_pdf', [
            'project_id' => $project_id,
            'relation_self' => $relation_self = Relation::find(Auth::user()->self_id),
        ]);

        $pdf->setOption('zoom', 1.1);
        $pdf->setOption('footer-font-size', 8);
        $pdf->setOption('footer-left', Project::find($project_id)->project_name);
        $pdf->setOption('footer-right', 'Pagina [page]/[toPage]');
        $pdf->setOption('lowquality', false);

        return $pdf->inline();
    }

}
