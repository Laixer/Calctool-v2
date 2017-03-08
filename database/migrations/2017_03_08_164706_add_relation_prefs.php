<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelationPrefs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('relation', function(Blueprint $table) {
            $table->decimal('hour_rate', 6, 3)->unsigned()->default(0);
            $table->decimal('hour_rate_more', 6, 3)->nullable()->unsigned();
            $table->tinyInteger('profit_calc_contr_mat')->unsigned()->default(0);
            $table->tinyInteger('profit_calc_contr_equip')->unsigned()->default(0);
            $table->tinyInteger('profit_calc_subcontr_mat')->unsigned()->default(0);
            $table->tinyInteger('profit_calc_subcontr_equip')->unsigned()->default(0);
            $table->tinyInteger('profit_more_contr_mat')->unsigned()->default(0);
            $table->tinyInteger('profit_more_contr_equip')->unsigned()->default(0);
            $table->tinyInteger('profit_more_subcontr_mat')->unsigned()->default(0);
            $table->tinyInteger('profit_more_subcontr_equip')->unsigned()->default(0);
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
            $table->dropColumn('hour_rate');
            $table->dropColumn('hour_rate_more');
            $table->dropColumn('profit_calc_contr_mat');
            $table->dropColumn('profit_calc_contr_equip');
            $table->dropColumn('profit_calc_subcontr_mat');
            $table->dropColumn('profit_calc_subcontr_equip');
            $table->dropColumn('profit_more_contr_mat');
            $table->dropColumn('profit_more_contr_equip');
            $table->dropColumn('profit_more_subcontr_mat');
            $table->dropColumn('profit_more_subcontr_equip');
        });
    }
}
