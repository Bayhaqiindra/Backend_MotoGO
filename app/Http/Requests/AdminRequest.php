<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
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
            'name' => 'required|string|max:255', // Nama admin harus diisi, berupa string, dan maksimal 255 karakter
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Foto profil opsional, jenis file gambar, maksimal 2MB
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
            'name.required' => 'Nama admin harus diisi.',
            'name.string' => 'Nama admin harus berupa teks.',
            'name.max' => 'Nama admin tidak boleh lebih dari 255 karakter.',
            'profile_picture.image' => 'Foto profil harus berupa gambar.',
            'profile_picture.mimes' => 'Foto profil harus berformat jpeg, png, jpg, atau gif.',
            'profile_picture.max' => 'Foto profil tidak boleh lebih dari 2MB.',
        ];
    }
}
