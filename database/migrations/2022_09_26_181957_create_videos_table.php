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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->dateTime('date_registered')->nullable();
            $table->string('filename')->nullable();
            $table->integer('status')->nullable();
            $table->string('file_size', 45)->nullable();
            $table->integer('geo_restrict')->nullable();
            $table->string('thumbnail', 45)->nullable();
            $table->string('parent_name', 45)->nullable();
            $table->string('url')->nullable();
            $table->integer('drm_enabled')->nullable();
            $table->foreignId('user_id')->constrained();
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
        Schema::dropIfExists('videos');
    }
};
