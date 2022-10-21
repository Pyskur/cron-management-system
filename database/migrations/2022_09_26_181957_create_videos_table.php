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
            $table->uuid('id')->primary();
            $table->string('title')->nullable();
            $table->string('filename')->nullable();
            $table->integer('status')->nullable();
            $table->integer('file_size')->nullable();
            $table->integer('geo_restrict')->nullable();
            $table->string('thumbnail')->nullable();
            $table->integer('thumbnail_count')->nullable();
            $table->string('parent_name', 45)->nullable();
            $table->string('src_url')->nullable();
            $table->string('uuid')->unique();
            $table->string('out_url')->nullable();
            $table->string('out_folder')->nullable();
            $table->double('out_folder_size')->nullable();
            $table->integer('drm_enabled')->nullable();
            $table->datetime('publish_date')->nullable();
            $table->datetime('unpublish_date')->nullable();
            $table->dropColumn('geo_restrict');
            $table->dropColumn('parent_name');
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
