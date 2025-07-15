<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'integritas',
        'ketepatan_waktu',
        'keahlian',
        'teamwork',
        'komunikasi',
        'penggunaan_ti',
        'pengembangan_diri',
        'rata_rata',
        'kategori',
        'ditandatangani_oleh',
        'tanggal_dinilai'
    ];

    public function mahasiswas(){
        return $this->belongsTo(MahasiswaModel::class);
    }
}
