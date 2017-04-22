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

use BynqIO\CalculatieTool\Models\Province;

class NLPostcode implements PostalInterface
{
    const ENDPOINT = 'https://postcode-api.apiwise.nl/v2/addresses/?postcode=%s&number=%s';

    /**/
    private $key;
    private $code;
    private $number;

    /*
     * Create new nl postcode instance.
     */
    public function __construct($code, $number)
    {
        $this->key = config('services.postcode.key');
        $this->code = $code;
        $this->number = $number;
    }

    private function perform($url)
    {
        $headers = [];
        $headers[] = 'X-Api-Key: ' . $this->key;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($curl);
        if (curl_error($curl)) {
            curl_close($curl);
            return;
        }

        curl_close($curl);
        return json_decode($response);
    }

    /**
     * {@inheritdoc}
     */
    public function postal()
    {
        $url = sprintf(self::ENDPOINT, $this->code, $this->number);

        $data = $this->perform($url);

        if (!property_exists($data, '_embedded'))
            return;

        if (count($data->_embedded->addresses) == 1) {
            $address['postcode'] = $data->_embedded->addresses[0]->postcode;
            $address['street'] = $data->_embedded->addresses[0]->street;
            $address['number'] = $data->_embedded->addresses[0]->number;
            $address['province'] = $data->_embedded->addresses[0]->province->label;
            $address['province_id'] = Province::where('province_name', strtolower($data->_embedded->addresses[0]->province->label))->first()['id'];
            $address['city'] = $data->_embedded->addresses[0]->city->label;

            return $address;
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function validator()
    {
        return [
            'zipcode' => ['required','regex:/[0-9]{4}[A-Z]{2}/'],
        ];
    }
}
