<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use \Calctool\Models\Supplier;
use \Calctool\Models\UserType;
use \Calctool\Models\UserGroup;
use \Calctool\Models\Province;
use \Calctool\Models\Country;
use \Calctool\Models\ProjectType;
use \Calctool\Models\Part;
use \Calctool\Models\PartType;
use \Calctool\Models\Detail;
use \Calctool\Models\ContactFunction;
use \Calctool\Models\RelationType;
use \Calctool\Models\RelationKind;
use \Calctool\Models\WholesaleType;
use \Calctool\Models\Valid;
use \Calctool\Models\DeliverTime;
use \Calctool\Models\Tax;
use \Calctool\Models\TimesheetKind;
use \Calctool\Models\PurchaseKind;
use \Calctool\Models\SysMessage;

/*
 * Static Models Only
 * Test are performed on other seeds
 */
class StaticSeeder extends Seeder {

	public function run()
	{

		DB::table('supplier')->delete();
		DB::table('tax')->delete();
		DB::table('deliver_time')->delete();
		DB::table('valid')->delete();
		DB::table('relation_kind')->delete();
		DB::table('relation_type')->delete();
		DB::table('contact_function')->delete();
		DB::table('part_type')->delete();
		DB::table('part')->delete();
		DB::table('project_type')->delete();
		DB::table('country')->delete();
		DB::table('province')->delete();
		DB::table('user_type')->delete();
		$this->command->info('Tables deleted');

		UserType::create(array('user_type' => 'system'));
		UserType::create(array('user_type' => 'admin'));
		UserType::create(array('user_type' => 'user'));
		UserType::create(array('user_type' => 'guest'));
		UserType::create(array('user_type' => 'tester'));
		UserType::create(array('user_type' => 'demo'));
		$this->command->info('UserType created');

		UserGroup::create(array('name' => 'Standaard groep', 'subscription_amount' => 0, 'note' => 'Stdandaard groep voor nieuwe gebruikers', 'token' => md5(mt_rand())));
		UserGroup::create(array('name' => 'Beta groep', 'subscription_amount' => 0, 'token' => md5(mt_rand()), 'experimental' => true));
		$this->command->info('UserGroup created');

		Province::create(array('province_name' => 'groningen'));
		Province::create(array('province_name' => 'friesland'));
		Province::create(array('province_name' => 'drenthe'));
		Province::create(array('province_name' => 'overijssel'));
		Province::create(array('province_name' => 'flevoland'));
		Province::create(array('province_name' => 'gelderland'));
		Province::create(array('province_name' => 'utrecht'));
		Province::create(array('province_name' => 'noord-holland'));
		Province::create(array('province_name' => 'zuid-holland'));
		Province::create(array('province_name' => 'noord-brabant'));
		Province::create(array('province_name' => 'limburg'));
		Province::create(array('province_name' => 'zeeland'));
		Province::create(array('province_name' => 'overig'));
		$this->command->info('Provance created');

		Country::create(array('country_name' => 'albanië'));
		Country::create(array('country_name' => 'andorra'));
		Country::create(array('country_name' => 'armenië'));
		Country::create(array('country_name' => 'azerbeidzjan'));
		Country::create(array('country_name' => 'belgië'));
		Country::create(array('country_name' => 'bosnië en herzegovina'));
		Country::create(array('country_name' => 'bulgarije'));
		Country::create(array('country_name' => 'cyprus'));
		Country::create(array('country_name' => 'denemarken'));
		Country::create(array('country_name' => 'duitsland'));
		Country::create(array('country_name' => 'estland'));
		Country::create(array('country_name' => 'faeroër'));
		Country::create(array('country_name' => 'finland'));
		Country::create(array('country_name' => 'frankrijk'));
		Country::create(array('country_name' => 'georgië'));
		Country::create(array('country_name' => 'griekenland'));
		Country::create(array('country_name' => 'groenland'));
		Country::create(array('country_name' => 'hongarije'));
		Country::create(array('country_name' => 'ierland'));
		Country::create(array('country_name' => 'ijsland'));
		Country::create(array('country_name' => 'italië'));
		Country::create(array('country_name' => 'kazachstan'));
		Country::create(array('country_name' => 'kosovo'));
		Country::create(array('country_name' => 'kroatië'));
		Country::create(array('country_name' => 'letland'));
		Country::create(array('country_name' => 'liechtenstein'));
		Country::create(array('country_name' => 'litouwen'));
		Country::create(array('country_name' => 'luxemburg'));
		Country::create(array('country_name' => 'macedonië'));
		Country::create(array('country_name' => 'malta'));
		Country::create(array('country_name' => 'moldavië'));
		Country::create(array('country_name' => 'monaco'));
		Country::create(array('country_name' => 'montenegro'));
		Country::create(array('country_name' => 'nederland'));
		Country::create(array('country_name' => 'noorwegen'));
		Country::create(array('country_name' => 'oekraïne'));
		Country::create(array('country_name' => 'oostenrijk'));
		Country::create(array('country_name' => 'polen'));
		Country::create(array('country_name' => 'portugal'));
		Country::create(array('country_name' => 'roemenië'));
		Country::create(array('country_name' => 'rusland'));
		Country::create(array('country_name' => 'san marino'));
		Country::create(array('country_name' => 'servië'));
		Country::create(array('country_name' => 'slovenië'));
		Country::create(array('country_name' => 'slowakije'));
		Country::create(array('country_name' => 'spanje'));
		Country::create(array('country_name' => 'tsjechië'));
		Country::create(array('country_name' => 'turkije'));
		Country::create(array('country_name' => 'vaticaanstad'));
		Country::create(array('country_name' => 'verenigd koninkrijk'));
		Country::create(array('country_name' => 'wit-rusland'));
		Country::create(array('country_name' => 'zweden'));
		Country::create(array('country_name' => 'zwitserland'));
		$this->command->info('Country created');

		$ProjectType1 = ProjectType::create(array('type_name' => 'regie'));
		$ProjectType2 = ProjectType::create(array('type_name' => 'calculatie'));
		$ProjectType3 = ProjectType::create(array('type_name' => 'snelle offerte en factuur'));
		$this->command->info('ProjectType created');

		$Part1 = Part::create(array('part_name' => 'contracting'));
		$Part2 = Part::create(array('part_name' => 'subcontracting'));
		$this->command->info('Part created');

		$PartType1 = PartType::create(array('type_name' => 'calculation'));
		$PartType2 = PartType::create(array('type_name' => 'estimate'));
		$this->command->info('PartType created');

		$Detail1 = Detail::create(array('detail_name' => 'more'));
		$Detail2 = Detail::create(array('detail_name' => 'less'));
		$this->command->info('Detail created');

		ContactFunction::create(array('function_name' => 'adjunct-directeur'));
		ContactFunction::create(array('function_name' => 'eigenaar'));
		ContactFunction::create(array('function_name' => 'afdelingshoofd'));
		ContactFunction::create(array('function_name' => 'teamleider'));
		ContactFunction::create(array('function_name' => 'ploegbaas'));
		ContactFunction::create(array('function_name' => 'chef'));
		ContactFunction::create(array('function_name' => 'directeur'));
		ContactFunction::create(array('function_name' => 'onderdirecteur'));
		ContactFunction::create(array('function_name' => 'voorzitter'));
		ContactFunction::create(array('function_name' => 'hoofdingenieur'));
		ContactFunction::create(array('function_name' => 'ingenieur'));
		ContactFunction::create(array('function_name' => 'manager'));
		ContactFunction::create(array('function_name' => 'meester'));
		ContactFunction::create(array('function_name' => 'gezel'));
		ContactFunction::create(array('function_name' => 'leerling'));
		ContactFunction::create(array('function_name' => 'notaris'));
		ContactFunction::create(array('function_name' => 'boekhouder'));
		ContactFunction::create(array('function_name' => 'architect'));
		ContactFunction::create(array('function_name' => 'secretaresse'));
		ContactFunction::create(array('function_name' => 'programma manager'));
		ContactFunction::create(array('function_name' => 'projectleider'));
		ContactFunction::create(array('function_name' => 'inkoop medewerker'));
		ContactFunction::create(array('function_name' => 'makelaar'));
		ContactFunction::create(array('function_name' => 'adviseur'));
		ContactFunction::create(array('function_name' => 'uitvoerder'));
		ContactFunction::create(array('function_name' => 'werkvoorbereider'));
		ContactFunction::create(array('function_name' => 'tekenaar'));
		ContactFunction::create(array('function_name' => 'bestekschrijver'));
		ContactFunction::create(array('function_name' => 'calculator'));
		ContactFunction::create(array('function_name' => 'opdrachtgever'));
		ContactFunction::create(array('function_name' => 'particulier'));
		$this->command->info('ContactFunction created');

		RelationType::create(array('type_name' => 'aannemer'));
		RelationType::create(array('type_name' => 'adviesbureau'));
		RelationType::create(array('type_name' => 'afvalverwerker'));
		RelationType::create(array('type_name' => 'architect'));
		RelationType::create(array('type_name' => 'betonboorder'));
		RelationType::create(array('type_name' => 'betonstaalvlechter'));
		RelationType::create(array('type_name' => 'betontimmerman'));
		RelationType::create(array('type_name' => 'bouwgroothandel'));
		RelationType::create(array('type_name' => 'containerverhuur'));
		RelationType::create(array('type_name' => 'cv-installateur'));
		RelationType::create(array('type_name' => 'dakdekker'));
		RelationType::create(array('type_name' => 'elektricien'));
		RelationType::create(array('type_name' => 'gevelreiniger'));
		RelationType::create(array('type_name' => 'glazenwasser'));
		RelationType::create(array('type_name' => 'grondwerker'));
		RelationType::create(array('type_name' => 'ijzervlechter'));
		RelationType::create(array('type_name' => 'ingenieursbureau'));
		RelationType::create(array('type_name' => 'installateur'));
		RelationType::create(array('type_name' => 'interieurarchitect'));
		RelationType::create(array('type_name' => 'klusjesman'));
		RelationType::create(array('type_name' => 'loodgieter'));
		RelationType::create(array('type_name' => 'metselaar'));
		RelationType::create(array('type_name' => 'meubelmaker'));
		RelationType::create(array('type_name' => 'natuursteenwerker'));
		RelationType::create(array('type_name' => 'onderhoudsbedrijf'));		
		RelationType::create(array('type_name' => 'opdrachtgever'));
		RelationType::create(array('type_name' => 'ontwikkelaar'));
		RelationType::create(array('type_name' => 'overheid'));
		RelationType::create(array('type_name' => 'overig'));
		RelationType::create(array('type_name' => 'parketteur'));
		RelationType::create(array('type_name' => 'schilder'));
		RelationType::create(array('type_name' => 'schoonmaakbedrijf'));
		RelationType::create(array('type_name' => 'steigerbouwer'));
		RelationType::create(array('type_name' => 'stukadoor'));
		RelationType::create(array('type_name' => 'tegelzetter'));
		RelationType::create(array('type_name' => 'timmerman'));
		RelationType::create(array('type_name' => 'hovenier'));
		RelationType::create(array('type_name' => 'tussenpersoon'));
		RelationType::create(array('type_name' => 'vereniging van eigenaren'));
		RelationType::create(array('type_name' => 'verfgroothandel'));
		RelationType::create(array('type_name' => 'verhuurbedrijf'));
		RelationType::create(array('type_name' => 'verwarmingsmonteur'));
		RelationType::create(array('type_name' => 'vloerenlegger'));
		RelationType::create(array('type_name' => 'voeger'));
		RelationType::create(array('type_name' => 'woningbouwvereniging'));
		$this->command->info('RelationType created');

		WholesaleType::create(array('type_name' => 'bouw'));
		WholesaleType::create(array('type_name' => 'installatie'));
		WholesaleType::create(array('type_name' => 'ijzerwaren'));
		WholesaleType::create(array('type_name' => 'electra'));
		WholesaleType::create(array('type_name' => 'verf'));
		WholesaleType::create(array('type_name' => 'hovenier'));
		WholesaleType::create(array('type_name' => 'sanitair'));
		WholesaleType::create(array('type_name' => 'verhuur'));
		WholesaleType::create(array('type_name' => 'groothandel'));
		WholesaleType::create(array('type_name' => 'veiling'));
		WholesaleType::create(array('type_name' => 'overig'));
		$this->command->info('WholesaleType created');

		RelationKind::create(array('kind_name' => 'zakelijk'));
		RelationKind::create(array('kind_name' => 'particulier'));
		$this->command->info('RelationKind created');

		Valid::create(array('valid_name' => '5 dagen'));
		Valid::create(array('valid_name' => '14 dagen'));
		Valid::create(array('valid_name' => '30 dagen'));
		Valid::create(array('valid_name' => '2 maanden'));
		Valid::create(array('valid_name' => '3 maanden'));
		$this->command->info('Valid created');

		DeliverTime::create(array('delivertime_name' => 'per direct'));
		DeliverTime::create(array('delivertime_name' => 'in overleg'));
		DeliverTime::create(array('delivertime_name' => '1 week'));
		DeliverTime::create(array('delivertime_name' => '2 weken'));
		DeliverTime::create(array('delivertime_name' => '3 weken'));
		DeliverTime::create(array('delivertime_name' => '1 maand'));
		DeliverTime::create(array('delivertime_name' => '2 maanden'));
		DeliverTime::create(array('delivertime_name' => '3 maanden'));
		$this->command->info('DeliverTime created');

		Tax::create(array('tax_rate' => 0));
		Tax::create(array('tax_rate' => 6));
		Tax::create(array('tax_rate' => 21));
		$this->command->info('Tax created');

		TimesheetKind::create(array('kind_name' => 'aanneming'));
		TimesheetKind::create(array('kind_name' => 'stelpost'));
		TimesheetKind::create(array('kind_name' => 'meerwerk'));
		$this->command->info('TimesheetKind created');

		PurchaseKind::create(array('kind_name' => 'aanneming'));
		PurchaseKind::create(array('kind_name' => 'onderaanneming'));
		$this->command->info('PurchaseKind created');

		SysMessage::create(array('level' => 1, 'content' => 'Dit is een voorbeeld melding', 'active' => true));
		$this->command->info('SystemMessage created');
	}
 }
