<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PelangganRequest extends FormRequest
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
            'name' => 'nullable|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Maksimal 2MB
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
            'name.required' => 'Nama pelanggan harus diisi.',
            'name.string' => 'Nama pelanggan harus berupa teks.',
            'name.max' => 'Nama pelanggan tidak boleh lebih dari 255 karakter.',
            'phone.string' => 'Nomor telepon harus berupa teks.',
            'phone.max' => 'Nomor telepon tidak boleh lebih dari 15 karakter.',
            'profile_picture.image' => 'Foto profil harus berupa gambar.',
            'profile_picture.mimes' => 'Foto profil harus berformat jpeg, png, jpg, atau gif.',
            'profile_picture.max' => 'Foto profil tidak boleh lebih dari 2MB.',
            'address.string' => 'Alamat harus berupa teks.',
        ];
    }
}
