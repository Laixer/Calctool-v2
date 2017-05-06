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

namespace BynqIO\Dynq\Http\Controllers\Company;

use BynqIO\Dynq\Models\Relation;
use BynqIO\Dynq\Models\Resource;
use BynqIO\Dynq\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Image;
use Storage;

class UploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('reqcompany')->except('setupCompany');

        //
    }

    public function uploadLogo(Request $request)
    {
        $this->validate($request, [
            'id' => ['required','integer'],
            'image' => ['required', 'mimes:jpeg,bmp,png,gif'],
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $path = Storage::putFile($request->user()->encodedName(), $file);
            if (!$path) {
                return back()->withErrors(['msg' => 'Upload mislukt']);
            }

            // $image = Image::make($path)->resize(null, 200, function ($constraint) {
            //     $constraint->aspectRatio();
            //     $constraint->upsize();
            // })->save();

            $resource = new Resource;
            $resource->resource_name = 'Bedrijfslogo';
            $resource->file_location = $path;
            $resource->file_size = $file->getClientSize();//$image->filesize();
            $resource->user_id = $request->user()->id;
            $resource->description = 'Relatielogo';
            $resource->save();

            $relation = Relation::findOrFail($request->input('id'));
            if (!$relation || !$relation->isOwner()) {
                return back()->withInput($request->all());
            }
            $relation->logo_id = $resource->id;

            $relation->save();

            return back()->with('success', 'Uw logo is geupload');
        } else {

            $messages->add('file', 'Geen afbeelding geupload');

            return back()->withErrors($messages);
        }

    }

    public function uploadAgreement(Request $request)
    {
        $this->validate($request, [
            'id' => ['required','integer'],
            'doc' => ['required', 'mimes:pdf'],
        ]);

        if ($request->hasFile('doc')) {
            $file = $request->file('doc');
            if (strlen($file->getClientOriginalName()) >= 50) {
                return back()->withErrors(['msg' => 'Bestandsnaam te lang']);
            }

            $path = Storage::putFile($request->user()->encodedName(), $file);
            if (!$path) {
                return back()->withErrors(['msg' => 'Upload mislukt']);
            }

            $resource = new Resource;
            $resource->resource_name = $file->getClientOriginalName();
            $resource->file_location = $path;
            $resource->file_size = $file->getClientSize();
            $resource->user_id = $request->user()->id;
            $resource->description = 'Algemene Voorwaarden';
            $resource->save();

            $relation = Relation::findOrFail($request->input('id'));
            if (!$relation || !$relation->isOwner()) {
                return back()->withInput($request->all());
            }
            $relation->agreement_id = $resource->id;

            $relation->save();

            return back()->with('success', 'Uw algemene voorwaarden zijn geupload');
        } else {

            return back()->withErrors(['file' => 'Geen document geupload']);
        }

    }

}
