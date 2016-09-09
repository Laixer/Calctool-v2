<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_group', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('group_name', 32);
        });

        Schema::create('product_category', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('category_name', 32);
            $table->integer('group_id')->unsigned();
            $table->foreign('group_id')->references('id')->on('product_group')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('product_sub_category', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('sub_category_name', 32);
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('product_category')->onUpdate('cascade')->onDelete('cascade');
        });

        DB::table('product')->delete();

        Schema::table('product', function(Blueprint $table)
        {
            $table->decimal('price', 11, 3)->unsigned()->change();
            $table->decimal('total_price', 11, 3)->nullable()->unsigned()->change();
            $table->dropForeign('product_group_id_foreign');
            $table->foreign('group_id')->references('id')->on('product_sub_category');
        });

        Schema::table('sub_group', function(Blueprint $table)
        {
            Schema::dropIfExists('sub_group');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {        
        Schema::table('product', function(Blueprint $table)
        {
            $table->dropForeign('product_group_id_foreign');
        });

        Schema::table('product_sub_category', function(Blueprint $table)
        {
            Schema::dropIfExists('product_sub_category');
        });
 
        Schema::table('product_category', function(Blueprint $table)
        {
            Schema::dropIfExists('product_category');
        });

        Schema::table('product_group', function(Blueprint $table)
        {
            Schema::dropIfExists('product_group');
        });
    }
}
