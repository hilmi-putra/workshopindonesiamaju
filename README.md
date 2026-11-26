# Workshop Indonesia Maju & WIM Bookstore

Platform penerbitan buku, toko buku online (**WIM Bookstore**), blog editorial, dan backoffice admin berbasis **Laravel 12 Monolith + Blade** dengan alur transaksi **Checkout WhatsApp**.

## Stack Teknologi
- **Framework**: Laravel 12 (PHP 8.2+) + Blade & Blade Components
- **Styling & Interaktivitas**: Tailwind CSS v4 (Vite), Alpine.js, Livewire 4 (live search/filter katalog, keranjang, wishlist, checkout WA)
- **Autentikasi**: Laravel Session Auth (`web` guard) + Google OAuth (`laravel/socialite`)
- **Ekspor & Dokumen**: `barryvdh/laravel-dompdf` (Invoice, Packing Slip, Laporan PDF) & `maatwebsite/excel`
- **Dokumentasi Migrasi**: [docs/](docs/)

## Setup Lokal
1. Instal dependensi:
   ```bash
   composer install
   npm install
   cp .env.example .env
   php artisan key:generate
   php artisan storage:link
   ```
2. Atur koneksi database dan WhatsApp Admin pada `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=workshop_indonesia_maju
   DB_USERNAME=root
   DB_PASSWORD=

   WHATSAPP_ADMIN_NUMBER=6285724693474
   ```
3. Jalankan migrasi, seeder, build aset, dan server:
   ```bash
   php artisan migrate --seed
   npm run build
   php artisan serve
   ```
4. *(Jika memigrasi database lama yang sudah memiliki data pesanan)*:
   ```bash
   php artisan wim:migrate-legacy-data
   ```

## Akun Admin Default (Hasil Seeder)
- **Email**: `superadmin@workshopindonesiamaju.co.id`
- **Password**: `passwordhilmi`

## Alur Checkout WhatsApp & Siklus Status Pesanan
1. **Guest / User** menambahkan buku ke keranjang (guest disimpan di session, otomatis di-merge ke `cart_items` saat login/register) atau langsung menekan **Beli via WhatsApp** di halaman detail buku.
2. Saat checkout WA, server membuat pesanan dengan kode unik `WIM-YYYYMMDD-XXXX` (`status = pending`), menyimpan snapshot item (`title_snapshot`, `price_snapshot`, `qty`), menghitung total dari harga server, dan mengarahkan ke `https://wa.me/{WHATSAPP_ADMIN_NUMBER}?text=...`.
3. **Stok tidak berkurang saat checkout**.
4. **Siklus Status Admin**:
   - `pending` → `confirmed` (**Stok berkurang otomatis** dengan `DB::transaction` + `lockForUpdate`, dicatat ke `stock_movements`)
   - `confirmed` → `paid` → `completed` (idempoten, stok tidak berkurang dua kali)
   - `cancelled` (bila sebelumnya sudah `confirmed`/`paid`/`completed`, **stok dikembalikan otomatis** dan dicatat ke `stock_movements`)

## Pengujian
```bash
php artisan test
```
