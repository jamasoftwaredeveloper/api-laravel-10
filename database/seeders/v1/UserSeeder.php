<?php

namespace Database\Seeders\v1;

use App\Models\v1\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Developer',
            'email' => 'test@example.com',
            'password' => Hash::make('0713'), // Asegúrate de utilizar el método Hash para cifrar la contraseña
        ]);
    }
}
