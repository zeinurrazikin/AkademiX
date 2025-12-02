<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nilai extends Model
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
        'dosen_id',
        'periode_akademik_id',
        'jadwal_id',
        'nilai_angka',
        'nilai_huruf',
        'nilai_mutu',
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
     * Relasi ke dosen
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }

    /**
     * Relasi ke periode akademik
     */
    public function periodeAkademik(): BelongsTo
    {
        return $this->belongsTo(PeriodeAkademik::class);
    }

    /**
     * Relasi ke jadwal
     */
    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class);
    }

    /**
     * Getter untuk konversi nilai huruf
     */
    public function getNilaiHurufAttribute(): string
    {
        $nilai = $this->attributes['nilai_angka'];
        if ($nilai === null) {
            return '-';
        }
        
        if ($nilai >= 85) return 'A';
        if ($nilai >= 80) return 'A-';
        if ($nilai >= 75) return 'B+';
        if ($nilai >= 70) return 'B';
        if ($nilai >= 65) return 'B-';
        if ($nilai >= 60) return 'C+';
        if ($nilai >= 55) return 'C';
        if ($nilai >= 40) return 'D';
        return 'E';
    }

    /**
     * Getter untuk konversi nilai mutu
     */
    public function getNilaiMutuAttribute(): float
    {
        $nilai = $this->attributes['nilai_angka'];
        if ($nilai === null) {
            return 0.00;
        }
        
        if ($nilai >= 85) return 4.00;
        if ($nilai >= 80) return 3.70;
        if ($nilai >= 75) return 3.30;
        if ($nilai >= 70) return 3.00;
        if ($nilai >= 65) return 2.70;
        if ($nilai >= 60) return 2.30;
        if ($nilai >= 55) return 2.00;
        if ($nilai >= 40) return 1.00;
        return 0.00;
    }

    /**
     * Setter untuk nilai huruf
     */
    public function setNilaiHurufAttribute($value)
    {
        $this->attributes['nilai_huruf'] = $value;
    }

    /**
     * Setter untuk nilai mutu
     */
    public function setNilaiMutuAttribute($value)
    {
        $this->attributes['nilai_mutu'] = $value;
    }
}