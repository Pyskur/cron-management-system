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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('user_name')->nullable();
            $table->string('email')->nullable();
            $table->string('plan_id', 100)->nullable();
            $table->string('plan_name')->nullable();
            $table->text('payment_response')->nullable();
            $table->string('payment_status', 55)->nullable();
            $table->string('created_date')->nullable();
            $table->string('amount', 55)->nullable();
            $table->string('client_secret')->nullable();
            $table->string('fingerprint')->nullable();
            $table->string('charge_id', 100)->nullable();
            $table->string('customer_id', 100)->nullable();
            $table->string('currency', 50)->nullable();
            $table->Integer('exp_month')->nullable();
            $table->Integer('exp_year')->nullable();
            $table->Integer('card_st_digit')->nullable();
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
        Schema::dropIfExists('orders');
    }
};
