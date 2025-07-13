<?php

namespace App\Http\Controllers;

use App\Http\Requests\PelangganRequest;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Exception;

class PelangganController extends Controller
{
    public function AddProfilePelanggan(PelangganRequest $request)
    {
        try {
            // Mendapatkan user yang sedang login
            $user = Auth::guard('api')->user();

            // Cek apakah pelanggan sudah ada berdasarkan user_id
            $existingPelanggan = Pelanggan::where('user_id', $user->user_id)->first();
            if ($existingPelanggan) {
                return response()->json([
                    'message' => 'Pelanggan sudah terdaftar',
                    'status_code' => 409,
                    'data' => null
                ], 409);
            }

            // Menyimpan foto profil jika ada
            $profilePicturePath = null;
            if ($request->hasFile('profile_picture')) {
                // Simpan hanya path relatif ke direktori 'profile_pictures' di disk 'public'
                $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            }

            // Membuat instansi pelanggan baru
            $pelanggan = new Pelanggan();
            $pelanggan->user_id = $user->user_id; // Menggunakan user_id dari pengguna yang sedang login
            $pelanggan->name = $request->name;
            $pelanggan->phone = $request->phone;
            $pelanggan->address = $request->address;
            // Simpan hanya path relatif di database
            $pelanggan->profile_picture = $profilePicturePath;

            // Menyimpan data pelanggan
            $pelanggan->save();

            // Bangun URL lengkap untuk respons
            $fullProfilePictureUrl = $pelanggan->profile_picture ? Storage::url($pelanggan->profile_picture) : null;

            return response()->json([
                'message' => 'Pelanggan berhasil ditambahkan',
                'status_code' => 201,
                'data' => [
                    'id_pelanggan' => $pelanggan->id_pelanggan,
                    'name' => $pelanggan->name,
                    'phone' => $pelanggan->phone,
                    'address' => $pelanggan->address,
                    'profile_picture' => $fullProfilePictureUrl, // Kirim URL lengkap ke frontend
                ]
            ], 201);
        } catch (Exception $e) {
            // Jika terjadi kesalahan, menangkap dan mencatatnya
            \Log::error("Error adding pelanggan: " . $e->getMessage());
            return response()->json([
                'status_code' => 500,
                'message' => 'Internal Server Error: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function getProfilePelanggan(Request $request)
    {
        try {
            // Mendapatkan user yang sedang login
            $user = Auth::guard('api')->user();

            // Mencari data pelanggan berdasarkan user_id yang sedang login
            $pelanggan = Pelanggan::where('user_id', $user->user_id)->first();

            // Jika pelanggan tidak ditemukan, kirimkan respons 404
            if (!$pelanggan) {
                return response()->json([
                    'message' => 'Pelanggan tidak ditemukan',
                    'status_code' => 404,
                    'data' => null
                ], 404);
            }

            // Bangun URL lengkap untuk respons
            $fullProfilePictureUrl = $pelanggan->profile_picture ? asset('storage/' . $pelanggan->profile_picture) : null;

            // Mengembalikan data pelanggan jika ditemukan
            return response()->json([
                'message' => 'Data pelanggan berhasil ditemukan',
                'status_code' => 200,
                'data' => [
                    'id_pelanggan' => $pelanggan->id_pelanggan,
                    'name' => $pelanggan->name,
                    'phone' => $pelanggan->phone,
                    'address' => $pelanggan->address,
                    'profile_picture' => $fullProfilePictureUrl, // Kirim URL lengkap ke frontend
                ]
            ], 200);

        } catch (Exception $e) {
            // Jika terjadi kesalahan, menangkap dan mencatatnya
            \Log::error("Error fetching pelanggan: " . $e->getMessage());
            return response()->json([
                'status_code' => 500,
                'message' => 'Internal Server Error: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function updateProfilePelanggan(PelangganRequest $request)
    {
        try {
            // Mendapatkan user yang sedang login
            $user = Auth::guard('api')->user();

            // Mencari data pelanggan berdasarkan user_id yang sedang login
            $pelanggan = Pelanggan::where('user_id', $user->user_id)->first();

            // Jika pelanggan tidak ditemukan, kirimkan respons 404
            if (!$pelanggan) {
                return response()->json([
                    'message' => 'Pelanggan tidak ditemukan',
                    'status_code' => 404,
                    'data' => null
                ], 404);
            }

            // Menyimpan foto profil baru jika ada
            $newProfilePicturePath = $pelanggan->profile_picture; // Inisialisasi dengan path lama
            if ($request->hasFile('profile_picture')) {
                // Menghapus foto profil lama jika ada dan pathnya tersimpan di database
                if ($pelanggan->profile_picture && Storage::disk('public')->exists($pelanggan->profile_picture)) {
                    Storage::disk('public')->delete($pelanggan->profile_picture);
                }
                
                // Menyimpan foto profil baru (hanya path relatif)
                $newProfilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            }

            // Update data pelanggan
            $pelanggan->name = $request->name ?? $pelanggan->name;
            $pelanggan->phone = $request->phone ?? $pelanggan->phone;
            $pelanggan->address = $request->address ?? $pelanggan->address;
            // Simpan hanya path relatif di database
            $pelanggan->profile_picture = $newProfilePicturePath;

            // Simpan perubahan
            $pelanggan->save();

            // Bangun URL lengkap untuk respons
            $fullProfilePictureUrl = $pelanggan->profile_picture ? asset('storage/' . $pelanggan->profile_picture) : null;

            return response()->json([
                'message' => 'Profil pelanggan berhasil diperbarui',
                'status_code' => 200,
                'data' => [
                    'id_pelanggan' => $pelanggan->id_pelanggan,
                    'name' => $pelanggan->name,
                    'phone' => $pelanggan->phone,
                    'profile_picture' => $fullProfilePictureUrl, // Kirim URL lengkap ke frontend
                    'address' => $pelanggan->address,
                    
                ]
            ], 200);

        } catch (Exception $e) {
            // Jika terjadi kesalahan, menangkap dan mencatatnya
            \Log::error("Error updating pelanggan profile: " . $e->getMessage());
            return response()->json([
                'status_code' => 500,
                'message' => 'Internal Server Error: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }
}
