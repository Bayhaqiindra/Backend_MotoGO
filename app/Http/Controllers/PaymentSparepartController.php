<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PaymentSparepartRequest;
use App\Models\Transaction;
use App\Models\PaymentSparepart;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class PaymentSparepartController extends Controller
{
    /**
     * Pelanggan: Kirim pembayaran sparepart
     * Endpoint: POST /pelanggan/payment-sparepart
     */
    public function submitPayment(PaymentSparepartRequest $request)
    {
        $transaction = Transaction::find($request->transaction_id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaksi tidak ditemukan.'], 404);
        }

        // Ambil total harga dari transaksi
        $total = $transaction->total_price;

        // Upload bukti jika metode transfer
        $buktiPath = null;
        if ($request->metode_pembayaran === 'transfer' && $request->hasFile('bukti_pembayaran')) {
            $buktiPath = $request->file('bukti_pembayaran')->store('bukti_pembayaran_sparepart', 'public');
        }

        $payment = PaymentSparepart::create([
            'transaction_id'     => $transaction->transaction_id,
            'total_pembayaran'   => $total,
            'payment_status'     => 'pending', // default
            'metode_pembayaran'  => $request->metode_pembayaran,
            'bukti_pembayaran'   => $buktiPath,
            'payment_date'       => now(),
        ]);

        return response()->json([
            'message' => 'Pembayaran berhasil dikirim.',
            'data' => $payment,
        ], 201);
    }

    public function verifyPayment(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:verified,ditolak'
        ], [
            'payment_status.required' => 'Status pembayaran wajib diisi.',
            'payment_status.in' => 'Status harus "verified" atau "ditolak".',
        ]);

        $payment = PaymentSparepart::find($id);

        if (!$payment) {
            return response()->json(['message' => 'Pembayaran tidak ditemukan.'], 404);
        }

        // Jika statusnya mau diset "verified" tapi bukti pembayaran kosong
        if (
            $payment->metode_pembayaran === 'transfer' &&
            $request->payment_status === 'verified' &&
            !$payment->bukti_pembayaran
        ) {
            return response()->json(['message' => 'Bukti pembayaran belum tersedia. Tidak dapat diverifikasi.'], 400);
        }

        $payment->payment_status = $request->payment_status;
        $payment->save();

        return response()->json([
            'message' => 'Status pembayaran berhasil diubah menjadi ' . $request->payment_status,
            'data' => $payment,
        ]);
    }


    /**
     * Admin: Melihat semua pembayaran sparepart
     * Endpoint: GET /admin/payment-sparepart
     */
    public function allPayments()
    {
        $payments = PaymentSparepart::with('transaction.user', 'transaction.sparepart')->latest()->get();

        return response()->json($payments);
    }

    /**
     * Pelanggan: Lihat riwayat pembayaran pribadi
     * Endpoint: GET /pelanggan/payment-sparepart
     */
    public function customerPayments()
    {
        $user = Auth::user();

        $payments = PaymentSparepart::whereHas('transaction', function ($query) use ($user) {
            $query->where('user_id', $user->user_id);
        })->with('transaction.sparepart')->latest()->get();

        return response()->json($payments);
    }
}
