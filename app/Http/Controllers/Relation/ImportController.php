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

namespace BynqIO\Dynq\Http\Controllers\Relation;

use BynqIO\Dynq\Models\Relation;
use BynqIO\Dynq\Models\RelationKind;
use BynqIO\Dynq\Models\RelationType;
use BynqIO\Dynq\Models\Contact;
use BynqIO\Dynq\Models\Province;
use BynqIO\Dynq\Models\Country;
use BynqIO\Dynq\Models\ContactFunction;
use BynqIO\Dynq\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'csvfile' => array('required'),
        ]);

        if ($request->hasFile('csvfile')) {
            $file = $request->file('csvfile');
            
            $kind_zakelijk = RelationKind::where('kind_name','zakelijk')->first()->id;
            $kind_particulier = RelationKind::where('kind_name','particulier')->first()->id;
            $type_id = RelationType::where('type_name','aannemer')->first()->id;

            $province_id = Province::where('province_name','overig')->first()->id;
            $country_id = Country::where('country_name','nederland')->first()->id;

            $function_directeur = ContactFunction::where('function_name','directeur')->first()->id;
            $function_opdrachtgever = ContactFunction::where('function_name','opdrachtgever')->first()->id;

            $row = 0; $success = 0; $skip = 0;
            if (($handle = fopen($file->getRealPath(), "r")) !== FALSE) {

                $line = fgets($handle);

                $delimiter = ";";
                if (count(explode(",", $line)) == 21)
                    $delimiter = ",";

                rewind($handle);
                while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                    if ($row++ == 0) {
                        if (strtolower($data[0]) != 'bedrijfsnaam')
                            return back()->withErrors('Bestand heeft verkeerde indeling');

                        if (strlen($data[14]) > 0)
                            return back()->withErrors('Bestand heeft verkeerde indeling');

                        if (strtolower($data[20]) != 'geslacht')
                            return back()->withErrors('Bestand heeft verkeerde indeling');
                        continue;
                    }

                    if (count($data) != 21) {
                        $skip++;
                        continue;
                    }

                    if (strlen($data[14]) > 0) {
                        $skip++;
                        continue;
                    }

                    /* Convert to params */
                    $company_name = trim($data[0]);
                    $address_street = $data[1];
                    $address_number = trim($data[2]);
                    $address_postal = trim($data[3]);
                    $address_city = trim($data[4]);
                    $kvk = trim($data[5]);
                    $btw = trim($data[6]);
                    $debtor = trim($data[7]);
                    $phone_comp = trim($data[8]);
                    $email_comp = trim($data[9]);
                    $note = $data[10];
                    $website = trim($data[11]);
                    $iban = trim($data[12]);
                    $iban_name = trim($data[13]);

                    $firstname = trim($data[15]);
                    $lastname = trim($data[16]);
                    $mobile = trim($data[17]);
                    $phone = trim($data[18]);
                    $email = trim($data[19]);
                    $gender = strtolower(trim($data[20]));

                    /* Fixes */
                    if (empty($debtor))
                        $debtor = mt_rand(1000000, 9999999);
                    if (empty($address_street))
                        $address_street = 'onbekend';
                    if (empty($address_number))
                        $address_number = 0;
                    if (empty($address_postal))
                        $address_postal = '0000AA';
                    if (empty($address_city))
                        $address_city = 'onbekend';
                    if (empty($email_comp))
                        $email = 'onbekend@calculatietool.com';
                    if (empty($lastname))
                        $lastname = 'onbekend';
                    if (empty($email))
                        $email = 'onbekend@calculatietool.com';

                    $input = compact(
                        'debtor',
                        'company_name',
                        'email_comp',
                        'lastname',
                        'firstname',
                        'email',
                        'address_street',
                        'address_number',
                        'address_postal',
                        'address_city',
                        'phone',
                        'mobile',
                        'website'
                    );

                    $validator = Validator::make($input, [
                        'debtor' => array('required','alpha_num','max:10'),
                        'company_name' => array('max:50'),
                        'email_comp' => array('email','max:80'),
                        // 'contact_salutation' => array('max:16'),
                        'lastname' => array('required','max:50'),
                        'firstname' => array('max:30'),
                        'email' => array('required','email','max:80'),
                        'address_street' => array('required','max:60'),
                        'address_number' => array('required','alpha_num','max:5'),
                        'address_postal' => array('required','size:6'),
                        'address_city' => array('required','max:35'),
                        'phone' => array('max:12'),
                        'mobile' => array('max:12'),
                        'website' => array('max:180'),
                    ]);

                    if ($validator->fails()) {
                        $skip++;
                        continue;
                    }

                    /* General */
                    $relation = new Relation;
                    $relation->user_id = Auth::id();
                    $relation->note = $note;
                    $relation->kind_id = $kind_particulier;
                    $relation->debtor_code = $debtor;

                    /* Company */
                    if (!empty($company_name)) {
                        $relation->kind_id = $kind_zakelijk;
                        $relation->company_name = $company_name;
                        $relation->type_id = $type_id;
                        $relation->kvk = $kvk;
                        $relation->btw = $btw;
                        $relation->phone = $phone_comp;
                        $relation->email = $email_comp;
                        $relation->website = $website;
                    }

                    /* Adress */
                    $relation->address_street = $address_street;
                    $relation->address_number = $address_number;
                    $relation->address_postal = $address_postal;
                    $relation->address_city = $address_city;
                    $relation->province_id = $province_id;
                    $relation->country_id = $country_id;
                    $relation->iban = $iban;
                    $relation->iban_name = $iban_name;
                    $relation->save();

                    /* Contact */
                    $contact = new Contact;
                    $contact->firstname = $firstname;
                    $contact->lastname = $lastname;
                    $contact->mobile = $mobile;
                    $contact->phone = $phone;
                    $contact->email = $email;
                    $contact->relation_id = $relation->id;

                    if (!empty($company_name)) {
                        $contact->function_id = $function_directeur;
                    } else {
                        $contact->function_id = $function_opdrachtgever;
                    }

                    if (!empty($gender)) {
                        if ($gender == 'man' || $gender[0] == 'm')
                            $contact->gender = 'M';
                        if ($gender == 'vrouw' || $gender[0] == 'v' || $gender[0] == 'f')
                            $contact->gender = 'V';
                    }

                    $contact->save();
                    $success++;
                }
                fclose($handle);
            }
            return back()->with('success', $success . ' relaties geimporteerd, ' . $skip . ' overgeslagen');
        } else {
            return back()->withErrors('Geen CSV geupload');
        }

    }
}
