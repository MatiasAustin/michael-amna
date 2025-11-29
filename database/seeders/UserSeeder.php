<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrInsert(
            ['email' => 'admin@michaelamna.com'], // kunci unik
            [
                'name' => 'Admin User',
                'password' => Hash::make('MichaelAmna374*'), // pastikan untuk meng-hash password
            ]
        );
    }
}
