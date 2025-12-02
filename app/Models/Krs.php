<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Krs extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mahasiswa_id',
        'periode_akademik_id',
        'nomor_krs',
        'status',
        'total_sks',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_sks' => 'integer',
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
     * Relasi ke periode akademik
     */
    public function periodeAkademik(): BelongsTo
    {
        return $this->belongsTo(PeriodeAkademik::class);
    }

    /**
     * Relasi ke krs_detail
     */
    public function krsDetail(): HasMany
    {
        return $this->hasMany(KrsDetail::class);
    }

    /**
     * Getter untuk jumlah mata kuliah
     */
    public function getJumlahMkAttribute(): int
    {
        return $this->krsDetail()->count();
    }

    /**
     * Getter untuk status lengkap
     */
    public function getStatusLengkapAttribute(): string
    {
        $statusMap = [
            'draft' => 'Draft',
            'diajukan' => 'Diajukan',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
        ];
        return $statusMap[$this->status] ?? $this->status;
    }

    /**
     * Cek apakah KRS bisa diedit
     */
    public function canEdit(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Cek apakah KRS sudah disetujui
     */
    public function isDisetujui(): bool
    {
        return $this->status === 'disetujui';
    }

    /**
     * Cek apakah KRS sudah diajukan
     */
    public function isDiajukan(): bool
    {
        return in_array($this->status, ['diajukan', 'disetujui', 'ditolak']);
    }
}