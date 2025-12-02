<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin Sistem',
            'email' => 'admin@akademix.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create dosen users
        User::factory()->count(2)->create([
            'role' => 'dosen',
        ]);

        // Create mahasiswa users
        User::factory()->count(4)->create([
            'role' => 'mahasiswa',
        ]);
    }
}