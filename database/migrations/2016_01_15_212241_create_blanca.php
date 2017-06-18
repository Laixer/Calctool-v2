<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlanca extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blanc_row', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('description', 100);
            $table->decimal('rate', 9, 3)->unsigned()->index();
            $table->decimal('amount', 9, 3)->unsigned()->index();
            $table->integer('tax_id')->unsigned();
            $table->foreign('tax_id')->references('id')->on('tax')->onUpdate('cascade')->onDelete('restrict');
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('project')->onUpdate('cascade')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('blanc_row', function(Blueprint $table)
        {
            Schema::dropIfExists('blanc_row');
        });
    }
}
