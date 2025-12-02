<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dosen>
 */
class DosenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nip' => 'NIP' . fake()->unique()->numberBetween(1000000000, 9999999999),
            'nama_lengkap' => fake()->name(),
            'gelar_depan' => fake()->randomElement(['', 'Dr.', 'Prof.', 'Dr. Ir.']),
            'gelar_belakang' => fake()->randomElement(['', 'S.T.', 'M.T.', 'S.Kom.', 'M.Kom.', 'Ph.D.']),
            'phone' => fake()->phoneNumber(),
            'alamat' => fake()->address(),
            'tanggal_lahir' => fake()->dateTimeBetween('-60 years', '-25 years'),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
        ];
    }

    /**
     * Set with gelar
     */
    public function withGelar(): static
    {
        return $this->state(fn (array $attributes) => [
            'gelar_depan' => fake()->randomElement(['Dr.', 'Prof.', 'Dr. Ir.']),
            'gelar_belakang' => fake()->randomElement(['S.T.', 'M.T.', 'S.Kom.', 'M.Kom.', 'Ph.D.']),
        ]);
    }
}