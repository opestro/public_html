<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubscriptionLastUpdateToShops extends Migration
{
    
      public function up()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->timestamp('subscription_last_update')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn('subscription_last_update');
        });
    }
}