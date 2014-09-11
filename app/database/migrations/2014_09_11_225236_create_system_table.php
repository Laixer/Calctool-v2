<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * System migration
 *
 * Color: White
 */
class CreateSystemTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('system_message', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('message_content');
			$table->nullableTimestamps();
		});

		Schema::create('system_option', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('option_key', 50);
			$table->text('option_value');
		});

		Schema::create('system_tooltip', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title', 50);
			$table->text('tooltip_content');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('system_tooltip', function(Blueprint $table)
		{
			Schema::drop('system_tooltip');
		});

		Schema::table('system_option', function(Blueprint $table)
		{
			Schema::drop('system_option');
		});

		Schema::table('system_message', function(Blueprint $table)
		{
			Schema::drop('system_message');
		});
	}

}
