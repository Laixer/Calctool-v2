<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

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
	public function fire()
	{
		$filename = $this->argument('file');
		$contents = File::get($filename);
		$xml =  str_replace("http://www.gs1.nl Artikelbericht_bou003_31012010.xsd", "http://calctool", $contents);
		$articles = Parser::xml($xml)['Bilateral']['PricatLine'];
		DB::raw("TRUNCATE product CASCADE");
		foreach ($articles as $key => $value) {
			$groupcode = explode(" ", $value['AdditionalDescriptions']['SupplierProductGroupDescription']);
			$subgroup = SubGroup::where('reference_code','=',$groupcode[0])->first();
			Product::create(array(
				'unit' => $value['Orderinginfo']['MinimumQuantity']['MeasureUnitQualifier'],
				'unit_price' => $value['Orderinginfo']['MinimumQuantity']['MeasureUnitQualifier'],
				'price' => $value['PriceInformation']['NetUnitPrice']['Price'],
				'package_height' => $value['Packaging']['Height']['Quantity'],
				'package_length' => $value['Packaging']['Length']['Quantity'],
				'package_width' => $value['Packaging']['Width']['Quantity'],
				'minimum_quantity' => $value['Orderinginfo']['MinimumQuantity']['Quantity'],
				'description' => strtolower($value['ArticleData']['SuppliersDescription']['Description']),
				'group_id' => $subgroup->id,
				'supplier_id' => 1
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
