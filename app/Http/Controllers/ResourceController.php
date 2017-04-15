<?php

namespace CalculatieTool\Http\Controllers;

use Illuminate\Http\Request;
use \CalculatieTool\Models\Resource;

use \Auth;
use \Storage;

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

        $storagePath = Storage::getDriver()->getAdapter()->getPathPrefix();

        return response()->download($storagePath . $res->file_location);
    }

    public function view(Request $request, $resourceid)
    {
        $res = Resource::find($resourceid);
        if (!$res || !$res->isOwner()) {
            return response(null, 404);
        }

        $storagePath = Storage::getDriver()->getAdapter()->getPathPrefix();

        return response()->file($storagePath . $res->file_location);
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
