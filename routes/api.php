<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ServiceConfirmationController;
use App\Http\Controllers\PaymentServiceController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PaymentSparepartController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\LaporanController;

// --- PUBLIC ROUTES ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- SHARED: Admin & Pelanggan ---
Route::middleware(['auth:api'])->group(function () {
    Route::get('/booking/{id}', [BookingController::class, 'detailBooking']);
    Route::get('/confirm-service/{id}', [ServiceConfirmationController::class, 'getConfirmation']);
    Route::get('/spareparts', [SparepartController::class, 'getAllSpareparts']);
    Route::get('/spareparts/{id}', [SparepartController::class, 'getSparepartById']);
    Route::get('/pelanggan/services', [ServiceController::class, 'getAllServices']);
    Route::get('/transactions/{id}', [TransactionController::class, 'getTransactionById']);
    Route::get('/confirm-service/by-booking/{booking_id}', [ServiceConfirmationController::class, 'getConfirmationByBookingId']);
});

// --- ADMIN ONLY ROUTES ---
Route::middleware(['auth:api', 'role:admin'])->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // Admin Profile
    Route::post('/admin/profile', [AdminController::class, 'AddAdminProfile']);
    Route::get('/admin/profile', [AdminController::class, 'getProfileAdmin']);
    Route::put('/admin/profile', [AdminController::class, 'updateAdminProfile']);

    // Service Management
    Route::post('/admin/services', [ServiceController::class, 'AddService']);
    Route::get('/admin/services', [ServiceController::class, 'getAllServices']);
    Route::get('/admin/services/{id}', [ServiceController::class, 'getServiceById']);
    Route::put('/admin/services/{id}', [ServiceController::class, 'updateService']);
    Route::delete('/admin/services/{id}', [ServiceController::class, 'deleteService']);

    // Booking (Admin)
    Route::get('/admin/bookings', [BookingController::class, 'allBookings']);
    Route::put('/booking/{id}/status', [BookingController::class, 'updateStatus']);

    // Konfirmasi Service (Admin)
    Route::post('/admin/confirm-service', [ServiceConfirmationController::class, 'confirmBooking']);
    Route::put('/admin/confirm-service/{id}/complete', [ServiceConfirmationController::class, 'markAsCompleted']);

    // Payment (Admin Verifikasi)
    Route::put('/admin/payment/{id}/verify', [PaymentServiceController::class, 'verifyPayment']);
    Route::get('/admin/payment', [PaymentServiceController::class, 'allPayments']);

    // Spareparts
    Route::post('/admin/spareparts', [SparepartController::class, 'addSparepart']);
    Route::put('/admin/spareparts/{id}', [SparepartController::class, 'updateSparepart']);
    Route::delete('/admin/spareparts/{id}', [SparepartController::class, 'deleteSparepart']);

    // Transactions SparePart
    Route::get('/admin/transactions', [TransactionController::class, 'getAllTransactions']);

    // Verifikasi pembayaran sparepart
    Route::put('/admin/payment-sparepart/{id}/verify', [PaymentSparepartController::class, 'verifyPayment']);

    // Lihat semua pembayaran sparepart
    Route::get('/admin/payment-sparepart', [PaymentSparepartController::class, 'allPayments']);

    // --- PENGELUARAN ---
    Route::post('/admin/pengeluaran', [PengeluaranController::class, 'addPengeluaran']);
    Route::get('/admin/pengeluaran', [PengeluaranController::class, 'getAllPengeluaran']);
    Route::put('/admin/pengeluaran/{id}', [PengeluaranController::class, 'updatePengeluaran']);
    Route::delete('/admin/pengeluaran/{id}', [PengeluaranController::class, 'deletePengeluaran']);

    //Total 
    Route::get('/admin/pengeluaran/total', [PengeluaranController::class, 'getTotalPengeluaran']);
    Route::get('/admin/pemasukan', [PemasukanController::class, 'getTotalPemasukan']);

    Route::get('/admin/laporan/export/pdf', [LaporanController::class, 'exportLaporanPDF']);

});

// --- PELANGGAN ONLY ROUTES ---
Route::middleware(['auth:api', 'role:pelanggan'])->group(function () {
    // Pelanggan Profile
    Route::post('/pelanggan/profile', [PelangganController::class, 'AddProfilePelanggan']);
    Route::get('/pelanggan/profile', [PelangganController::class, 'getProfilePelanggan']);
    Route::put('/pelanggan/profile', [PelangganController::class, 'updateProfilePelanggan']);

    // Booking (Pelanggan)
    Route::post('/pelanggan/booking', [BookingController::class, 'addBooking']);
    Route::get('/pelanggan/booking', [BookingController::class, 'customerBookings']);

    // Konfirmasi Service (Pelanggan)
    Route::put('/pelanggan/confirm-service/{id}', [ServiceConfirmationController::class, 'respondToConfirmation']);

    // Payment (Pelanggan Bayar)
    Route::post('/pelanggan/payment', [PaymentServiceController::class, 'submitPayment']);
    Route::get('/pelanggan/payment', [PaymentServiceController::class, 'customerPayments']);

    // Transaksi Sparepart (Pelanggan Beli)
    Route::post('/pelanggan/transactions', [TransactionController::class, 'beliSparepart']);
    Route::get('/pelanggan/transactions', [TransactionController::class, 'riwayatTransaksi']);

    Route::post('/pelanggan/payment-sparepart', [PaymentSparepartController::class, 'submitPayment']);

    Route::get('/pelanggan/payment-sparepart', [PaymentSparepartController::class, 'customerPayments']);

});
