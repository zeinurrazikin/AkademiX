<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KrsDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'krs_id',
        'jadwal_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke krs
     */
    public function krs(): BelongsTo
    {
        return $this->belongsTo(Krs::class);
    }

    /**
     * Relasi ke jadwal
     */
    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class);
    }

    /**
     * Getter untuk mata kuliah dari jadwal
     */
    public function getMataKuliah()
    {
        return $this->jadwal->mataKuliah;
    }

    /**
     * Getter untuk dosen dari jadwal
     */
    public function getDosen()
    {
        return $this->jadwal->dosen;
    }

    /**
     * Getter untuk jumlah SKS
     */
    public function getSks(): int
    {
        return $this->jadwal->mataKuliah->total_sks;
    }
}