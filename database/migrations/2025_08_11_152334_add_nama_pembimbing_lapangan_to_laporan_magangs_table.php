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
        Schema::table('laporan_magangs', function (Blueprint $table) {
            $table->string('nama_pembimbing_lapangan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laporan_magangs', function (Blueprint $table) {
            $table->dropColumn('nama_pembimbing_lapangan');
        });
    }
};
