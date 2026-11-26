<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class WhatsAppCheckoutService
{
    public function __construct(
        protected CartService $cartService
    ) {}

    public function generateOrderCode(): string
    {
        $date = now()->format('Ymd');
        do {
            $code = 'MP-' . $date . '-' . Str::upper(Str::random(4));
        } while (Order::where('code', $code)->exists());

        return $code;
    }

    public function createOrderFromBook(Book $book, int $qty, array $customer, ?User $user = null): Order
    {
        if ($book->status !== 'published') {
            throw new InvalidArgumentException('Buku ini sedang tidak tersedia.');
        }

        if ($qty < 1 || $qty > $book->stock) {
            throw new InvalidArgumentException("Jumlah tidak valid atau stok tersisa {$book->stock}.");
        }

        return DB::transaction(function () use ($book, $qty, $customer, $user) {
            $freshBook = Book::findOrFail($book->id);
            $price = (float) $freshBook->final_price;
            $total = round($price * $qty, 2);

            $order = Order::create([
                'code' => $this->generateOrderCode(),
                'user_id' => $user?->id,
                'customer_name' => $customer['customer_name'],
                'customer_phone' => $customer['customer_phone'],
                'customer_address' => $customer['customer_address'],
                'notes' => $customer['notes'] ?? null,
                'status' => Order::STATUS_PENDING,
                'total' => $total,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'book_id' => $freshBook->id,
                'title_snapshot' => $freshBook->title,
                'price_snapshot' => $price,
                'qty' => $qty,
                'quantity' => $qty,
                'price' => (int) round($price),
                'subtotal' => (int) round($total),
            ]);

            $order->timeline()->create([
                'status' => Order::STATUS_PENDING,
                'description' => 'Pesanan dibuat via checkout WhatsApp.',
                'user_id' => $user?->id,
                'actor_name' => $customer['customer_name'],
                'action' => 'created',
                'new_value' => Order::STATUS_PENDING,
            ]);

            return $order->load('items.book');
        });
    }

    public function createOrderFromCart(array $customer, ?User $user = null): Order
    {
        $cartItems = $this->cartService->getItems();
        if ($cartItems->isEmpty()) {
            throw new InvalidArgumentException('Keranjang belanja Anda masih kosong.');
        }

        $order = DB::transaction(function () use ($cartItems, $customer, $user) {
            $bookIds = $cartItems->pluck('book_id')->all();
            $books = Book::whereIn('id', $bookIds)->get()->keyBy('id');

            $total = 0;
            $preparedItems = [];

            foreach ($cartItems as $item) {
                $book = $books->get($item->book_id);
                if (!$book || $book->status !== 'published') {
                    throw new InvalidArgumentException("Buku \"{$item->title}\" sudah tidak tersedia.");
                }
                if ($item->qty < 1 || $item->qty > $book->stock) {
                    throw new InvalidArgumentException("Stok buku \"{$book->title}\" tidak mencukupi (tersedia: {$book->stock}).");
                }

                $price = (float) $book->final_price;
                $lineTotal = round($price * $item->qty, 2);
                $total += $lineTotal;

                $preparedItems[] = [
                    'book' => $book,
                    'price' => $price,
                    'qty' => (int) $item->qty,
                    'line_total' => $lineTotal,
                ];
            }

            $order = Order::create([
                'code' => $this->generateOrderCode(),
                'user_id' => $user?->id,
                'customer_name' => $customer['customer_name'],
                'customer_phone' => $customer['customer_phone'],
                'customer_address' => $customer['customer_address'],
                'notes' => $customer['notes'] ?? null,
                'status' => Order::STATUS_PENDING,
                'total' => $total,
            ]);

            foreach ($preparedItems as $prepared) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $prepared['book']->id,
                    'title_snapshot' => $prepared['book']->title,
                    'price_snapshot' => $prepared['price'],
                    'qty' => $prepared['qty'],
                    'quantity' => $prepared['qty'],
                    'price' => (int) round($prepared['price']),
                    'subtotal' => (int) round($prepared['line_total']),
                ]);
            }

            $order->timeline()->create([
                'status' => Order::STATUS_PENDING,
                'description' => 'Pesanan dibuat dari keranjang via WhatsApp.',
                'user_id' => $user?->id,
                'actor_name' => $customer['customer_name'],
                'action' => 'created',
                'new_value' => Order::STATUS_PENDING,
            ]);

            return $order->load('items.book');
        });

        $this->cartService->clear();

        return $order;
    }

    public function buildWhatsAppUrl(Order $order): string
    {
        $order->loadMissing('items.book');

        $adminNumber = preg_replace('/[^0-9]/', '', (string) config('services.whatsapp.number', '6285724693474'));
        if (str_starts_with($adminNumber, '0')) {
            $adminNumber = '62' . substr($adminNumber, 1);
        }

        $lines = [
            "Halo Admin Mega Press, saya ingin memesan buku:",
            "",
            "*Kode Pesanan:* {$order->display_code}",
            "*Nama:* {$order->customer_name}",
            "*No. HP/WA:* {$order->customer_phone}",
            "*Alamat:* {$order->customer_address}",
        ];

        if ($order->notes) {
            $lines[] = "*Catatan:* {$order->notes}";
        }

        $lines[] = "";
        $lines[] = "*Daftar Buku:*";

        $items = $order->items;
        $maxListed = 8;
        foreach ($items->take($maxListed) as $idx => $item) {
            $num = $idx + 1;
            $priceFmt = 'Rp ' . number_format($item->display_price, 0, ',', '.');
            $bookUrl = $item->book ? route('bookstore.show', $item->book->slug) : '';
            $lines[] = "{$num}. {$item->display_title} ({$item->display_qty}x @ {$priceFmt})";
            if ($bookUrl) {
                $lines[] = "   Link: {$bookUrl}";
            }
        }

        if ($items->count() > $maxListed) {
            $remaining = $items->count() - $maxListed;
            $lines[] = "... dan {$remaining} judul buku lainnya (cek kode pesanan {$order->display_code}).";
        }

        $totalFmt = 'Rp ' . number_format($order->display_total, 0, ',', '.');
        $lines[] = "";
        $lines[] = "*Total Harga Buku:* {$totalFmt}";
        $lines[] = "_Mohon konfirmasi ketersediaan, ongkos kirim, dan info pembayarannya ya Admin. Terima kasih!_";

        $message = implode("\n", $lines);

        return "https://wa.me/{$adminNumber}?text=" . rawurlencode($message);
    }
}
