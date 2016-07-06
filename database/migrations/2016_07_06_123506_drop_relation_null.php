<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropRelationNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('relation', function(Blueprint $table) {
            $table->string('address_street', 50)->nullable()->change();
            $table->string('address_number', 5)->nullable()->change();
            $table->string('address_postal', 6)->nullable()->change();
            $table->string('address_city', 35)->nullable()->change();
            $table->integer('province_id')->unsigned()->nullable()->change();
            $table->integer('country_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('relation', function(Blueprint $table) {
            $table->string('address_street', 50)->change();
            $table->string('address_number', 5)->change();
            $table->string('address_postal', 6)->change();
            $table->string('address_city', 35)->change();
            $table->integer('province_id')->unsigned()->change();
            $table->integer('country_id')->unsigned()->change();
        });
    }
}
