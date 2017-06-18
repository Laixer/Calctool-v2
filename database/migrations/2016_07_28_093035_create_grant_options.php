<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGrantOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oauth_clients', function(Blueprint $table) {
            $table->boolean('grant_authorization_code')->default('N');
            $table->boolean('grant_implicit')->default('N');
            $table->boolean('grant_password')->default('N');
            $table->boolean('grant_client_credential')->default('N');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oauth_clients', function ($table) {
            $table->dropColumn('grant_authorization_code');
            $table->dropColumn('grant_implicit');
            $table->dropColumn('grant_password');
            $table->dropColumn('grant_client_credential');
        });
    }
}
