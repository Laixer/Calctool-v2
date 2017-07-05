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

class ManifestController extends Controller
{
    const VERSION = 2;

    /**
     * Display a listing of the resource.
     * GET /relation
     *
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        return response()->json([
            'manifest_version'  => self::VERSION,
            'version'           => config('app.version'),
            'default_locale'    => app()->getLocale(),
            'description'       => 'Calculeren, Offreren, Registreren en Administreren in een handomdraai',
            'name'              => config('app.name'),
            'short_name'        => APP_KEY,
            'theme_color'       => APP_THEME_COLOR,
            'background_color'  => APP_BG_COLOR,
            'homepage_url'      => '/',
            'start_url'         => '/',
            'orientation'       => 'any',
            'display'           => 'browser',
            'dir'               => LOCALE_DIRECTION,
            'author'            => 'Bynq.io B.V.',
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
