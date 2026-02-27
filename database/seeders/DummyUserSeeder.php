<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class DummyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!User::where('email', 'system@example.com')->exists()) {
            User::create([
                'name' => 'System User',
                'email' => 'system@example.com',
                'password' => Hash::make('password123'), // hashed password
            ]);
        }
    }
}
