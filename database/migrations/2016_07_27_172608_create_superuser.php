<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \CalculatieTool\Models\UserType;

class CreateSuperuser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        UserType::create(array('user_type' => 'superuser'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        UserType::where('user_type', 'superuser')->delete();
    }
}
