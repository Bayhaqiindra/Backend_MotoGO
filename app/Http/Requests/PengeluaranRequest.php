<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PengeluaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Pastikan ini true agar request diizinkan
    }

    public function rules(): array
    {
        return [
            'jumlah_pengeluaran' => 'required|numeric|min:0',
            'deskripsi_pengeluaran' => 'required|string|max:1000',
            'category_pengeluaran' => 'required|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'jumlah_pengeluaran.required'     => 'Jumlah pengeluaran wajib diisi.',
            'jumlah_pengeluaran.numeric'      => 'Jumlah pengeluaran harus berupa angka.',
            'jumlah_pengeluaran.min'          => 'Jumlah pengeluaran tidak boleh negatif.',
            'deskripsi_pengeluaran.required'  => 'Deskripsi pengeluaran wajib diisi.',
            'deskripsi_pengeluaran.max'       => 'Deskripsi terlalu panjang, maksimal 1000 karakter.',
            'category_pengeluaran.required' => 'Kategori pengeluaran harus diisi.',
            'category_pengeluaran.string' => 'Kategori pengeluaran harus berupa teks.',
            'category_pengeluaran.max' => 'Kategori pengeluaran tidak boleh lebih dari 50 karakter.',
        ];
    }
}
