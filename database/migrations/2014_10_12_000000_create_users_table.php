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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->string('first_name');
            $table->string('last_name');
            $table->timestamp('last_login')->nullable();
            $table->string('uniqueid', 50)->nullable();
            $table->tinyInteger('active')->nullable();
            $table->string('phone', 20)->nullable();
            $table->decimal('rate', 13, 2)->nullable();
            $table->decimal('balance', 13, 2)->nullable();
            $table->string('stripe_cust_id')->nullable();
            $table->string('stripe_plan_id', 50)->nullable();
            $table->string('subscription_id', 100)->nullable();
            $table->date('subscription_end_date')->nullable();
            $table->string('cvv', 50)->nullable();
            $table->string('verification_code', 50)->nullable();
            $table->string('verification_code_expiry', 50)->nullable();
            $table->string('verify_page_string')->nullable();
            $table->integer('is_verify')->nullable();
            $table->string('autologin_key')->nullable();
            $table->enum('login_type', ['1', '2', '3'])->default('1');
            $table->string('fingerprint')->nullable();
            $table->string('ip_address', 50)->nullable();
            $table->string('activation_selector')->nullable();
            $table->string('activation_code')->nullable();
            $table->string('forgotten_password_selector')->nullable();
            $table->string('forgotten_password_code')->nullable();
            $table->integer('forgotten_password_time')->nullable();
            $table->string('remember_selector')->nullable();
            $table->string('remember_code')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('epd_id')->nullable();
            $table->string('api_token')->nullable();
  
            $table->rememberToken();
            $table->timestamps();
        });
        //TODO keys
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
