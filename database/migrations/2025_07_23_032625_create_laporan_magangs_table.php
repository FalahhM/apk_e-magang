<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_magangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengajuan_id');
            $table->integer('integritas');
            $table->integer('ketepatan_waktu');
            $table->integer('keahlian');
            $table->integer('teamwork');
            $table->integer('komunikasi');
            $table->integer('teknologi'); // penggunaan teknologi informasi
            $table->integer('pengembangan_diri');
            $table->integer('total_nilai');
            $table->string('predikat');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('pengajuan_id')->references('id')->on('pengajuans')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_magangs');
    }
};
