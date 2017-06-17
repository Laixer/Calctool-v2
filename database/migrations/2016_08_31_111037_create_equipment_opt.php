<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentOpt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project', function(Blueprint $table) {
            $table->boolean('use_equipment')->default('N');
            $table->boolean('use_subcontract')->default('N');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project', function(Blueprint $table) {
            $table->dropColumn('use_equipment');
            $table->dropColumn('use_subcontract');
        });
    }
}
