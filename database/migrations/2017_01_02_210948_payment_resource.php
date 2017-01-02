<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PaymentResource extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment', function(Blueprint $table) {
            $table->integer('resource_id')->unsigned()->nullable();
            $table->foreign('resource_id')->references('id')->on('resource')->onUpdate('cascade')->onDelete('restrict');
        });

        Schema::create('ctinvoice', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('invoice_count')->unsigned()->default(0);
            $table->integer('payment_id')->unsigned()->nullable();
            $table->foreign('payment_id')->references('id')->on('payment')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ctinvoice', function(Blueprint $table)
        {
            Schema::dropIfExists('ctinvoice');
        });

        Schema::table('payment', function(Blueprint $table) {
            $table->dropColumn('resource_id');
        });
    }
}
