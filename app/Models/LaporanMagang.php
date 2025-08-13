<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanMagang extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_id',
        'mahasiswa_id',
        'tanggal_kegiatan',
        'keterangan',
        'foto_dokumentasi',
        'file_kegiatan_magang',
        'integritas',
        'ketepatan_waktu',
        'keahlian',
        'teamwork',
        'komunikasi',
        'teknologi',
        'pengembangan_diri',
        'total_nilai',
        'predikat',
        'jumlah_edit',
        'jumlah_kirim',
        'terakhir_kirim_at',
    ];

    protected $casts = [
        'terakhir_kirim_at' => 'datetime',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanModel::class, 'pengajuan_id');
    }

    public function mahasiswas()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id');
    }

}
