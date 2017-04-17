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

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute moet geaccepteerd zijn.',
    'active_url'           => ':attribute is geen geldige URL.',
    'after'                => ':attribute moet een datum na :date zijn.',
    'alpha'                => ':attribute mag alleen letters bevatten.',
    'alpha_dash'           => ':attribute mag alleen letters, nummers, onderstreep(_) en strepen(-) bevatten.',
    'alpha_num'            => ':attribute mag alleen letters en nummers bevatten.',
    'array'                => ':attribute moet geselecteerde elementen bevatten.',
    'before'               => ':attribute moet een datum voor :date zijn.',
    'between'              => [
        'numeric' => ':attribute moet tussen :min en :max zijn.',
        'file'    => ':attribute moet tussen :min en :max kilobytes zijn.',
        'string'  => ':attribute moet tussen :min en :max karakters zijn.',
        'array'   => ':attribute moet tussen :min en :max items bevatten.',
    ],
    'boolean'              => ':attribute moet true of false zijn.',
    'confirmed'            => ':attribute bevestiging komt niet overeen.',
    'date'                 => ':attribute moet een datum bevatten.',
    'date_format'          => ':attribute moet een geldig datum formaat bevatten.',
    'different'            => ':attribute en :other moeten verschillend zijn.',
    'digits'               => ':attribute moet bestaan uit :digits cijfers.',
    'digits_between'       => ':attribute moet bestaan uit minimaal :min en maximaal :max cijfers.',
    'dimensions'           => ':attribute heeft geen geldige afmetingen voor afbeeldingen.',
    'distinct'             => ':attribute heeft een dubbele waarde.',
    'email'                => ':attribute is geen geldig e-mailadres.',
    'exists'               => ':attribute bestaat niet.',
    'file'                 => ':attribute moet een bestand zijn.',
    'filled'               => ':attribute is verplicht.',
    'image'                => ':attribute moet een afbeelding zijn.',
    'in'                   => ':attribute is ongeldig.',
    'in_array'             => ':attribute bestaat niet in :other.',
    'integer'              => ':attribute moet een getal zijn.',
    'ip'                   => ':attribute moet een geldig IP-adres zijn.',
    'json'                 => ':attribute moet een geldige JSON-string zijn.',
    'max'                  => [
        'numeric' => ':attribute mag niet hoger dan :max zijn.',
        'file'    => ':attribute mag niet meer dan :max kilobytes zijn.',
        'string'  => ':attribute mag niet uit meer dan :max karakters bestaan.',
        'array'   => ':attribute mag niet meer dan :max items bevatten.',
    ],
    'mimes'                => ':attribute moet een bestand zijn van het bestandstype :values.',
    'mimetypes'            => ':attribute moet een bestand zijn van het bestandstype :values.',
    'min'                  => [
        'numeric' => ':attribute moet minimaal :min zijn.',
        'file'    => ':attribute moet minimaal :min kilobytes zijn.',
        'string'  => ':attribute moet minimaal :min karakters zijn.',
        'array'   => ':attribute moet minimaal :min items bevatten.',
    ],
    'not_in'               => 'Het formaat van :attribute is ongeldig.',
    'numeric'              => ':attribute moet een nummer zijn.',
    'present'              => ':attribute moet bestaan.',
    'regex'                => ':attribute formaat is ongeldig.',
    'required'             => ':attribute is verplicht.',
    'required_if'          => ':attribute is verplicht indien :other gelijk is aan :value.',
    'required_unless'      => ':attribute is verplicht tenzij :other gelijk is aan :values.',
    'required_with'        => ':attribute is verplicht i.c.m. :values',
    'required_with_all'    => ':attribute is verplicht i.c.m. :values',
    'required_without'     => ':attribute is verplicht als :values niet ingevuld is.',
    'required_without_all' => ':attribute is verplicht als :values niet ingevuld zijn.',
    'same'                 => ':attribute en :other moeten overeenkomen.',
    'size'                 => [
        'numeric' => ':attribute moet :size zijn.',
        'file'    => ':attribute moet :size kilobyte zijn.',
        'string'  => ':attribute moet :size karakters zijn.',
        'array'   => ':attribute moet :size items bevatten.',
    ],
    'string'               => ':attribute moet een tekenreeks zijn.',
    'timezone'             => ':attribute moet een geldige tijdzone zijn.',
    'unique'               => ':attribute is al in gebruik.',
    'uploaded'             => 'Het uploaden van :attribute is mislukt.',
    'url'                  => ':attribute is geen geldige URL.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'username' => [
            'required' => 'Gebruikersnaam kan niet leeg zijn',
            'unique' => 'Deze gebruikersnaam is al in gebruik',
        ],
        'name' => [
            'required' => 'Naam is een verplicht veld',
        ],
        'street' => [
            'required' => 'Straat is een verplicht veld',
            'max' => 'Straat mag niet groter zijn dan 60 karakters',
        ],
        'address_number' => [
            'required' => 'Huisnummer is een verplicht veld',
            'alpha_num' => 'Huisnummer mag alleen letters en getallen bevatten',
            'max' => 'Huisnummer mag niet groter zijn dan 5 karakters',
        ],
        'zipcode' => [
            'required' => 'Postcode is een verplicht veld',
            'numeric' => 'Postcode moet exact 6 karakters bevatten',
        ],
        'city' => [
            'required' => 'Plaats is een verplicht veld',
            'max' => 'Plaats mag niet groter zijn dan 35 karakters',
        ],
        'email' => [
            'required' => 'Email is een verplicht veld',
            'email' => 'Email is niet geldig',
        ],
        'more_hour_rate' => [
            'regex' => 'Ongeldig uurtarief',
            'required' => 'Meerwerk uurtarief is een verplicht veld',
        ],
        'hour_rate' => [
            'regex' => 'Ongeldig uurtarief. Gebruik een komma.',
        ],
        'pref_hourrate_calc' => [
            'regex' => 'Ongeldig uurtarief. Gebruik een komma.',
        ],
        'pref_hourrate_more' => [
            'regex' => 'Ongeldig uurtarief. Gebruik een komma.',
        ],
        'contact_firstname' => [
            'required' => 'Voornaam is een verplicht veld',
            'max' => 'Voornaam mag niet groter zijn dan 30 karakters',
        ],
        'contact_name' => [
            'required' => 'Achternaam contactpersoon is een verplicht veld',
            'max' => 'Achternaam mag niet groter zijn dan 50 karakters',
        ],
        'email_comp' => [
            'required_if' => 'Email bedrijf is een verplicht veld voor deze relatiesoort',
        ],
        'company_name' => [
            'required_if' => 'Bedrijfsnaam is een verplicht veld voor deze relatiesoort',
            'required' => 'Bedrijfsnaam is een verplicht veld',
            'max' => 'Bedrijfsnaam mag niet groter zijn dan 50 karakters',
        ],
        'debtor' => [
            'required' => 'Het debiteurennummer is een verplicht veld',
            'max' => 'Het debiteurennummer mag niet groter zijn dan 10 karakters',
        ],
        'contact_salutation' => [
            'max' => 'Aanhef mag niet groter zijn dan 16 karakters',
        ],
        'website' => [
            'url' => 'De URL van de website is ongeldig',
        ],
        'curr_secret' => [
            'required' => 'Geef het huidige wachtwoord op',
        ],
        'secret' => [
            'confirmed' => 'Wachtwoorden komen niet overeen',
            'min' => 'Nieuwe wachtwoord moet minimaal 5 karakters bevatten',
            'required' => 'Geef een wachtwoord op',
        ],
        'secret_confirmation' => [
            'min' => 'Bevestig het wachtwoord',
            'required' => 'Bevestig het wachtwoord',
        ],
        'iban_name' => [
            'required' => 'Naam rekeninghouder is verplicht',
        ],
        'image' => [
            'mimes' => 'Het logo een van de volgende bestandstypes zijn: jpeg, bmp, png, gif',
        ],
        
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];