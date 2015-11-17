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
		Schema::create('timesheet_kind', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('kind_name');
		});

		Schema::create('purchase_kind', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('kind_name');
		});

		Schema::create('timesheet', function(Blueprint $table)
		{
			$table->increments('id');
			$table->date('register_date');
			$table->decimal('register_hour', 6, 3)->unsigned();
			$table->text('note')->nullable();
			$table->integer('activity_id')->unsigned();
			$table->foreign('activity_id')->references('id')->on('activity')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('timesheet_kind_id')->unsigned();
			$table->foreign('timesheet_kind_id')->references('id')->on('timesheet_kind')->onUpdate('cascade')->onDelete('restrict');
		});

		Schema::create('purchase', function(Blueprint $table)
		{
			$table->increments('id');
			$table->decimal('amount', 9, 3);
			$table->text('note')->nullable();
			$table->date('register_date');
			$table->integer('project_id')->unsigned();
			$table->foreign('project_id')->references('id')->on('project')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('relation_id')->nullable()->unsigned();
			$table->foreign('relation_id')->references('id')->on('relation')->onUpdate('cascade')->onDelete('set null');
			$table->integer('wholesale_id')->nullable()->unsigned();
			$table->foreign('wholesale_id')->references('id')->on('wholesale')->onUpdate('cascade')->onDelete('set null');
			$table->integer('kind_id')->unsigned();
			$table->foreign('kind_id')->references('id')->on('purchase_kind')->onUpdate('cascade')->onDelete('restrict');
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
			Schema::dropIfExists('purchase');
		});

		Schema::table('timesheet', function(Blueprint $table)
		{
			Schema::dropIfExists('timesheet');
		});
		Schema::table('timesheet_kind', function(Blueprint $table)
		{
			Schema::dropIfExists('timesheet_kind');
		});
		Schema::table('purchase_kind', function(Blueprint $table)
		{
			Schema::dropIfExists('purchase_kind');
		});
	}

}
