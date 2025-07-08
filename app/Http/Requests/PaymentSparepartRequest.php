<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentSparepartRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Sesuaikan dengan middleware jika perlu
    }

    public function rules()
    {
        return [
            'transaction_id' => 'required|exists:transactions,transaction_id',
            'metode_pembayaran' => ['required', Rule::in(['transfer', 'cod'])],
            'bukti_pembayaran' => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048', // hanya wajib jika transfer
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $metode = $this->metode_pembayaran;
            if ($metode === 'transfer' && !$this->hasFile('bukti_pembayaran')) {
                $validator->errors()->add('bukti_pembayaran', 'Bukti pembayaran harus diunggah untuk metode transfer.');
            }
        });
    }

    public function messages()
    {
        return [
            'transaction_id.required' => 'ID transaksi wajib diisi.',
            'transaction_id.exists' => 'Transaksi tidak ditemukan.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib diisi.',
            'metode_pembayaran.in' => 'Metode pembayaran hanya boleh transfer atau cod.',
            'bukti_pembayaran.image' => 'Bukti harus berupa gambar.',
            'bukti_pembayaran.mimes' => 'Format gambar harus jpg, jpeg, atau png.',
            'bukti_pembayaran.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
