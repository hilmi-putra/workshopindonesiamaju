# FASE 3 — FITUR USER & CHECKOUT WHATSAPP

## Tujuan
Mengimplementasikan inti alur baru: keranjang (guest + user), like/wishlist, dan checkout via WhatsApp.

## Lingkup
1. **Like / wishlist / simpan buku**: hanya user login. Guest yang menekan akan diarahkan ke login lalu kembali ke halaman semula (intended URL). Toggle dengan Livewire/Alpine, unik per user+buku.
2. **Keranjang**:
   - Guest: session. User: tabel `cart_items`.
   - Service `CartService` satu pintu untuk tambah, ubah qty, hapus, kosongkan, hitung total.
   - **Merge** keranjang session ke DB saat login/register (jumlahkan qty buku yang sama, batasi ke stok).
   - Validasi: buku aktif, qty >= 1, qty <= stok.
3. **Checkout WhatsApp**:
   - Tombol **"Beli via WhatsApp"** di detail buku (1 buku) dan **"Checkout via WhatsApp"** di keranjang.
   - Guest mengisi nama, nomor HP, alamat singkat di modal (Form Request). User login terisi otomatis dari profil, tetap bisa diedit.
   - `CheckoutService::createOrder()` dalam DB transaction: buat `orders` (`pending`, kode unik), `order_items` snapshot, hitung total dari harga **server**, bukan dari input klien.
   - Bangun pesan WhatsApp (`rawurlencode`) berisi kode pesanan, daftar buku (judul, qty, harga, link), total, data pemesan, dan catatan bahwa ongkir/pembayaran dibahas di chat.
   - Nomor WA admin dari `config/services.php` (`.env`, format `628xxxx`) atau tabel `settings`.
   - Redirect ke `https://wa.me/{nomor}?text=...`. Setelah itu keranjang dikosongkan.
   - Stok **tidak** dikurangi di tahap ini.
   - Cegah pesanan ganda akibat klik berulang (token idempotensi atau disable tombol + cek).
4. **Halaman "Pesanan Saya"** (user login): daftar dan detail pesanan, status hanya baca. Tombol "Hubungi admin via WA" memakai kode pesanan.

## Aturan khusus
- Jangan menyentuh payment gateway, ongkir, atau kupon. Hapus dari alur UI saja, kode lama dibiarkan (deprecated).
- Panjang URL WA terbatas: bila item sangat banyak, ringkas daftar di pesan dan cukup sertakan kode pesanan.

## Test wajib
Merge keranjang, validasi stok, total dihitung server, kode pesanan unik, guest vs user, pembuatan URL WA yang benar.

## Output
Ringkasan perubahan, skenario manual yang harus saya coba, test, pertanyaan, saran fase berikutnya.