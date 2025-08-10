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
            $table->string('tanggal_kegiatan')->nullable()->after('pengajuan_id');
            $table->text('keterangan')->after('tanggal_kegiatan')->nullable();
            $table->string('foto_dokumentasi')->after('keterangan')->nullable();
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
            $table->dropColumn(['tanggal_kegiatan', 'keterangan', 'foto_dokumentasi']);
        });
    }
};
