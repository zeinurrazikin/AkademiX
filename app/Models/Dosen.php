<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dosen extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nip',
        'nama_lengkap',
        'gelar_depan',
        'gelar_belakang',
        'phone',
        'alamat',
        'tanggal_lahir',
        'jenis_kelamin',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
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
     * Relasi ke mahasiswa melalui nilai
     */
    public function mahasiswa(): HasMany
    {
        return $this->hasManyThrough(
            Mahasiswa::class,
            Nilai::class,
            'dosen_id',
            'id',
            'id',
            'mahasiswa_id'
        );
    }

    /**
     * Getter untuk nama lengkap dengan gelar
     */
    public function getNamaLengkapWithGelarAttribute(): string
    {
        $gelarDepan = $this->gelar_depan ? $this->gelar_depan . ' ' : '';
        $gelarBelakang = $this->gelar_belakang ? ', ' . $this->gelar_belakang : '';
        return $gelarDepan . $this->nama_lengkap . $gelarBelakang;
    }
}