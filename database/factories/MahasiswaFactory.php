<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mahasiswa>
 */
class MahasiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tahunMasuk = fake()->year();
        $tanggalLahir = fake()->dateTimeBetween('-25 years', '-18 years');
        
        return [
            'nim' => $this->generateNim($tahunMasuk),
            'nama_lengkap' => fake()->name(),
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => $tanggalLahir,
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'phone' => fake()->phoneNumber(),
            'alamat' => fake()->address(),
            'tahun_masuk' => $tahunMasuk,
            'status' => 'aktif',
            'ipk' => fake()->randomElement([0.00, 2.50, 3.00, 3.50, 3.75]),
            'total_sks' => fake()->numberBetween(0, 144),
        ];
    }

    /**
     * Generate a unique NIM based on tahun masuk
     */
    private function generateNim($tahunMasuk): string
    {
        $random = str_pad(fake()->numberBetween(1000, 9999), 4, '0', STR_PAD_LEFT);
        return $tahunMasuk . $random;
    }

    /**
     * Set status to lulus
     */
    public function lulus(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'lulus',
            'ipk' => fake()->randomElement([3.00, 3.25, 3.50, 3.75]),
            'total_sks' => 144,
        ]);
    }

    /**
     * Set status to cuti
     */
    public function cuti(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cuti',
        ]);
    }

    /**
     * Set status to drop
     */
    public function drop(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'drop',
        ]);
    }
}