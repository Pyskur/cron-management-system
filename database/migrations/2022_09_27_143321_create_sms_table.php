<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms', function (Blueprint $table) {
            $table->id();
            $table->string('number_to_send', 24);
            $table->string('uniqueid', 32);
            $table->integer('date_expires');
            $table->integer('status');
            $table->string('cost', 4);
            $table->string('charge', 4);
            $table->datetime('date_added');
            $table->integer('user_id');
            $table->integer('log');
            $table->integer('key_id');
            $table->string('code_variable', 445);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms');
    }
};
