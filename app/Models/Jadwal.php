<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jadwal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'periode_akademik_id',
        'mata_kuliah_id',
        'dosen_id',
        'kelas_id',
        'ruang_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'kode_jadwal',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke periode akademik
     */
    public function periodeAkademik(): BelongsTo
    {
        return $this->belongsTo(PeriodeAkademik::class);
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
     * Relasi ke kelas
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * Relasi ke ruang
     */
    public function ruang(): BelongsTo
    {
        return $this->belongsTo(Ruang::class);
    }

    /**
     * Relasi ke krs_detail
     */
    public function krsDetail(): HasMany
    {
        return $this->hasMany(KrsDetail::class);
    }

    /**
     * Relasi ke nilai
     */
    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }

    /**
     * Getter untuk informasi jadwal lengkap
     */
    public function getInfoJadwalAttribute(): string
    {
        return $this->mataKuliah->kode_mk . ' - ' . $this->mataKuliah->nama_mk . 
               ' | ' . $this->dosen->getNamaLengkapWithGelarAttribute() . 
               ' | ' . $this->hari . ' ' . $this->jam_mulai . '-' . $this->jam_selesai;
    }

    /**
     * Getter untuk durasi jam
     */
    public function getDurasiJamAttribute(): float
    {
        $mulai = \Carbon\Carbon::parse($this->jam_mulai);
        $selesai = \Carbon\Carbon::parse($this->jam_selesai);
        return $selesai->diffInMinutes($mulai) / 60;
    }

    /**
     * Cek apakah jadwal bentrok dengan jadwal lain
     */
    public function isBentrok($hari, $jam_mulai, $jam_selesai): bool
    {
        if ($this->hari !== $hari) {
            return false;
        }

        $jadwalMulai = \Carbon\Carbon::parse($this->jam_mulai);
        $jadwalSelesai = \Carbon\Carbon::parse($this->jam_selesai);
        $cekMulai = \Carbon\Carbon::parse($jam_mulai);
        $cekSelesai = \Carbon\Carbon::parse($jam_selesai);

        return ($cekMulai < $jadwalSelesai && $cekSelesai > $jadwalMulai);
    }
}