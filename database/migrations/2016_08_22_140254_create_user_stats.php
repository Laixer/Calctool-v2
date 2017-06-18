<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserStats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_account', function(Blueprint $table) {
            $table->string('current_url', 180)->nullable();
            $table->integer('login_count')->default(0);
        });

        Schema::create('admin_log_label', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('label_name', 25);
        });

        DB::table('admin_log_label')->insert([
            ['label_name' => 'telefoon'],
            ['label_name' => 'email'],
            ['label_name' => 'tour'],
            ['label_name' => 'meeting'],
            ['label_name' => 'chat'],
        ]);

        DB::table('admin_log')->delete();

        Schema::table('admin_log', function(Blueprint $table) {
            $table->integer('label_id')->unsigned();
            $table->foreign('label_id')->references('id')->on('admin_log_label')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_log', function ($table) {
            $table->dropColumn('label_id');
        });

        Schema::table('admin_log_label', function(Blueprint $table)
        {
            Schema::dropIfExists('admin_log_label');
        });

        Schema::table('user_account', function ($table) {
            $table->dropColumn('current_url');
            $table->dropColumn('login_count');
        });
    }
}
