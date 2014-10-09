<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Cost Registation migration
 *
 * Color: Yellow
 */
class CreateCostRegistration extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('timesheet', function(Blueprint $table)
		{
			$table->increments('id');
			$table->date('register_date');
			$table->decimal('register_hour', 5, 2)->unsigned();
			$table->text('note');
			$table->integer('part_id')->unsigned();
			$table->foreign('part_id')->references('id')->on('part')->onUpdate('cascade')->onDelete('restrict');
			$table->integer('part_type_id')->unsigned();
			$table->foreign('part_type_id')->references('id')->on('part_type')->onUpdate('cascade')->onDelete('restrict');
			$table->integer('detail_id')->nullable()->unsigned();
			$table->foreign('detail_id')->references('id')->on('detail')->onUpdate('cascade')->onDelete('restrict');
			$table->integer('project_id')->unsigned();
			$table->foreign('project_id')->references('id')->on('project')->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::create('purchase', function(Blueprint $table)
		{
			$table->increments('id');
			$table->decimal('amount', 9, 2);
			$table->text('note');
			$table->date('register_date');
			$table->integer('part_id')->unsigned();
			$table->foreign('part_id')->references('id')->on('part')->onUpdate('cascade')->onDelete('restrict');
			$table->integer('project_id')->unsigned();
			$table->foreign('project_id')->references('id')->on('project')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('relation_id')->nullable()->unsigned();
			$table->foreign('relation_id')->references('id')->on('relation')->onUpdate('cascade')->onDelete('set null');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('purchase', function(Blueprint $table)
		{
			Schema::drop('purchase');
		});

		Schema::table('timesheet', function(Blueprint $table)
		{
			Schema::drop('timesheet');
		});
	}

}
