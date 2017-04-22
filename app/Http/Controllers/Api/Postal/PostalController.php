<?php

/**
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the BynqIO\CalculatieTool.com.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 *
 * @package  CalculatieTool
 * @author   Yorick de Wid <y.dewid@calculatietool.com>
 */

namespace BynqIO\CalculatieTool\Http\Controllers\Api\Postal;

use Illuminate\Http\Request;
use BynqIO\CalculatieTool\Http\Controllers\Controller;

use Validator;
use Cache;

class PostalController extends Controller
{
    private $locale;

    /*
     * Create new zipcode instance.
     */
    public function __construct()
    {
        $this->locale = app()->getLocale();
    }

    private function fromCache($postal, $number, $other)
    {
        if (Cache::has($postal . $number . $other)) {
            $address = Cache::get($postal . $number . $other);
            $address['cache'] = true;
            return $address;
        }
    }

    private function fromFactory($postal, $number, $other, $request)
    {
        $factory = new PostalFactory($postal, $number, $other);

        /* Fetch the postal object for this locate */
        $instance = $factory->getPostal($this->locale);
        if (is_null($instance)) {
            return ['err' => 'invalid locale'];
        }

        /* Validate input */
        $validator = Validator::make($request, $instance::validator());
        if ($validator->fails()) {
            return ['err' => 'invalid request for locale'];
        }

        /* Save in cache */
        $address = $instance->postal();
        if (is_array($address)) {
            Cache::put($postal . $number . $other, $address, 5184000);
        }

        return $address;
    }

    /**
     * Return adderss object.
     * GET /relation
     *
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $postal = $request->get('zipcode');
        $number = $request->get('number');
        $other = $request->get('other');
        $locale = $request->get('locale');

        /* Determine locale */
        if (!empty($locale)) {
            $this->locale = $locale;
        }

        /* Resolve address */
        $address = $this->fromCache($postal, $number, $other);
        if (!$address) {
            $address = $this->fromFactory($postal, $number, $other, $request->all());
        }

        return response()->json($address);
    }

}
