# PROYEK
Migrasi **Mega Press** dari Next.js (`frontend/`) + Laravel REST API (`backend/`) menjadi **satu Laravel 12 monolith dengan Blade**. Alur toko buku berubah: checkout dan pembayaran lewat **WhatsApp**, bukan di sistem.

# ATURAN
1. Baca file terkait dulu sebelum mengubah (migration, model, controller, halaman Next.js yang sesuai). Jangan berasumsi.
2. **Pertahankan gaya visual** Next.js (warna, tipografi, spacing, layout). Terjemahkan ke Blade Components + Tailwind v4, jangan didesain ulang.
3. Jangan hapus `frontend/` sebelum Fase 5. Pakai sebagai referensi.
4. Jangan tambah fitur di luar lingkup fase. Hal ambigu masuk ke "Pertanyaan", jangan diputuskan sendiri.
5. Perubahan DB hanya lewat migration baru, jangan edit migration lama.
6. Ikuti konvensi Laravel (Form Request, Policy, resource route, scope). Tambahkan feature test untuk logika penting.
7. Setelah fase selesai, berhenti dan tunggu konfirmasi.

# STACK
Blade + Blade Components, Tailwind v4 (Vite), Alpine.js, Livewire 3 (hanya untuk live search/filter katalog dan keranjang), Laravel session auth + Socialite (Google), DomPDF/Excel tetap dipakai.

# 4 MODUL
Company Profile & Layanan Penerbitan, Toko Buku Online, Blog & Editorial Magazine, Backoffice & Admin.

# ALUR TOKO (RINGKAS)
- Role: `admin`, `user`; guest = tanpa login.
- Guest boleh lihat katalog, memakai keranjang (session), dan checkout WA. Like/wishlist/simpan buku hanya untuk user login.
- Keranjang user disimpan di DB; saat guest login, keranjang session di-merge ke DB.
- Checkout WA: server membuat `orders` status `pending` + kode unik (`MP-YYYYMMDD-XXXX`), menyimpan `order_items` (snapshot judul/harga/qty), lalu redirect ke `wa.me` dengan pesan otomatis berisi kode, daftar buku, link buku, dan total.
- Stok **tidak** berkurang saat checkout. Stok dikurangi admin saat status berubah ke titik yang dipilih, dicatat di `stock_movements`, dikembalikan bila dibatalkan.
- Status: `pending` → `confirmed` → `paid` → `completed`, atau `cancelled`.