<?php

// 1. Migration: create_penilaian_magangs_table.php
// Jalankan: php artisan make:migration create_penilaian_magangs_table

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenilaianMagangsTable extends Migration
{
    public function up()
    {
        Schema::create('penilaian_magangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengajuan_id');
            $table->integer('integritas')->default(0);
            $table->integer('ketepatan_waktu')->default(0);
            $table->integer('keahlian')->default(0);
            $table->integer('teamwork')->default(0);
            $table->integer('komunikasi')->default(0);
            $table->integer('teknologi')->default(0);
            $table->integer('pengembangan_diri')->default(0);
            $table->decimal('total_nilai', 5, 2)->default(0);
            $table->string('predikat')->nullable();
            $table->integer('jumlah_kirim')->default(0);
            $table->integer('jumlah_edit')->default(0);
            $table->timestamp('terakhir_kirim_at')->nullable();
            $table->timestamps();

            $table->foreign('pengajuan_id')->references('id')->on('pengajuans')->onDelete('cascade');
            $table->index('pengajuan_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('penilaian_magangs');
    }
}