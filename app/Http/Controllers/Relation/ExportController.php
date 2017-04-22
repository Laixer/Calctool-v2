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

namespace BynqIO\CalculatieTool\Http\Controllers\Relation;

use BynqIO\CalculatieTool\Models\Relation;
use BynqIO\CalculatieTool\Models\RelationKind;
use BynqIO\CalculatieTool\Models\Contact;
use BynqIO\CalculatieTool\Http\Controllers\Controller;

class ExportController extends Controller
{
    public function __invoke()
    {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="export.csv"');
        $fp = fopen('php://output', 'w');

        $header = ['Bedrijfsnaam','Straat','Nummer','Postcode','Plaats','KVK','BTWnummer','Debiteurnummer','Telefoon','email','Notitie','Website','Iban','Naam Iban houder','Type relatie','','Voornaam','Achternaam','Mobiel','Telefoon','Email','Geslacht'];

        fputcsv($fp, $header, ";");

        $relations = Relation::where('user_id',Auth::id())->where('active',true)->orderBy('created_at', 'desc')->get();
        foreach ($relations as $relation) {
            $contact = Contact::where('relation_id',$relation->id)->first();

            $row = [];
            array_push($row, $relation->company_name ? $relation->company_name : $contact->firstname . ' '. $contact->lastname);
            array_push($row, $relation->address_street);
            array_push($row, $relation->address_number);
            array_push($row, $relation->address_postal);
            array_push($row, $relation->address_city);
            array_push($row, $relation->kvk);
            array_push($row, $relation->btw);
            array_push($row, $relation->debtor);
            array_push($row, $relation->phone_comp);
            array_push($row, $relation->email_comp);
            array_push($row, $relation->note);
            array_push($row, $relation->website);
            array_push($row, $relation->iban);
            array_push($row, $relation->iban_name);
            array_push($row, ucfirst(RelationKind::find($relation->kind_id)->kind_name));
            array_push($row, '');
            array_push($row, $contact->firstname);
            array_push($row, $contact->lastname);
            array_push($row, $contact->mobile);
            array_push($row, $contact->phone);
            array_push($row, $contact->email);
            array_push($row, $contact->gender);

            fputcsv($fp, $row, ";");
        }
        fclose($fp);

        return null;
    }
}
