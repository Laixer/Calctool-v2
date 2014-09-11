<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Works migration
 *
 * Color: Grey
 */
class CreateWorks extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('chapter', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('chapter_name', 10);
			$table->smallInteger('priority')->index();
			$table->text('note')->nullable();
			$table->integer('project_id')->unsigned();
			$table->foreign('project_id')->references('id')->on('project')->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::create('activity', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('activity_name', 50);
			$table->smallInteger('priority')->index();
			$table->text('note')->nullable();
			$table->integer('chapter_id')->unsigned();
			$table->foreign('chapter_id')->references('id')->on('chapter')->onUpdate('cascade')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('activity', function(Blueprint $table)
		{
			Schema::drop('activity');
		});

		Schema::table('chapter', function(Blueprint $table)
		{
			Schema::drop('chapter');
		});
	}

}
