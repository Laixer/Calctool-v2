<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('StaticSeeder');
		$this->command->info('StaticSeeder finished.');
		//$this->call('MaterialStorageSeeder');
		//$this->command->info('MaterialStorageSeeder finished.');
		$this->call('TestProjectSeeder');
		$this->command->info('TestProjectSeeder finished.');

	}

}
