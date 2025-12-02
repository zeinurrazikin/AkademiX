<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'dosen')->get();
        
        foreach ($users as $index => $user) {
            $nip = 'NIP' . str_pad(2022000000 + $index + 1, 10, '0', STR_PAD_LEFT);
            
            Dosen::create([
                'user_id' => $user->id,
                'nip' => $nip,
                'nama_lengkap' => $user->name,
                'gelar_depan' => fake()->randomElement(['', 'Dr.', 'Prof.']),
                'gelar_belakang' => fake()->randomElement(['', 'S.T.', 'M.T.', 'S.Kom.', 'M.Kom.']),
                'phone' => fake()->phoneNumber(),
                'alamat' => fake()->address(),
                'tanggal_lahir' => fake()->dateTimeBetween('-60 years', '-25 years'),
                'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            ]);
        }
    }
}