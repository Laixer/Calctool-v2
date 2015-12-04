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
			$table->char('gender', 1)->nullable();
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
			$table->string('website', 180)->nullable();
			$table->text('note')->nullable();
			$table->text('notepad')->nullable();
			$table->integer('mobile')->nullable()->unsigned();
			$table->integer('phone')->nullable()->unsigned();
			$table->string('email', 80)->unique();
			$table->boolean('pref_mailings_optin')->default('N');
			$table->decimal('pref_hourrate_calc', 6, 3)->nullable();
			$table->decimal('pref_hourrate_more', 6, 3)->nullable();
			$table->tinyInteger('pref_profit_calc_contr_mat')->default(0)->unsigned();
			$table->tinyInteger('pref_profit_calc_contr_equip')->default(0)->unsigned();
			$table->tinyInteger('pref_profit_calc_subcontr_mat')->default(0)->unsigned();
			$table->tinyInteger('pref_profit_calc_subcontr_equip')->default(0)->unsigned();
			$table->tinyInteger('pref_profit_more_contr_mat')->default(0)->unsigned();
			$table->tinyInteger('pref_profit_more_contr_equip')->default(0)->unsigned();
			$table->tinyInteger('pref_profit_more_subcontr_mat')->default(0)->unsigned();
			$table->tinyInteger('pref_profit_more_subcontr_equip')->default(0)->unsigned();
			$table->text('pref_email_offer')->nullable()->default('Hierbij doe ik u mijn offerte betreffende bovengenoemd project toekomen.');
			$table->text('pref_offer_description')->nullable()->default('Bij deze doe ik u toekomen mijn prijsopgaaf betreffende het uit te voeren werk. Onderstaand zal ik het werk en de uit te voeren werkzaamheden specificeren zoals afgesproken.');
			$table->text('pref_closure_offer')->nullable()->default('Hopende u hiermee een passende aanbieding gedaan te hebben, zie ik uw reactie met genoegen tegemoet. ');
			$table->text('pref_email_invoice')->nullable()->default('Nog niet beschikbaar.');
			$table->text('pref_invoice_description')->nullable()->default('Bij deze doe ik u toekomen mijn factuur betreffende het uitgevoerde werk behorende bij offerte [projectnaam]. Hierin zit tevens het eventule meer- en minderwerk verwerkt zoals besproken.');
			$table->text('pref_invoice_closure')->nullable()->default('Met dank voor uw opdracht en vertrouwen.');
			$table->text('pref_email_invoice_first_reminder')->nullable()->default('Nog niet beschikbaar.');
			$table->text('pref_email_invoice_last_reminder')->nullable()->default('Nog niet beschikbaar.');
			$table->text('pref_email_invoice_first_demand')->nullable()->default('Nog niet beschikbaar.');
			$table->text('pref_email_invoice_last_demand')->nullable()->default('Nog niet beschikbaar.');
			$table->string('offernumber_prefix', 10)->default('OF');
			$table->smallinteger('offer_counter')->default(1)->unsigned();
			$table->string('invoicenumber_prefix', 10)->default('FA');
			$table->smallinteger('invoice_counter')->default(1)->unsigned();
			$table->decimal('administration_cost', 6, 2)->nullable()->default(12.50);
			$table->rememberToken();
			$table->nullableTimestamps();
			$table->integer('user_type')->unsigned();
			$table->foreign('user_type')->references('id')->on('user_type')->onUpdate('cascade')->onDelete('restrict');
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
			$table->decimal('hour_rate', 6, 3)->unsigned()->default(0);;
			$table->decimal('hour_rate_more', 6, 3)->nullable()->unsigned();
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
			$table->date('work_completion')->nullable();
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

		Schema::create('project_share', function(Blueprint $table)
		{
			$table->increments('id');
			$table->char('token', 40)->unique();
			$table->text('user_note')->nullable();
			$table->text('client_note')->nullable();
			$table->nullableTimestamps();
			$table->integer('project_id')->unsigned();
			$table->foreign('project_id')->references('id')->on('project')->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::create('resource', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('resource_name', 50);
			$table->string('file_location', 120)->unique();
			$table->integer('file_size')->unsigned();
			$table->text('description')->nullable();
			$table->boolean('unlinked')->default('N');
			$table->nullableTimestamps();
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('user_account')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('project_id')->nullable()->unsigned();
			$table->foreign('project_id')->references('id')->on('project')->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::create('payment', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('transaction', 16);
			$table->char('token', 40)->unique();
			$table->string('status', 16);
			$table->decimal('amount', 9, 3);
			$table->string('description', 100);
			$table->string('method', 25);
			$table->integer('increment');
			$table->timestamps();
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('user_account')->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::create('telegram', function(Blueprint $table)
		{
			$table->increments('id');
			$table->boolean('alert')->default('N');
			$table->integer('uid');
			$table->timestamps();
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('user_account')->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::create('audit', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('ip', 45);
			$table->string('event', 1024);
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

		Schema::table('audit', function(Blueprint $table)
		{
			Schema::dropIfExists('audit');
		});
		Schema::table('telegram', function(Blueprint $table)
		{
			Schema::dropIfExists('telegram');
		});
		Schema::table('payment', function(Blueprint $table)
		{
			Schema::dropIfExists('payment');
		});
		Schema::table('resource', function(Blueprint $table)
		{
			Schema::dropIfExists('resource');
		});
		Schema::table('project_share', function(Blueprint $table)
		{
			Schema::dropIfExists('project_share');
		});
		Schema::table('project', function(Blueprint $table)
		{
			Schema::dropIfExists('project');
		});
		Schema::table('project_type', function(Blueprint $table)
		{
			Schema::dropIfExists('project_type');
		});
		Schema::table('user_account', function(Blueprint $table)
		{
			Schema::dropIfExists('user_account');
		});
		Schema::table('user_type', function(Blueprint $table)
		{
			Schema::dropIfExists('user_type');
		});
		Schema::table('country', function(Blueprint $table)
		{
			Schema::dropIfExists('country');
		});
		Schema::table('province', function(Blueprint $table)
		{
			Schema::dropIfExists('province');
		});
	}

}
