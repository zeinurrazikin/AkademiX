<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMataKuliahRequest extends FormRequest
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
            'kode_mk' => 'required|string|unique:mata_kuliah,kode_mk',
            'nama_mk' => 'required|string|max:255',
            'sks_teori' => 'required|integer|min:0',
            'sks_praktikum' => 'required|integer|min:0',
            'sks_praktek_lapangan' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'prasyarat' => 'nullable|string|max:255',
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
            'kode_mk.required' => 'Kode mata kuliah wajib diisi',
            'kode_mk.unique' => 'Kode mata kuliah sudah terdaftar',
            'nama_mk.required' => 'Nama mata kuliah wajib diisi',
            'nama_mk.max' => 'Nama mata kuliah maksimal 255 karakter',
            'sks_teori.required' => 'SKS teori wajib diisi',
            'sks_teori.integer' => 'SKS teori harus berupa angka',
            'sks_teori.min' => 'SKS teori minimal 0',
            'sks_praktikum.required' => 'SKS praktikum wajib diisi',
            'sks_praktikum.integer' => 'SKS praktikum harus berupa angka',
            'sks_praktikum.min' => 'SKS praktikum minimal 0',
            'sks_praktek_lapangan.required' => 'SKS praktek lapangan wajib diisi',
            'sks_praktek_lapangan.integer' => 'SKS praktek lapangan harus berupa angka',
            'sks_praktek_lapangan.min' => 'SKS praktek lapangan minimal 0',
            'prasyarat.max' => 'Prasyarat maksimal 255 karakter',
        ];
    }
}