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
            $table->integer('length')->default(0);
            $table->integer('views')->default(0);
            $table->decimal('cost', 13, 2)->default(0);
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
            if (Schema::hasColumn('videos', 'length')){
                $table->dropColumn('length');
            }
            if (Schema::hasColumn('videos', 'views')){
                $table->dropColumn('views');
            }
            if (Schema::hasColumn('videos', 'cost')){
                $table->dropColumn('cost');
            }
        });
    }
};
