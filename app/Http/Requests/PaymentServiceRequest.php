<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentServiceRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Validasi otorisasi dilakukan di middleware
    }

    public function rules()
    {
        return [
            'confirmation_id' => 'required|exists:service_confirmation,confirmation_id',
            'metode_pembayaran' => 'required|in:cod,transfer',
            'bukti_pembayaran' => 'required_if:metode_pembayaran,transfer|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'payment_status' => 'required|in:selesai,ditolak',
        ];
    }

    public function messages()
    {
        return [
            'confirmation_id.required' => 'ID konfirmasi wajib diisi.',
            'confirmation_id.exists' => 'ID konfirmasi tidak ditemukan.',
            
            'metode_pembayaran.required' => 'Metode pembayaran harus dipilih.',
            'metode_pembayaran.in' => 'Metode pembayaran harus COD atau transfer.',

            'bukti_pembayaran.required_if' => 'Bukti pembayaran wajib diunggah jika memilih metode transfer.',
            'bukti_pembayaran.file' => 'Bukti pembayaran harus berupa file.',
            'bukti_pembayaran.mimes' => 'Format bukti pembayaran harus JPG, JPEG, PNG, atau PDF.',
            'bukti_pembayaran.max' => 'Ukuran file tidak boleh lebih dari 2MB.',
        ];
    }
}
