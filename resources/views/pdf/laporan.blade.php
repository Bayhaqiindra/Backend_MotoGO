<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: sans-serif; }
        h2 { text-align: center; }
        table { width: 100%; margin-top: 30px; border-collapse: collapse; }
        td, th { padding: 10px; border: 1px solid #000; }
    </style>
</head>
<body>
    <h2>Laporan Keuangan Bengkel</h2>
    <p><strong>Tanggal Export:</strong> {{ $tanggal }}</p>

    <table>
        <tr>
            <th>Jenis</th>
            <th>Nominal</th>
        </tr>
        <tr>
            <td>Total Pemasukan</td>
            <td>Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Total Pengeluaran</td>
            <td>Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Total Laba</strong></td>
            <td><strong>Rp {{ number_format($total_laba, 0, ',', '.') }}</strong></td>
        </tr>
    </table>
</body>
</html>
