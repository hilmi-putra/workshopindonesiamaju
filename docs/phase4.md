# FASE 4 — BACKOFFICE ADMIN

## Tujuan
Membangun panel admin untuk mengelola buku, stok, pesanan WA, blog, dan laporan. Akses hanya role `admin`.

## Keputusan (isi sebelum memulai)
- Pendekatan admin: [Filament 3 / Blade + Livewire manual]
- Stok berkurang saat status berubah ke: [confirmed / paid]

## Lingkup
1. **Dashboard**: pesanan pending, pendapatan ringkas, buku stok menipis, grafik sederhana.
2. **Manajemen buku**: CRUD buku, genre, kategori, penulis, penerbit, upload cover, status aktif/nonaktif, stok.
3. **Manajemen pesanan** (inti):
   - Daftar dengan filter status/tanggal/pencarian kode atau nama.
   - Detail pesanan: item, data pemesan, tombol "Chat pelanggan via WA".
   - Ubah status sesuai siklus: `pending` → `confirmed` → `paid` → `completed` / `cancelled`.
   - **Pengurangan stok** di titik yang saya pilih, dalam DB transaction dengan `lockForUpdate`. Tolak bila stok tidak cukup dan tampilkan pesan jelas.
   - Pembatalan pesanan yang sudah mengurangi stok akan **mengembalikan stok**.
   - Perubahan stok tidak boleh terjadi dua kali (idempoten terhadap perubahan status berulang).
   - Buat pesanan manual (penjualan offline/langsung).
4. **Stok**: penyesuaian stok manual dengan alasan, dan halaman riwayat `stock_movements`.
5. **Blog**: CRUD artikel, kategori, tag, editor rich text, publish/draft.
6. **Pengajuan naskah** dan **newsletter**: lihat daftar, ubah status, ekspor Excel.
7. **Laporan**: penjualan per periode, buku terlaris, ekspor Excel/PDF. Invoice/packing slip DomPDF dipertahankan dan disesuaikan dengan data `orders` baru.
8. Authorization lewat Policy, aksi sensitif tercatat (siapa mengubah apa).

## Test wajib
Pengurangan stok tepat satu kali, pengembalian stok saat batal, stok tidak bisa minus, akses non-admin ditolak, race condition dua admin mengubah status bersamaan.

## Output
Ringkasan perubahan, skenario manual, test, pertanyaan, saran fase berikutnya.