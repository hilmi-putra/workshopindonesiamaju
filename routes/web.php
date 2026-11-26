<?php

use App\Http\Controllers\Admin\BlogPostController as AdminBlogPostController;
use App\Http\Controllers\Admin\BlogTaxonomyController as AdminBlogTaxonomyController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\NewsletterController as AdminNewsletterController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\StockController as AdminStockController;
use App\Http\Controllers\Admin\SubmissionController as AdminSubmissionController;
use App\Http\Controllers\Admin\TaxonomyController as AdminTaxonomyController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Public\BlogController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\SearchController;
use App\Http\Controllers\Store\BookstoreController;
use App\Http\Controllers\Store\CartController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use Illuminate\Support\Facades\Route;

// Public & Company Profile Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/pengajuan-naskah', [HomeController::class, 'submitManuscript'])
    ->middleware('throttle:10,1')
    ->name('submissions.store');
Route::post('/newsletter/subscribe', [HomeController::class, 'subscribeNewsletter'])
    ->middleware('throttle:10,1')
    ->name('newsletter.subscribe');
Route::get('/sitemap.xml', [HomeController::class, 'sitemap'])->name('sitemap');

// Blog Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Search
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Bookstore & Cart (Guest + User)
Route::prefix('bookstore')->name('bookstore.')->group(function () {
    Route::get('/', [BookstoreController::class, 'index'])->name('index');
    Route::get('/koleksi', [BookstoreController::class, 'index'])->name('collection');
    Route::get('/buku/{slug}', [BookstoreController::class, 'show'])->name('show');
});

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

// Auth Routes (Web Guard Session)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:10,1')->name('login.post');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:10,1')->name('register.post');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->middleware('throttle:5,1')->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Google Socialite OAuth
Route::get('/auth/google', [SocialiteController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [SocialiteController::class, 'callback'])->name('auth.google.callback');

// Authenticated Customer Dashboard
Route::middleware('auth')->prefix('dashboard')->name('dashboard')->group(function () {
    Route::get('/', [UserDashboardController::class, 'index']);
    Route::get('/pesanan', [UserDashboardController::class, 'orders'])->name('.orders');
    Route::get('/orders/{code}', [UserDashboardController::class, 'orderShow'])->name('.orders.show');
    Route::get('/wishlist', [UserDashboardController::class, 'wishlist'])->name('.wishlist');
    Route::delete('/wishlist/{bookId}', [UserDashboardController::class, 'removeWishlist'])->name('.wishlist.remove');
    Route::get('/profil', [UserDashboardController::class, 'profile'])->name('.profile');
    Route::put('/profil', [UserDashboardController::class, 'updateProfile'])->name('.profile.update');
    Route::put('/password', [UserDashboardController::class, 'updatePassword'])->name('.password.update');
});

// Admin Backoffice Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Orders & Stock
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [AdminOrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [AdminOrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('/orders/{order}/invoice', [AdminOrderController::class, 'invoicePdf'])->name('orders.invoice');
    Route::get('/orders/{order}/packing-slip', [AdminOrderController::class, 'packingSlipPdf'])->name('orders.packing-slip');

    Route::get('/stock', [AdminStockController::class, 'index'])->name('stock.index');
    Route::post('/stock/adjust', [AdminStockController::class, 'adjust'])->name('stock.adjust');

    // Books & Taxonomies
    Route::resource('books', AdminBookController::class)->except(['show']);

    Route::get('/categories', [AdminTaxonomyController::class, 'categories'])->name('categories.index');
    Route::post('/categories', [AdminTaxonomyController::class, 'storeCategory'])->name('categories.store');
    Route::delete('/categories/{category}', [AdminTaxonomyController::class, 'destroyCategory'])->name('categories.destroy');
    Route::post('/genres', [AdminTaxonomyController::class, 'storeGenre'])->name('genres.store');
    Route::delete('/genres/{genre}', [AdminTaxonomyController::class, 'destroyGenre'])->name('genres.destroy');

    Route::get('/authors', [AdminTaxonomyController::class, 'authors'])->name('authors.index');
    Route::post('/authors', [AdminTaxonomyController::class, 'storeAuthor'])->name('authors.store');
    Route::delete('/authors/{author}', [AdminTaxonomyController::class, 'destroyAuthor'])->name('authors.destroy');

    Route::get('/publishers', [AdminTaxonomyController::class, 'publishers'])->name('publishers.index');
    Route::post('/publishers', [AdminTaxonomyController::class, 'storePublisher'])->name('publishers.store');
    Route::delete('/publishers/{publisher}', [AdminTaxonomyController::class, 'destroyPublisher'])->name('publishers.destroy');

    // Blog Admin
    Route::prefix('blog')->name('blog.')->group(function () {
        Route::resource('posts', AdminBlogPostController::class)->except(['show']);
        Route::get('/taxonomy', [AdminBlogTaxonomyController::class, 'index'])->name('taxonomy.index');
        Route::post('/categories', [AdminBlogTaxonomyController::class, 'storeCategory'])->name('categories.store');
        Route::delete('/categories/{category}', [AdminBlogTaxonomyController::class, 'destroyCategory'])->name('categories.destroy');
        Route::post('/tags', [AdminBlogTaxonomyController::class, 'storeTag'])->name('tags.store');
        Route::delete('/tags/{tag}', [AdminBlogTaxonomyController::class, 'destroyTag'])->name('tags.destroy');
        Route::post('/authors', [AdminBlogTaxonomyController::class, 'storeAuthor'])->name('authors.store');
        Route::delete('/authors/{author}', [AdminBlogTaxonomyController::class, 'destroyAuthor'])->name('authors.destroy');
    });

    // Manuscript Submissions & Newsletter
    Route::get('/submissions', [AdminSubmissionController::class, 'index'])->name('submissions.index');
    Route::patch('/submissions/{submission}/status', [AdminSubmissionController::class, 'updateStatus'])->name('submissions.status');
    Route::get('/submissions/{submission}/download', [AdminSubmissionController::class, 'download'])->name('submissions.download');
    Route::get('/submissions/export', [AdminSubmissionController::class, 'export'])->name('submissions.export');

    Route::get('/newsletter', [AdminNewsletterController::class, 'index'])->name('newsletter.index');
    Route::post('/newsletter/broadcast', [AdminNewsletterController::class, 'broadcast'])->name('newsletter.broadcast');
    Route::delete('/newsletter/subscribers/{subscriber}', [AdminNewsletterController::class, 'destroySubscriber'])->name('newsletter.subscribers.destroy');
    Route::get('/newsletter/export', [AdminNewsletterController::class, 'export'])->name('newsletter.export');

    // Reports
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');

    // Customers, Reviews, Admin Profile
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [AdminCustomerController::class, 'show'])->name('customers.show');

    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{review}/toggle-hide', [AdminReviewController::class, 'toggleHide'])->name('reviews.toggle-hide');
    Route::post('/reviews/{review}/reply', [AdminReviewController::class, 'reply'])->name('reviews.reply');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/profile', [AdminProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');
});
