<?php

namespace CalculatieTool\Console\Commands;

use Illuminate\Console\Command;

class DropHard extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'migrate:drophard';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Drop all tables and rebuild';

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
		$rs = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
		foreach ($rs as $value) {
			DB::statement("DROP TABLE public.".$value->tablename." CASCADE");
		}
		Artisan::call('migrate');
		Artisan::call('db:seed');
		echo "Done\n";
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [];
	}

}
