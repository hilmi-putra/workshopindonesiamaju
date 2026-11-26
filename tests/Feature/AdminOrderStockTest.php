<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Author;
use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Publisher;
use App\Models\StockMovement;
use App\Models\User;
use App\Services\AdminOrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrderStockTest extends TestCase
{
    use RefreshDatabase;

    private function createOrderWithBook(int $stock = 10, int $qty = 3): array
    {
        $author = Author::factory()->create();
        $publisher = Publisher::factory()->create();
        $book = Book::factory()->create([
            'author_id' => $author->id,
            'publisher_id' => $publisher->id,
            'price' => 50000,
            'discount' => 0,
            'promo_price' => null,
            'stock' => $stock,
            'status' => 'published',
        ]);

        $order = Order::create([
            'code' => 'WIM-20261126-TEST',
            'customer_name' => 'Pelanggan Uji',
            'customer_phone' => '081200001111',
            'customer_address' => 'Yogyakarta',
            'status' => Order::STATUS_PENDING,
            'total' => 50000 * $qty,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'book_id' => $book->id,
            'title_snapshot' => $book->title,
            'price_snapshot' => 50000,
            'qty' => $qty,
            'quantity' => $qty,
            'price' => 50000,
            'subtotal' => 50000 * $qty,
        ]);

        return [$order, $book];
    }

    public function test_stock_is_deducted_once_at_confirmed_and_not_duplicated_through_completed(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        [$order, $book] = $this->createOrderWithBook(stock: 10, qty: 3);

        $this->actingAs($admin);

        // pending -> confirmed (deducts 3)
        $this->patch(route('admin.orders.status', $order), ['status' => Order::STATUS_CONFIRMED])
            ->assertRedirect();
        $this->assertSame(7, $book->fresh()->stock);

        // confirmed -> paid (must NOT deduct again)
        $this->patch(route('admin.orders.status', $order), ['status' => Order::STATUS_PAID])
            ->assertRedirect();
        $this->assertSame(7, $book->fresh()->stock);

        // paid -> completed (must NOT deduct again)
        $this->patch(route('admin.orders.status', $order), ['status' => Order::STATUS_COMPLETED])
            ->assertRedirect();
        $this->assertSame(7, $book->fresh()->stock);

        $this->assertSame(1, StockMovement::where('order_id', $order->id)->count());
        $this->assertDatabaseHas('stock_movements', [
            'order_id' => $order->id,
            'book_id' => $book->id,
            'change' => -3,
            'stock_after' => 7,
            'admin_id' => $admin->id,
        ]);
    }

    public function test_cancelling_confirmed_order_restores_stock_once(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        [$order, $book] = $this->createOrderWithBook(stock: 10, qty: 4);
        $service = app(AdminOrderService::class);

        $service->transitionStatus($order, Order::STATUS_CONFIRMED, $admin);
        $this->assertSame(6, $book->fresh()->stock);

        $service->transitionStatus($order->fresh(), Order::STATUS_CANCELLED, $admin);
        $this->assertSame(10, $book->fresh()->stock);

        // Calling cancelled again should remain idempotent
        $service->transitionStatus($order->fresh(), Order::STATUS_CANCELLED, $admin);
        $this->assertSame(10, $book->fresh()->stock);
        $this->assertSame(2, StockMovement::where('order_id', $order->id)->count());
    }

    public function test_cancelling_pending_order_does_not_add_stock(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        [$order, $book] = $this->createOrderWithBook(stock: 10, qty: 4);

        app(AdminOrderService::class)->transitionStatus($order, Order::STATUS_CANCELLED, $admin);

        $this->assertSame(10, $book->fresh()->stock);
        $this->assertSame(0, StockMovement::where('order_id', $order->id)->count());
    }

    public function test_confirming_order_with_insufficient_stock_is_rejected(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        [$order, $book] = $this->createOrderWithBook(stock: 2, qty: 5);

        $this->actingAs($admin)
            ->patch(route('admin.orders.status', $order), ['status' => Order::STATUS_CONFIRMED])
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertSame(Order::STATUS_PENDING, $order->fresh()->status);
        $this->assertSame(2, $book->fresh()->stock);
    }

    public function test_manual_stock_adjustment_cannot_make_stock_negative(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        [, $book] = $this->createOrderWithBook(stock: 3, qty: 1);

        $this->actingAs($admin)
            ->post(route('admin.stock.adjust'), [
                'book_id' => $book->id,
                'change' => -5,
                'reason' => 'Rusak',
            ])
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertSame(3, $book->fresh()->stock);

        $this->actingAs($admin)
            ->post(route('admin.stock.adjust'), [
                'book_id' => $book->id,
                'change' => 5,
                'reason' => 'Cetak ulang',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame(8, $book->fresh()->stock);
    }

    public function test_non_admin_cannot_change_order_status_or_adjust_stock(): void
    {
        $user = User::factory()->create(['role' => UserRole::User]);
        [$order, $book] = $this->createOrderWithBook(stock: 5, qty: 1);

        $this->actingAs($user)
            ->patch(route('admin.orders.status', $order), ['status' => Order::STATUS_CONFIRMED])
            ->assertForbidden();

        $this->actingAs($user)
            ->post(route('admin.stock.adjust'), [
                'book_id' => $book->id,
                'change' => 10,
                'reason' => 'Test',
            ])
            ->assertForbidden();
    }
}
