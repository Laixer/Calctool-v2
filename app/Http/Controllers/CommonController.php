<?php

namespace BynqIO\CalculatieTool\Http\Controllers;

class CommonController extends Controller
{
    /**
     * Display the help page.
     * GET /get-help
     *
     * @return Response
     */
    public function helpPage()
    {
        return view('base.get_help');
    }
}
