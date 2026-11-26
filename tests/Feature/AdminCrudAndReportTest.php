<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Author;
use App\Models\Book;
use App\Models\ManuscriptSubmission;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Publisher;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCrudAndReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_book_and_blog_post_and_view_reports_and_pdfs(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $author = Author::factory()->create();
        $publisher = Publisher::factory()->create();

        $this->actingAs($admin);

        // Create Book
        $this->post(route('admin.books.store'), [
            'title' => 'Buku Uji Monolith',
            'author_id' => $author->id,
            'publisher_id' => $publisher->id,
            'price' => 95000,
            'stock' => 15,
            'cover_type' => 'softcover',
            'book_type' => 'physical',
            'status' => 'published',
        ])->assertRedirect(route('admin.books.index'));

        $book = Book::where('title', 'Buku Uji Monolith')->firstOrFail();
        $this->assertSame(15, $book->stock);

        // Create Blog Post
        $this->post(route('admin.blog.posts.store'), [
            'title' => 'Artikel Uji Editorial',
            'content' => '<p>Isi artikel blog editorial Mega Press.</p>',
            'status' => 'published',
        ])->assertRedirect(route('admin.blog.posts.index'));

        $this->assertDatabaseHas('blog_posts', ['title' => 'Artikel Uji Editorial']);

        // Order PDFs & Reports
        $order = Order::create([
            'code' => 'MP-20261126-PDF1',
            'customer_name' => 'Pembeli PDF',
            'customer_phone' => '08123456789',
            'customer_address' => 'Jakarta',
            'status' => Order::STATUS_PAID,
            'total' => 95000,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'book_id' => $book->id,
            'title_snapshot' => $book->title,
            'price_snapshot' => 95000,
            'qty' => 1,
        ]);

        $this->get(route('admin.orders.invoice', $order))->assertOk();
        $this->get(route('admin.orders.packing-slip', $order))->assertOk();
        $this->get(route('admin.reports.index'))->assertOk()->assertSee('Laporan Penjualan');

        // Manuscript submission status update
        $sub = ManuscriptSubmission::create([
            'title' => 'Naskah Uji',
            'author_name' => 'Penulis Uji',
            'email' => 'penulis@example.com',
            'phone' => '081234',
            'synopsis' => 'Sinopsis uji coba.',
            'file_path' => 'manuscripts/test.pdf',
            'status' => ManuscriptSubmission::STATUS_PENDING,
        ]);

        $this->patch(route('admin.submissions.status', $sub), [
            'status' => ManuscriptSubmission::STATUS_ACCEPTED,
            'admin_notes' => 'Layak terbit',
        ])->assertRedirect();

        $this->assertSame(ManuscriptSubmission::STATUS_ACCEPTED, $sub->fresh()->status);

        // Customer list & detail, Review moderation, Admin Profile
        $customer = User::factory()->create(['role' => UserRole::User]);
        $review = Review::create([
            'user_id' => $customer->id,
            'book_id' => $book->id,
            'order_id' => $order->id,
            'rating' => 5,
            'comment' => 'Buku sangat bagus!',
            'is_hidden' => false,
        ]);

        $this->get(route('admin.customers.index'))->assertOk()->assertSee($customer->name);
        $this->get(route('admin.customers.show', $customer))->assertOk()->assertSee($customer->email);

        $this->get(route('admin.reviews.index'))->assertOk()->assertSee('Buku sangat bagus!');
        $this->patch(route('admin.reviews.toggle-hide', $review))->assertRedirect();
        $this->assertTrue($review->fresh()->is_hidden);

        $this->post(route('admin.reviews.reply', $review), ['reply' => 'Terima kasih!'])->assertRedirect();
        $this->assertSame('Terima kasih!', $review->fresh()->reply);

        $this->get(route('admin.profile.show'))->assertOk();
        $this->put(route('admin.profile.update'), ['name' => 'Admin Updated', 'phone' => '081111'])->assertRedirect();
        $this->assertSame('Admin Updated', $admin->fresh()->name);
    }
}
