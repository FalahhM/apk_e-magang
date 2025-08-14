<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dospem extends Model
{
    protected $fillable = [
        'nama_dospem', 
        'email', 
        'no_telp', 
        'user_id', 
        'pengajuan_id'];

    public function mahasiswas()
    {
        return $this->hasMany(MahasiswaModel::class, 'dospem_id');
    }

    public function pengajuan()
    {
        return $this->belongsTo(\App\Models\PengajuanModel::class, 'pengajuan_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

}
