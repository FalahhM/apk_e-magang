<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMahasiswaIdToLaporanMagangTable extends Migration
{
    public function up()
    {
        Schema::table('laporan_magangs', function (Blueprint $table) {
            $table->unsignedBigInteger('mahasiswa_id')->after('id')->nullable();

            $table->foreign('mahasiswa_id')
                ->references('id')->on('mahasiswas')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('laporan_magang', function (Blueprint $table) {
            $table->dropForeign(['mahasiswa_id']);
            $table->dropColumn('mahasiswa_id');
        });
    }
}
