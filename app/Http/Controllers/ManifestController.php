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

use BynqIO\Dynq\Models\Project;
use BynqIO\Dynq\Models\Offer;
use BynqIO\Dynq\Models\Invoice;
use BynqIO\Dynq\Models\ProjectType;
use BynqIO\Dynq\Models\Relation;
use BynqIO\Dynq\Models\Contact;
use BynqIO\Dynq\Models\Chapter;
use BynqIO\Dynq\Models\Audit;
use BynqIO\Dynq\Models\RelationKind;
use BynqIO\Dynq\Http\Controllers\InvoiceController;
use BynqIO\Dynq\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManifestController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /relation
     *
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        return response()->json([
            'manifest_version'  => 2,
            'version'           => 'alpha',
            'default_locale'    => app()->getLocale(),
            'description'       => 'Calculeren, Offreren, Registreren en Administreren in een handomdraai',
            'name'              => config('app.name'),
            'short_name'        => 'CT',
            'homepage_url'      => '/',
            'start_url'         => '/',
            'orientation'       => 'any',
            'display'           => 'browser',
            'theme_color'       => '#517a00',
            'background_color'  => '#ccc',
            'author'            => 'Bynq.io B.V.',
            'dir'               => 'ltr',
            'icons'             => [
                [
                    'src'=> '/images/android-chrome-36x36.png',
                    'sizes'=> '36x36',
                    'type'=> 'image/png',
                    'density'=> '0.75'
                ],
                [
                    'src'=> '/images/android-chrome-48x48.png',
                    'sizes'=> '48x48',
                    'type'=> 'image/png',
                    'density'=> '1.0'
                ],
                [
                    'src'=> '/images/android-chrome-72x72.png',
                    'sizes'=> '72x72',
                    'type'=> 'image/png',
                    'density'=> '1.5'
                ],
                [
                    'src'=> '/images/android-chrome-96x96.png',
                    'sizes'=> '96x96',
                    'type'=> 'image\/png',
                    'density'=> '2.0'
                ],
                [
                    'src'=> '/images/android-chrome-144x144.png',
                    'sizes'=> '144x144',
                    'type'=> 'image/png',
                    'density'=> '3.0'
                ],
                [
                    'src'=> '/images/android-chrome-192x192.png',
                    'sizes'=> '192x192',
                    'type'=> 'image/png',
                    'density'=> '4.0'
                ]
            ]
        ], 200, [
            'content-type' => 'application/manifest+json'
        ], JSON_PRETTY_PRINT);
    }
}
