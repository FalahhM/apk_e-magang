<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dospem extends Model
{
    protected $fillable = ['nama_dospem', 'email', 'no_telp'];

    public function mahasiswas()
    {
        return $this->hasMany(MahasiswaModel::class, 'dospem_id');
    }
}
