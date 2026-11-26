# FASE 1 — AUDIT, RENCANA, FONDASI

## Tujuan
Memetakan kode lama, menetapkan rencana database, lalu membangun fondasi monolith (layout, auth session, role).

## Bagian A: Audit (tanpa kode, hasilkan dokumen `docs/migration-plan.md`)
1. Petakan semua halaman di `frontend/` ke route Blade baru (tabel: halaman Next.js → route web → controller → view).
2. Audit semua migration dan model di `backend/`. Beri label tiap tabel: **pertahankan / ubah / deprecated**.
3. Usulkan perubahan DB untuk alur WA:
   - `users`: tambah/pastikan kolom `role` (`admin`|`user`)
   - `orders` disederhanakan: `code`, `user_id` nullable, `customer_name`, `customer_phone`, `customer_address`, `status`, `total`, `notes`, `confirmed_at`, `completed_at`
   - `order_items`: `order_id`, `book_id`, `title_snapshot`, `price_snapshot`, `qty`
   - `cart_items`: `user_id`, `book_id`, `qty` (unik user+book)
   - `book_likes` / `wishlists`: `user_id`, `book_id` (unik)
   - `stock_movements`: `book_id`, `change`, `reason`, `order_id` nullable, `admin_id`, `stock_after`
   - Tabel/kode payment gateway, shipment/RajaOngkir, retur/refund, kupon: tandai deprecated, **jangan hapus**
4. Beri rekomendasi: Filament 3 vs Blade+Livewire manual untuk admin (dengan alasan singkat), dan titik pengurangan stok (`confirmed` atau `paid`).
5. Tulis rencana migrasi data untuk pesanan lama agar histori tidak hilang.

**Berhenti setelah Bagian A dan tunggu persetujuan saya sebelum Bagian B.**

## Bagian B: Fondasi (setelah disetujui)
1. Layout master `layouts/app.blade.php`, plus Blade Components: navbar, footer, button, input, modal, book-card, flash message. Samakan dengan tampilan Next.js.
2. Pastikan Vite + Tailwind v4 + Alpine berjalan; Livewire 3 terpasang.
3. Session auth: login, register, logout, lupa password, Google OAuth (Socialite). Gunakan guard `web`, bukan Sanctum.
4. Middleware `role:admin` dan policy dasar.
5. Jalankan migration perubahan DB yang disetujui (tabel baru, kolom `role`).
6. Seeder admin awal.

## Output
Ringkasan file dibuat/diubah, cara verifikasi (URL dan perintah), test yang ditambahkan, pertanyaan untuk saya, saran fase berikutnya.