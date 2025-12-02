<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PeriodeAkademik>
 */
class PeriodeAkademikFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tahun = fake()->year();
        $semester = fake()->randomElement(['Ganjil', 'Genap']);
        
        $tanggalMulai = $semester === 'Ganjil' 
            ? $tahun . '-08-01' 
            : $tahun . '-01-01';
        
        $tanggalSelesai = $semester === 'Ganjil' 
            ? $tahun . '-12-31' 
            : $tahun . '-06-30';

        return [
            'kode_periode' => $tahun . '-' . $semester,
            'tahun_akademik' => $tahun . '/' . ($tahun + 1),
            'semester' => $semester,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'status' => 'tutup',
        ];
    }

    /**
     * Set as aktif
     */
    public function aktif(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'aktif',
        ]);
    }
}