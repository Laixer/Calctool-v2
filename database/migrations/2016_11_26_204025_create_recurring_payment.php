<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecurringPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_account', function(Blueprint $table) {
            $table->timestamp('online_at')->nullable();
            $table->char('payment_customer_id', 14)->nullable();
            $table->char('payment_subscription_id', 14)->nullable();
        });
        
        Schema::table('payment', function(Blueprint $table) {
            $table->string('recurring_type', 32)->nullable();
            $table->char('subscription_id', 14)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment', function(Blueprint $table) {
            $table->dropColumn('recurring_type');
            $table->dropColumn('subscription_id');
        });

        Schema::table('user_account', function(Blueprint $table) {   
            $table->dropColumn('online_at');
            $table->dropColumn('payment_customer_id');
            $table->dropColumn('payment_subscription_id');
        });
    }
}
