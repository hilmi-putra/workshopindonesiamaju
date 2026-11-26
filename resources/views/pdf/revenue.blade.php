<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Revenue & Pendapatan</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { border-bottom: 2px solid #DB8B00; padding-bottom: 10px; margin-bottom: 15px; }
        .title { font-size: 18px; font-weight: bold; color: #171512; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; font-size: 10px; text-transform: uppercase; }
        .text-right { text-align: right; }
        .summary-card { width: 100%; margin-bottom: 15px; }
        .summary-card td { width: 50%; padding: 4px; border: none; }
        .footer { text-align: center; color: #888; font-size: 9px; margin-top: 30px; border-top: 1px solid #eee; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <span class="title">MEGA PRESS - LAPORAN REVENUE</span><br>
        <small style="color: #666;">Dicetak pada: {{ now()->format('d M Y H:i') }}</small>
    </div>

    <h3>Ringkasan Finansial</h3>
    <table>
        <tbody>
            <tr>
                <th style="width: 35%;">Gross Sales (Subtotal Produk)</th>
                <td class="text-right">Rp {{ number_format($stats['gross_sales'] ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Diskon / Promo</th>
                <td class="text-right" style="color: red;">- Rp {{ number_format($stats['total_discount'] ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Pendapatan Ongkos Kirim</th>
                <td class="text-right">Rp {{ number_format($stats['shipping_revenue'] ?? ($stats['total_shipping'] ?? 0), 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total Diterima (Gross Revenue)</th>
                <td class="text-right">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total Pengembalian Dana (Refund)</th>
                <td class="text-right" style="color: red;">- Rp {{ number_format($stats['total_refunds'] ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr style="background-color: #fef7ed; font-weight: bold;">
                <th style="font-size: 12px; color: #DB8B00;">NET REVENUE (Pendapatan Bersih)</th>
                <td class="text-right" style="font-size: 12px; color: #DB8B00;">Rp {{ number_format($stats['net_revenue'] ?? ($stats['net_profit'] ?? 0), 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Jumlah Transaksi Berhasil</th>
                <td class="text-right">{{ $stats['successful_transactions'] ?? ($stats['total_transactions'] ?? 0) }} pesanan</td>
            </tr>
            <tr>
                <th>Rata-rata Nilai Pesanan (AOV)</th>
                <td class="text-right">Rp {{ number_format($stats['average_order_value'] ?? 0, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    @if(!empty($stats['daily_breakdown']))
    <h3 style="margin-top: 25px;">Rincian Pendapatan Harian</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th class="text-right">Pesanan</th>
                <th class="text-right">Gross Sales</th>
                <th class="text-right">Diskon</th>
                <th class="text-right">Ongkir</th>
                <th class="text-right">Refund</th>
                <th class="text-right">Net Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stats['daily_breakdown'] as $d)
            <tr>
                <td>{{ $d['date'] }}</td>
                <td class="text-right">{{ $d['orders_count'] }}</td>
                <td class="text-right">Rp {{ number_format($d['gross_sales'], 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($d['discount'], 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($d['shipping_fee'], 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($d['refunds'], 0, ',', '.') }}</td>
                <td class="text-right" style="font-weight: bold;">Rp {{ number_format($d['net_revenue'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        Mega Press Publishing Platform &copy; {{ date('Y') }}. Dokumen ini dihasilkan secara otomatis dari sistem keuangan.
    </div>
</body>
</html>
