<?php

return array(

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

	"accepted"             => "De :attribute moet worden geaccepteerd.",
	"active_url"           => "De :attribute is geen geldige URL.",
	"after"                => "De :attribute moet een datum zijn na :date.",
	"alpha"                => "De :attribute mag alleen letters bevatten.",
	"alpha_dash"           => "De :attribute mag alleen letters, getallen en streepjes bevatten.",
	"alpha_num"            => "De :attribute mag alleen letters en getallen bevatten.",
	"array"                => "De :attribute moet een array zijn.",
	"before"               => "De :attribute moet een datum zijn voor :date.",
	"between"              => array(
		"numeric" => "De :attribute moet liggen tussen :min en :max.",
		"file"    => "De :attribute moet liggen tussen :min en :max kilobytes.",
		"string"  => "De :attribute moet liggen tussen :min en :max tekens.",
		"array"   => "De :attribute moet liggen tussen :min and :max items.",
	),
	"confirmed"            => "De :attribute bevestiging komt niet overeen.",
	"date"                 => "De :attribute is geen geldige datum.",
	"date_format"          => "De :attribute komt niet overeen met het formaat :format.",
	"different"            => "De :attribute en :other mogen niet hetzelfde zijn.",
	"digits"               => "De :attribute moet :digits getallen zijn.",
	"digits_between"       => "De :attribute moet tussen de :min and :max getallen zijn.",
	"email"                => "De :attribute moet een geldig email adres zijn.",
	"exists"               => "De geselecteerde :attribute is ongeldig.",
	"image"                => "De :attribute moet een afbeelding zijn.",
	"in"                   => "De geselecteerde :attribute is ongeldig.",
	"integer"              => "De :attribute moet een numerieke waarde zijn.",
	"ip"                   => "De :attribute moet een geldig IP adres zijn.",
	"max"                  => array(
		"numeric" => "De :attribute mag niet groter zijn dan :max.",
		"file"    => "De :attribute mag niet groter zijn dan :max kilobytes.",
		"string"  => "De :attribute mag niet groter zijn dan :max tekens.",
		"array"   => "De :attribute mag niet meer zijn dan :max items.",
	),
	"mimes"                => "De :attribute moet een bestand zijn van type: :values.",
	"min"                  => array(
		"numeric" => "De :attribute moet minimaal :min zijn.",
		"file"    => "De :attribute moet minimaal :min kilobytes zijn.",
		"string"  => "De :attribute moet minimaal :min tekens zjn.",
		"array"   => "De :attribute moet minimaal :min items hebben.",
	),
	"not_in"               => "De geselecteerde :attribute is ongeldig.",
	"numeric"              => "De :attribute moet een nummer zijn.",
	"regex"                => "De :attribute formaat is ongeldig.",
	"required"             => "De :attribute veld is vereist.",
	"required_if"          => "De :attribute veld is vereist wanneer :other is :value.",
	"required_with"        => "De :attribute veld is vereist wanneer :values is aanwezig.",
	"required_with_all"    => "De :attribute veld is vereist wanneer :values is aanwezig.",
	"required_without"     => "De :attribute veld is vereist wanneer :values is niet aanwezig.",
	"required_without_all" => "De :attribute veld is vereist wanneer geen van de :values aanwezig zijn.",
	"same"                 => "De :attribute en :other moeten overeenkomen.",
	"size"                 => array(
		"numeric" => "De :attribute moet gelijk zijn aan :size.",
		"file"    => "De :attribute moet gelijk zijn aan :size kilobytes.",
		"string"  => "De :attribute moet gelijk zijn aan :size tekens.",
		"array"   => "De :attribute moet :size items bevatten.",
	),
	"unique"               => "De :attribute is al gekozen.",
	"url"                  => "De :attribute formaat is ongeldig.",

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

	'custom' => array(
		'attribute-name' => array(
			'rule-name' => 'custom-message',
		),
	),

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

	'attributes' => array(),

);
