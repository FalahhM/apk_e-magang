<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_mahasiswa',
        'nim',
        'jurusan',
        'dospem',
        'mulai_tanggal',
        'sampai_tanggal',
        'user_id',
    ];
}
