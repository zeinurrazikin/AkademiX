<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;

class StoreKrsRequest extends FormRequest
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
            'jadwal_id' => 'required|array',
            'jadwal_id.*' => 'required|exists:jadwal,id',
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
            'jadwal_id.required' => 'Pilih minimal satu jadwal untuk KRS',
            'jadwal_id.array' => 'Format jadwal tidak valid',
            'jadwal_id.*.required' => 'Jadwal wajib dipilih',
            'jadwal_id.*.exists' => 'Jadwal yang dipilih tidak ditemukan',
        ];
    }
}