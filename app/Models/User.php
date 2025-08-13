<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'alamat',
        'no_telp',
        'password',
        'role',
        'email_verification_token',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function contactPerson()
    {
        return $this->hasOne(ContactPerson::class, 'user_id', 'id');
    }

    public function kampus()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mahasiswa()
    {
        return $this->hasOne(\App\Models\MahasiswaModel::class, 'user_id', 'id');
    }

    public function dospem()
    {
        return $this->hasOne(Dospem::class, 'user_id', 'id');
    }


}
