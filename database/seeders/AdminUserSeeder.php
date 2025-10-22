<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

           User::updateOrCreate(
                ['email' => 'admin2@anna.com'],
                [
                    'name' => 'anna',
                    'password' => Hash::make('anna123456'),
                    // 'role' => 'admin',
                ]
            );


    }
}
