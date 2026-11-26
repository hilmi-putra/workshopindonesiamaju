<!DOCTYPE html>
<html>
<head>
    <title>Invoice Pesanan Anda</title>
</head>
<body>
    <h2>Terima Kasih atas Pesanan Anda di MegaBookstore!</h2>
    <p>Halo {{ $order->user->name }},</p>
    <p>Pembayaran untuk pesanan dengan nomor invoice <strong>{{ $order->invoice_number }}</strong> telah berhasil kami terima.</p>
    
    <p>Terlampir invoice dalam format PDF untuk keperluan pencatatan Anda.</p>
    <p>Pesanan Anda akan segera kami proses dan kirim. Anda dapat melihat status pesanan melalui dashboard pelanggan di website kami.</p>

    <br>
    <p>Salam hangat,</p>
    <p><strong>Tim MegaBookstore</strong></p>
</body>
</html>
