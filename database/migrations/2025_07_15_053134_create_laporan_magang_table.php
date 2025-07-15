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
        Schema::create('laporan_magang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('pengajuan_id');

            // komponen penilaian
            $table->tinyInteger('integritas');
            $table->tinyInteger('ketepatan_waktu');
            $table->tinyInteger('keahlian');
            $table->tinyInteger('teamwork');
            $table->tinyInteger('komunikasi');
            $table->tinyInteger('penggunaan_teknologi');
            $table->tinyInteger('pengembangan_diri');

            // hasil nilai akhir dan keterangan
            $table->float('rata_rata')->nullable();
            $table->string('kategori')->nullable();

            // sertifikat
            $table->string('file_sertifikat')->nullable(); //pdf path

            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('id')->on('mahasiswas')->onDelete('cascade');
            $table->foreign('pengajuan_id')->references('id')->on('pengajuans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laporan_magang');
    }
};
