<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianMagang extends Model
{
    protected $table = 'penilaian_magangs';
    
    protected $fillable = [
        'pengajuan_id',
        'mahasiswa_id',
        'integritas',
        'ketepatan_waktu', 
        'keahlian',
        'teamwork',
        'komunikasi',
        'teknologi',
        'pengembangan_diri',
        'total_nilai',
        'predikat',
        'jumlah_kirim',
        'terakhir_kirim_at',
        'jumlah_edit'
    ];

    protected $casts = [
    'terakhir_kirim_at' => 'datetime',
    ];


    public function pengajuan()
    {
        return $this->belongsTo(PengajuanModel::class, 'pengajuan_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id');
    }

}