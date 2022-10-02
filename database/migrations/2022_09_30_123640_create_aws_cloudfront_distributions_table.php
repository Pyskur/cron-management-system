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
        Schema::create('aws_cloudfront_distributions', function (Blueprint $table) {
            $table->id();
            $table->string('dist_id');
            $table->string('description')->nullable();
            $table->string('domain_name');
            $table->string('alt_domain_name');
            $table->string('origin');
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
        Schema::dropIfExists('aws_cloudfront_distributions');
    }
};
