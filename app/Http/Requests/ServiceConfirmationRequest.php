<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceConfirmationRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna diberi izin untuk melakukan request ini.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Pastikan rute memakai middleware admin
    }

    /**
     * Aturan validasi untuk permintaan konfirmasi layanan.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'booking_id' => 'required|exists:bookings,booking_id',
            'service_id' => 'required|exists:services,service_id',
            'service_status' => 'nullable|string|in:menunggu,dalam_pekerjaan,selesai,dibatalkan',
            'total_cost' => 'required|numeric|min:0',
            'admin_notes' => 'nullable|string',
        ];
    }

    /**
     * Pesan kesalahan kustom untuk validasi.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'booking_id.required' => 'Booking ID wajib diisi.',
            'booking_id.exists' => 'Booking ID tidak ditemukan.',

            'service_id.required' => 'Service ID wajib diisi.',
            'service_id.exists' => 'Service ID tidak valid.',

            'service_status.required' => 'Status layanan wajib diisi.',
            'service_status.string' => 'Status layanan harus berupa teks.',
            'service_status.in' => 'Status layanan harus salah satu dari: menunggu, dalam_pekerjaan, selesai, dibatalkan.',

            'total_cost.required' => 'Biaya total wajib diisi.',
            'total_cost.numeric' => 'Biaya total harus berupa angka.',
            'total_cost.min' => 'Biaya total tidak boleh kurang dari 0.',

            'admin_notes.string' => 'Catatan admin harus berupa teks.',
        ];
    }
}
