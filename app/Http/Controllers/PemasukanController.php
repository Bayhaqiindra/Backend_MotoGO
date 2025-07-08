<?php

namespace App\Http\Controllers;

use App\Models\PaymentSparepart;
use App\Models\PaymentService;
use Illuminate\Http\Request;

class PemasukanController extends Controller
{
    /**
     * Admin: Lihat total pemasukan
     * Endpoint: GET /admin/pemasukan
     */
    public function getTotalPemasukan()
    {
        $totalSparepart = PaymentSparepart::where('payment_status', 'verified')->sum('total_pembayaran');
        $totalService = PaymentService::where('payment_status', 'selesai')->sum('total_amount');

        $totalPemasukan = $totalSparepart + $totalService;

        return response()->json([
            'message' => 'Total pemasukan berhasil dihitung.',
            'pemasukan_sparepart' => $totalSparepart,
            'pemasukan_service' => $totalService,
            'total_pemasukan' => $totalPemasukan,
        ]);
    }
}
