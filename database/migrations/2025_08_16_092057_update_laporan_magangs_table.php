<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLaporanMagangsTable extends Migration
{
    public function up()
    {
        Schema::table('laporan_magangs', function (Blueprint $table) {
            // Hapus kolom penilaian yang tidak diperlukan (jika ada)
            $table->dropColumn([
                'integritas', 'ketepatan_waktu', 'keahlian', 'teamwork', 
                'komunikasi', 'teknologi', 'pengembangan_diri', 'total_nilai', 
                'predikat', 'jumlah_kirim', 'jumlah_edit', 'terakhir_kirim_at'
            ]);
            
            // Pastikan kolom untuk laporan kegiatan ada
            if (!Schema::hasColumn('laporan_magangs', 'mahasiswa_id')) {
                $table->unsignedBigInteger('mahasiswa_id');
                $table->foreign('mahasiswa_id')->references('id')->on('mahasiswas')->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('laporan_magangs', 'tanggal_kegiatan')) {
                $table->date('tanggal_kegiatan');
            }
            
            if (!Schema::hasColumn('laporan_magangs', 'keterangan')) {
                $table->text('keterangan');
            }
            
            if (!Schema::hasColumn('laporan_magangs', 'foto_dokumentasi')) {
                $table->string('foto_dokumentasi')->nullable();
            }
            
            if (!Schema::hasColumn('laporan_magangs', 'nama_pembimbing_lapangan')) {
                $table->string('nama_pembimbing_lapangan')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('laporan_magangs', function (Blueprint $table) {
            // Rollback jika diperlukan
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
        });
    }
}

