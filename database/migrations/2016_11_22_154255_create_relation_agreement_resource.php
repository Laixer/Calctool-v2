<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationAgreementResource extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('relation', function(Blueprint $table) {
            $table->integer('agreement_id')->unsigned()->nullable();
            $table->foreign('agreement_id')->references('id')->on('resource')->onUpdate('cascade')->onDelete('restrict');
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
            $table->dropColumn('agreement_id');
        });
    }
}
