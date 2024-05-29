<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeleteAccountRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('delete_account_requests', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->enum('type', ['user', 'seller']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('delete_account_requests');
    }
}
