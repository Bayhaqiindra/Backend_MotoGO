<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register a new user
     *
     * Register a user with name, email, password, and role_id.
     *
     * @bodyParam name string required Nama pengguna. Example: john
     * @bodyParam email string required Email pengguna. Example: john@example.com
     * @bodyParam password string required Kata sandi minimal 6 karakter. Example: rahasia123
     * @bodyParam role_id integer required ID dari role yang tersedia. Example: 2
     *
     * @response 201 {
     *   "status_code": 201,
     *   "message": "User created successfully",
     *   "data": {
     *     "id": 1,
     *     "name": "john",
     *     "email": "john@example.com",
     *     "role_id": 2
     *   }
     * }
     *
     * @response 400 {
     *   "status_code": 400,
     *   "message": "The email has already been taken.",
     *   "data": null
     * }
     *
     * @response 500 {
     *   "status_code": 500,
     *   "message": "Internal server error"
     * }
     */
    public function register(RegisterRequest $request)
    {

        try {
            // Buat user baru
            $user = new User;
            $user->email    = $request->email;
            $user->password = Hash::make($request->password);
            $user->role_id     = 2; 
            $user->save();

            // Return response sukses
            return response()->json([
                'status_code' => 201,
                'message' => 'User created successfully',
                'data'    => $user,

            ], 201);
        } catch (Exception $e) {
            // Return response gagal
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Login
     *
     * @bodyParam email string required Email pengguna. Example: john@example.com
     * @bodyParam password string required Kata sandi pengguna. Example: rahasia123
     *
     * @response 200 {
     *   "message": "Login berhasil",
     *   "status_code": 200,
     *   "data": {
     *     "id": 1,
     *     "name": "john",
     *     "email": "john@example.com",
     *     "role": "admin",
     *     "token": "eyJ0eXAiOiJKV1Qi..."
     *   }
     * }
     *
     * @response 401 {
     *   "message": "Email atau password salah",
     *   "status_code": 401,
     *   "data": null
     * }
     */

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json([
                'message' => 'Email atau password salah',
                'status_code' => 401,
                'data' => null
            ], 401);
        }

        try {
            $user = Auth::guard('api')->user();

            $formatedUser = [
                'user_id' => $user->user_id,
                'email' => $user->email,
                'role' => $user->role->name,
                'token' => $token
            ];

            return response()->json([
                'message' => 'Login berhasil',
                'status_code' => 200,
                'data' => $formatedUser
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status_code' => 500,
                'data' => null
            ], 500);
        }
    }

    /**
     * Get current authenticated user.
     *
     * @authenticated
     *
     * @response 200 {
     *   "message": "User ditemukan",
     *   "status_code": 200,
     *   "data": {
     *     "id": 1,
     *     "name": "john",
     *     "email": "john@example.com",
     *     "role": "admin"
     *   }
     * }
     */

    public function me()
    {
        try {
            $user = Auth::guard('api')->user();

            $formatedUser = [
                'user_id' => $user->user_id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role->role_id,
            ];

            return response()->json([
                'message' => 'User ditemukan',
                'status_code' => 200,
                'data' => $formatedUser
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status_code' => 500,
                'data' => null
            ], 500);
        }
    }

    /**
     * Logout user
     *
     * @authenticated
     *
     * @response 200 {
     *   "message": "Logout berhasil",
     *   "status_code": 200,
     *   "data": null
     * }
     */

    public function logout()
    {
        Auth::guard('api')->logout();

        return response()->json([
            'message' => 'Logout berhasil',
            'status_code' => 200,
            'data' => null
        ]);
    }
}
