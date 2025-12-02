<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MataKuliah>
 */
class MataKuliahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sksTeori = fake()->numberBetween(1, 3);
        $sksPraktikum = fake()->numberBetween(0, 2);
        $sksPraktekLapangan = fake()->numberBetween(0, 1);
        $totalSks = $sksTeori + $sksPraktikum + $sksPraktekLapangan;

        return [
            'kode_mk' => fake()->unique()->regexify('[A-Z]{2}[0-9]{4}'),
            'nama_mk' => fake()->sentence(3, true),
            'sks_teori' => $sksTeori,
            'sks_praktikum' => $sksPraktikum,
            'sks_praktek_lapangan' => $sksPraktekLapangan,
            'total_sks' => $totalSks,
            'deskripsi' => fake()->paragraph(),
            'prasyarat' => fake()->randomElement(['', 'KOM101', 'KOM102', 'KOM103', 'KOM104']),
        ];
    }

    /**
     * Set as praktikum
     */
    public function praktikum(): static
    {
        return $this->state(fn (array $attributes) => [
            'sks_teori' => 1,
            'sks_praktikum' => 2,
            'sks_praktek_lapangan' => 0,
            'total_sks' => 3,
        ]);
    }

    /**
     * Set as teori
     */
    public function teori(): static
    {
        return $this->state(fn (array $attributes) => [
            'sks_teori' => 3,
            'sks_praktikum' => 0,
            'sks_praktek_lapangan' => 0,
            'total_sks' => 3,
        ]);
    }
}