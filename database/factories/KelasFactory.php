<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kelas>
 */
class KelasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_kelas' => fake()->unique()->regexify('[A-Z]{2}[0-9]{2}'),
            'nama_kelas' => fake()->word() . ' ' . fake()->randomElement(['A', 'B', 'C', 'D']),
            'kuota' => fake()->numberBetween(20, 50),
        ];
    }

    /**
     * Set besar kuota
     */
    public function besar(): static
    {
        return $this->state(fn (array $attributes) => [
            'kuota' => fake()->numberBetween(50, 100),
        ]);
    }
}