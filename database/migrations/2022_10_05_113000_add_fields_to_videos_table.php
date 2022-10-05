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
            //
            $table->datetime('expire_time')->nullable();
            $table->dropColumn('geo_restrict');
            $table->dropColumn('parent_name');

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
            //
            $table->dropColumn('expire_time');
            $table->integer('geo_restrict')->nullable();
            $table->string('parent_name', 45)->nullable();
        });
    }
};
