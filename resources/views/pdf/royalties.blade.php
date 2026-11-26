<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Royalti & Keuangan</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; color: #333; }
        .header { border-bottom: 2px solid #DB8B00; padding-bottom: 8px; margin-bottom: 12px; }
        .title { font-size: 16px; font-weight: bold; color: #171512; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 5px 6px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; font-size: 9px; text-transform: uppercase; }
        .text-right { text-align: right; }
        .summary-box { width: 100%; margin-bottom: 15px; border: 1px solid #e5e7eb; background: #fafafa; padding: 8px; }
        .summary-table { width: 100%; border: none; margin: 0; }
        .summary-table td { border: none; padding: 3px 6px; font-size: 10px; }
        .footer { text-align: center; color: #888; font-size: 9px; margin-top: 25px; border-top: 1px solid #eee; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <span class="title">MEGA PRESS - LAPORAN ROYALTI & KEUANGAN</span><br>
        <small style="color: #666;">Dicetak pada: {{ now()->format('d M Y H:i') }}</small>
    </div>

    @if(isset($summary))
    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <td><strong>Total Penjualan Buku:</strong> Rp {{ number_format($summary['total_sales'] ?? 0, 0, ',', '.') }}</td>
                <td><strong>Total Royalti Dihasilkan:</strong> <span style="color: #DB8B00; font-weight: bold;">Rp {{ number_format($summary['total_royalty_earned'] ?? 0, 0, ',', '.') }}</span></td>
            </tr>
            <tr>
                <td><strong>Royalti Sudah Dibayar:</strong> <span style="color: green; font-weight: bold;">Rp {{ number_format($summary['total_royalty_paid'] ?? 0, 0, ',', '.') }}</span></td>
                <td><strong>Sisa Hutang Royalti:</strong> <span style="color: red; font-weight: bold;">Rp {{ number_format($summary['total_royalty_pending'] ?? 0, 0, ',', '.') }}</span></td>
            </tr>
        </table>
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Tipe</th>
                <th>Nama Penulis / Penerbit</th>
                <th class="text-right">Buku</th>
                <th class="text-right">Unit Terjual</th>
                <th class="text-right">Total Penjualan</th>
                <th class="text-right">Royalti Hak</th>
                <th class="text-right">Sudah Dibayar</th>
                <th class="text-right">Sisa Hutang</th>
            </tr>
        </thead>
        <tbody>
            @foreach($aggregated as $royalty)
            <tr>
                <td>{{ $royalty['entity_type'] }}</td>
                <td><strong>{{ $royalty['entity_name'] }}</strong></td>
                <td class="text-right">{{ $royalty['books_count'] }}</td>
                <td class="text-right">{{ $royalty['units_sold'] ?? 0 }}</td>
                <td class="text-right">Rp {{ number_format($royalty['total_sales'] ?? ($royalty['total_revenue'] ?? 0), 0, ',', '.') }}</td>
                <td class="text-right" style="color: #DB8B00; font-weight: bold;">Rp {{ number_format($royalty['total_earned'], 0, ',', '.') }}</td>
                <td class="text-right" style="color: green;">Rp {{ number_format($royalty['total_paid'], 0, ',', '.') }}</td>
                <td class="text-right" style="color: red; font-weight: bold;">Rp {{ number_format($royalty['total_pending'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Mega Press Publishing Platform &copy; {{ date('Y') }}. Dokumen perhitungan royalti resmi.
    </div>
</body>
</html>
