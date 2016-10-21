<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditPrefText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('telegram', function(Blueprint $table)
        {
            Schema::dropIfExists('telegram');
        });

        Schema::table('user_account', function(Blueprint $table) {
            $table->text('pref_invoice_description')->nullable()->default('Bij deze doe ik u toekomen mijn factuur betreffende het uitgevoerde werk behorende bij offerte. Hierin zit tevens het eventuele meer- en minderwerk en de stelpost verwerkt zoals besproken.')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
