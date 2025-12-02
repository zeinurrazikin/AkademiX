<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mahasiswa extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nim',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'phone',
        'alamat',
        'tahun_masuk',
        'status',
        'ipk',
        'total_sks',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'ipk' => 'decimal:2',
        'total_sks' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
     * Getter untuk nama lengkap dengan nim
     */
    public function getNamaLengkapWithNimAttribute(): string
    {
        return $this->nama_lengkap . ' (' . $this->nim . ')';
    }

    /**
     * Getter untuk usia
     */
    public function getUsiaAttribute(): int
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : 0;
    }

    /**
     * Getter untuk status akademik
     */
    public function getStatusAkademikAttribute(): string
    {
        $statusMap = [
            'aktif' => 'Aktif',
            'cuti' => 'Cuti',
            'lulus' => 'Lulus',
            'drop' => 'Drop Out',
        ];
        return $statusMap[$this->status] ?? $this->status;
    }
}