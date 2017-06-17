<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PasswordReset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_account', function(Blueprint $table) {
            $table->dropColumn('api');
            $table->dropColumn('token');
            $table->char('reset_token', 40)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_account', function(Blueprint $table) {
            $table->dropColumn('reset_token');
            $table->char('api', 32)->nullable();
            $table->char('token', 40)->nullable();
        });
    }
}
