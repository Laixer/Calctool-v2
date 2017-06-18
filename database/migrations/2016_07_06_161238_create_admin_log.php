<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_log', function(Blueprint $table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->string('note', 100);
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('user_account')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_log', function(Blueprint $table)
        {
            Schema::dropIfExists('admin_log');
        });
    }
}
