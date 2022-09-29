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
            $table->renameColumn('url','src_url');
            $table->string('uuid')->unique()->after('url');
            $table->string('out_url')->nullable()->after('uuid');
            $table->string('out_folder')->nullable()->after('out_url');
            $table->double('out_folder_size')->nullable()->after('out_folder');
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
            $table->renameColumn('src_url','url');
            $table->dropColumn('uuid');
            $table->dropColumn('out_url');
            $table->dropColumn('out_folder');
            $table->dropColumn('out_folder_size');
            $table->dropIndex(['uuid']);

        });
    }
};
