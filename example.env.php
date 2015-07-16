<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Environment config
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

	/* Database */
	'DB_HOST'			=> '',
	'DB_NAME'			=> '',
	'DB_SCHEMA'			=> '',
	'DB_USERNAME'		=> '',
	'DB_PASSWORD'		=> '',

	/* Redis */
	'RDS_HOST'			=> '',
	'RDS_PORT'			=> 0,
	'RDS_DATABASE'		=> 0,
	'RDS_PASSWORD'		=> '',

	/* Mollie keys */
	'MOLLIE_TEST_API'	=> '',
	'MOLLIE_API'		=> '',

	/* Mailgun config */
	'MGUN_ADDRESS'		=> '',
	'MGUN_NAME'			=> '',
	'MGUN_API'			=> '',
	'MGUN_PUBLIC_API'	=> '',
	'MGUN_DOMAIN'		=> '',

	/* wkhtmlto config */
	'WKHTML_PDF_BIN'	=> '',
	'WKHTML_IMG_BIN'	=> '',

);
