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
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('auto_renew_id')->nullable();
            $table->foreign('auto_renew_id')->references('id')->on('auto_renews');
        });
        Schema::table('notifications', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users');
        });
        Schema::table('crons', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users');
        });
        Schema::table('keys', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->uuid('keys_ref_id')->nullable();
            $table->foreign('keys_ref_id')->references('id')->on('keys_refs');
            $table->uuid('keys_code_id')->nullable();
            $table->foreign('keys_code_id')->references('id')->on('keys_codes');
            $table->uuid('keys_sms_id')->nullable();
            $table->foreign('keys_sms_id')->references('id')->on('keys_smses');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users');
        });
        Schema::table('videos', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->uuid('geo_group_id');
            $table->foreign('geo_group_id')->references('id')->on('geo_groups');
        });
        Schema::table('geo_groups', function (Blueprint $table) {
            $table->uuid('aws_cloudfront_distribution_id');
            $table->foreign('aws_cloudfront_distribution_id')->references('id')->on('aws_cloudfront_distributions');
        });
        Schema::table('video_players', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users');
        });
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['auto_renew_id']);
            $table->dropColumn('auto_renew_id');
        });
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
        Schema::table('crons', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
        Schema::table('keys', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropForeign(['keys_ref_id']);
            $table->dropColumn('keys_ref_id');
            $table->dropForeign(['keys_code_id']);
            $table->dropColumn('keys_code_id');
            $table->dropForeign(['keys_sms_id']);
            $table->dropColumn('keys_sms_id');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
        Schema::table('videos', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropForeign(['geo_group_id']);
            $table->dropColumn('geo_group_id');
        });
        Schema::table('geo_groups', function (Blueprint $table) {
            $table->dropForeign(['aws_cloudfront_distribution_id']);
            $table->dropColumn('aws_cloudfront_distribution_id');
        });
        Schema::table('video_players', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
