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

namespace BynqIO\Dynq\Http\Controllers\Project;

use BynqIO\Dynq\Models\Project;
use BynqIO\Dynq\Models\Resource;
use BynqIO\Dynq\Models\Audit;
use BynqIO\Dynq\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Storage;

class DocumentController extends Controller
{
    const MIMETYPES = 'mimes:jpeg,jpg,bmp,png,gif,pdf,doc,docx,xls,xlsx,csv,txt,ppt,pptx,xml,zip,7z,tar,gz,rar,wav,mp3,flac,mkv,mp4,avi,css,html';
    
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'projectfile' => array('required', self::MIMETYPES, 'file'),
            'project' => array('required'),
        ]);

        $project = Project::find($request->input('project'));
        if (!$project || !$project->isOwner()) {
            return back()->withInput($request->all());
        }

        if ($request->hasFile('projectfile')) {
            $file = $request->file('projectfile');

            $path = Storage::putFile($request->user()->encodedName(), $file);
            if (!$path) {
                return back()->withErrors(['msg' => 'Upload mislukt']);
            }

            $resource = new Resource;
            $resource->resource_name = $file->getClientOriginalName();
            $resource->file_location = $path;
            $resource->file_size     = $file->getClientSize();
            $resource->user_id       = $request->user()->id;
            $resource->project_id    = $project->id;
            $resource->description   = 'Project document';
            $resource->save();

            Audit::CreateEvent('project.new.document.upload.success', 'Document ' . $resource->resource_name . ' attached to project ' . $project->project_name);

            return back()->with('success', 'Document aan project toegevoegd');
        } else {
            // redirect our user back to the form with the errors from the validator
            return back()->withErrors('Geen bestand geupload');
        }
    }

}
