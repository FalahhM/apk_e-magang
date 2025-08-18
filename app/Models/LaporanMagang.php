<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanMagang extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'tanggal_kegiatan',
        'keterangan', 
        'foto_dokumentasi',
        'nama_pembimbing_lapangan'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id');
    }
}
