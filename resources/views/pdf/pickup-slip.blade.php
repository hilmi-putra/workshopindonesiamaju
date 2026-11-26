<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pickup Order - {{ $order->invoice_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #111; margin: 0; padding: 15px; }
        .container { border: 2px solid #DB8B00; border-radius: 8px; padding: 20px; max-width: 550px; margin: 0 auto; }
        .header { border-bottom: 2px solid #DB8B00; padding-bottom: 12px; margin-bottom: 15px; display: table; width: 100%; }
        .logo { font-size: 20px; font-weight: 900; color: #171512; display: table-cell; vertical-align: middle; }
        .badge { display: table-cell; text-align: right; vertical-align: middle; font-size: 13px; font-weight: bold; background: #FFF4E5; color: #DB8B00; padding: 6px 12px; border: 1px solid #DB8B00; border-radius: 4px; }
        .code-box { background: #fdfdfd; border: 1px dashed #DB8B00; text-align: center; padding: 12px; margin-bottom: 16px; border-radius: 6px; }
        .pickup-code { font-size: 22px; font-weight: 900; letter-spacing: 2px; color: #171512; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .info-table td { padding: 6px; vertical-align: top; border: 1px solid #eee; width: 50%; font-size: 11px; }
        .title { font-weight: bold; color: #666; font-size: 10px; text-transform: uppercase; margin-bottom: 3px; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 8px; font-size: 11px; }
        .items-table th { background: #f5f5f5; text-align: left; }
        .signatures { width: 100%; margin-top: 30px; }
        .signatures td { width: 50%; text-align: center; font-size: 11px; }
        .sign-space { height: 50px; }
        .footer { font-size: 10px; color: #888; text-align: center; margin-top: 20px; border-top: 1px solid #eee; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">MEGA PRESS</div>
            <div class="badge">DOKUMEN PICKUP ORDER</div>
        </div>

        <div class="code-box">
            <div class="title">KODE PENGAMBILAN / NO. PESANAN</div>
            <div class="pickup-code">{{ $order->pickup_code ?? $order->invoice_number }}</div>
            <div style="font-size: 10px; color: #777; margin-top: 4px;">Tunjukkan kode ini saat mengambil pesanan di kasir toko</div>
        </div>

        <table class="info-table">
            <tr>
                <td>
                    <div class="title">LOKASI TOKO PENGAMBILAN</div>
                    <strong>Mega Press Bookstore — Toko Utama</strong><br>
                    Jl. Percetakan Negara No. 1<br>
                    Jakarta Pusat, DKI Jakarta 10560<br>
                    Jam Operasional: 09:00 - 21:00 WIB
                </td>
                <td>
                    <div class="title">DATA PELANGGAN</div>
                    <strong>{{ $order->user->name }}</strong><br>
                    Telp: {{ $order->user->phone ?? '-' }}<br>
                    Email: {{ $order->user->email }}<br>
                    Tgl Order: {{ $order->created_at->format('d M Y H:i') }}
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="title">INFORMASI PEMBAYARAN</div>
                    Metode: <strong>{{ strtoupper($order->payment_method ?? ($order->payment->payment_method ?? 'COD')) }}</strong>
                    &bull; Status: 
                    @if(strtolower($order->payment->payment_status ?? '') === 'paid' || strtolower($order->payment->status ?? '') === 'paid')
                        <strong style="color: green;">LUNAS (PAID)</strong>
                        @if($order->payment->paid_at)
                            <small>pada {{ $order->payment->paid_at->format('d M Y H:i') }}</small>
                        @endif
                    @else
                        <strong style="color: red;">BELUM BAYAR (BAYAR DI KASIR SAAT AMBIL)</strong>
                        &bull; Nominal: <strong>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</strong>
                    @endif
                </td>
            </tr>
        </table>

        <div class="title" style="margin-bottom: 6px;">DAFTAR BARANG YANG DIAMBIL:</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th width="10%">No</th>
                    <th width="65%">Judul Buku / Produk</th>
                    <th width="25%" style="text-align: center;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $idx => $item)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td>{{ $item->book->title ?? 'Buku' }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $item->quantity }} pcs</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="signatures">
            <tr>
                <td>
                    Petugas Toko / Kasir
                    <div class="sign-space"></div>
                    ( ............................................ )
                </td>
                <td>
                    Pelanggan Penerima
                    <div class="sign-space"></div>
                    ( {{ $order->user->name }} )
                </td>
            </tr>
        </table>

        <div class="footer">
            Dokumen internal Toko Mega Press &bull; Dicetak: {{ date('d M Y H:i') }} WIB
        </div>
    </div>
</body>
</html>
