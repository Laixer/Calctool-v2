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

namespace BynqIO\Dynq\Http\Controllers\Product;

use BynqIO\Dynq\Models\Wholesale;
use BynqIO\Dynq\Models\WholesaleType;
use BynqIO\Dynq\Http\Controllers\Controller;
use Illuminate\Http\Request;

use \Auth;

class WholesaleController extends Controller {

    /**
     * Display a listing of the resource.
     * GET /relation
     *
     * @return Response
     */

    public function getAll(Request $request)
    {
        return view('wholesale.wholesale');
    }

    public function getNew(Request $request)
    {
        return view('wholesale.new_wholesale');
    }

    public function getEdit(Request $request)
    {
        return view('wholesale.edit_wholesale');
    }

    public function getShow(Request $request)
    {
        return view('wholesale.show_wholesale');
    }

    public function doUpdateIban(Request $request)
    {
        $this->validate($request, [
            'id' => array('required','integer'),
            'iban' => array('alpha_num'),
            'iban_name' => array('required','max:50')
        ]);

        $wholesale = Wholesale::find($request->input('id'));
        if (!$wholesale || !$wholesale->isOwner()) {
            return back()->withInput($request->all());
        }
        $wholesale->iban = $request->get('iban');
        $wholesale->iban_name = $request->get('iban_name');

        $wholesale->save();

        return back()->with('success', 'Betalingsgegevens zijn aangepast');
    }

    public function getDelete(Request $request, $wholesale_id)
    {
        $wholesale = Wholesale::find($wholesale_id);
        if (!$wholesale || !$wholesale->isOwner()) {
            return back()->withInput($request->all());
        }

        $wholesale->active = false;

        $wholesale->save();

        return redirect('/wholesale');
    }

    public function doUpdate(Request $request)
    {
        $this->validate($request, [
            'id' => array('required','integer'),
            'company_type' => array('required_if:relationkind,zakelijk','numeric'),
            'company_name' => array('required_if:relationkind,zakelijk','max:50'),
            'email_comp' => array('required_if:relationkind,zakelijk','email','max:80'),
            'street' => array('required','alpha_num','max:60'),
            'address_number' => array('required','alpha_num','max:5'),
            'zipcode' => array('required','size:6'),
            'city' => array('required','alpha_num','max:35'),
            'province' => array('required','numeric'),
            'country' => array('required','numeric')
        ]);

        /* General */
        $wholesale = Wholesale::find($request->input('id'));
        if (!$wholesale || !$wholesale->isOwner()) {
            return back()->withInput($request->all());
        }
        $wholesale->note = $request->input('note');

        /* Company */
        $wholesale->company_name = $request->input('company_name');
        $wholesale->type_id = $request->input('company_type');
        $wholesale->phone = $request->input('telephone_comp');
        $wholesale->email = $request->input('email_comp');
        $wholesale->website = $request->input('website');

        /* Adress */
        $wholesale->address_street = $request->input('street');
        $wholesale->address_number = $request->input('address_number');
        $wholesale->address_postal = $request->input('zipcode');
        $wholesale->address_city = $request->input('city');
        $wholesale->province_id = $request->input('province');
        $wholesale->country_id = $request->input('country');

        $wholesale->save();

        return back()->with('success', 'Leveranciersgegevens zijn aangepast');
    }

    public function doNew(Request $request)
    {
        $this->validate($request, [
            /* Company */
            'company_type' => array('required','numeric'),
            'company_name' => array('required','max:50'),
            /* Contact */
            'email' => array('email','max:80'),
            /* Adress */
            'street' => array('required','alpha_num','max:60'),
            'address_number' => array('required','alpha_num','max:5'),
            'zipcode' => array('required','size:6'),
            'city' => array('required','alpha_num','max:35'),
            'province' => array('required','numeric'),
            'country' => array('required','numeric'),
        ]);

        /* General */
        $wholesale = new Wholesale;
        $wholesale->user_id = \Auth::id();
        $wholesale->note = $request->input('note');

        $wholesale->company_name = $request->input('company_name');
        $wholesale->type_id = $request->input('company_type');
        $wholesale->phone = $request->input('telephone_comp');
        $wholesale->email = $request->input('email');
        $wholesale->website = $request->input('website');

        /* Adress */
        $wholesale->address_street = $request->input('street');
        $wholesale->address_number = $request->input('address_number');
        $wholesale->address_postal = $request->input('zipcode');
        $wholesale->address_city = $request->input('city');
        $wholesale->province_id = $request->input('province');
        $wholesale->country_id = $request->input('country');

        $wholesale->save();

        return redirect('/wholesale-'.$wholesale->id.'/edit')->with('success', 'Leverancier is toegevoegd');
    }

}
