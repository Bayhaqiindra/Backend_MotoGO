<?php

namespace App\Http\Controllers;

use App\Http\Requests\SparepartRequest;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SparepartController extends Controller
{
    // Admin: Tambah sparepart
    public function addSparepart(SparepartRequest $request)
    {
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('spareparts', 'public');
        }

        $sparepart = Sparepart::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'image_url' => $imagePath,
        ]);

        return response()->json([
            'message' => 'Sparepart berhasil ditambahkan.',
            'data' => $sparepart,
        ], 201);
    }

    // Admin: Update sparepart
    public function updateSparepart(SparepartRequest $request, $id)
    {
        $sparepart = Sparepart::find($id);
        if (!$sparepart) return response()->json(['message' => 'Sparepart tidak ditemukan.'], 404);

        if ($request->hasFile('image')) {
            if ($sparepart->image_url && Storage::disk('public')->exists($sparepart->image_url)) {
                Storage::disk('public')->delete($sparepart->image_url);
            }
            $sparepart->image_url = $request->file('image')->store('spareparts', 'public');
        }

        $sparepart->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
        ]);

        return response()->json([
            'message' => 'Sparepart berhasil diperbarui.',
            'data' => $sparepart,
        ]);
    }

    // Admin: Hapus sparepart
    public function deleteSparepart($id)
    {
        $sparepart = Sparepart::find($id);
        if (!$sparepart) return response()->json(['message' => 'Sparepart tidak ditemukan.'], 404);

        if ($sparepart->image_url && Storage::disk('public')->exists($sparepart->image_url)) {
            Storage::disk('public')->delete($sparepart->image_url);
        }

        $sparepart->delete();

        return response()->json(['message' => 'Sparepart berhasil dihapus.']);
    }

    // Semua: Lihat semua sparepart
    public function getAllSpareparts()
    {
        return response()->json(Sparepart::all());
    }

    // Semua: Detail sparepart
    public function getSparepartById($id)
    {
        $sparepart = Sparepart::find($id);
        if (!$sparepart) return response()->json(['message' => 'Sparepart tidak ditemukan.'], 404);
        return response()->json($sparepart);
    }
}
