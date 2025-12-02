<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataKuliah extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks_teori',
        'sks_praktikum',
        'sks_praktek_lapangan',
        'total_sks',
        'deskripsi',
        'prasyarat',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sks_teori' => 'integer',
        'sks_praktikum' => 'integer',
        'sks_praktek_lapangan' => 'integer',
        'total_sks' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke jadwal
     */
    public function jadwal(): HasMany
    {
        return $this->hasMany(Jadwal::class);
    }

    /**
     * Relasi ke nilai
     */
    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }

    /**
     * Relasi ke transkrip
     */
    public function transkrip(): HasMany
    {
        return $this->hasMany(Transkrip::class);
    }

    /**
     * Getter untuk nama mata kuliah lengkap
     */
    public function getNamaMkLengkapAttribute(): string
    {
        return $this->kode_mk . ' - ' . $this->nama_mk . ' (' . $this->total_sks . ' SKS)';
    }

    /**
     * Getter untuk total SKS
     */
    public function getTotalSksAttribute(): int
    {
        return $this->sks_teori + $this->sks_praktikum + $this->sks_praktek_lapangan;
    }

    /**
     * Getter untuk SKS utama
     */
    public function getSksUtamaAttribute(): int
    {
        return $this->total_sks;
    }
}