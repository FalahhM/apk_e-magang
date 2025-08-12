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
        'email',
        'nim',
        'jurusan',
        'dospem',
        'pengajuan_id',
        'user_id'
    ];

    public function absensis(){
        return $this->hasMany(\App\Models\Absensi::class, 'mahasiswa_id');
    }

    public function pengajuan(){
        return $this->belongsTo(PengajuanModel::class, 'pengajuan_id');
    }

    public function kampus(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function laporanMagang()
    {
        return $this->hasOne(LaporanMagang::class, 'mahasiswa_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // cek nama foreign key dan modelnya
    }


}
