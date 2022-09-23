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
        Schema::create('keys', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50);
            $table->string('key', 50);
            $table->integer('level');
            $table->tinyInteger('ignore_limits')->default(0);
            $table->tinyInteger('is_private_key')->default(0);
            $table->string('ip_address', 50)->nullable();
            $table->tinyInteger('otp_key')->nullable();
            $table->integer('video_enabled')->nullable();
            $table->foreignId('user_id')->constrained();

            $table->foreignId('keys_ref_id')->constrained();
            $table->foreignId('keys_code_id')->constrained();
            $table->foreignId('keys_sms_id')->constrained('keys_smses');
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
        Schema::dropIfExists('keys');
    }
};
