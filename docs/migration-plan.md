# Rencana Migrasi Mega Press — Laravel 12 Monolith + Blade (Alur Checkout WhatsApp)

Dokumen ini disusun berdasarkan hasil audit kode aktual pada direktori `frontend/` (Next.js 16 App Router) dan `backend/` (Laravel 12 REST API) sesuai arahan pada `docs/context.md` dan `docs/phase1.md` (Bagian A).

---

## 1. Pemetaan Halaman `frontend/` ke Route Blade Baru

Total ditemukan **102 file `page.tsx`** di dalam `frontend/app/`. Berikut pemetaan lengkap seluruh halaman tersebut ke arsitektur monolith Laravel + Blade:

> **Catatan Konvensi Penamaan**:
> - Jika pendekatan Admin Panel pada Fase 4 menggunakan **Blade + Livewire manual**, controller dan view admin mengikuti kolom **Controller** & **View Blade** di bawah.
> - Jika pendekatan Admin Panel menggunakan **Filament 3**, route `/admin/*` (kecuali yang berstatus `Deprecated` atau `Stub`) akan ditangani oleh Filament Panel Resources & Pages.

### 1.1 Modul Autentikasi (7 Halaman)

| Halaman Next.js (`frontend/app/`) | Route Web Baru | Controller | View Blade | Fase & Status |
| :--- | :--- | :--- | :--- | :--- |
| `(auth)/login/page.tsx` | `GET /login`, `POST /login` | `App\Http\Controllers\Auth\LoginController` | `auth.login` | Fase 1B (Aktif) |
| `(auth)/register/page.tsx` | `GET /register`, `POST /register` | `App\Http\Controllers\Auth\RegisterController` | `auth.register` | Fase 1B (Aktif) |
| `(auth)/verify/page.tsx` | `GET /verify`, `POST /verify`, `POST /verify/resend` | `App\Http\Controllers\Auth\VerificationController` | `auth.verify` | Fase 1B (Perlu konfirmasi OTP vs standar Laravel) |
| `(auth)/forgot-password/page.tsx` | `GET /forgot-password`, `POST /forgot-password` | `App\Http\Controllers\Auth\ForgotPasswordController` | `auth.forgot-password` | Fase 1B (Aktif) |
| `(auth)/forgot-password/verify/page.tsx` | `GET /forgot-password/verify`, `POST /forgot-password/verify` | `App\Http\Controllers\Auth\ForgotPasswordController` | `auth.forgot-password-verify` | Fase 1B (Perlu konfirmasi OTP vs link token) |
| `(auth)/forgot-password/reset/page.tsx` | `GET /reset-password`, `POST /reset-password` | `App\Http\Controllers\Auth\ResetPasswordController` | `auth.reset-password` | Fase 1B (Aktif) |
| `auth/google/callback/page.tsx` | `GET /auth/google`, `GET /auth/google/callback` | `App\Http\Controllers\Auth\SocialiteController` | *(Redirect langsung via session web)* | Fase 1B (Aktif) |

### 1.2 Modul Halaman Publik & Blog (4 Halaman)

| Halaman Next.js (`frontend/app/`) | Route Web Baru | Controller / Livewire | View Blade | Fase & Status |
| :--- | :--- | :--- | :--- | :--- |
| `(public)/page.tsx` | `GET /` | `App\Http\Controllers\Public\HomeController@index` | `public.home` | Fase 2 (Aktif — Landing & Company Profile) |
| `(public)/blog/page.tsx` | `GET /blog` | `App\Http\Controllers\Public\BlogController@index` | `public.blog.index` | Fase 2 (Aktif) |
| `(public)/blog/[slug]/page.tsx` | `GET /blog/{slug}` | `App\Http\Controllers\Public\BlogController@show` | `public.blog.show` | Fase 2 (Aktif) |
| `(public)/search/page.tsx` | `GET /search` | `App\Http\Controllers\Public\SearchController@index` | `public.search` | Fase 2 (Aktif) |

### 1.3 Modul Toko Buku & Dashboard Pelanggan (11 Halaman)

| Halaman Next.js (`frontend/app/`) | Route Web Baru | Controller / Livewire | View Blade | Fase & Status |
| :--- | :--- | :--- | :--- | :--- |
| `(store)/bookstore/page.tsx` | `GET /bookstore` | `App\Http\Controllers\Store\BookstoreController@index` (+ Livewire `CatalogFilter`) | `store.bookstore.index` | Fase 2 (Aktif) |
| `(store)/bookstore/koleksi/page.tsx` | `GET /bookstore/koleksi` | `App\Http\Controllers\Store\BookstoreController@collection` (+ Livewire `CatalogFilter`) | `store.bookstore.collection` | Fase 2 (Aktif — atau gabung ke `/bookstore`) |
| `(store)/bookstore/buku/[slug]/page.tsx` | `GET /bookstore/buku/{slug}` | `App\Http\Controllers\Store\BookstoreController@show` | `store.bookstore.show` | Fase 2 (tampilan), Fase 3 (Like & Beli via WA) |
| `(store)/cart/page.tsx` | `GET /cart` | `App\Http\Controllers\Store\CartController@index` (+ Livewire `CartPage` / `CartDrawer`) | `store.cart` | Fase 3 (Aktif — Guest session & User DB) |
| `(store)/checkout/page.tsx` | `POST /checkout/whatsapp` *(dan modal di `/cart` & `/bookstore/buku/{slug}`)* | `App\Http\Controllers\Store\CheckoutController@process` | `store.checkout` *(opsional/modal WA)* | Fase 3 (**Diubah total** ke Checkout WA tanpa ongkir/payment gateway) |
| `(store)/checkout/success/page.tsx` | — | — | — | **Deprecated** (Digantikan redirect `wa.me` + halaman detail pesanan `/dashboard/orders/{code}`) |
| `(store)/dashboard/page.tsx` | `GET /dashboard` | `App\Http\Controllers\User\DashboardController@index` | `user.dashboard` | Fase 3 (Aktif) |
| `(store)/dashboard/pesanan/page.tsx` | `GET /dashboard/pesanan` | `App\Http\Controllers\User\OrderController@index` | `user.orders.index` | Fase 3 (Aktif — "Pesanan Saya") |
| `(store)/dashboard/orders/[id]/page.tsx` | `GET /dashboard/orders/{order}` | `App\Http\Controllers\User\OrderController@show` | `user.orders.show` | Fase 3 (Aktif — hanya baca + tombol "Hubungi Admin via WA") |
| `(store)/dashboard/profil/page.tsx` | `GET /dashboard/profil`, `PUT /dashboard/profil`, `PUT /dashboard/password` | `App\Http\Controllers\User\ProfileController` | `user.profile` | Fase 3 (Aktif — Tab alamat RajaOngkir dihapus/disederhanakan) |
| `(store)/dashboard/wishlist/page.tsx` | `GET /dashboard/wishlist`, `POST /wishlist/{book}/toggle` | `App\Http\Controllers\User\WishlistController` | `user.wishlist` | Fase 3 (Aktif — Login only) |

### 1.4 Modul Admin — Dashboard & Profil (2 Halaman)

| Halaman Next.js (`frontend/app/`) | Route Web Baru | Controller | View Blade | Fase & Status |
| :--- | :--- | :--- | :--- | :--- |
| `admin/dashboard/page.tsx` | `GET /admin/dashboard` | `App\Http\Controllers\Admin\DashboardController@index` | `admin.dashboard` | Fase 4 (Aktif) |
| `admin/profile/page.tsx` | `GET /admin/profile`, `PUT /admin/profile` | `App\Http\Controllers\Admin\ProfileController` | `admin.profile` | Fase 4 (Aktif) |

### 1.5 Modul Admin — Buku, Penulis, Kategori, Genre, Penerbit (24 Halaman)

| Halaman Next.js (`frontend/app/`) | Route Web Baru | Controller | View Blade | Fase & Status |
| :--- | :--- | :--- | :--- | :--- |
| `admin/books/page.tsx` | `GET /admin/books` | `App\Http\Controllers\Admin\BookController@index` | `admin.books.index` | Fase 4 (Aktif) |
| `admin/books/create/page.tsx` | `GET /admin/books/create`, `POST /admin/books` | `App\Http\Controllers\Admin\BookController@create` / `store` | `admin.books.create` | Fase 4 (Aktif) |
| `admin/books/[id]/page.tsx` | `GET /admin/books/{book}` | `App\Http\Controllers\Admin\BookController@show` | `admin.books.show` | Fase 4 (Aktif) |
| `admin/books/edit/[id]/page.tsx` | `GET /admin/books/{book}/edit`, `PUT /admin/books/{book}` | `App\Http\Controllers\Admin\BookController@edit` / `update` | `admin.books.edit` | Fase 4 (Aktif) |
| `admin/books/inventory/page.tsx` | `GET /admin/books/inventory`, `POST /admin/stock/adjust` | `App\Http\Controllers\Admin\StockController@index` | `admin.books.inventory` | Fase 4 (Aktif + integrasi `stock_movements`) |
| `admin/books/isbn/page.tsx` | `GET /admin/books/isbn`, `PATCH /admin/books/{book}/isbn` | `App\Http\Controllers\Admin\BookController@isbn` | `admin.books.isbn` | Fase 4 (Opsional / digabung ke CRUD Buku) |
| `admin/books/digital/page.tsx` | `GET /admin/books/digital`, `PATCH /admin/books/{book}/digital` | `App\Http\Controllers\Admin\BookController@digital` | `admin.books.digital` | Fase 4 (Opsional / digabung ke CRUD Buku) |
| `admin/books/pricing/page.tsx` | `GET /admin/books/pricing`, `PATCH /admin/books/{book}/pricing` | `App\Http\Controllers\Admin\BookController@pricing` | `admin.books.pricing` | Fase 4 (Opsional / digabung ke CRUD Buku) |
| `admin/books/authors/page.tsx` | — | — | — | **Stub `PlaceholderPage`** (Duplikat dari `/admin/authors`) |
| `admin/books/categories/page.tsx` | — | — | — | **Stub `PlaceholderPage`** (Duplikat dari `/admin/categories`) |
| `admin/authors/page.tsx` | `GET /admin/authors` | `App\Http\Controllers\Admin\AuthorController@index` | `admin.authors.index` | Fase 4 (Aktif) |
| `admin/authors/create/page.tsx` | `GET /admin/authors/create`, `POST /admin/authors` | `App\Http\Controllers\Admin\AuthorController@create` / `store` | `admin.authors.create` | Fase 4 (Aktif) |
| `admin/authors/edit/[id]/page.tsx` | `GET /admin/authors/{author}/edit`, `PUT /admin/authors/{author}` | `App\Http\Controllers\Admin\AuthorController@edit` / `update` | `admin.authors.edit` | Fase 4 (Aktif) |
| `admin/categories/page.tsx` | `GET /admin/categories` | `App\Http\Controllers\Admin\CategoryController@index` | `admin.categories.index` | Fase 4 (Aktif — Gabungan Kategori & Genre) |
| `admin/categories/create/page.tsx` | `GET /admin/categories/create`, `POST /admin/categories` | `App\Http\Controllers\Admin\CategoryController@create` / `store` | `admin.categories.create` | Fase 4 (Aktif) |
| `admin/categories/edit/[id]/page.tsx` | `GET /admin/categories/{category}/edit`, `PUT /admin/categories/{category}` | `App\Http\Controllers\Admin\CategoryController@edit` / `update` | `admin.categories.edit` | Fase 4 (Aktif) |
| `admin/genres/create/page.tsx` | `GET /admin/genres/create`, `POST /admin/genres` | `App\Http\Controllers\Admin\GenreController@create` / `store` | `admin.genres.create` | Fase 4 (Aktif) |
| `admin/genres/edit/[id]/page.tsx` | `GET /admin/genres/{genre}/edit`, `PUT /admin/genres/{genre}` | `App\Http\Controllers\Admin\GenreController@edit` / `update` | `admin.genres.edit` | Fase 4 (Aktif) |
| `admin/master-data/categories/page.tsx` | *(Merge ke `/admin/categories`)* | `App\Http\Controllers\Admin\CategoryController@index` | `admin.categories.index` | **Duplikat** dari `admin/categories` |
| `admin/master-data/categories/create/page.tsx` | *(Merge ke `/admin/categories/create`)* | `App\Http\Controllers\Admin\CategoryController@create` | `admin.categories.create` | **Duplikat** |
| `admin/master-data/categories/[id]/edit/page.tsx` | *(Merge ke `/admin/categories/{category}/edit`)* | `App\Http\Controllers\Admin\CategoryController@edit` | `admin.categories.edit` | **Duplikat** |
| `admin/master-data/publishers/page.tsx` | `GET /admin/publishers` | `App\Http\Controllers\Admin\PublisherController@index` | `admin.publishers.index` | Fase 4 (Aktif) |
| `admin/master-data/publishers/create/page.tsx` | `GET /admin/publishers/create`, `POST /admin/publishers` | `App\Http\Controllers\Admin\PublisherController@create` / `store` | `admin.publishers.create` | Fase 4 (Aktif) |
| `admin/master-data/publishers/[id]/edit/page.tsx` | `GET /admin/publishers/{publisher}/edit`, `PUT /admin/publishers/{publisher}` | `App\Http\Controllers\Admin\PublisherController@edit` / `update` | `admin.publishers.edit` | Fase 4 (Aktif) |

### 1.6 Modul Admin — Pesanan, Transaksi & Pelanggan (12 Halaman)

| Halaman Next.js (`frontend/app/`) | Route Web Baru | Controller | View Blade | Fase & Status |
| :--- | :--- | :--- | :--- | :--- |
| `admin/orders/page.tsx` | `GET /admin/orders` *(+ buat pesanan manual)* | `App\Http\Controllers\Admin\OrderController@index` | `admin.orders.index` | Fase 4 (Aktif — alur WA) |
| `admin/orders/[id]/page.tsx` | `GET /admin/orders/{order}`, `PATCH /admin/orders/{order}/status` | `App\Http\Controllers\Admin\OrderController@show` / `updateStatus` | `admin.orders.show` | Fase 4 (Aktif — ubah status, chat WA, PDF) |
| `admin/invoices/page.tsx` | `GET /admin/invoices`, `GET /admin/orders/{order}/invoice` | `App\Http\Controllers\Admin\InvoiceController` | `admin.invoices.index` / `pdf.invoice` | Fase 4 (Aktif — sesuaikan DomPDF ke `orders` baru) |
| `admin/payments/page.tsx` | — | — | — | **Deprecated** (Payment gateway QRISly/COD dihapus dari alur) |
| `admin/shipping/page.tsx` | — | — | — | **Deprecated** (Pengiriman/RajaOngkir/Resi dibahas di WA) |
| `admin/refunds/page.tsx` | — | — | — | **Deprecated** (Retur/refund ditandai deprecated) |
| `admin/customers/page.tsx` | `GET /admin/customers` | `App\Http\Controllers\Admin\CustomerController@index` | `admin.customers.index` | Fase 4 (Aktif) |
| `admin/customers/[id]/page.tsx` | `GET /admin/customers/{customer}` | `App\Http\Controllers\Admin\CustomerController@show` | `admin.customers.show` | Fase 4 (Aktif) |
| `admin/customers/addresses/page.tsx` | — | — | — | **Deprecated / Evaluasi** (Alamat RajaOngkir tidak lagi dipakai di checkout WA) |
| `admin/customers/history/page.tsx` | `GET /admin/customers/history` | `App\Http\Controllers\Admin\CustomerController@history` | `admin.customers.history` | Fase 4 (Opsional — dapat digabung ke detail pelanggan/pesanan) |
| `admin/customers/history/[id]/page.tsx` | `GET /admin/customers/{customer}/history` | `App\Http\Controllers\Admin\CustomerController@historyShow` | `admin.customers.history-show` | Fase 4 (Opsional) |
| `admin/customers/wishlists/page.tsx` | `GET /admin/customers/wishlists` | `App\Http\Controllers\Admin\CustomerController@wishlists` | `admin.customers.wishlists` | Fase 4 (Opsional) |

### 1.7 Modul Admin — Blog & Konten (14 Halaman)

| Halaman Next.js (`frontend/app/`) | Route Web Baru | Controller | View Blade | Fase & Status |
| :--- | :--- | :--- | :--- | :--- |
| `admin/blog/posts/page.tsx` | `GET /admin/blog/posts` | `App\Http\Controllers\Admin\BlogPostController@index` | `admin.blog.posts.index` | Fase 4 (Aktif) |
| `admin/blog/posts/create/page.tsx` | `GET /admin/blog/posts/create`, `POST /admin/blog/posts` | `App\Http\Controllers\Admin\BlogPostController@create` / `store` | `admin.blog.posts.create` | Fase 4 (Aktif) |
| `admin/blog/posts/edit/[id]/page.tsx` | `GET /admin/blog/posts/{post}/edit`, `PUT /admin/blog/posts/{post}` | `App\Http\Controllers\Admin\BlogPostController@edit` / `update` | `admin.blog.posts.edit` | Fase 4 (Aktif) |
| `admin/blog/categories/page.tsx` | `GET /admin/blog/categories` | `App\Http\Controllers\Admin\BlogCategoryController@index` | `admin.blog.categories.index` | Fase 4 (Aktif) |
| `admin/blog/categories/create/page.tsx` | `GET /admin/blog/categories/create`, `POST /admin/blog/categories` | `App\Http\Controllers\Admin\BlogCategoryController@create` / `store` | `admin.blog.categories.create` | Fase 4 (Aktif) |
| `admin/blog/categories/edit/[id]/page.tsx` | `GET /admin/blog/categories/{category}/edit`, `PUT /admin/blog/categories/{category}` | `App\Http\Controllers\Admin\BlogCategoryController@edit` / `update` | `admin.blog.categories.edit` | Fase 4 (Aktif) |
| `admin/blog/tags/page.tsx` | `GET /admin/blog/tags` | `App\Http\Controllers\Admin\BlogTagController@index` | `admin.blog.tags.index` | Fase 4 (Aktif) |
| `admin/blog/tags/create/page.tsx` | `GET /admin/blog/tags/create`, `POST /admin/blog/tags` | `App\Http\Controllers\Admin\BlogTagController@create` / `store` | `admin.blog.tags.create` | Fase 4 (Aktif) |
| `admin/blog/tags/edit/[id]/page.tsx` | `GET /admin/blog/tags/{tag}/edit`, `PUT /admin/blog/tags/{tag}` | `App\Http\Controllers\Admin\BlogTagController@edit` / `update` | `admin.blog.tags.edit` | Fase 4 (Aktif) |
| `admin/blog/authors/page.tsx` | `GET /admin/blog/authors` | `App\Http\Controllers\Admin\BlogAuthorController@index` | `admin.blog.authors.index` | Fase 4 (Aktif) |
| `admin/blog/authors/create/page.tsx` | `GET /admin/blog/authors/create`, `POST /admin/blog/authors` | `App\Http\Controllers\Admin\BlogAuthorController@create` / `store` | `admin.blog.authors.create` | Fase 4 (Aktif) |
| `admin/blog/authors/edit/[id]/page.tsx` | `GET /admin/blog/authors/{author}/edit`, `PUT /admin/blog/authors/{author}` | `App\Http\Controllers\Admin\BlogAuthorController@edit` / `update` | `admin.blog.authors.edit` | Fase 4 (Aktif) |
| `admin/blog/articles/page.tsx` | — | — | — | **Stub `PlaceholderPage`** (Duplikat dari `/admin/blog/posts`) |
| `admin/blog/comments/page.tsx` | — | — | — | **Stub `PlaceholderPage`** (Belum ada tabel/fitur komentar blog) |

### 1.8 Modul Admin — Marketing (11 Halaman)

| Halaman Next.js (`frontend/app/`) | Route Web Baru | Controller | View Blade | Fase & Status |
| :--- | :--- | :--- | :--- | :--- |
| `admin/marketing/banners/page.tsx` | `GET /admin/marketing/banners` | `App\Http\Controllers\Admin\BannerController@index` | `admin.marketing.banners.index` | Fase 4 (Opsional / pertahankan) |
| `admin/marketing/banners/create/page.tsx` | `GET /admin/marketing/banners/create`, `POST /admin/marketing/banners` | `App\Http\Controllers\Admin\BannerController@create` / `store` | `admin.marketing.banners.create` | Fase 4 (Opsional / pertahankan) |
| `admin/marketing/banners/[id]/edit/page.tsx` | `GET /admin/marketing/banners/{banner}/edit`, `PUT /admin/marketing/banners/{banner}` | `App\Http\Controllers\Admin\BannerController@edit` / `update` | `admin.marketing.banners.edit` | Fase 4 (Opsional / pertahankan) |
| `admin/marketing/campaigns/page.tsx` | `GET /admin/marketing/campaigns` | `App\Http\Controllers\Admin\CampaignController@index` | `admin.marketing.campaigns.index` | Fase 4 (Opsional / pertahankan) |
| `admin/marketing/campaigns/create/page.tsx` | `GET /admin/marketing/campaigns/create`, `POST /admin/marketing/campaigns` | `App\Http\Controllers\Admin\CampaignController@create` / `store` | `admin.marketing.campaigns.create` | Fase 4 (Opsional / pertahankan) |
| `admin/marketing/campaigns/[id]/edit/page.tsx` | `GET /admin/marketing/campaigns/{campaign}/edit`, `PUT /admin/marketing/campaigns/{campaign}` | `App\Http\Controllers\Admin\CampaignController@edit` / `update` | `admin.marketing.campaigns.edit` | Fase 4 (Opsional / pertahankan) |
| `admin/marketing/vouchers/page.tsx` | — | — | — | **Deprecated** (Kupon tidak dipakai di alur WA) |
| `admin/marketing/vouchers/create/page.tsx` | — | — | — | **Deprecated** |
| `admin/marketing/vouchers/[id]/edit/page.tsx` | — | — | — | **Deprecated** |
| `admin/marketing/newsletter/page.tsx` | `GET /admin/newsletter`, `POST /admin/newsletter/broadcast`, `GET /admin/newsletter/export` | `App\Http\Controllers\Admin\NewsletterController` | `admin.marketing.newsletter` | Fase 4 (Aktif) |
| `admin/marketing/reviews/page.tsx` | `GET /admin/reviews`, `PATCH /admin/reviews/{review}/...` | `App\Http\Controllers\Admin\ReviewController` | `admin.marketing.reviews` | Fase 4 (Opsional / pertahankan) |

### 1.9 Modul Admin — Publishing, Laporan & Sistem (17 Halaman)

| Halaman Next.js (`frontend/app/`) | Route Web Baru | Controller | View Blade | Fase & Status |
| :--- | :--- | :--- | :--- | :--- |
| `admin/publishing/submissions/page.tsx` | `GET /admin/submissions`, `PATCH /admin/submissions/{submission}` | `App\Http\Controllers\Admin\SubmissionController` | `admin.publishing.submissions` | Di Next.js masih **Stub `PlaceholderPage`**, namun masuk lingkup Fase 4 (`phase4.md`) — perlu konfirmasi tabel DB |
| `admin/publishing/layout/page.tsx` | — | — | — | **Stub `PlaceholderPage`** |
| `admin/publishing/proofreading/page.tsx` | — | — | — | **Stub `PlaceholderPage`** |
| `admin/publishing/reviews/page.tsx` | — | — | — | **Stub `PlaceholderPage`** |
| `admin/publishing/status/page.tsx` | — | — | — | **Stub `PlaceholderPage`** |
| `admin/reports/sales/page.tsx` | `GET /admin/reports/sales` *(+ export Excel/PDF)* | `App\Http\Controllers\Admin\ReportController@sales` | `admin.reports.sales` | Fase 4 (Aktif) |
| `admin/reports/revenue/page.tsx` | `GET /admin/reports/revenue` *(+ export Excel/PDF)* | `App\Http\Controllers\Admin\ReportController@revenue` | `admin.reports.revenue` | Fase 4 (Aktif) |
| `admin/reports/top-products/page.tsx` | `GET /admin/reports/top-products` *(+ export Excel/PDF)* | `App\Http\Controllers\Admin\ReportController@bestSellers` | `admin.reports.top-products` | Fase 4 (Aktif) |
| `admin/reports/royalties/page.tsx` | `GET /admin/reports/royalties` | `App\Http\Controllers\Admin\RoyaltyController@index` | `admin.reports.royalties` | Fase 4 (Opsional / konfirmasi) |
| `admin/reports/traffic/page.tsx` | `GET /admin/reports/traffic` | `App\Http\Controllers\Admin\ReportController@traffic` | `admin.reports.traffic` | Fase 4 (Opsional / konfirmasi) |
| `admin/system/roles/page.tsx` | — | — | — | **Stub `PlaceholderPage`** |
| `admin/system/settings/page.tsx` | *(Opsional: `GET /admin/settings` untuk nomor WA)* | `App\Http\Controllers\Admin\SettingController` | `admin.system.settings` | Di Next.js **Stub `PlaceholderPage`** — perlu konfirmasi apakah nomor WA disimpan di DB atau `.env` |
| `admin/system/integrations/page.tsx` | — | — | — | **Stub `PlaceholderPage`** |
| `admin/system/notifications/page.tsx` | — | — | — | **Stub `PlaceholderPage`** |
| `admin/media/page.tsx` | — | — | — | **Stub `PlaceholderPage`** |
| `admin/logs/page.tsx` | — | — | — | **Stub `PlaceholderPage`** |
| `admin/recycle-bin/page.tsx` | — | — | — | **Stub `PlaceholderPage`** |

---

## 2. Audit Migration & Model di `backend/`

Hasil audit seluruh **55 file migration** dan **34 file model** di `backend/`:

### 2.1 Tabel & Model Inti / Sistem

| Tabel Database | File Migration Terkait | Model Eloquent | Status Audit | Alasan & Catatan |
| :--- | :--- | :--- | :--- | :--- |
| `users` | `0001_01_01_000000_create_users_table.php`, `2026_08_04_021912_add_profile_fields_to_users_table.php` | `User` | **Pertahankan** | Kolom `role` (`admin`\|`user`), `provider`, `provider_id`, `phone`, `avatar`, `is_active` sudah tersedia dan cast ke `UserRole`. Pastikan relasi ke `cartItems()`, `wishlists()`, `orders()` disesuaikan. |
| `password_reset_tokens` | `0001_01_01_000000_create_users_table.php` | *(Laravel core)* | **Pertahankan** | Standar Laravel untuk reset password web session. |
| `sessions` | `0001_01_01_000000_create_users_table.php` | *(Laravel core)* | **Pertahankan** | Digunakan oleh `SESSION_DRIVER=database`, guard `web`, dan keranjang guest session. |
| `cache`, `cache_locks` | `0001_01_01_000001_create_cache_table.php` | *(Laravel core)* | **Pertahankan** | Infrastruktur cache Laravel. |
| `jobs`, `job_batches`, `failed_jobs` | `0001_01_01_000002_create_jobs_table.php` | *(Laravel core)* | **Pertahankan** | Infrastruktur queue Laravel (pengiriman email/broadcast). |
| `otp_verifications` | `2026_07_22_000001_create_otp_verifications_table.php` | `OtpVerification` | **Pertahankan** | Dipakai bila alur verifikasi registrasi & reset password tetap menggunakan OTP email (lihat Bagian 6 Pertanyaan). |
| `personal_access_tokens` | `2026_07_22_041118_create_personal_access_tokens_table.php` | *(Sanctum)* | **Deprecated** | Sanctum Bearer Token tidak lagi dipakai setelah beralih ke guard `web`. **Jangan hapus** sebelum Fase 5. |

### 2.2 Tabel & Model Katalog Buku

| Tabel Database | File Migration Terkait | Model Eloquent | Status Audit | Alasan & Catatan |
| :--- | :--- | :--- | :--- | :--- |
| `authors` | `2026_07_23_065103_create_authors_table.php` | `Author` | **Pertahankan** | Master data penulis buku. |
| `publishers` | `2026_07_23_065104_create_publishers_table.php` | `Publisher` | **Pertahankan** | Master data penerbit buku. |
| `categories` | `2026_07_23_065105_create_categories_table.php` | `Category` | **Pertahankan** | Master data kategori buku. |
| `genres` | `2026_07_23_065106_create_genres_table.php` | `Genre` | **Pertahankan** | Master data genre buku. |
| `books` | `2026_07_23_065106_create_books_table.php`, `2026_08_04_041257_add_royalty_percentage_to_books_table.php` | `Book` | **Pertahankan** | Tabel utama produk buku (termasuk `stock`, `final_price` accessor, `status`). Tambah relasi `stockMovements()` dan `wishlists()`. |
| `book_category` | `2026_07_23_065107_create_book_category_table.php` | *(Pivot)* | **Pertahankan** | Relasi many-to-many buku-kategori. |
| `book_genre` | `2026_07_23_065108_create_book_genre_table.php` | *(Pivot)* | **Pertahankan** | Relasi many-to-many buku-genre. |

### 2.3 Tabel & Model Blog & Editorial

| Tabel Database | File Migration Terkait | Model Eloquent | Status Audit | Alasan & Catatan |
| :--- | :--- | :--- | :--- | :--- |
| `blog_categories` | `2026_07_28_020516_create_blog_categories_table.php` | `BlogCategory` | **Pertahankan** | Kategori artikel blog. |
| `blog_authors` | `2026_07_28_020517_create_blog_authors_table.php` | `BlogAuthor` | **Pertahankan** | Penulis artikel blog. |
| `blog_tags` | `2026_07_28_020517_create_blog_tags_table.php` | `BlogTag` | **Pertahankan** | Tag artikel blog. |
| `blog_ctas` | `2026_07_28_020518_create_blog_c_t_a_s_table.php` | `BlogCTA` | **Pertahankan** | Call-to-action di dalam artikel blog. |
| `blog_posts` | `2026_07_28_020518_create_blog_posts_table.php` | `BlogPost` | **Pertahankan** | Artikel blog & metadata SEO. |
| `blog_post_tag` | `2026_07_28_020519_create_blog_post_tag_table.php` | *(Pivot)* | **Pertahankan** | Relasi many-to-many artikel-tag. |

### 2.4 Tabel & Model Toko, Keranjang, Pesanan, Pengiriman & Pembayaran

| Tabel Database | File Migration Terkait | Model Eloquent | Status Audit | Alasan & Catatan |
| :--- | :--- | :--- | :--- | :--- |
| `carts` | `2026_07_28_034119_create_carts_table.php` | `Cart` | **Deprecated** | Alur baru menyimpan `user_id` langsung di `cart_items` (unique `user_id` + `book_id`). **Jangan hapus** dulu. |
| `cart_items` | `2026_07_28_034120_create_cart_items_table.php` | `CartItem` | **Ubah** | Saat ini memakai `cart_id` + `quantity`. Ubah lewat migration baru agar memiliki `user_id`, `book_id`, `qty` (unik `user_id` + `book_id`). |
| `wishlists` | `2026_07_28_034121_create_wishlists_table.php` | `Wishlist` | **Pertahankan** | Sudah sesuai spesifikasi: `user_id`, `book_id` dengan constraint unik `['user_id', 'book_id']`. |
| `orders` | `2026_07_28_034122`, `2026_08_04_030915`, `2026_08_04_043836`, `2026_09_27_100000`, `2026_09_27_110000` | `Order` | **Ubah** | Disederhanakan untuk checkout WA: tambah `code`, buat `user_id` nullable (untuk guest), tambah `customer_name`, `customer_phone`, `customer_address`, `total`, `confirmed_at`; kolom lama (`invoice_number`, `grand_total`, voucher, ongkir) dibuat nullable & **deprecated**. |
| `order_items` | `2026_07_28_034123_create_order_items_table.php` | `OrderItem` | **Ubah** | Tambah `title_snapshot`, `price_snapshot`, `qty`. Kolom lama (`quantity`, `price`, `subtotal`) dibuat nullable & **deprecated** setelah backfill. |
| `order_timelines` | `2026_07_29_044448_create_order_timelines_table.php`, `2026_09_27_110000` | `OrderTimeline` | **Pertahankan** | Audit log perubahan status pesanan oleh admin (sesuai Fase 4 poin 8: "aksi sensitif tercatat"). |
| `payments` | `2026_07_28_034123`, `2026_07_28_064702`, `2026_09_27_100000`, `2026_09_27_110000` | `Payment` | **Deprecated** | Payment gateway (QRISly, Midtrans Snap, COD) dihapus dari alur UI/checkout. **Jangan hapus**. |
| `order_shipments` | `2026_07_29_044449_create_order_shipments_table.php` | `OrderShipment` | **Deprecated** | Pengiriman & tracking kurir dibahas manual lewat WA. **Jangan hapus**. |
| `refund_requests` | `2026_07_29_044450_create_refund_requests_table.php` | `RefundRequest` | **Deprecated** | Fitur retur/refund di sistem dihapus. **Jangan hapus**. |
| `user_addresses` | `2026_07_28_034116`, `2026_07_29_022821`, `2026_07_29_030158` | `UserAddress` | **Deprecated** | Terikat kode wilayah RajaOngkir (`province_code`, `regency_code`, `district_code`, `village_code`); checkout WA memakai teks `customer_address`. **Jangan hapus**. |
| `shipping_zones` | `2026_07_28_034118_create_shipping_zones_table.php` | `ShippingZone` | **Deprecated** | Zona ongkos kirim tidak dipakai di sistem baru. **Jangan hapus**. |
| `provinces`, `cities` | `2026_07_28_064700`, `2026_07_28_064701` | *(Tidak ada model)* | **Deprecated** | Sudah pernah di-drop oleh migration `2026_07_29_022821_update_user_addresses_for_rajaongkir_v2.php`. |

### 2.5 Tabel & Model Marketing, Newsletter, Review, Royalti & Analitik

| Tabel Database | File Migration Terkait | Model Eloquent | Status Audit | Alasan & Catatan |
| :--- | :--- | :--- | :--- | :--- |
| `vouchers` | `2026_08_04_030856_create_vouchers_table.php` | `Voucher` | **Deprecated** | Sistem kupon tidak dipakai di checkout WA. **Jangan hapus**. |
| `voucher_usages` | `2026_08_04_043815_create_voucher_usages_table.php` | `VoucherUsage` | **Deprecated** | Riwayat penggunaan kupon. **Jangan hapus**. |
| `banners` | `2026_08_04_030855_create_banners_table.php` | `Banner` | **Pertahankan** | Banner promo di halaman publik/bookstore. |
| `campaigns`, `campaign_books`, `campaign_categories` | `2026_08_04_030857`, `2026_08_04_030913`, `2026_08_04_030914` | `Campaign` | **Pertahankan** | Pengelompokan buku promo/koleksi unggulan. |
| `reviews` | `2026_07_28_034124`, `2026_08_04_030914`, `2026_08_04_043841` | `Review` | **Pertahankan** | Data ulasan yang sudah ada tetap ditampilkan di detail buku (Fase 2) & dapat dimoderasi admin. |
| `newsletter_subscribers` | `2026_08_04_030857_create_newsletter_subscribers_table.php` | `NewsletterSubscriber` | **Pertahankan** | Dipakai di form newsletter publik (Fase 2) & daftar/ekspor admin (Fase 4). |
| `newsletter_messages` | `2026_08_04_030858_create_newsletter_messages_table.php` | `NewsletterMessage` | **Pertahankan** | Riwayat broadcast newsletter admin. |
| `royalty_payments` | `2026_08_04_041302_create_royalty_payments_table.php` | `RoyaltyPayment` | **Pertahankan** | Histori pembayaran royalti penulis (perlu konfirmasi apakah tetap diaktifkan di Fase 4). |
| `page_views` | `2026_08_04_041312_create_page_views_table.php` | `PageView` | **Pertahankan** | Analitik kunjungan halaman. |
| `search_histories` | `2026_08_04_041316_create_search_histories_table.php` | `SearchHistory` | **Pertahankan** | Analitik kata kunci pencarian populer (`/search`). |
| `book_events` | `2026_08_04_041401_create_book_events_table.php` | `BookEvent` | **Pertahankan** | Analitik event view/wishlist/cart/checkout per buku. |

### 2.6 Audit Services & Controllers Lama (Untuk Catatan Pembersihan Bertahap)

- **Services deprecated** (tidak dipakai di alur baru, biarkan sementara): `PaymentService.php`, `QrislyService.php`, `ShippingService.php`, `VoucherService.php`, `Admin/VoucherService.php`.
- **Services diubah/dirombak**: `CartService.php` (support guest session + merge ke `cart_items` user), `CheckoutService.php` (tanpa ongkir/kupon/gateway, menghasilkan `orders` + URL `wa.me`), `OrderService.php` (siklus 5 status WA + `lockForUpdate` + catat `stock_movements`).
- **Controller API (`app/Http/Controllers/Api/*`)**: Tetap dibiarkan selama masa transisi sampai Fase 5 sesuai aturan, sementara route web baru memakai controller baru di `App\Http\Controllers\Web\*` atau `App\Http\Controllers\{Auth,Public,Store,User,Admin}\*`.

---

## 3. Usulan Perubahan Database untuk Alur WhatsApp (Migration Baru)

Sesuai Aturan #5 (`docs/context.md`), **tidak ada migration lama yang diedit**. Semua perubahan dilakukan melalui **migration baru** pada Fase 1 Bagian B (setelah disetujui).

### 3.1 `users`
- Kolom `role` sudah tersedia di `0001_01_01_000000_create_users_table.php` (`string('role', 20)->default('user')->index()`).
- Tidak perlu menambah kolom `role` lagi, cukup memastikan nilai validnya adalah `'admin'` dan `'user'` via enum `App\Enums\UserRole`.
- *(Opsional, menunggu jawaban Pertanyaan)*: Jika fitur daftar alamat `user_addresses` dihapus, pertimbangkan menambah kolom `address` (`text` nullable) di tabel `users` untuk auto-fill form checkout WA.

### 3.2 `orders` (Disederhanakan untuk Alur WA)
Migration baru pada tabel `orders`:
- Tambah kolom:
  - `code` (`string(32)->unique()->nullable()->after('id')`) — format `MP-YYYYMMDD-XXXX` (dibuat nullable saat schema change, diisi saat backfill, lalu constraint `unique` aktif).
  - `customer_name` (`string()->nullable()->after('user_id')`)
  - `customer_phone` (`string(30)->nullable()->after('customer_name')`)
  - `customer_address` (`text()->nullable()->after('customer_phone')`)
  - `total` (`unsignedBigInteger` atau `decimal(15, 2)` nullable->default(0) — *lihat Pertanyaan #4*)
  - `confirmed_at` (`timestamp()->nullable()->after('notes')`)
- Modifikasi kolom agar kompatibel dengan guest & alur WA tanpa kolom gateway:
  - `user_id` diubah menjadi **nullable** (drop FK lama `orders_user_id_foreign`, ubah ke nullable, pasang FK ulang dengan `nullOnDelete()`).
  - `invoice_number`, `subtotal`, `grand_total` diubah menjadi **nullable** (agar insert order baru alur WA yang hanya mengisi `code` & `total` tidak gagal `NOT NULL`).
  - Kolom `status` tetap `string`, dengan nilai standar alur baru: `'pending'`, `'confirmed'`, `'paid'`, `'completed'`, `'cancelled'` (plus index pada `status` dan `created_at`).
  - Kolom lama (`invoice_number`, `subtotal`, `shipping_fee`, `insurance_fee`, `discount`, `tax`, `grand_total`, `shipping_address_id`, `shipping_method`, `payment_method`, `awb_number`, `pickup_code`, `picked_up_at`, `promo_voucher_id`, `shipping_voucher_id`, `cashback_voucher_id`, `promo_discount_amount`, `shipping_discount_amount`, `cashback_amount`) **dipertahankan sementara (deprecated)** agar histori pesanan lama tidak hilang.

### 3.3 `order_items`
Migration baru pada tabel `order_items`:
- Tambah kolom:
  - `title_snapshot` (`string()->nullable()->after('book_id')`)
  - `price_snapshot` (`unsignedBigInteger` atau `decimal(15, 2)` nullable — *lihat Pertanyaan #4*)
  - `qty` (`unsignedInteger()->nullable()->after('price_snapshot')`)
- Modifikasi kolom lama:
  - `quantity`, `price`, `subtotal` diubah menjadi **nullable** agar item pesanan baru hanya wajib mengisi `title_snapshot`, `price_snapshot`, `qty`.
  - Ubah FK `book_id` menjadi **nullable** dengan `nullOnDelete()` (bukan `cascadeOnDelete`), agar histori pembelian di `order_items` tetap aman beserta snapshot judul/harga meskipun suatu buku dihapus permanen.

### 3.4 `cart_items`
Saat ini `cart_items` memakai `cart_id` + `quantity`, bukan `user_id` + `qty`. Migration baru pada tabel `cart_items`:
- Tambah kolom:
  - `user_id` (`foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete()`)
  - `qty` (`unsignedInteger('qty')->default(1)->after('book_id')`)
- Modifikasi kolom lama:
  - `cart_id` dibuat **nullable** (deprecated, tidak lagi digunakan untuk item baru).
  - `quantity` dibuat **nullable** (deprecated).
- Tambah unique constraint:
  - `$table->unique(['user_id', 'book_id']);` (mencegah duplikasi buku yang sama per user).

### 3.5 `wishlists` (atau `book_likes`)
- Tabel `wishlists` sudah tersedia dengan struktur `id`, `user_id` (`constrained()->cascadeOnDelete()`), `book_id` (`constrained()->cascadeOnDelete()`), `unique(['user_id', 'book_id'])`, dan `timestamps`.
- Diusulkan **langsung memakai tabel `wishlists`** tanpa membuat tabel baru `book_likes` (kecuali ada kebutuhan penamaan khusus — lihat Pertanyaan #3).

### 3.6 `stock_movements` (Tabel Baru)
Migration baru `create_stock_movements_table`:
```php
Schema::create('stock_movements', function (Blueprint $table) {
    $table->id();
    $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
    $table->integer('change'); // negatif saat keluar/terjual, positif saat masuk/batal
    $table->string('reason'); // e.g. 'order_confirmed', 'order_cancelled', 'manual_adjustment'
    $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
    $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
    $table->integer('stock_after');
    $table->timestamps();

    $table->index(['book_id', 'created_at']);
    $table->index(['order_id', 'reason']);
});
```
*(Index `[order_id, reason]` membantu pengecekan idempotensi agar pengurangan/pengembalian stok per pesanan tidak terjadi dua kali).*

### 3.7 Tabel & Kode Deprecated (Jangan Dihapus Sampai Fase 5)
- **Tabel**: `personal_access_tokens`, `carts`, `payments`, `order_shipments`, `refund_requests`, `user_addresses`, `shipping_zones`, `vouchers`, `voucher_usages`.
- **Kode terkait**: seluruh `Api\Store\PaymentController`, `Api\Store\ShippingController`, `Api\Admin\PaymentController`, `Api\Admin\ShipmentController`, `Api\Admin\RefundController`, `Api\Admin\VoucherController`, `PaymentService`, `QrislyService`, `ShippingService`, `VoucherService`, dan `routes/api.php`.

---

## 4. Rekomendasi Teknis (Untuk Keputusan Pengguna)

### 4.1 Pendekatan Admin Panel: Filament 3 vs Blade + Livewire Manual

| Aspek | Blade + Livewire Manual | Filament 3 |
| :--- | :--- | :--- |
| **Konsistensi Visual Next.js** | **Sangat tinggi.** Sidebar `#F7F8F2`, active menu `#F7F09E`, komponen, dan tipografi dari `frontend/app/admin/*` bisa diterjemahkan persis sesuai Aturan #2 (`context.md`). | **Berubah total.** Menggunakan UI bawaan Filament (tidak sama dengan desain admin Next.js yang sudah ada). |
| **Kompatibilitas Stack (Tailwind v4)** | **Mulus.** Project backend saat ini sudah menggunakan Tailwind CSS v4 (`@tailwindcss/vite`). | **Perlu penyesuaian.** Filament 3 masih memakai Tailwind CSS v3 dan menginstal dependensi sendiri. |
| **Kecepatan Pengembangan CRUD** | Lebih lama karena view form & tabel tiap resource ditulis manual (meski logic di `App\Services\Admin\*` sudah ada). | **Sangat cepat** untuk 15+ resource CRUD (Buku, Author, Kategori, Genre, Penerbit, Blog, Banner, Campaign, Newsletter). |
| **Kontrol Alur Pesanan WA & Stok** | Kontrol penuh dan langsung di Controller/Livewire tanpa menabrak konvensi panel. | Bisa dilakukan via custom Action & Page di Filament, namun tetap butuh penyesuaian modal/konfirmasi stok. |

**Rekomendasi**:
- Jika prioritas utama adalah **mempertahankan gaya visual Next.js Admin** dan menjaga stack tetap ringan & seragam di Tailwind v4: pilih **Blade + Livewire manual**.
- Jika prioritas utama adalah **kecepatan pengerjaan ~15+ modul CRUD admin** dan Anda bersedia tampilan admin memakai standar Filament: pilih **Filament 3**.
- **Rekomendasi default saya**: **Blade + Livewire manual** (dengan komponen Blade reusable untuk tabel, form, dan modal) karena lebih selaras dengan Aturan #2 (`Pertahankan gaya visual Next.js`) dan kompatibilitas native dengan Tailwind v4 yang sudah terpasang. *(Menunggu keputusan Anda).*

### 4.2 Titik Pengurangan Stok: `confirmed` vs `paid`

- **Opsi 1 — `confirmed` (Direkomendasikan)**:
  - Stok dikurangi saat admin mengonfirmasi ketersediaan pesanan di chat WhatsApp (`pending` → `confirmed`).
  - **Alasan**: Dalam transaksi berbasis WhatsApp, ada jeda waktu antara admin menyatakan "buku tersedia, silakan transfer" hingga pelanggan melakukan pembayaran. Jika stok belum dikurangi saat `confirmed`, buku berisiko dibeli pelanggan lain di katalog web (*overselling*). Jika pembeli batal/tidak membayar, admin ubah status ke `cancelled` dan stok otomatis dikembalikan.
- **Opsi 2 — `paid`**:
  - Stok baru dikurangi saat admin menandai pesanan sudah lunas (`paid`).
  - **Alasan**: Stok fisik baru dianggap keluar saat uang diterima. Namun pada alur WA asinkron, stok masih terlihat tersedia selama pembeli menunggu transfer.

**Rekomendasi**: Kurangi stok saat status berubah ke **`confirmed`** (dengan pengembalian otomatis apabila status berubah ke `cancelled` dari `confirmed`, `paid`, atau `completed`). *(Menunggu keputusan Anda).*

---

## 5. Rencana Migrasi Data untuk Pesanan Lama

Agar histori transaksi lama tetap utuh ketika skema baru diaktifkan, migrasi data dijalankan melalui **Artisan Command / Data Migration** yang bersifat **idempoten** di dalam `DB::transaction()` (wajib backup database terlebih dahulu sebelum eksekusi di produksi).

### Langkah 1 — Backup & Jalankan Schema Migration Baru
- Lakukan `mysqldump` sebelum menjalankan `php artisan migrate`.
- Migration baru hanya **menambah** kolom baru dan melonggarkan `nullable` pada kolom lama, tidak menghapus kolom apa pun.

### Langkah 2 — Backfill Tabel `orders`
Untuk setiap record `orders` yang belum memiliki `code` (`whereNull('code')`), proses secara chunked (`chunkById`):
1. `code`: Salin dari `invoice_number` (jika sudah unik) atau generate fallback `MP-LEGACY-{id}` bila kosong, untuk menjaga referensi nomor faktur lama.
2. `customer_name`: Ambil dari `shippingAddress->recipient_name`, fallback ke `user->name`, fallback `'Pelanggan Lama'`.
3. `customer_phone`: Ambil dari `shippingAddress->phone`, fallback ke `user->phone`, fallback `'-'`.
4. `customer_address`:
   - Jika `shipping_method` mengandung `'pickup'`, isi `'Pickup di Toko'`.
   - Jika punya `shippingAddress`, gabungkan `address`, `village_name`, `district_name`, `regency_name`, `province_name`, `postal_code`.
   - Fallback ke `'Alamat tidak tercatat'`.
5. `total`: Salin dari `grand_total`.
6. Mapping `status` lama ke status baru:
   - `['Pending', 'pending', 'pending_payment']` → `'pending'`
   - `['Processing', 'Ready to Pack', 'ready_to_ship', 'ready_for_pickup']` → `'confirmed'`
   - `['Paid', 'Shipped', 'picked_up']` → `'paid'`
   - `['Completed', 'completed']` → `'completed'`
   - `['Cancelled', 'cancelled', 'Refunded', 'refunded', 'expired', 'failed']` → `'cancelled'`
   - *(Catat status lama di `order_timelines` bila belum ada log transisi agar riwayat detail tidak hilang).*
7. `confirmed_at`: Jika status hasil mapping adalah `confirmed`, `paid`, atau `completed` dan `confirmed_at` masih null, isi dari `payment?->paid_at ?? $order->updated_at ?? $order->created_at`.
8. `completed_at`: Jika status hasil mapping adalah `completed` dan `completed_at` masih null, isi dari `picked_up_at ?? $order->updated_at`.

### Langkah 3 — Backfill Tabel `order_items`
Untuk setiap `order_items` di mana `qty` masih null (`whereNull('qty')`):
1. `title_snapshot` = `$item->book?->title ?? 'Buku (Arsip #' . $item->book_id . ')'`
2. `price_snapshot` = `$item->price`
3. `qty` = `$item->quantity`

### Langkah 4 — Backfill Tabel `cart_items`
1. Join `cart_items` dengan `carts` untuk menyalin `carts.user_id` ke `cart_items.user_id`, dan `cart_items.quantity` ke `cart_items.qty`.
2. Jika ditemukan beberapa baris dengan kombinasi `(user_id, book_id)` yang sama (misal dari beberapa cart lama), gabungkan `qty`-nya pada baris pertama dan hapus baris duplikat sebelum menambahkan unique index `['user_id', 'book_id']`.

### Langkah 5 — Verifikasi Pasca Migrasi Data
- Pastikan `Order::whereNull('code')->count() === 0` dan `Order::whereNull('total')->count() === 0`.
- Pastikan semua status `Order` berada dalam daftar `['pending', 'confirmed', 'paid', 'completed', 'cancelled']`.
- Pastikan `OrderItem::whereNull('title_snapshot')->orWhereNull('qty')->count() === 0`.
- Uji render PDF invoice (`pdf.invoice`) terhadap data pesanan lama maupun format pesanan baru (penyesuaian template DomPDF dikerjakan di Fase 4).

---

## 6. Pertanyaan & Hal Ambigu yang Perlu Konfirmasi

Sebelum masuk ke **Bagian B (Fondasi)**, mohon keputusan/konfirmasi Anda atas hal-hal berikut yang ditemukan saat audit:

1. **Tabel & Fitur Pengajuan Naskah (`manuscript_submissions`)**:
   `docs/phase2.md` menyebut *"form pengajuan naskah (Form Request, upload file, simpan ke tabel yang ada, notifikasi/email bila sebelumnya ada)"* dan `docs/phase4.md` menyebut pengelolaan pengajuan naskah di admin. Namun hasil audit menemukan **belum ada tabel database, model, maupun controller pengajuan naskah** di `backend/`, dan halaman `/admin/publishing/submissions` di `frontend/` masih berupa stub `PlaceholderPage`.
   - Apakah kita perlu **membuat tabel baru `manuscript_submissions`** (di Fase 1B atau Fase 2), atau tombol "Kirim Naskah" di landing page cukup diarahkan ke link WhatsApp/email tanpa menyimpan file ke database?

2. **Alur Auth Session: OTP 6 Digit vs Verifikasi Standar Laravel**:
   Backend saat ini memiliki tabel `otp_verifications` dan mewajibkan verifikasi OTP 6 digit via email saat registrasi & lupa password. Di `docs/phase1.md` Bagian B.3 tertulis: *"Session auth: login, register, logout, lupa password, Google OAuth (Socialite). Gunakan guard `web`, bukan Sanctum."*
   - Apakah pada Fase 1B nanti proses register dan lupa password **tetap menggunakan kode OTP 6 digit** (`otp_verifications`), atau disederhanakan menjadi auth Laravel standar (register langsung aktif/email link, dan lupa password via link email `password_reset_tokens`)?

3. **Penamaan Tabel Like/Wishlist (`wishlists` vs `book_likes`)**:
   Di database sudah ada tabel `wishlists` dengan struktur persis (`user_id`, `book_id`, unik). Apakah kita tetap memakai tabel `wishlists` (tanpa membuat tabel baru `book_likes`)?

4. **Tipe Data Harga & Total (`decimal(15,2)` vs `unsignedBigInteger`)**:
   Di tabel `books`, `price` menggunakan `decimal(15, 2)`, sedangkan di tabel `orders` dan `order_items` lama menggunakan `integer`. Untuk kolom baru `orders.total` dan `order_items.price_snapshot`, apakah diseragamkan ke `decimal(15, 2)` atau `unsignedBigInteger` (Rupiah bulat)?

5. **Alamat Pelanggan & Nomor WhatsApp Admin**:
   - Di `frontend/` profil user memiliki tab "Daftar Alamat" (`user_addresses` yang terikat RajaOngkir). Karena checkout WA hanya membutuhkan alamat teks singkat, apakah tab "Daftar Alamat" dihapus dan diganti 1 kolom `address` (`text` nullable) di tabel `users`?
   - `docs/phase3.md` menyebut nomor WA admin bisa dari `config/services.php` (`.env`) **atau** tabel `settings`. Apakah cukup memakai `.env` / `config/services.php` saja, atau perlu membuat tabel `settings` di DB agar nomor WA bisa diubah dari Admin Panel?

6. **Cakupan Halaman Admin Duplikat, Stub, & Non-Inti**:
   - Halaman duplikat kategori (`/admin/categories` vs `/admin/master-data/categories`): apakah disatukan menjadi satu modul Kategori & Genre saja?
   - Halaman stub `PlaceholderPage` di `frontend/` (`admin/publishing/{layout,proofreading,reviews,status}`, `admin/system/{roles,integrations,notifications}`, `admin/media`, `admin/logs`, `admin/recycle-bin`, `admin/blog/{articles,comments}`): apakah dikonfirmasi **tidak dimigrasi / diabaikan**?
   - Modul tambahan yang sudah jadi di backend & Next.js namun tidak disebut eksplisit di `phase4.md` (`banners`, `campaigns`, `royalty_payments`, `reviews` moderasi, `reports/traffic`): apakah tetap dipertahankan di Fase 4?

7. **Dua Keputusan Wajib Sebelum Bagian B / Fase 4**:
   - **Pendekatan Admin Panel**: memilih **Blade + Livewire manual** (rekomendasi) atau **Filament 3**?
   - **Titik Pengurangan Stok**: saat status berubah ke **`confirmed`** (rekomendasi) atau **`paid`**?
