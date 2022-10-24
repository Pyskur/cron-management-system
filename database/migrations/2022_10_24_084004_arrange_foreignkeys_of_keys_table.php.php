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
        Schema::table('keys', function (Blueprint $table) {
            $table->dropForeign(['keys_ref_id']);
            $table->dropColumn('keys_ref_id');
            $table->dropForeign(['keys_code_id']);
            $table->dropColumn('keys_code_id');
            $table->dropColumn('otp_key');
            $table->dropColumn('video_enabled');
        });
        Schema::table('keys_smses', function (Blueprint $table) {
            $table->uuid('keys_ref_id')->nullable();
            $table->foreign('keys_ref_id')->references('id')->on('keys_refs');
            $table->uuid('keys_code_id')->nullable();
            $table->foreign('keys_code_id')->references('id')->on('keys_codes');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('keys', function (Blueprint $table) {
            $table->uuid('keys_ref_id')->nullable();
            $table->foreign('keys_ref_id')->references('id')->on('keys_refs');
            $table->uuid('keys_code_id')->nullable();
            $table->foreign('keys_code_id')->references('id')->on('keys_codes');
            $table->tinyInteger('otp_key')->nullable();
            $table->integer('video_enabled')->nullable();

        });
        Schema::table('keys_smses', function (Blueprint $table) {
            $table->dropForeign(['keys_ref_id']);
            $table->dropColumn('keys_ref_id');
            $table->dropForeign(['keys_code_id']);
            $table->dropColumn('keys_code_id');
        });
    }
};
