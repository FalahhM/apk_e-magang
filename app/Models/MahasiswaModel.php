<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaModel extends Model
{
    use HasFactory;
    protected $table = 'mahasiswas';

    protected $fillable = [
        'nama_mahasiswa',
        'nim',
        'jurusan',
        'dospem',
        'pengajuan_id',
        'user_id'
    ];

    public function absensis(){
        return $this->hasMany(\App\Models\Absensi::class, 'mahasiswa_id');
    }


    public function laporan(){
        return $this->hasMany(Laporan::class);
    }

    public function pengajuan(){
        return $this->belongsTo(PengajuanModel::class, 'pengajuan_id');
    }

    public function kampus(){
        return $this->belongsTo(User::class, 'user_id');
    }

}
