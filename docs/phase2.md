# FASE 2 — HALAMAN PUBLIK & BLOG

## Tujuan
Memindahkan Company Profile, Layanan Penerbitan, Katalog/Detail Buku (tampilan saja), dan Blog ke Blade dengan tampilan yang sama seperti Next.js.

## Lingkup
1. **Company Profile & Layanan Penerbitan**: landing, profil penerbit, layanan, alur kerja sama, dan form pengajuan naskah (Form Request, upload file, simpan ke tabel yang ada, notifikasi/email bila sebelumnya ada).
2. **Katalog Toko**: daftar buku dengan search, filter (genre, kategori, penulis, penerbit), sort, pagination. Gunakan **Livewire** untuk live search dan filter tanpa reload, URL tetap bisa dibagikan (query string).
3. **Detail buku**: ISBN, spesifikasi, sinopsis, preview, rating/ulasan (tampil saja bila datanya ada), buku terkait. Tombol aksi dibiarkan sebagai placeholder, akan diisi di Fase 3.
4. **Blog**: daftar, detail, kategori, tag, artikel populer, newsletter (simpan email, validasi, anti-duplikat).
5. SEO dasar: `<title>`, meta description, Open Graph, sitemap sederhana.

## Aturan khusus
- Tidak boleh memanggil API JSON lama. Gunakan Eloquent langsung di Controller/Livewire.
- Hindari N+1 (eager loading), tambahkan index bila perlu.
- Halaman tidak butuh login.

## Verifikasi
Bandingkan tiap halaman dengan versi Next.js (desktop dan mobile). Tambahkan feature test untuk katalog (filter/search) dan form pengajuan naskah.

## Output
Ringkasan perubahan, URL untuk dicek, test, pertanyaan, saran fase berikutnya.