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
        Schema::create('crons', function (Blueprint $table) {
            $table->id();
            $table->string('uniqueid', 50);
            $table->string('name', 50);
            $table->string('action', 50);
            $table->string('expression', 50);
            $table->datetime('date_last_run')->nullable();
            $table->datetime('date_next_run')->nullable();
            $table->integer('next_cron_id')->default(0);
            $table->foreignId('user_id')->constrained();
            $table->string('status', 50)->default(0);
            $table->tinyInteger('is_running');
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->string('timezone', 50);
            $table->string('location', 50);
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
        Schema::dropIfExists('crons');
    }
};
