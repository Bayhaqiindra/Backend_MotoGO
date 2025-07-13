<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentService;
use App\Models\ServiceConfirmation;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PaymentServiceController extends Controller
{
    /**
     * Pelanggan: Submit pembayaran
     */
    public function submitPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'confirmation_id' => 'required|exists:service_confirmation,confirmation_id',
            'metode_pembayaran' => 'required|in:cod,transfer',
            'bukti_pembayaran' => 'required_if:metode_pembayaran,transfer|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'confirmation_id.required' => 'Konfirmasi ID wajib diisi.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
            'metode_pembayaran.in' => 'Metode pembayaran tidak valid.',
            'bukti_pembayaran.required_if' => 'Bukti pembayaran wajib diunggah untuk metode transfer.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = auth()->user();
        $pelanggan = Pelanggan::where('user_id', $user->user_id)->first();

        $confirmation = ServiceConfirmation::with('booking')->find($request->confirmation_id);

        if (
            !$confirmation ||
            !$confirmation->booking ||
            $confirmation->booking->id_pelanggan !== $pelanggan->id_pelanggan
        ) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        // Cegah pembayaran ganda
        if (PaymentService::where('confirmation_id', $confirmation->confirmation_id)->exists()) {
            return response()->json(['message' => 'Pembayaran sudah pernah dilakukan.'], 409);
        }

        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $buktiPath = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
        }

        $payment = PaymentService::create([
            'confirmation_id'   => $confirmation->confirmation_id,
            'total_amount'      => $confirmation->total_cost,
            'payment_status'    => 'menunggu',
            'metode_pembayaran' => $request->metode_pembayaran,
            'bukti_pembayaran'  => $buktiPath,
            'payment_date'      => now(),
        ]);

        return response()->json([
            'message' => 'Pembayaran berhasil dikirim.',
            'data' => $payment,
        ], 201);
    }

    /**
     * Admin: Verifikasi pembayaran
     */
    public function verifyPayment(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:selesai,ditolak'
        ], [
            'payment_status.required' => 'Status pembayaran wajib dipilih.',
            'payment_status.in' => 'Status pembayaran harus salah satu dari: selesai atau ditolak.',
        ]);

        $payment = PaymentService::find($id);

        if (!$payment) {
            return response()->json(['message' => 'Data pembayaran tidak ditemukan.'], 404);
        }

        // Jika metode transfer dan admin ingin menyetujui, harus ada bukti
        if ($payment->metode_pembayaran === 'transfer' && $request->payment_status === 'selesai' && !$payment->bukti_pembayaran) {
            return response()->json(['message' => 'Bukti pembayaran belum tersedia. Tidak dapat diverifikasi.'], 400);
        }

        $payment->payment_status = $request->payment_status;
        $payment->save();

        return response()->json([
            'message' => "Pembayaran telah di{$request->payment_status}.",
            'data' => $payment,
        ]);
    }

    /**
     * Pelanggan: Mendapatkan semua riwayat pembayaran layanan miliknya.
     */
    public function customerPayments()
    {
        $user = auth()->user();
        $pelanggan = Pelanggan::where('user_id', $user->user_id)->first();

        if (!$pelanggan) {
            return response()->json(['message' => 'Data pelanggan tidak ditemukan.'], 404);
        }

        // Dapatkan semua konfirmasi layanan yang terkait dengan pelanggan ini
        $confirmationIds = ServiceConfirmation::whereHas('booking', function ($query) use ($pelanggan) {
            $query->where('id_pelanggan', $pelanggan->id_pelanggan);
        })->pluck('confirmation_id');

        // Dapatkan semua pembayaran layanan yang terkait dengan konfirmasi tersebut
        $payments = PaymentService::with([
            'serviceConfirmation.booking.pelanggan', // Eager load pelanggan dari booking
            'serviceConfirmation.service'
        ])
        ->whereIn('confirmation_id', $confirmationIds)
        ->orderBy('payment_date', 'desc')
        ->get();

        return response()->json($payments);
    }

    /**
     * Admin: Mendapatkan semua riwayat pembayaran layanan dari semua pelanggan.
     */
    public function allPayments()
    {
        // Admin dapat melihat semua pembayaran.
        $payments = PaymentService::with([
            'serviceConfirmation.booking.pelanggan', // Eager load pelanggan dari booking
            'serviceConfirmation.service'
        ])
        ->orderBy('payment_date', 'desc')
        ->get();

        return response()->json($payments);
    }
}
