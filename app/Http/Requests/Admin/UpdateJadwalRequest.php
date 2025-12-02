<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJadwalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'periode_akademik_id' => 'required|exists:periode_akademik,id',
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'dosen_id' => 'required|exists:dosen,id',
            'kelas_id' => 'required|exists:kelas,id',
            'ruang_id' => 'required|exists:ruang,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'periode_akademik_id.required' => 'Periode akademik wajib dipilih',
            'periode_akademik_id.exists' => 'Periode akademik tidak ditemukan',
            'mata_kuliah_id.required' => 'Mata kuliah wajib dipilih',
            'mata_kuliah_id.exists' => 'Mata kuliah tidak ditemukan',
            'dosen_id.required' => 'Dosen wajib dipilih',
            'dosen_id.exists' => 'Dosen tidak ditemukan',
            'kelas_id.required' => 'Kelas wajib dipilih',
            'kelas_id.exists' => 'Kelas tidak ditemukan',
            'ruang_id.required' => 'Ruang wajib dipilih',
            'ruang_id.exists' => 'Ruang tidak ditemukan',
            'hari.required' => 'Hari wajib dipilih',
            'hari.in' => 'Hari harus salah satu dari: Senin, Selasa, Rabu, Kamis, Jumat, Sabtu',
            'jam_mulai.required' => 'Jam mulai wajib diisi',
            'jam_mulai.date_format' => 'Format jam mulai tidak valid',
            'jam_selesai.required' => 'Jam selesai wajib diisi',
            'jam_selesai.date_format' => 'Format jam selesai tidak valid',
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai',
        ];
    }
}