<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_tag', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name', 32)->nullable();
        });

        Schema::table('user_account', function(Blueprint $table) {
            $table->integer('user_tag_id')->unsigned()->nullable();
            $table->foreign('user_tag_id')->references('id')->on('user_tag')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_account', function(Blueprint $table) {   
            $table->dropColumn('user_tag_id');
        });

        Schema::table('user_tag', function(Blueprint $table)
        {
            Schema::dropIfExists('user_tag');
        });
    }
}
