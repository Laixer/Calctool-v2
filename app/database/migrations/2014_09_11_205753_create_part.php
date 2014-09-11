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
			$table->string('part_name', 10);
			$table->boolean('activity')->default('Y');
			$table->boolean('invoice')->default('N');
			$table->char('acronym', 2);
		});

		Schema::create('part_type', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('type_name', 10);
		});

		Schema::create('detail', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('detail_name', 10);
		});

		Schema::create('part_part_type', function(Blueprint $table)
		{
			$table->integer('part_id')->unsigned();
			$table->foreign('part_id')->references('id')->on('part')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('type_id')->unsigned();
			$table->foreign('type_id')->references('id')->on('part_type')->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::create('part_part_detail', function(Blueprint $table)
		{
			$table->integer('detail_id')->unsigned();
			$table->foreign('detail_id')->references('id')->on('detail')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('type_id')->unsigned();
			$table->foreign('type_id')->references('id')->on('part_type')->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::table('activity', function(Blueprint $table)
		{
			$table->integer('part_id')->unsigned();
			$table->foreign('part_id')->references('id')->on('part')->onUpdate('cascade')->onDelete('restrict');
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
			$table->dropForeign('activity_part_id_foreign');
		});

		Schema::table('part_part_detail', function(Blueprint $table)
		{
			Schema::drop('part_part_detail');
		});

		Schema::table('part_part_type', function(Blueprint $table)
		{
			Schema::drop('part_part_type');
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
