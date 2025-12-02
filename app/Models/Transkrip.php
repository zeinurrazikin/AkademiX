<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transkrip extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mahasiswa_id',
        'mata_kuliah_id',
        'periode_akademik_id',
        'nilai_angka',
        'nilai_huruf',
        'nilai_mutu',
        'sks',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'nilai_angka' => 'decimal:2',
        'nilai_huruf' => 'string',
        'nilai_mutu' => 'decimal:2',
        'sks' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke mahasiswa
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    /**
     * Relasi ke mata kuliah
     */
    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class);
    }

    /**
     * Relasi ke periode akademik
     */
    public function periodeAkademik(): BelongsTo
    {
        return $this->belongsTo(PeriodeAkademik::class);
    }

    /**
     * Getter untuk status lulus
     */
    public function isLulus(): bool
    {
        return $this->nilai_mutu > 0;
    }

    /**
     * Getter untuk bobot mutu
     */
    public function getBobotMutuAttribute(): float
    {
        return $this->nilai_mutu * $this->sks;
    }
}