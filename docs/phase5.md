# FASE 5 — PEMBERSIHAN & FINALISASI

## Tujuan
Menyelesaikan migrasi: hapus sisa arsitektur lama, rapikan database dan dokumentasi, pastikan siap deploy.

## Lingkup
1. **Audit kelengkapan**: bandingkan daftar halaman `frontend/` dengan route Blade. Laporkan yang belum dimigrasi. **Jangan hapus `frontend/` sebelum saya setujui laporan ini.**
2. Setelah disetujui: hapus `frontend/`, route API yang tidak terpakai (`routes/api.php`), Laravel Sanctum, konfigurasi CORS, dan paket yang tidak dipakai di `composer.json` / `package.json`.
3. **Database**: untuk tabel dan kode deprecated (payment gateway, shipment, retur, kupon), buat daftar dan tanyakan dulu sebelum menghapus. Jika disetujui, buat migration yang aman (backup dulu, jangan drop data pesanan lama tanpa konfirmasi).
4. **Performa dan keamanan**: eager loading, index DB, cache query berat, rate limiting untuk login/checkout/newsletter, proteksi CSRF, validasi upload, pengecekan mass assignment.
5. **Konfigurasi**: rapikan `.env.example` (nomor WA, Google OAuth, mail), `config/` , dan perintah build produksi (`npm run build`, `php artisan optimize`).
6. **Dokumentasi**: update `README.md` (setup, perintah, alur WA, siklus status pesanan, cara admin mengelola stok), dan `docs/` sesuai arsitektur baru.
7. **QA akhir**: jalankan seluruh test, `php artisan route:list`, cek responsif, dan buat checklist uji manual end-to-end (guest beli via WA, user login + like + keranjang, admin konfirmasi pesanan dan stok berkurang, pembatalan mengembalikan stok).

## Output
Daftar yang dihapus, daftar yang ditunda menunggu keputusan saya, hasil test, checklist uji manual, langkah deploy.