<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Relation migration
 *
 * Color: Green
 */
class CreateRelation extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contact_function', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('function_name', 30)->unique();
		});

		Schema::create('relation_type', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('type_name', 50)->unique();
		});

		Schema::create('relation_kind', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('kind_name', 11)->unique();
		});

		Schema::create('relation', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('company_name', 50)->index();
			$table->string('address_street', 50);
			$table->string('address_number', 5);
			$table->string('address_postal', 6);
			$table->string('address_city', 35);
			$table->integer('kvk')->nullable();
			$table->string('btw', 14)->nullable();
			$table->string('debtor_code', 10)->index();
			$table->integer('phone')->nullable()->unsigned();
			$table->string('email', 80);
			$table->text('note')->nullable();
			$table->string('website', 180)->nullable();
			$table->nullableTimestamps();
			$table->integer('logo_id')->nullable()->unsigned();
			$table->foreign('logo_id')->references('id')->on('resource')->onUpdate('cascade')->onDelete('set null');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('user_account')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('type_id')->unsigned();
			$table->foreign('type_id')->references('id')->on('relation_type')->onUpdate('cascade')->onDelete('restrict');
			$table->integer('kind_id')->unsigned();
			$table->foreign('kind_id')->references('id')->on('relation_kind')->onUpdate('cascade')->onDelete('restrict');
			$table->integer('provance_id')->unsigned();
			$table->foreign('provance_id')->references('id')->on('provance')->onUpdate('cascade')->onDelete('restrict');
			$table->integer('country_id')->unsigned();
			$table->foreign('country_id')->references('id')->on('country')->onUpdate('cascade')->onDelete('restrict');
		});

		Schema::create('contact', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('firstname', 30)->nullable();
			$table->string('lastname', 50);
			$table->string('email', 80)->unique();
			$table->integer('mobile')->nullable()->unsigned();
			$table->integer('phone')->nullable()->unsigned();
			$table->text('note')->nullable();
			$table->integer('relation_id')->unsigned();
			$table->foreign('relation_id')->references('id')->on('relation')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('function_id')->unsigned();
			$table->foreign('function_id')->references('id')->on('contact_function')->onUpdate('cascade')->onDelete('restrict');
		});

		Schema::table('user_account', function(Blueprint $table)
		{
			$table->integer('self_id')->unsigned()->nullable();
			$table->foreign('self_id')->references('id')->on('relation')->onUpdate('cascade')->onDelete('restrict');
		});

		Schema::table('iban', function(Blueprint $table)
		{
			$table->integer('relation_id')->unsigned();
			$table->foreign('relation_id')->references('id')->on('relation')->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::table('project', function(Blueprint $table)
		{
			$table->integer('client_id')->unsigned();
			$table->foreign('client_id')->references('id')->on('relation')->onUpdate('cascade')->onDelete('restrict');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('project', function(Blueprint $table)
		{
			$table->dropForeign('project_client_id_foreign');
		});

		Schema::table('iban', function(Blueprint $table)
		{
			$table->dropForeign('iban_relation_id_foreign');
		});

		Schema::table('user_account', function(Blueprint $table)
		{
			$table->dropForeign('user_account_self_id_foreign');
		});

		Schema::table('contact', function(Blueprint $table)
		{
			Schema::drop('contact');
		});

		Schema::table('relation', function(Blueprint $table)
		{
			Schema::drop('relation');
		});

		Schema::table('relation_kind', function(Blueprint $table)
		{
			Schema::drop('relation_kind');
		});

		Schema::table('relation_type', function(Blueprint $table)
		{
			Schema::drop('relation_type');
		});

		Schema::table('contact_function', function(Blueprint $table)
		{
			Schema::drop('contact_function');
		});
	}

}



