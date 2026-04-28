<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        

        $users = [
            [
                'id'                => 1,
                'role_id'            => 1,
                'cedula'            => '71378991',
                'nombres'           => 'MANAGER',
                'apellidos'         => 'SUPPORT',
                'estado'            => 'ACTIVO',
                'email'             => 'pruebas.correo@helpdigital.com.co',
                // 'email_verified_at' => now(),
                'password'          => bcrypt('Cde2023?'),
                'remember_token'    => Str::random(10),
            ],
        ];    

        User::insert($users);

        // \App\Models\User::factory(50000)->create();
    }
}
