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
		Schema::create('tax', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('tax_rate')->unsigned();
		});

		Schema::create('chapter', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('chapter_name', 50);
			$table->smallInteger('priority')->index();
			$table->text('note')->nullable();
			$table->nullableTimestamps();
			$table->integer('project_id')->unsigned();
			$table->foreign('project_id')->references('id')->on('project')->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::create('activity', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('activity_name', 50);
			$table->smallInteger('priority')->index();
			$table->text('note')->nullable();
			$table->nullableTimestamps();
			$table->integer('chapter_id')->unsigned();
			$table->foreign('chapter_id')->references('id')->on('chapter')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('tax_labor_id')->unsigned();
			$table->foreign('tax_labor_id')->references('id')->on('tax')->onUpdate('cascade')->onDelete('restrict');
			$table->integer('tax_material_id')->unsigned();
			$table->foreign('tax_material_id')->references('id')->on('tax')->onUpdate('cascade')->onDelete('restrict');
			$table->integer('tax_equipment_id')->unsigned();
			$table->foreign('tax_equipment_id')->references('id')->on('tax')->onUpdate('cascade')->onDelete('restrict');
		});

		$seq_chapter = "ALTER SEQUENCE chapter_id_seq RESTART WITH 1000";
		$seq_activity = "ALTER SEQUENCE activity_id_seq RESTART WITH 1000";

		DB::unprepared($seq_chapter);
		DB::unprepared($seq_activity);
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
			Schema::dropIfExists('activity');
		});

		Schema::table('chapter', function(Blueprint $table)
		{
			Schema::dropIfExists('chapter');
		});

		Schema::table('tax', function(Blueprint $table)
		{
			Schema::dropIfExists('tax');
		});
	}

}
