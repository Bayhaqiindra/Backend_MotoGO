<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna diberi izin untuk melakukan request ini.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Set to true jika request dapat dijalankan oleh semua pengguna
    }

    /**
     * Mendapatkan aturan validasi yang diterapkan pada request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'service_id' => 'required|exists:services,service_id', // Memastikan service_id valid di tabel services
            'pickup_location' => 'required|string|max:255', // Memastikan lokasi penjemputan ada dan dalam bentuk string
            'customer_notes' => 'nullable|string|max:255', // Catatan pelanggan opsional
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
            'service_id.required' => 'Layanan harus dipilih.',
            'service_id.exists' => 'Layanan yang dipilih tidak valid.',
            'pickup_location.required' => 'Lokasi penjemputan harus diisi.',
            'pickup_location.string' => 'Lokasi penjemputan harus berupa teks.',
            'pickup_location.max' => 'Lokasi penjemputan tidak boleh lebih dari 255 karakter.',
            'customer_notes.string' => 'Catatan pelanggan harus berupa teks.',
            'customer_notes.max' => 'Catatan pelanggan tidak boleh lebih dari 255 karakter.',
        ];
    }
}
