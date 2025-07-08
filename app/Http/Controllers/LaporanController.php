<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\PaymentService;
use App\Models\PaymentSparepart;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Admin: Export PDF Laporan Pemasukan & Pengeluaran
     * GET /admin/laporan/export/pdf
     */
    public function exportLaporanPDF()
    {
        // Hitung total pemasukan dari dua sumber
        $totalPemasukan = PaymentService::where('payment_status', 'selesai')->sum('total_amount') +
                        PaymentSparepart::where('payment_status', 'verified')->sum('total_pembayaran');

        // Hitung total pengeluaran
        $totalPengeluaran = Pengeluaran::sum('jumlah_pengeluaran');

        // Hitung selisih
        $totalLaba = $totalPemasukan - $totalPengeluaran;

        // Kirim ke view Blade
        $data = [
            'total_pemasukan' => $totalPemasukan,
            'total_pengeluaran' => $totalPengeluaran,
            'total_laba' => $totalLaba,
            'tanggal' => now()->locale('id')->translatedFormat('l, d F Y H:i') . ' WIB',
        ];

        $pdf = Pdf::loadView('pdf.laporan', $data)->setPaper('A4', 'portrait');
        return $pdf->download('laporan_keuangan.pdf');
    }
}
