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
use BynqIO\Dynq\Models\Audit;
use BynqIO\Dynq\Models\Resource;

use Auth;
use Encryptor;

class ResourceController extends Controller
{
    /**
     * Instantiate the dashboard controller.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('payzone')->only('delete');

        //
    }

    /**
     * Convert storage object to transferable file.
     *
     * @return Response
     */
    protected function fileTransfer($resource)
    {
        $date = date('D, d M Y H:i:s T', Encryptor::lastModified($resource->file_location));
        $size = Encryptor::size($resource->file_location);
        $type = Encryptor::mimeType($resource->file_location);

        return response(Encryptor::get($resource->file_location))
            ->header('Content-Type', $type)
            ->header('Accept-Ranges', 'bytes')
            ->header('Cache-Control', 'no-cache, private')
            ->header('Content-Length', $size)
            ->header('Last-Modified', $date);
    }

    public function download(Request $request, $resourceid)
    {
        $res = Resource::findOrFail($resourceid);
        if (!$res->isOwner()){
            return response(null, 403);
        }

        return $this->fileTransfer($res)->header('Content-Disposition', 'attachment; filename="' . $res->resource_name . '"');
    }

    public function view(Request $request, $resourceid)
    {
        $res = Resource::findOrFail($resourceid);
        if (!$res->isOwner()) {
            return response(null, 403);
        }

        return $this->fileTransfer($res);
    }

    public function delete(Request $request, $resourceid)
    {
        $res = Resource::findOrFail($resourceid);
        if (!$res->isOwner()) {
            return back();
        }

        $res->delete();

        Audit::CreateEvent('resource.delete.success', 'Resource ' . $res->id . ' deleted');

        return back();
    }

    // public function endpoint(Request $request)
    // {
    //     switch ($request->method()) {
    //         case 'GET':
    //             break;
    //         case 'POST':
    //             break;
    //         case 'PUT':
    //             break;
    //         case 'DELETE':
    //             break;
    //         default:
    //             return response(null, 405);
    //     }
    // }

}
