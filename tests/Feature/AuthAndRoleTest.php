<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Author;
use App\Models\Book;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Publisher;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndRoleTest extends TestCase
{
    use RefreshDatabase;

    private function createBook(int $stock = 10): Book
    {
        $author = Author::factory()->create();
        $publisher = Publisher::factory()->create();

        return Book::factory()->create([
            'author_id' => $author->id,
            'publisher_id' => $publisher->id,
            'price' => 75000,
            'discount' => 0,
            'promo_price' => null,
            'stock' => $stock,
            'status' => 'published',
        ]);
    }

    public function test_guest_and_user_cannot_access_admin_routes(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));

        $user = User::factory()->create(['role' => UserRole::User]);
        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard Admin');
    }

    public function test_login_merges_session_cart_into_database_and_clamps_to_stock(): void
    {
        $book = $this->createBook(stock: 5);
        $user = User::factory()->create([
            'role' => UserRole::User,
            'password' => 'secret123',
        ]);

        // Existing DB cart item has qty 3
        CartItem::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'qty' => 3,
        ]);

        // Guest adds 4 more in session (total 7 > stock 5 -> should clamp to 5)
        $response = $this->withSession([CartService::SESSION_KEY => [$book->id => 4]])
            ->post(route('login.post'), [
                'email' => $user->email,
                'password' => 'secret123',
            ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseHas('cart_items', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'qty' => 5,
        ]);
        $this->assertEmpty(session(CartService::SESSION_KEY, []));
    }

    public function test_user_cannot_view_another_users_order(): void
    {
        $owner = User::factory()->create(['role' => UserRole::User]);
        $other = User::factory()->create(['role' => UserRole::User]);

        $order = Order::create([
            'code' => 'MP-20261126-ABCD',
            'user_id' => $owner->id,
            'customer_name' => 'Owner',
            'customer_phone' => '0812345',
            'customer_address' => 'Jakarta',
            'status' => Order::STATUS_PENDING,
            'total' => 50000,
        ]);

        $this->actingAs($other)
            ->get(route('dashboard.orders.show', $order->code))
            ->assertForbidden();

        $this->actingAs($owner)
            ->get(route('dashboard.orders.show', $order->code))
            ->assertOk();
    }
}
