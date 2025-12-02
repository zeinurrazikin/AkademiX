<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ruang>
 */
class RuangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_ruang' => fake()->unique()->regexify('R[0-9]{3}'),
            'nama_ruang' => fake()->word() . ' ' . fake()->numberBetween(100, 999),
            'lokasi' => fake()->randomElement(['Gedung A', 'Gedung B', 'Gedung C', 'Gedung D']),
            'kapasitas' => fake()->numberBetween(20, 100),
        ];
    }

    /**
     * Set besar kapasitas
     */
    public function besar(): static
    {
        return $this->state(fn (array $attributes) => [
            'kapasitas' => fake()->numberBetween(100, 200),
        ]);
    }
}