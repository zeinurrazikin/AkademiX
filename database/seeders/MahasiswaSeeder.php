<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'mahasiswa')->get();
        
        foreach ($users as $index => $user) {
            $tahunMasuk = '2022';
            $nim = $tahunMasuk . str_pad($index + 1, 4, '0', STR_PAD_LEFT);
            
            Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $nim,
                'nama_lengkap' => $user->name,
                'tempat_lahir' => fake()->city(),
                'tanggal_lahir' => fake()->dateTimeBetween('-25 years', '-18 years'),
                'jenis_kelamin' => fake()->randomElement(['L', 'P']),
                'phone' => fake()->phoneNumber(),
                'alamat' => fake()->address(),
                'tahun_masuk' => $tahunMasuk,
                'status' => 'aktif',
                'ipk' => fake()->randomElement([3.00, 3.25, 3.50, 3.75]),
                'total_sks' => fake()->numberBetween(40, 80),
            ]);
        }
    }
}