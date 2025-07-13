<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna diberi izin untuk melakukan request ini.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Set to false jika hanya pengguna tertentu yang boleh melakukan request ini
    }

    /**
     * Mendapatkan aturan validasi yang diterapkan pada request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'service_name' => 'nullable|string|max:255',
            'service_cost' => 'nullable|numeric|min:0',
        ];
    }

    /**
     * Mendapatkan pesan kesalahan yang kustom untuk aturan validasi tertentu.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'service_name.required' => 'Nama layanan harus diisi.',
            'service_name.string' => 'Nama layanan harus berupa teks.',
            'service_name.max' => 'Nama layanan tidak boleh lebih dari 255 karakter.',
            'service_cost.required' => 'Biaya layanan harus diisi.',
            'service_cost.numeric' => 'Biaya layanan harus berupa angka.',
            'service_cost.min' => 'Biaya layanan tidak boleh kurang dari 0.',
        ];
    }
}
