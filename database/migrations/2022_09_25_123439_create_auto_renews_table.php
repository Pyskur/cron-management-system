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
        Schema::create('auto_renews', function (Blueprint $table) {
            $table->id();
            $table->string('auto_renew_min_amt')->nullable();
            $table->string('auto_renew_amt', 50)->nullable();
            $table->string('auto_renewal', 100)->nullable();
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
        Schema::dropIfExists('auto_renews');
    }
};
