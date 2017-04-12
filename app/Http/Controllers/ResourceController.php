<?php

namespace CalculatieTool\Http\Controllers;

use Illuminate\Http\Request;
use \CalculatieTool\Models\Resource;

use \Auth;

class ResourceController extends Controller
{
    /**
     * Instantiate the dashboard controller.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('payzone')->only('doDeleteResource');

        //
    }

    public function download(Request $request, $resourceid)
    {
        $res = Resource::find($resourceid);
        if (!$res || !$res->isOwner()) {
            return response(null, 404);
        }

        return response()->download($res->file_location);
    }

    public function view(Request $request, $resourceid)
    {
        $res = Resource::find($resourceid);
        if (!$res || !$res->isOwner()) {
            return response(null, 404);
        }

        return response()->file($res->file_location);
    }

    public function doDeleteResource(Request $request, $resourceid)
    {
        $res = Resource::find($resourceid);
        if (!$res || !$res->isOwner()) {
            return back();
        }

        $res->delete();

        Audit::CreateEvent('resource.delete.success', 'Resource ' . $res->id . ' deleted');

        return back();
    }

}
