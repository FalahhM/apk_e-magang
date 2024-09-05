<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanModel extends Model
{
    use HasFactory;
    protected $table = 'pengajuans';

    protected $fillable = [
        'no_surat',
        'perihal',
        'tanggal_surat',
        'dokumen',
        'user_id',
    ];
}
