<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanModel extends Model
{
    use HasFactory;
    protected $table = 'pengajuans';
    protected $fillable = ['no_surat', 'tanggal_surat', 'perihal', 'dokumen', 'status', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function mahasiswas()
    {
        return $this->hasMany(MahasiswaModel::class,'pengajuan_id','id');
    }
}
