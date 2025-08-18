<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMahasiswaIdToPenilaianMagangsTable extends Migration
{
    public function up()
    {
        Schema::table('penilaian_magangs', function (Blueprint $table) {
            $table->unsignedBigInteger('mahasiswa_id')->after('pengajuan_id');

            $table->foreign('mahasiswa_id')
                  ->references('id')->on('mahasiswas')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('penilaian_magangs', function (Blueprint $table) {
            $table->dropForeign(['mahasiswa_id']);
            $table->dropColumn('mahasiswa_id');
        });
    }
}
