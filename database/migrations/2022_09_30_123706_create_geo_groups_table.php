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
        Schema::create('geo_groups', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_blacklist')->default(false);
            $table->boolean('is_global')->default(false);
            $table->foreignId('aws_cloudfront_distribution_id')->constrained();
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
        Schema::dropIfExists('geo_groups');
    }
};
