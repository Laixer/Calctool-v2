<?php

namespace CalculatieTool\Http\Controllers;

use Illuminate\Http\Request;

class GuestController extends Controller {

    /**
     * Display a listing of the resource.
     * GET /relation
     *
     * @return Response
     */

    public function getAbout(Request $request)
    {
        return view('generic.about');
    }

}
