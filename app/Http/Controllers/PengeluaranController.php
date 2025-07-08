<?php

namespace App\Http\Controllers;

use App\Http\Requests\PengeluaranRequest;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    /**
     * Admin: Tambah pengeluaran
     * POST /admin/pengeluarans
     */
    public function addPengeluaran(PengeluaranRequest $request)
    {
        $pengeluaran = Pengeluaran::create([
            'category_pengeluaran'    => $request->category_pengeluaran,
            'jumlah_pengeluaran'      => $request->jumlah_pengeluaran,
            'deskripsi_pengeluaran'   => $request->deskripsi_pengeluaran,
        ]);

        return response()->json([
            'message' => 'Data pengeluaran berhasil ditambahkan.',
            'data'    => $pengeluaran,
        ], 201);
    }

    /**
     * Admin: Lihat semua pengeluaran
     * GET /admin/pengeluarans
     */
    public function getAllPengeluaran()
    {
        $data = Pengeluaran::latest()->get();

        return response()->json([
            'message' => 'Daftar semua pengeluaran',
            'data'    => $data,
        ]);
    }
    

    /**
     * Admin: Update pengeluaran
     * PUT /admin/pengeluarans/{id}
     */
    public function updatePengeluaran(PengeluaranRequest $request, $id)
    {
        $pengeluaran = Pengeluaran::find($id);

        if (!$pengeluaran) {
            return response()->json(['message' => 'Data pengeluaran tidak ditemukan.'], 404);
        }

        $pengeluaran->update([
            'category_pengeluaran'    => $request->category_pengeluaran,
            'jumlah_pengeluaran'      => $request->jumlah_pengeluaran,
            'deskripsi_pengeluaran'   => $request->deskripsi_pengeluaran,
        ]);

        return response()->json([
            'message' => 'Data pengeluaran berhasil diperbarui.',
            'data'    => $pengeluaran,
        ]);
    }

    /**
     * Admin: Hapus pengeluaran
     * DELETE /admin/pengeluarans/{id}
     */
    public function deletePengeluaran($id)
    {
        $pengeluaran = Pengeluaran::find($id);

        if (!$pengeluaran) {
            return response()->json(['message' => 'Data pengeluaran tidak ditemukan.'], 404);
        }

        $pengeluaran->delete();

        return response()->json(['message' => 'Data pengeluaran berhasil dihapus.']);
    }

    public function getTotalPengeluaran()
    {
        $total = Pengeluaran::sum('jumlah_pengeluaran');

        return response()->json([
            'message' => 'Total pengeluaran saat ini',
            'total_pengeluaran' => $total,
        ]);
    }
}
