<?php

class UserSeeder extends Seeder {

	public function run()
	{
		UserType::create(array('user_type' => ''));

		Provance::create(array('provance_name' => ''));

		Country::create(array('country_name' => ''));

		ProjectType::create(array('type_name' => ''));
	}
 
}