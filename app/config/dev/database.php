<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Database Connections
	|--------------------------------------------------------------------------
	|
	| Here are each of the database connections setup for your application.
	| Of course, examples of configuring each database platform that is
	| supported by Laravel is shown below to make development simple.
	|
	|
	| All database work in Laravel is done through the PHP PDO facilities
	| so make sure you have the driver for your particular database of
	| choice installed on your machine before you begin development.
	|
	*/

	'connections' => array(

		'pgsql' => array(
			'driver'   => 'pgsql',
			'host'     => $_ENV['DB_HOST'],
			'database' => $_ENV['DB_NAME'],
			'username' => $_ENV['DB_USERNAME'],
			'password' => $_ENV['DB_PASSWORD'],
			'charset'  => 'utf8',
			'prefix'   => '',
			'schema'   => $_ENV['DB_SCHEMA'],
		),

	),

	/*
	|--------------------------------------------------------------------------
	| Redis Databases
	|--------------------------------------------------------------------------
	|
	| Redis is an open source, fast, and advanced key-value store that also
	| provides a richer set of commands than a typical key-value systems
	| such as APC or Memcached. Laravel makes it easy to dig right in.
	|
	*/

	'redis' => array(

		'cluster' => false,

		'default' => array(
			'host'     => $_ENV['RDS_HOST'],
			'port'     => $_ENV['RDS_PORT'],
			'database' => $_ENV['RDS_DATABASE'],
			'password' => $_ENV['RDS_PASSWORD'],
		),

	),

);
