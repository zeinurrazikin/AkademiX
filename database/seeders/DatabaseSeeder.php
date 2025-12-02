<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            MahasiswaSeeder::class,
            DosenSeeder::class,
            PeriodeAkademikSeeder::class,
            MataKuliahSeeder::class,
            KelasSeeder::class,
            RuangSeeder::class,
            JadwalSeeder::class,
            KrsSeeder::class,
            NilaiSeeder::class,
        ]);
    }
}