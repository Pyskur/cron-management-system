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
        Schema::table('billingdetails', function (Blueprint $table) {
            $table->boolean('billed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('billingdetails', function (Blueprint $table) {
            if (Schema::hasColumn('billingdetails', 'billed')){
                $table->dropColumn('billed');
            }
        });
    }
};
