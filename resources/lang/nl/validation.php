<?php

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

    'accepted'             => 'De :attribute moet geaccepteerd worden.',
    'active_url'           => 'De :attribute is geen geldige URL.',
    'after'                => 'De :attribute moet een datum zijn na :date.',
    'alpha'                => 'De :attribute mag alleen letters bevatten.',
    'alpha_dash'           => 'De :attribute mag alleen letters, nummers, en streepjes bevatten.',
    'alpha_num'            => 'De :attribute mag alleen letters en numers bevatten.',
    'array'                => 'De :attribute moet een reeks zijn.',
    'before'               => 'De :attribute moet een datum voor zijn :date.',
    'between'              => [
        'numeric' => 'De :attribute moet zijn tussen :min and :max.',
        'file'    => 'De :attribute moet zijn tussen :min and :max kilobytes.',
        'string'  => 'De :attribute moet zijn tussen :min and :max characters.',
        'array'   => 'De :attribute moet hebben tussen :min and :max items.',
    ],
    'boolean'              => 'De :attribute veld moet goed of fout zijn.',
    'confirmed'            => 'De :attribute bevestiging komt niet overeen.',
    'date'                 => 'De :attribute is geen geldige datum.',
    'date_format'          => 'De :attribute komt niet overeen met het formaat :format.',
    'different'            => 'De :attribute en :other must be different.',
    'digits'               => 'De :attribute moet zijn :digits digits.',
    'digits_between'       => 'De :attribute moet zijn tussen :min and :max digits.',
    'email'                => 'De :attribute moet een geldig email adres zijn.',
    'exists'               => 'Het geselcteerde :attribute is ongeldig.',
    'filled'               => 'De :attribute veld is verplicht.',
    'image'                => 'De :attribute moet een plaatje zijn.',
    'in'                   => 'De selected :attribute is ongeldig.',
    'integer'              => 'De :attribute moet een getal zijn.',
    'ip'                   => 'De :attribute moet een geldig IP adres zijn.',
    'json'                 => 'De :attribute moet een geldige JSON zijn.',
    'max'                  => [
        'numeric' => 'De :attribute mag niet groter zijn dan :max.',
        'file'    => 'De :attribute mag niet groter zijn dan :max kilobytes.',
        'string'  => 'De :attribute mag niet groter zijn dan :max characters.',
        'array'   => 'De :attribute mag niet meer hebben dan :max items.',
    ],
    'mimes'                => 'De :attribute moet een bestand van het type zijn: :values.',
    'min'                  => [
        'numeric' => 'De :attribute moet op zijn minst :min.',
        'file'    => 'De :attribute moet op zijn minst :min kilobytes.',
        'string'  => 'De :attribute moet op zijn minst :min characters.',
        'array'   => 'De :attribute moet op zijn minst :min items.',
    ],
    'not_in'               => 'Het geselecteerde :attribute is ongeldig.',
    'numeric'              => 'De :attribute moet een nummer wezen.',
    'regex'                => 'De :attribute format is invalid.',
    'required'             => 'Het veld \':attribute\' is vereist.',
    'required_if'          => 'De :attribute veld is vereist wanneer :other is :value.',
    'required_with'        => 'De :attribute veld is vereist wanneer :values is present.',
    'required_with_all'    => 'De :attribute veld is vereist wanneer :values is present.',
    'required_without'     => 'De :attribute veld is vereist wanneer :values is not present.',
    'required_without_all' => 'De :attribute veld is vereist wanneer geen van :values are present.',
    'same'                 => 'De :attribute en :other must match.',
    'size'                 => [
        'numeric' => 'De :attribute moe zijn :size.',
        'file'    => 'De :attribute moet zijn :size kilobytes.',
        'string'  => 'De :attribute moet zijn :size characters.',
        'array'   => 'De :attribute moet bevatten :size items.',
    ],
    'string'               => 'De :attribute moet een tekenreeks zijn.',
    'timezone'             => 'De :attribute moet een geldige zone zijn.',
    'unique'               => 'De :attribute is al bezet.',
    'url'                  => 'De :attribute formaat is ongeldig.',

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
        'name' => [
            'required' => 'Naam is een verplicht veld',
        ],
        'street' => [
            'required' => 'Straat is een verplicht veld',
        ],
        'address_number' => [
            'required' => 'Huisnummer is een verplicht veld',
        ],
        'zipcode' => [
            'required' => 'Postcode is een verplicht veld',
        ],
        'city' => [
            'required' => 'Plaats is een verplicht veld',
        ],
        'email' => [
            'required' => 'Email is een verplicht veld',
        ],
        'contact_name' => [
            'required' => 'Achternaam contactpersoon is een verplicht veld',
        ],
        'email_comp' => [
            'required_if' => 'Bedrijfs email is een verplicht veld voor deze relatiesoort',
        ],
        'company_name' => [
            'required_if' => 'Bedrijfsnaam is een verplicht veld voor deze relatiesoort',
        ]
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