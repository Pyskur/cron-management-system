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
        Schema::create('epds', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('epd')->nullable();
            $table->integer('epd_interval')->nullable();
            $table->integer('timeout')->default(0);
            $table->integer('epd_daily')->nullable();
            $table->tinyInteger('service_type')->default(0);
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
        Schema::dropIfExists('epds');
    }
};
