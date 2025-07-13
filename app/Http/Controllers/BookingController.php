<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Exception;

class BookingController extends Controller
{

    /**
     * Pelanggan membuat booking
     */
    public function addBooking(BookingRequest $request)
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['message' => 'User not authenticated.'], 401);
            }

            $pelanggan = Pelanggan::where('user_id', $user->user_id)->first();
            if (!$pelanggan) {
                return response()->json(['message' => 'Pelanggan tidak ditemukan.'], 404);
            }

            $booking = new Booking();
            $booking->id_pelanggan = $pelanggan->id_pelanggan;
            $booking->service_id = $request->service_id;
            $booking->status = 'menunggu'; // default status
            $booking->pickup_location = $request->pickup_location;
            $booking->latitude = $request->latitude; // <-- AMBIL LANGSUNG DARI REQUEST
            $booking->longitude = $request->longitude; 
            $booking->customer_notes = $request->customer_notes;
            $booking->save();

             $booking->load('pelanggan');

            return response()->json([
                'message' => 'Pemesanan berhasil dibuat',
                'data' => $booking
            ], 201);
        } catch (Exception $e) {
            \Log::error('Error creating booking: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal membuat pemesanan'], 500);
        }
    }

    /**
     * Pelanggan melihat semua booking miliknya
     */
    public function customerBookings()
    {
        $user = auth()->user();
        $pelanggan = Pelanggan::where('user_id', $user->user_id)->first();

        if (!$pelanggan) {
            return response()->json(['message' => 'Pelanggan tidak ditemukan.'], 404);
        }

        $bookings = Booking::where('id_pelanggan', $pelanggan->id_pelanggan)->with('pelanggan')->get();
        return response()->json($bookings);
    }

    /**
     * Pelanggan atau Admin melihat detail booking
     */
    public function detailBooking($id)
    {
        $booking = Booking::with('pelanggan')->find($id);
        if (!$booking) {
            return response()->json(['message' => 'Booking tidak ditemukan.'], 404);
        }

        $user = auth()->user();
        $role = $user->role->name ?? null;

        if ($role === 'pelanggan') {
            $pelanggan = Pelanggan::where('user_id', $user->user_id)->first();
            if (!$pelanggan || $booking->id_pelanggan !== $pelanggan->id_pelanggan) {
                return response()->json(['message' => 'Akses ditolak.'], 403);
            }
        }

        return response()->json($booking);
    }

    /**
     * Admin melihat semua booking dari semua pelanggan
     */
    public function allBookings()
    {
        $user = auth()->user();
        if ($user->role->name !== 'admin') {
            return response()->json(['message' => 'Akses hanya untuk admin.'], 403);
        }

        $bookings = Booking::with('pelanggan')->get();
        return response()->json($bookings);
    }

    /**
     * Admin mengubah status booking
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $user = auth()->user();
            if ($user->role->name !== 'admin') {
                return response()->json(['message' => 'Akses ditolak.'], 403);
            }

            $request->validate([
                'status' => 'nullablle|in:menunggu,diterima,ditolak,selesai'
            ]);

            $booking = Booking::find($id);
            if (!$booking) {
                return response()->json(['message' => 'Booking tidak ditemukan.'], 404);
            }

            $booking->status = $request->status;
            $booking->save();

            $booking->load('pelanggan');

            return response()->json([
                'message' => 'Status berhasil diperbarui.',
                'data' => $booking
            ]);
        } catch (Exception $e) {
            \Log::error('Error updating booking status: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal memperbarui status'], 500);
        }
    }
}
