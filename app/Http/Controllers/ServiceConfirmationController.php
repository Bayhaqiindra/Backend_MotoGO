<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceConfirmationRequest;
use App\Models\ServiceConfirmation;
use App\Models\Booking;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class ServiceConfirmationController extends Controller
{
    // Admin: Buat konfirmasi service
    public function confirmBooking(ServiceConfirmationRequest $request)
    {
        $confirmation = ServiceConfirmation::create([
            'booking_id' => $request->booking_id,
            'service_id' => $request->service_id,
            'service_status' => $request->service_status,
            'total_cost' => $request->total_cost,
            'admin_notes' => $request->admin_notes,
            'confirmed_at' => now(),
            'customer_agreed' => null
        ]);

        return response()->json([
            'message' => 'Konfirmasi layanan berhasil dikirim ke pelanggan.',
            'data' => $confirmation
        ], 201);
    }

    // Pelanggan: Menyetujui atau menolak konfirmasi
    public function respondToConfirmation(Request $request, $id)
    {
        $request->validate([
            'customer_agreed' => 'required|boolean'
        ]);

        $confirmation = ServiceConfirmation::find($id);

        if (!$confirmation) {
            return response()->json(['message' => 'Data konfirmasi tidak ditemukan'], 404);
        }

        // Validasi bahwa booking ini milik pelanggan yang login
        $user = auth()->user();
        if ($user->role->name !== 'pelanggan') {
            return response()->json(['message' => 'Hanya pelanggan yang dapat melakukan ini'], 403);
        }

        $pelanggan = Pelanggan::where('user_id', $user->user_id)->first();
        if (!$pelanggan || $confirmation->booking->id_pelanggan !== $pelanggan->id_pelanggan) {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

        $confirmation->customer_agreed = $request->customer_agreed;

        if ($request->customer_agreed && $confirmation->service_status === 'menunggu') {
            $confirmation->service_status = 'dalam_pekerjaan';
        }

        $confirmation->save();

        return response()->json([
            'message' => 'Konfirmasi pelanggan berhasil diperbarui.',
            'data' => $confirmation
        ]);
    }

    // Admin: Tandai selesai
    public function markAsCompleted($id)
    {
        $confirmation = ServiceConfirmation::find($id);

        if (!$confirmation) {
            return response()->json(['message' => 'Konfirmasi tidak ditemukan'], 404);
        }

        $user = auth()->user();
        if ($user->role->name !== 'admin') {
            return response()->json(['message' => 'Hanya admin yang bisa menyelesaikan layanan'], 403);
        }

        if ($confirmation->customer_agreed != true) {
            return response()->json(['message' => 'Pelanggan belum menyetujui layanan.'], 400);
        }

        $confirmation->service_status = 'selesai';
        $confirmation->save();

        return response()->json([
            'message' => 'Layanan berhasil ditandai selesai.',
            'data' => $confirmation
        ]);
    }

    // Admin dan Pelanggan: Lihat detail konfirmasi
    public function getConfirmation($id)
    {
        $confirmation = ServiceConfirmation::with(['booking', 'service'])->find($id);

        if (!$confirmation) {
            return response()->json(['message' => 'Konfirmasi tidak ditemukan'], 404);
        }

        return response()->json($confirmation);
    }

    public function getConfirmationByBookingId($booking_id)
    {
        // Mencari konfirmasi berdasarkan booking_id, bukan primary key (id)
        $confirmation = ServiceConfirmation::with(['booking', 'service'])
                            ->where('booking_id', $booking_id)
                            ->first(); // Gunakan first() karena seharusnya hanya ada satu konfirmasi per booking

        if (!$confirmation) {
            return response()->json(['message' => 'Konfirmasi untuk booking ini tidak ditemukan'], 404);
        }

        // Opsional: Tambahkan validasi jika user yang login adalah pemilik booking
        $user = auth()->user();
        if ($user->role->name === 'pelanggan') {
            $pelanggan = \App\Models\Pelanggan::where('user_id', $user->user_id)->first();
            if (!$pelanggan || $confirmation->booking->id_pelanggan !== $pelanggan->id_pelanggan) {
                return response()->json(['message' => 'Akses ditolak. Konfirmasi bukan milik Anda.'], 403);
            }
        }


        return response()->json($confirmation);
    }
}
