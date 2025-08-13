<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensis';

    protected $fillable = [
        'mahasiswa_id',
        'tanggal',
        'status',
        'keterangan',
        'user_id'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function mahasiswa(){
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id');
    }

    public function pengajuan(){
        return $this->hasOne(PengajuanModel::class, 'mahasiswa_id', 'id');
    }

    public function mahasiswaBimbingan()
    {
        return $this->hasOneThrough(
            MahasiswaModel::class,
            User::class,      
            'id',             
            'user_id',        
            'mahasiswa_id',   
            'id'              
        );
    }


}
