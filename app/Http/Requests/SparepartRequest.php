<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SparepartRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Middleware akan membatasi hanya admin
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048', // gunakan 'image' bukan 'image_url'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama sparepart wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',

            'price.required' => 'Harga wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',

            'stock_quantity.required' => 'Stok wajib diisi.',
            'stock_quantity.integer' => 'Stok harus berupa bilangan bulat.',

            'image.file' => 'File gambar tidak valid.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus JPG, JPEG, atau PNG.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
