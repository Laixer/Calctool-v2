<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_element', function(Blueprint $table)
        {
            Schema::dropIfExists('product_element');
        });
        
        Schema::table('element', function(Blueprint $table)
        {
            Schema::dropIfExists('element');
        });

        Schema::create('favorite_activity', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('activity_name', 100);
            $table->text('note')->nullable();
            $table->nullableTimestamps();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('user_account')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('tax_labor_id')->unsigned();
            $table->foreign('tax_labor_id')->references('id')->on('tax')->onUpdate('cascade')->onDelete('restrict');
            $table->integer('tax_material_id')->unsigned();
            $table->foreign('tax_material_id')->references('id')->on('tax')->onUpdate('cascade')->onDelete('restrict');
            $table->integer('tax_equipment_id')->unsigned();
            $table->foreign('tax_equipment_id')->references('id')->on('tax')->onUpdate('cascade')->onDelete('restrict');
        });

        Schema::create('favorite_labor', function(Blueprint $table)
        {
            $table->increments('id');
            $table->decimal('rate', 6, 3)->unsigned()->nullable();
            $table->decimal('amount', 9, 3)->unsigned()->index();
            $table->integer('activity_id')->unsigned();
            $table->foreign('activity_id')->references('id')->on('favorite_activity')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('favorite_material', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('material_name', 100);
            $table->string('unit', 10);
            $table->decimal('rate', 9, 3)->unsigned()->index();
            $table->decimal('amount', 9, 3)->unsigned()->index();
            $table->integer('activity_id')->unsigned();
            $table->foreign('activity_id')->references('id')->on('favorite_activity')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('favorite_equipment', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('equipment_name', 100);
            $table->string('unit', 10);
            $table->decimal('rate', 9, 3)->unsigned()->index();
            $table->decimal('amount', 9, 3)->unsigned()->index();
            $table->integer('activity_id')->unsigned();
            $table->foreign('activity_id')->references('id')->on('favorite_activity')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('favorite_equipment', function(Blueprint $table)
        {
            Schema::dropIfExists('favorite_equipment');
        });

        Schema::table('favorite_material', function(Blueprint $table)
        {
            Schema::dropIfExists('favorite_material');
        });

        Schema::table('favorite_labor', function(Blueprint $table)
        {
            Schema::dropIfExists('favorite_labor');
        });

        Schema::table('favorite_activity', function(Blueprint $table)
        {
            Schema::dropIfExists('favorite_activity');
        });
    }
}
