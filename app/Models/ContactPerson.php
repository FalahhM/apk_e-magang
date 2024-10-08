<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactPerson extends Model
{
    use HasFactory;
    protected $table = 'contact_person';

    protected $fillable = [
        'namecp',
        'emailcp',
        'nohpcp',
        'jabatan',
        'user_id',
    ];
}
