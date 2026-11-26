<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Packing Slip - {{ $order->display_code }}</title>
    <style>
        body { font-family: sans-serif; font-size: 13px; color: #000; }
        .header { border-bottom: 3px solid #000; padding-bottom: 12px; margin-bottom: 24px; text-align: center; }
        .logo { font-size: 20px; font-weight: bold; letter-spacing: 2px; }
        .doc-title { font-size: 14px; font-weight: bold; margin-top: 6px; text-transform: uppercase; letter-spacing: 4px; }
        .info-section { width: 100%; margin-bottom: 24px; }
        .info-section td { width: 50%; vertical-align: top; padding: 10px; border: 1px solid #000; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .table th, .table td { border: 1px solid #000; padding: 10px; text-align: left; }
        .table th { background-color: #f0f0f0; font-weight: bold; text-transform: uppercase; font-size: 11px; }
        .text-center { text-align: center !important; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">WORKSHOP INDONESIA MAJU</div>
        <div class="doc-title">PACKING SLIP</div>
    </div>

    <table class="info-section">
        <tr>
            <td>
                <strong>PENGIRIM</strong><br>
                Workshop Indonesia Maju<br>
                WA: {{ config('services.whatsapp.number', '6285724693474') }}
            </td>
            <td>
                <strong>PENERIMA</strong><br>
                <strong>{{ $order->customer_name ?: ($order->user?->name ?? 'Pelanggan') }}</strong><br>
                {{ $order->customer_address ?: ($order->user?->address ?? '-') }}<br>
                Telp: {{ $order->customer_phone ?: ($order->user?->phone ?? '-') }}
            </td>
        </tr>
        <tr>
            <td><strong>Kode Pesanan:</strong> {{ $order->display_code }}</td>
            <td><strong>Tanggal:</strong> {{ $order->created_at?->format('d/m/Y H:i') }}</td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th width="10%" class="text-center">No</th>
                <th width="75%">Judul Buku</th>
                <th width="15%" class="text-center">Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->display_title }}</td>
                    <td class="text-center"><strong>{{ $item->display_qty }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
