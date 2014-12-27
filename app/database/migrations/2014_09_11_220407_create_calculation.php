<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Calculation migration
 *
 * Color: Black
 */
class CreateCalculation extends Migration {

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

		Schema::create('calculation_labor', function(Blueprint $table)
		{
			$table->increments('id');
			$table->decimal('rate', 5, 2)->unsigned()->nullable();
			$table->decimal('amount', 9, 2)->unsigned()->index();
			$table->integer('activity_id')->unsigned();
			$table->foreign('activity_id')->references('id')->on('activity')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('tax_id')->unsigned();
			$table->foreign('tax_id')->references('id')->on('tax')->onUpdate('cascade')->onDelete('restrict');
		});

		Schema::create('calculation_material', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('material_name', 50);
			$table->string('unit', 10);
			$table->decimal('rate', 9, 2)->unsigned()->index();
			$table->decimal('amount', 9, 2)->unsigned()->index();
			$table->integer('activity_id')->unsigned();
			$table->foreign('activity_id')->references('id')->on('activity')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('tax_id')->unsigned();
			$table->foreign('tax_id')->references('id')->on('tax')->onUpdate('cascade')->onDelete('restrict');
		});

		Schema::create('calculation_equipment', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('equipment_name', 50);
			$table->string('unit', 10);
			$table->decimal('rate', 9, 2)->unsigned()->index();
			$table->decimal('amount', 9, 2)->unsigned()->index();
			$table->integer('activity_id')->unsigned();
			$table->foreign('activity_id')->references('id')->on('activity')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('tax_id')->unsigned();
			$table->foreign('tax_id')->references('id')->on('tax')->onUpdate('cascade')->onDelete('restrict');
		});

		Schema::create('less_labor', function(Blueprint $table)
		{
			$table->increments('id');
			$table->decimal('amount', 9, 2)->index();
			$table->integer('activity_id')->unsigned();
			$table->foreign('activity_id')->references('id')->on('activity')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('original_id')->unsigned();
			$table->foreign('original_id')->references('id')->on('calculation_labor')->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::create('less_material', function(Blueprint $table)
		{
			$table->increments('id');
			$table->decimal('rate', 9, 2)->unsigned()->index();
			$table->decimal('amount', 9, 2)->index();
			$table->integer('activity_id')->unsigned();
			$table->foreign('activity_id')->references('id')->on('activity')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('original_id')->unsigned();
			$table->foreign('original_id')->references('id')->on('calculation_material')->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::create('less_equipment', function(Blueprint $table)
		{
			$table->increments('id');
			$table->decimal('rate', 9, 2)->unsigned()->index();
			$table->decimal('amount', 9, 2)->index();
			$table->integer('activity_id')->unsigned();
			$table->foreign('activity_id')->references('id')->on('activity')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('original_id')->unsigned();
			$table->foreign('original_id')->references('id')->on('calculation_equipment')->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::create('more_labor', function(Blueprint $table)
		{
			$table->increments('id');
			$table->decimal('rate', 5, 2)->unsigned()->index();
			$table->decimal('amount', 9, 2)->unsigned()->index();
			$table->text('note');
			$table->integer('activity_id')->unsigned();
			$table->foreign('activity_id')->references('id')->on('activity')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('tax_id')->unsigned();
			$table->foreign('tax_id')->references('id')->on('tax')->onUpdate('cascade')->onDelete('restrict');
			$table->integer('hour_id')->unsigned();
			$table->foreign('hour_id')->references('id')->on('timesheet')->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::create('more_material', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('material_name', 50);
			$table->string('unit', 10);
			$table->decimal('rate', 9, 2)->unsigned()->index();
			$table->decimal('amount', 9, 2)->unsigned()->index();
			$table->integer('activity_id')->unsigned();
			$table->foreign('activity_id')->references('id')->on('activity')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('tax_id')->unsigned();
			$table->foreign('tax_id')->references('id')->on('tax')->onUpdate('cascade')->onDelete('restrict');
		});

		Schema::create('more_equipment', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('equipment_name', 50);
			$table->string('unit', 10);
			$table->decimal('rate', 9, 2)->unsigned()->index();
			$table->decimal('amount', 9, 2)->unsigned()->index();
			$table->integer('activity_id')->unsigned();
			$table->foreign('activity_id')->references('id')->on('activity')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('tax_id')->unsigned();
			$table->foreign('tax_id')->references('id')->on('tax')->onUpdate('cascade')->onDelete('restrict');
		});

		Schema::create('estimate_labor', function(Blueprint $table)
		{
			$table->increments('id');
			$table->decimal('rate', 5, 2)->unsigned()->index();
			$table->decimal('amount', 9, 2)->unsigned()->index();
			$table->decimal('set_rate', 5, 2)->unsigned()->index();
			$table->decimal('set_amount', 9, 2)->unsigned()->index();
			$table->integer('activity_id')->unsigned();
			$table->foreign('activity_id')->references('id')->on('activity')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('tax_id')->unsigned();
			$table->foreign('tax_id')->references('id')->on('tax')->onUpdate('cascade')->onDelete('restrict');
			$table->integer('hour_id')->unsigned();
			$table->foreign('hour_id')->references('id')->on('timesheet')->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::create('estimate_material', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('material_name', 50);
			$table->string('unit', 10);
			$table->decimal('rate', 9, 2)->unsigned()->index();
			$table->decimal('amount', 9, 2)->unsigned()->index();
			$table->string('set_material_name', 50);
			$table->string('set_unit', 10);
			$table->decimal('set_rate', 9, 2)->unsigned()->index();
			$table->decimal('set_amount', 9, 2)->unsigned()->index();
			$table->integer('activity_id')->unsigned();
			$table->foreign('activity_id')->references('id')->on('activity')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('tax_id')->unsigned();
			$table->foreign('tax_id')->references('id')->on('tax')->onUpdate('cascade')->onDelete('restrict');
		});

		Schema::create('estimate_equipment', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('equipment_name', 50);
			$table->string('unit', 10);
			$table->decimal('rate', 9, 2)->unsigned()->index();
			$table->decimal('amount', 9, 2)->unsigned()->index();
			$table->string('set_equipment_name', 50);
			$table->string('set_unit', 10);
			$table->decimal('set_rate', 9, 2)->unsigned()->index();
			$table->decimal('set_amount', 9, 2)->unsigned()->index();
			$table->integer('activity_id')->unsigned();
			$table->foreign('activity_id')->references('id')->on('activity')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('tax_id')->unsigned();
			$table->foreign('tax_id')->references('id')->on('tax')->onUpdate('cascade')->onDelete('restrict');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('estimate_equipment', function(Blueprint $table)
		{
			Schema::drop('estimate_equipment');
		});

		Schema::table('estimate_material', function(Blueprint $table)
		{
			Schema::drop('estimate_material');
		});

		Schema::table('estimate_labor', function(Blueprint $table)
		{
			Schema::drop('estimate_labor');
		});

		Schema::table('more_equipment', function(Blueprint $table)
		{
			Schema::drop('more_equipment');
		});

		Schema::table('more_material', function(Blueprint $table)
		{
			Schema::drop('more_material');
		});

		Schema::table('more_labor', function(Blueprint $table)
		{
			Schema::drop('more_labor');
		});

		Schema::table('less_equipment', function(Blueprint $table)
		{
			Schema::drop('less_equipment');
		});

		Schema::table('less_material', function(Blueprint $table)
		{
			Schema::drop('less_material');
		});

		Schema::table('less_labor', function(Blueprint $table)
		{
			Schema::drop('less_labor');
		});

		Schema::table('calculation_equipment', function(Blueprint $table)
		{
			Schema::drop('calculation_equipment');
		});

		Schema::table('calculation_material', function(Blueprint $table)
		{
			Schema::drop('calculation_material');
		});

		Schema::table('calculation_labor', function(Blueprint $table)
		{
			Schema::drop('calculation_labor');
		});

		Schema::table('tax', function(Blueprint $table)
		{
			Schema::drop('tax');
		});
	}

}
