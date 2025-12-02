<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodeAkademik extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_periode',
        'tahun_akademik',
        'semester',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
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
     * Relasi ke krs
     */
    public function krs(): HasMany
    {
        return $this->hasMany(Krs::class);
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
     * Getter untuk nama periode lengkap
     */
    public function getNamaPeriodeLengkapAttribute(): string
    {
        return $this->tahun_akademik . ' - ' . $this->semester;
    }

    /**
     * Cek apakah periode aktif
     */
    public function isActive(): bool
    {
        return $this->status === 'aktif';
    }

    /**
     * Scope untuk periode aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope untuk periode terbaru
     */
    public function scopeTerbaru($query)
    {
        return $query->orderBy('tanggal_mulai', 'desc');
    }
}