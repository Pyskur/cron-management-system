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
        Schema::table('videos', function (Blueprint $table) {
            $table->string('drm_keyid')->nullable();
            $table->string('out_url_dash')->nullable();
            $table->string('out_url_apple')->nullable();

            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $sm->listTableIndexes('videos');
            if(array_key_exists("videos_uuid_unique", $indexesFound))
                $table->dropUnique("videos_uuid_unique");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn('drm_keyid');
            $table->dropColumn('out_url_dash');
            $table->dropColumn('out_url_apple');
        });
    }
};
