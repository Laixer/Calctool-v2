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

namespace BynqIO\Dynq\Http\Controllers;

use Illuminate\Http\Request;
use BynqIO\Dynq\Models\Resource;

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

    /**
     * Convert storage object to transferable file.
     *
     * @return Response
     */
    protected function fileTransfer($resource)
    {
        $date = date('D, d M Y H:i:s T', Storage::lastModified($resource->file_location));
        $size = Storage::size($resource->file_location);
        $type = Storage::mimeType($resource->file_location);

        return response(Storage::get($resource->file_location))
            ->header('Content-Type', $type)
            ->header('Accept-Ranges', 'bytes')
            ->header('Cache-Control', 'no-cache, private')
            ->header('Content-Length', $size)
            ->header('Last-Modified', $date);
    }

    public function download(Request $request, $resourceid)
    {
        $res = Resource::find($resourceid);
        if (!$res || !$res->isOwner()) {
            return response(null, 404);
        }

        return $this->fileTransfer($res)->header('Content-Disposition', 'attachment; filename="' . $res->resource_name . '"');
    }

    public function view(Request $request, $resourceid)
    {
        $res = Resource::find($resourceid);
        if (!$res || !$res->isOwner()) {
            return response(null, 404);
        }

        return $this->fileTransfer($res);
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
