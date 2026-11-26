<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Livewire\BookActions;
use App\Livewire\CartPage;
use App\Livewire\WishlistToggle;
use App\Models\Author;
use App\Models\Book;
use App\Models\Order;
use App\Models\Publisher;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CartAndWhatsAppCheckoutTest extends TestCase
{
    use RefreshDatabase;

    private function createBook(float $price = 100000, int $stock = 5): Book
    {
        $author = Author::factory()->create();
        $publisher = Publisher::factory()->create();

        return Book::factory()->create([
            'author_id' => $author->id,
            'publisher_id' => $publisher->id,
            'price' => $price,
            'discount' => 0,
            'promo_price' => null,
            'stock' => $stock,
            'status' => 'published',
        ]);
    }

    public function test_cart_validates_stock_limit(): void
    {
        $book = $this->createBook(price: 50000, stock: 2);
        $cartService = app(CartService::class);

        $cartService->addItem($book->id, 2);
        $this->assertSame(2, $cartService->getCount());

        $this->expectException(\InvalidArgumentException::class);
        $cartService->addItem($book->id, 1);
    }

    public function test_wishlist_toggle_redirects_guest_to_login_and_works_for_authenticated_user(): void
    {
        $book = $this->createBook();

        Livewire::test(WishlistToggle::class, ['bookId' => $book->id])
            ->call('toggle')
            ->assertRedirect(route('login'));

        $user = User::factory()->create(['role' => UserRole::User]);
        $this->actingAs($user);

        Livewire::test(WishlistToggle::class, ['bookId' => $book->id])
            ->call('toggle')
            ->assertSet('isWishlisted', true);

        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);

        Livewire::test(WishlistToggle::class, ['bookId' => $book->id])
            ->call('toggle')
            ->assertSet('isWishlisted', false);

        $this->assertDatabaseMissing('wishlists', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);
    }

    public function test_guest_checkout_via_whatsapp_creates_order_with_server_total_and_does_not_deduct_stock(): void
    {
        config()->set('services.whatsapp.number', '6285724693474');

        $book = $this->createBook(price: 85000, stock: 10);
        app(CartService::class)->addItem($book->id, 2);

        $component = Livewire::test(CartPage::class)
            ->set('customer_name', 'Pembeli Guest')
            ->set('customer_phone', '081299988877')
            ->set('customer_address', 'Jl. Merdeka No. 10, Bandung')
            ->call('checkoutWhatsApp');

        $order = Order::with('items')->firstOrFail();

        $this->assertMatchesRegularExpression('/^MP-\d{8}-[A-Z0-9]{4}$/', $order->code);
        $this->assertNull($order->user_id);
        $this->assertSame(Order::STATUS_PENDING, $order->status);
        $this->assertEquals(170000.00, (float) $order->total);

        $item = $order->items->first();
        $this->assertSame($book->title, $item->title_snapshot);
        $this->assertEquals(85000.00, (float) $item->price_snapshot);
        $this->assertSame(2, $item->qty);

        // Stock must NOT be deducted during checkout
        $this->assertSame(10, $book->fresh()->stock);

        // Cart cleared
        $this->assertSame(0, app(CartService::class)->getCount());

        // Redirects to wa.me with encoded code & total
        $redirectTarget = $component->effects['redirect'] ?? '';
        $this->assertStringStartsWith('https://wa.me/6285724693474?text=', $redirectTarget);
        $this->assertStringContainsString($order->code, rawurldecode($redirectTarget));
    }

    public function test_direct_buy_via_whatsapp_on_book_detail_works_for_logged_in_user(): void
    {
        config()->set('services.whatsapp.number', '6285724693474');

        $user = User::factory()->create([
            'role' => UserRole::User,
            'name' => 'Rina',
            'phone' => '08123456789',
            'address' => 'Surabaya',
        ]);
        $book = $this->createBook(price: 60000, stock: 5);

        $this->actingAs($user);

        $component = Livewire::test(BookActions::class, ['book' => $book])
            ->set('qty', 3)
            ->call('buyViaWhatsApp');

        $order = Order::firstOrFail();
        $this->assertSame($user->id, $order->user_id);
        $this->assertEquals(180000.00, (float) $order->total);
        $this->assertSame(5, $book->fresh()->stock);

        $redirectTarget = $component->effects['redirect'] ?? '';
        $this->assertStringStartsWith('https://wa.me/6285724693474?text=', $redirectTarget);
    }
}
