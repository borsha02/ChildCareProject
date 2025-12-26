<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'borsha',
            'email' => 'borsha12@gmail.com',
            'phone' => '01974871196',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);

        $user = User::create([
            'name' => 'jinia',
            'email' => 'jinia@gmail.com',
            'phone' => '01974871194',
            'password' => Hash::make('12345678'),
            'role' => 'caregiver',
        ]);

        $user = User::create([
            'name' => 'lima',
            'email' => 'lima@gmail.com',
            'phone' => '01974871193',
            'password' => Hash::make('12345678'),
            'role' => 'caregiver',
        ]);
    }
}
