<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'owner@nyapahbanyu.com'],
            [
                'name' => 'Haji Banyu (Pemilik)',
                'password' => Hash::make('password'),
                'role' => 'owner',
            ]
        );

        User::firstOrCreate(
            ['email' => 'sawit@nyapahbanyu.com'],
            [
                'name' => 'Ahmad (Pengurus Sawit)',
                'password' => Hash::make('password'),
                'role' => 'pengurus',
            ]
        );

        User::firstOrCreate(
            ['email' => 'kopi@nyapahbanyu.com'],
            [
                'name' => 'Andi Wijaya (Pengurus Kopi)',
                'password' => Hash::make('password'),
                'role' => 'pengurus',
            ]
        );

        User::firstOrCreate(
            ['email' => 'lada@nyapahbanyu.com'],
            [
                'name' => 'Siti Aminah (Pengurus Lada)',
                'password' => Hash::make('password'),
                'role' => 'pengurus',
            ]
        );
    }
}
