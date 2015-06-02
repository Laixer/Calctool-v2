<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Material Storage migration
 *
 * Color: Red
 */
class CreateMaterialStorage extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sub_group', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('reference_code', 10)->index();
			$table->string('group_type', 50)->unique();
		});

		Schema::create('supplier', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('supplier_name', 50)->unique();
			$table->integer('user_id')->nullable()->unsigned();
			$table->foreign('user_id')->references('id')->on('user_account')->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::create('product', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('unit', 30)->nullable();
			$table->string('unit_price', 30);
			$table->decimal('price', 9, 2)->index()->unsigned();
			$table->decimal('package_height', 9, 2)->unsigned();
			$table->decimal('package_length', 9, 2)->unsigned();
			$table->decimal('package_width', 9, 2)->unsigned();
			$table->decimal('minimum_quantity', 9, 2)->unsigned();
			$table->string('description');
			$table->integer('group_id')->unsigned();
			$table->foreign('group_id')->references('id')->on('sub_group')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('supplier_id')->unsigned();
			$table->foreign('supplier_id')->references('id')->on('supplier')->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::create('product_favorite', function(Blueprint $table)
		{
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('user_account')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('product_id')->unsigned();
			$table->foreign('product_id')->references('id')->on('product')->onUpdate('cascade')->onDelete('cascade');
			$table->primary(array('user_id', 'product_id'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('product_favorite', function(Blueprint $table)
		{
			Schema::drop('product_favorite');
		});

		Schema::table('product', function(Blueprint $table)
		{
			Schema::drop('product');
		});

		Schema::table('supplier', function(Blueprint $table)
		{
			Schema::drop('supplier');
		});

		Schema::table('sub_group', function(Blueprint $table)
		{
			Schema::drop('sub_group');
		});
	}

}
