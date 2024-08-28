<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userData = [
            [
                'name'=>'admin',
                'email'=>'admin@gmail.com',
                'alamat'=>'Paal 10',
                'no_telp'=>'0852723572176',
                'role'=>'admin',
                'password'=>bcrypt('12345')
            ],
            [
                'name'=>'Universitas Nurdin Hamzah',
                'email'=>'nurdinhamzah@gmail.com',
                'alamat'=>'Sipin',
                'no_telp'=>'9392834',
                'role'=>'kampus',
                'password'=>bcrypt('11111')
            ]
        ];

        foreach($userData as $key => $val){
            User::create($val);
        }
    }
}
