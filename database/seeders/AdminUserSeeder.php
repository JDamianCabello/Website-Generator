<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Configura los datos del usuario admin
        $adminData = [
            'name'     => 'jdamiancabello fullstack developer',
            'email'    => 'admin@admin.com', 
            'password' => Hash::make('admin'),
        ];

        // Crear o buscar el usuario por email
        $admin = User::firstOrCreate(
            ['email' => $adminData['email']],
            $adminData
        );

        // Asignar el rol "admin" al usuario
        $admin->assignRole('admin');
    }
}
