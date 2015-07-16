<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * User migration
 *
 * Color: Blue
 */
class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_type', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('user_type', 50)->unique();
		});

		Schema::create('province', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('province_name', 25)->unique();
		});

		Schema::create('country', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('country_name', 80)->unique();
		});

		Schema::create('user_account', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('username', 50)->unique();
			$table->string('secret', 64);
			$table->string('firstname', 30);
			$table->string('lastname', 50)->nullable();
			$table->char('api', 32)->unique();
			$table->char('token', 40)->unique();
			$table->string('ip', 45);
			$table->boolean('active')->default('Y');
			$table->boolean('api_access')->default('N');
			$table->dateTime('banned')->nullable();
			$table->dateTime('confirmed_mail')->nullable();
			$table->date('registration_date')->default(DB::raw('now()::timestamp(0)'));
			$table->date('expiration_date');
			$table->char('referral_key', 32)->unique();
			$table->string('address_street', 60)->nullable();
			$table->string('address_number', 5)->nullable();
			$table->string('address_postal', 6)->nullable();
			$table->string('address_city', 35)->nullable();
			$table->string('website', 180)->nullable();
			$table->text('note')->nullable();
			$table->integer('mobile')->nullable()->unsigned();
			$table->integer('phone')->nullable()->unsigned();
			$table->string('email', 80)->unique();
			$table->boolean('pref_mailings_optin')->default('N');
			$table->decimal('pref_hourrate_calc', 5, 2)->nullable();
			$table->decimal('pref_hourrate_more', 5, 2)->nullable();
			$table->tinyInteger('pref_profit_calc_contr_mat')->default(0)->unsigned();
			$table->tinyInteger('pref_profit_calc_contr_equip')->default(0)->unsigned();
			$table->tinyInteger('pref_profit_calc_subcontr_mat')->default(0)->unsigned();
			$table->tinyInteger('pref_profit_calc_subcontr_equip')->default(0)->unsigned();
			$table->tinyInteger('pref_profit_more_contr_mat')->default(0)->unsigned();
			$table->tinyInteger('pref_profit_more_contr_equip')->default(0)->unsigned();
			$table->tinyInteger('pref_profit_more_subcontr_mat')->default(0)->unsigned();
			$table->tinyInteger('pref_profit_more_subcontr_equip')->default(0)->unsigned();
			$table->text('pref_email_offer')->nullable();
			$table->text('pref_offer_description')->nullable();
			$table->text('pref_closure_offer')->nullable();
			$table->text('pref_email_invoice')->nullable();
			$table->text('pref_invoice_description')->nullable();
			$table->text('pref_invoice_closure')->nullable();
			$table->text('pref_email_invoice_first_reminder')->nullable();
			$table->text('pref_email_invoice_last_reminder')->nullable();
			$table->text('pref_email_invoice_first_demand')->nullable();
			$table->text('pref_email_invoice_last_demand')->nullable();
			$table->string('offernumber_prefix', 10)->default('OF');
			$table->smallinteger('offer_counter')->default(1)->unsigned();
			$table->string('invoicenumber_prefix', 10)->default('FA');
			$table->smallinteger('invoice_counter')->default(1)->unsigned();
			$table->decimal('administration_cost', 5, 2)->nullable();
			$table->rememberToken();
			$table->nullableTimestamps();
			$table->integer('province_id')->unsigned()->nullable();
			$table->foreign('province_id')->references('id')->on('province')->onUpdate('cascade')->onDelete('restrict');
			$table->integer('country_id')->unsigned()->nullable();
			$table->foreign('country_id')->references('id')->on('country')->onUpdate('cascade')->onDelete('restrict');
			$table->integer('user_type')->unsigned();
			$table->foreign('user_type')->references('id')->on('user_type')->onUpdate('cascade')->onDelete('restrict');
		});

		Schema::create('iban', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('iban', 25);
			$table->string('iban_name');
			$table->integer('user_id')->unsigned()->nullable();
			$table->foreign('user_id')->references('id')->on('user_account')->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::create('payment', function(Blueprint $table)
		{
			$table->increments('id');
			$table->date('payment_date');
			$table->date('due_date');
			$table->decimal('amount', 9, 2)->index();
			$table->boolean('payed')->default('N');
			$table->text('note')->nullable();
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('user_account')->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::create('project_type', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('type_name', 15)->unique();
		});

		Schema::create('project', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('project_name', 50);
			$table->string('address_street', 60);
			$table->string('address_number', 5);
			$table->string('address_postal', 6);
			$table->string('address_city', 35);
			$table->text('note')->nullable();
			$table->decimal('hour_rate', 5, 2)->unsigned()->default(0);;
			$table->decimal('hour_rate_more', 5, 2)->nullable()->unsigned();
			$table->tinyInteger('profit_calc_contr_mat')->unsigned()->default(0);
			$table->tinyInteger('profit_calc_contr_equip')->unsigned()->default(0);
			$table->tinyInteger('profit_calc_subcontr_mat')->unsigned()->default(0);
			$table->tinyInteger('profit_calc_subcontr_equip')->unsigned()->default(0);
			$table->tinyInteger('profit_more_contr_mat')->unsigned()->default(0);
			$table->tinyInteger('profit_more_contr_equip')->unsigned()->default(0);
			$table->tinyInteger('profit_more_subcontr_mat')->unsigned()->default(0);
			$table->tinyInteger('profit_more_subcontr_equip')->unsigned()->default(0);
			$table->nullableTimestamps();
			$table->date('work_execution')->nullable();
			$table->date('start_more')->nullable();
			$table->date('update_more')->nullable();
			$table->date('start_less')->nullable();
			$table->date('update_less')->nullable();
			$table->date('start_estimate')->nullable();
			$table->date('update_estimate')->nullable();
			$table->date('project_close')->nullable();
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('user_account')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('province_id')->unsigned();
			$table->foreign('province_id')->references('id')->on('province')->onUpdate('cascade')->onDelete('restrict');
			$table->integer('country_id')->unsigned();
			$table->foreign('country_id')->references('id')->on('country')->onUpdate('cascade')->onDelete('restrict');
			$table->integer('type_id')->unsigned();
			$table->foreign('type_id')->references('id')->on('project_type')->onUpdate('cascade')->onDelete('restrict');
		});

		Schema::create('resource', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('resource_name', 50);
			$table->string('file_location', 120)->unique();
			$table->integer('file_size')->unsigned();
			$table->text('description')->nullable();
			$table->nullableTimestamps();
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('user_account')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('project_id')->nullable()->unsigned();
			$table->foreign('project_id')->references('id')->on('project')->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::create('order', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('transaction', 16);
			$table->char('token', 40)->unique();
			$table->string('status', 16);
			$table->decimal('amount', 9, 2);
			$table->string('description', 100);
			$table->integer('increment');
			$table->timestamps();
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('user_account')->onUpdate('cascade')->onDelete('cascade');
		});

		$seq_user_account = "ALTER SEQUENCE user_account_id_seq RESTART WITH 1000";
		$seq_project = "ALTER SEQUENCE project_id_seq RESTART WITH 10000";
		$seq_order = "ALTER SEQUENCE project_id_seq RESTART WITH 1000";

		DB::unprepared($seq_user_account);
		DB::unprepared($seq_project);
		DB::unprepared($seq_order);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

		Schema::table('order', function(Blueprint $table)
		{
			Schema::drop('order');
		});

		Schema::table('resource', function(Blueprint $table)
		{
			Schema::drop('resource');
		});

		Schema::table('project', function(Blueprint $table)
		{
			Schema::drop('project');
		});

		Schema::table('project_type', function(Blueprint $table)
		{
			Schema::drop('project_type');
		});

		Schema::table('payment', function(Blueprint $table)
		{
			Schema::drop('payment');
		});

		Schema::table('iban', function(Blueprint $table)
		{
			Schema::drop('iban');
		});

		Schema::table('user_account', function(Blueprint $table)
		{
			Schema::drop('user_account');
		});

		Schema::table('user_type', function(Blueprint $table)
		{
			Schema::drop('user_type');
		});

		Schema::table('country', function(Blueprint $table)
		{
			Schema::drop('country');
		});

		Schema::table('province', function(Blueprint $table)
		{
			Schema::drop('province');
		});
	}

}
