<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Pastikan hanya pelanggan yang bisa akses via middleware
    }

    public function rules()
    {
        return [
            'sparepart_id' => 'required|exists:spareparts,sparepart_id',
            'quantity' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'sparepart_id.required' => 'ID sparepart wajib diisi.',
            'sparepart_id.exists' => 'Sparepart tidak ditemukan.',
            'quantity.required' => 'Jumlah pembelian wajib diisi.',
            'quantity.integer' => 'Jumlah harus berupa bilangan bulat.',
            'quantity.min' => 'Minimal pembelian adalah 1.',
        ];
    }
}
