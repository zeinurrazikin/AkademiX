<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRuangRequest extends FormRequest
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
            'kode_ruang' => [
                'required',
                'string',
                Rule::unique('ruang', 'kode_ruang')->ignore($this->route('ruang')->id),
            ],
            'nama_ruang' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
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
            'kode_ruang.required' => 'Kode ruang wajib diisi',
            'kode_ruang.unique' => 'Kode ruang sudah terdaftar',
            'nama_ruang.required' => 'Nama ruang wajib diisi',
            'nama_ruang.max' => 'Nama ruang maksimal 255 karakter',
            'lokasi.required' => 'Lokasi wajib diisi',
            'lokasi.max' => 'Lokasi maksimal 255 karakter',
            'kapasitas.required' => 'Kapasitas wajib diisi',
            'kapasitas.integer' => 'Kapasitas harus berupa angka',
            'kapasitas.min' => 'Kapasitas minimal 1',
        ];
    }
}