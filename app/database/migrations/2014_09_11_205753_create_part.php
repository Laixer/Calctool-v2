<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Part migration
 *
 * Color: Purple
 */
class CreatePart extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('part', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('part_name', 15);
		});

		Schema::create('part_type', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('type_name', 15);
		});

		Schema::create('detail', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('detail_name', 15);
		});

		Schema::table('activity', function(Blueprint $table)
		{
			$table->integer('part_id')->unsigned();
			$table->foreign('part_id')->references('id')->on('part')->onUpdate('cascade')->onDelete('restrict');
			$table->integer('part_type_id')->unsigned();
			$table->foreign('part_type_id')->references('id')->on('part_type')->onUpdate('cascade')->onDelete('restrict');
			$table->integer('detail_id')->nullable()->unsigned();
			$table->foreign('detail_id')->references('id')->on('detail')->onUpdate('cascade')->onDelete('restrict');
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
			$table->dropForeign('activity_detail_id_foreign');
		});

		Schema::table('activity', function(Blueprint $table)
		{
			$table->dropForeign('activity_part_type_id_foreign');
		});

		Schema::table('activity', function(Blueprint $table)
		{
			$table->dropForeign('activity_part_id_foreign');
		});

		Schema::table('detail', function(Blueprint $table)
		{
			Schema::drop('detail');
		});

		Schema::table('part_type', function(Blueprint $table)
		{
			Schema::drop('part_type');
		});

		Schema::table('part', function(Blueprint $table)
		{
			Schema::drop('part');
		});
	}

}
