<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'falahmubarak98@gmail.com', 
            'password' => Hash::make('12345'), 
            'alamat' => 'paal 10',
            'no_telp' => '985979494',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }
}
