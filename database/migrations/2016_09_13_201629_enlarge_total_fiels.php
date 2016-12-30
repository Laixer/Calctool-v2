<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EnlargeTotalFiels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offer', function(Blueprint $table)
        {
            $table->decimal('offer_total', 11, 3)->unsigned()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offer', function(Blueprint $table)
        {
            $table->decimal('offer_total', 9, 3)->unsigned()->change();
        });
    }
}
