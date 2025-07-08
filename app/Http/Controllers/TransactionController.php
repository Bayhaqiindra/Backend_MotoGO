<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Models\Sparepart;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Pelanggan: Membeli sparepart
     * Endpoint: POST /pelanggan/transactions
     */
    public function beliSparepart(TransactionRequest $request)
    {
        // Ambil data sparepart berdasarkan ID
        $sparepart = Sparepart::find($request->sparepart_id);

        // Validasi stok cukup
        if ($sparepart->stock_quantity < $request->quantity) {
            return response()->json(['message' => 'Stok tidak mencukupi.'], 400);
        }

        // Hitung total harga
        $totalPrice = $sparepart->price * $request->quantity;

        // Ambil user login (pelanggan)
        $user = Auth::user();

        // Simpan transaksi
        $transaksi = Transaction::create([
            'user_id' => $user->user_id,
            'sparepart_id' => $sparepart->sparepart_id,
            'quantity' => $request->quantity,
            'total_price' => $totalPrice,
            'transaction_date' => now(),
        ]);

        // Kurangi stok sparepart
        $sparepart->decrement('stock_quantity', $request->quantity);

        return response()->json([
            'message' => 'Pembelian berhasil. Transaksi tersimpan.',
            'data' => $transaksi,
        ], 201);
    }

    /**
     * Pelanggan: Lihat riwayat transaksi
     * Endpoint: GET /pelanggan/transactions
     */
    public function riwayatTransaksi()
    {
        $user = Auth::user();
        $transaksi = Transaction::with('sparepart') // pastikan relasi sparepart dibuat
            ->where('user_id', $user->user_id)
            ->latest()
            ->get();

        return response()->json($transaksi);
    }

    // Menampilkan semua transaksi (khusus admin)
    public function getAllTransactions()
    {
        $transactions = Transaction::with(['user', 'sparepart'])->orderBy('transaction_date', 'desc')->get();

        return response()->json([
            'message' => 'Daftar semua transaksi.',
            'data' => $transactions,
        ]);
    }

}
