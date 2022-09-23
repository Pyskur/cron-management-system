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
        Schema::create('keys_smses', function (Blueprint $table) {
            $table->id();
            $table->string('from', 10)->nullable();
            $table->string('text')->nullable();
            $table->string('status', 50)->nullable();
            $table->string('friendly_name')->nullable();
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
        Schema::dropIfExists('keys_smses');
    }
};
