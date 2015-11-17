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
			$table->string('reference_code', 10)->nullable()->unique();
			$table->string('group_type', 50)->unique();
		});

		Schema::create('supplier', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('wholesale_id')->nullable()->unsigned();
			$table->foreign('wholesale_id')->references('id')->on('wholesale')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('user_id')->nullable()->unsigned();
			$table->foreign('user_id')->references('id')->on('user_account')->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::create('product', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('unit', 30)->nullable();
			$table->integer('article_code')->nullable()->unique()->unsigned();
			$table->decimal('price', 9, 3)->index()->unsigned();
			$table->decimal('total_price', 9, 3)->nullable()->unsigned();
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

		Schema::create('element', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('user_account')->onUpdate('cascade')->onDelete('cascade');
			$table->string('name', 50)->nullable()->unique();
		});

		Schema::create('product_element', function(Blueprint $table)
		{
			$table->integer('element_id')->unsigned();
			$table->foreign('element_id')->references('id')->on('element')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('product_id')->unsigned();
			$table->foreign('product_id')->references('id')->on('product')->onUpdate('cascade')->onDelete('cascade');
			$table->primary(array('product_id', 'element_id'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('product_element', function(Blueprint $table)
		{
			Schema::dropIfExists('product_element');
		});

		Schema::table('element', function(Blueprint $table)
		{
			Schema::dropIfExists('element');
		});

		Schema::table('product_favorite', function(Blueprint $table)
		{
			Schema::dropIfExists('product_favorite');
		});

		Schema::table('product', function(Blueprint $table)
		{
			Schema::dropIfExists('product');
		});

		Schema::table('supplier', function(Blueprint $table)
		{
			Schema::dropIfExists('supplier');
		});

		Schema::table('sub_group', function(Blueprint $table)
		{
			Schema::dropIfExists('sub_group');
		});
	}

}
