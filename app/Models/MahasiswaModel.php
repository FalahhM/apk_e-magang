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
    ];
}
