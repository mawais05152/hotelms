<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate([
            'email' => 'muhammadawais05152@gmail.com',
        ], [
            'name' => 'Admin User',
            'password' => Hash::make('admin123'),
            'role' => 'Admin',
        ]);
    }
}
