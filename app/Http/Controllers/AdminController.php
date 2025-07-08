<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Pastikan ini diimpor
use Exception;

class AdminController extends Controller
{
    /**
     * Menambahkan data admin baru
     *
     * @param AdminRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function AddAdminProfile(AdminRequest $request)
    {
        try {
            $user = Auth::guard('api')->user();

            $profilePicturePath = null;
            if ($request->hasFile('profile_picture')) {
                // Simpan file dan dapatkan path relatif
                $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            }

            $admin = new Admin();
            $admin->user_id = $user->user_id;
            $admin->name = $request->name;
            // Gunakan Storage::url() untuk mendapatkan URL publik yang benar
            $admin->profile_picture = $profilePicturePath; // <--- PERBAIKAN DI SINI
            $admin->save();

            // Saat mengembalikan respons, generate URL lengkap dari path relatif
            $profilePictureFullUrl = $profilePicturePath ? Storage::url($profilePicturePath) : null;

            return response()->json([
                'message' => 'Admin berhasil ditambahkan',
                'status_code' => 201,
                'data' => [
                    'id_admin' => $admin->id_admin,
                    'name' => $admin->name,
                    'profile_picture' => $profilePictureFullUrl,
                ]
            ], 201);

        } catch (Exception $e) {
            \Log::error("Error adding admin: " . $e->getMessage());
            return response()->json([
                'status_code' => 500,
                'message' => 'Internal Server Error: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Mengambil data admin berdasarkan user yang sedang login
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfileAdmin(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();
            $admin = Admin::where('user_id', $user->user_id)->first();

            if (!$admin) {
                return response()->json([
                    'message' => 'Admin tidak ditemukan',
                    'status_code' => 404,
                    'data' => null
                ], 404);
            }

            // Ambil path relatif dari database (yang seharusnya sudah relatif)
            $profilePictureRelativePath = $admin->profile_picture;
            // Selalu generate URL lengkap saat mengembalikan ke frontend
            $profilePictureFullUrl = $profilePictureRelativePath ? Storage::url($profilePictureRelativePath) : null; // <--- PERBAIKAN: Selalu gunakan Storage::url()

            return response()->json([
                'message' => 'Data admin berhasil ditemukan',
                'status_code' => 200,
                'data' => [
                    'id_admin' => $admin->id_admin,
                    'name' => $admin->name,
                    'profile_picture' => $profilePictureFullUrl, // <--- Mengembalikan URL lengkap
                ]
            ], 200);

        } catch (Exception $e) {
            \Log::error("Error fetching admin: " . $e->getMessage());
            return response()->json([
                'status_code' => 500,
                'message' => 'Internal Server Error: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function updateAdminProfile(AdminRequest $request)
    {
        try {
            $user = Auth::guard('api')->user();
            $admin = Admin::where('user_id', $user->user_id)->first();

            if (!$admin) {
                return response()->json([
                    'message' => 'Admin tidak ditemukan',
                    'status_code' => 404,
                    'data' => null
                ], 404);
            }

            $oldProfilePictureRelativePath = $admin->profile_picture; // Ambil path relatif lama dari DB
            $newProfilePictureRelativePath = null;

            if ($request->hasFile('profile_picture')) {
                // Hapus foto profil lama jika ada dan itu adalah path relatif
                if ($oldProfilePictureRelativePath && Storage::disk('public')->exists($oldProfilePictureRelativePath)) {
                    Storage::disk('public')->delete($oldProfilePictureRelativePath);
                }

                // Menyimpan foto profil baru
                $newProfilePictureRelativePath = $request->file('profile_picture')->store('profile_pictures', 'public');
                $admin->profile_picture = $newProfilePictureRelativePath; // <--- PERBAIKAN: Simpan path relatif baru
            } else {
                // Jika tidak ada file baru diupload, pertahankan path relatif lama
                $admin->profile_picture = $oldProfilePictureRelativePath;
            }

            $admin->name = $request->name ?? $admin->name;
            $admin->save();

            // Saat mengembalikan respons, generate URL lengkap
            $profilePictureFullUrl = $admin->profile_picture ? Storage::url($admin->profile_picture) : null; // <--- Mengembalikan URL lengkap

            return response()->json([
                'message' => 'Profil admin berhasil diperbarui',
                'status_code' => 200,
                'data' => [
                    'id_admin' => $admin->id_admin,
                    'name' => $admin->name,
                    'profile_picture' => $profilePictureFullUrl, // <--- Mengembalikan URL lengkap
                ]
            ], 200);

        } catch (Exception $e) {
            \Log::error("Error updating admin profile: " . $e->getMessage());
            return response()->json([
                'status_code' => 500,
                'message' => 'Internal Server Error: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }
}