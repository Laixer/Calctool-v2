<?php

namespace Calctool\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Nathanmac\Utilities\Parser\Parser;

use \Calctool\Models\SubGroup;
use \Calctool\Models\Wholesale;
use \Calctool\Models\Product;
use \Calctool\Models\Supplier;

use DB;

class MaterialImport extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'material:import';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import new materiallist.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$boumaat = Supplier::where('wholesale_id', Wholesale::where('company_name','Bouwmaat NL')->first()['id'])->first();

		$parser = new Parser();
		$filename = $this->argument('file');
		$contents = file_get_contents($filename);
		$xml =  str_replace("http://www.gs1.nl Artikelbericht_bou003_31012010.xsd", "http://calctool", $contents);
		$articles = $parser->xml($xml)['Bilateral']['PricatLine'];

		DB::raw("TRUNCATE product CASCADE");
		foreach ($articles as $key => $value) {
			$groupcode = explode(" ", $value['AdditionalDescriptions']['SupplierProductGroupDescription']);
			$subgroup = SubGroup::where('reference_code','=',$groupcode[0])->first();
			if (!$subgroup) {
				$subgroup = SubGroup::where('group_type','=', $value['AdditionalDescriptions']['SupplierProductGroupDescription'])->first();
				if (!$subgroup) {
					$subgroup = SubGroup::create(['group_type'=>$value['AdditionalDescriptions']['SupplierProductGroupDescription']]);
					echo "Adding " . $value['AdditionalDescriptions']['SupplierProductGroupDescription'] . "\n";
				}
			}
			$price = $value['PriceInformation']['GrossUnitPrice']['Price'];
			$quant = $value['ArticleData']['SmallestUnitQuantity']['Quantity'];
			$price_total = ($price/$quant);
			Product::create(array(
				'article_code' => $value['TradeItemId']['TradeItemNumber'],
				'unit' => $value['PriceInformation']['GrossUnitPrice']['MeasureUnitQualifier'],
				'price' => $price_total,
				'total_price' => $price,
				'description' => strtolower($value['ArticleData']['SuppliersDescription']['Description']),
				'group_id' => $subgroup->id,
				'supplier_id' => $boumaat->id 
			));
		}
		echo "New materials loaded\n";
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('file', InputArgument::REQUIRED, 'The XML file containing the materials.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		/*return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);*/
		return [];
	}

}
