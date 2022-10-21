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
        Schema::create('http_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('authentication')->nullable();
            $table->string('authentication_username', 50)->nullable();
            $table->string('authentication_password', 50)->nullable();
            $table->string('method', 50)->nullable();
            $table->string('message_body', 50)->nullable();
            $table->string('headers', 50)->nullable();
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
        Schema::dropIfExists('http_settings');
    }
};
