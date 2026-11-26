<?php

namespace App\Services;

use App\Models\Book;
use App\Models\CartItem;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CartService
{
    public const SESSION_KEY = 'megapress_cart';

    public function getItems(): Collection
    {
        if (Auth::check()) {
            $dbItems = CartItem::with(['book.author'])
                ->where('user_id', Auth::id())
                ->get();

            return $dbItems->filter(fn (CartItem $item) => $item->book !== null)
                ->map(function (CartItem $item) {
                    $book = $item->book;
                    $price = (float) $book->final_price;
                    $qty = (int) $item->qty;

                    return (object) [
                        'id' => $item->id,
                        'book_id' => $book->id,
                        'book' => $book,
                        'title' => $book->title,
                        'slug' => $book->slug,
                        'author' => $book->author?->name ?? 'Mega Press',
                        'cover_url' => $book->cover_url,
                        'price' => $price,
                        'qty' => $qty,
                        'subtotal' => $price * $qty,
                        'stock' => (int) $book->stock,
                    ];
                })
                ->values();
        }

        $sessionCart = session()->get(self::SESSION_KEY, []);
        if (empty($sessionCart)) {
            return collect();
        }

        $books = Book::with('author')
            ->whereIn('id', array_keys($sessionCart))
            ->get()
            ->keyBy('id');

        $items = collect();
        foreach ($sessionCart as $bookId => $qty) {
            $book = $books->get((int) $bookId);
            if (!$book || $book->status !== 'published') {
                continue;
            }
            $qty = max(1, min((int) $qty, max(1, (int) $book->stock)));
            $price = (float) $book->final_price;

            $items->push((object) [
                'id' => $book->id,
                'book_id' => $book->id,
                'book' => $book,
                'title' => $book->title,
                'slug' => $book->slug,
                'author' => $book->author?->name ?? 'Mega Press',
                'cover_url' => $book->cover_url,
                'price' => $price,
                'qty' => $qty,
                'subtotal' => $price * $qty,
                'stock' => (int) $book->stock,
            ]);
        }

        return $items;
    }

    public function getCount(): int
    {
        if (Auth::check()) {
            return (int) CartItem::where('user_id', Auth::id())->sum('qty');
        }

        return array_sum(session()->get(self::SESSION_KEY, []));
    }

    public function getSubtotal(): float
    {
        return (float) $this->getItems()->sum('subtotal');
    }

    public function addItem(int $bookId, int $qty = 1): void
    {
        if ($qty < 1) {
            throw new InvalidArgumentException('Jumlah buku minimal 1.');
        }

        $book = Book::findOrFail($bookId);
        if ($book->status !== 'published') {
            throw new InvalidArgumentException('Buku sedang tidak tersedia untuk pembelian.');
        }

        if ($book->stock < 1) {
            throw new InvalidArgumentException('Stok buku sedang habis.');
        }

        if (Auth::check()) {
            $existing = CartItem::where('user_id', Auth::id())
                ->where('book_id', $bookId)
                ->first();

            $targetQty = ($existing?->qty ?? 0) + $qty;
            if ($targetQty > $book->stock) {
                throw new InvalidArgumentException("Stok tidak mencukupi. Tersedia: {$book->stock}.");
            }

            if ($existing) {
                $existing->update(['qty' => $targetQty]);
            } else {
                CartItem::create([
                    'user_id' => Auth::id(),
                    'book_id' => $bookId,
                    'qty' => $targetQty,
                ]);
            }

            return;
        }

        $sessionCart = session()->get(self::SESSION_KEY, []);
        $targetQty = ($sessionCart[$bookId] ?? 0) + $qty;

        if ($targetQty > $book->stock) {
            throw new InvalidArgumentException("Stok tidak mencukupi. Tersedia: {$book->stock}.");
        }

        $sessionCart[$bookId] = $targetQty;
        session()->put(self::SESSION_KEY, $sessionCart);
    }

    public function updateQty(int $bookId, int $qty): void
    {
        if ($qty <= 0) {
            $this->removeItem($bookId);
            return;
        }

        $book = Book::findOrFail($bookId);
        if ($book->status !== 'published') {
            throw new InvalidArgumentException('Buku tidak tersedia.');
        }

        if ($qty > $book->stock) {
            throw new InvalidArgumentException("Stok tidak mencukupi. Tersedia: {$book->stock}.");
        }

        if (Auth::check()) {
            CartItem::updateOrCreate(
                ['user_id' => Auth::id(), 'book_id' => $bookId],
                ['qty' => $qty]
            );
            return;
        }

        $sessionCart = session()->get(self::SESSION_KEY, []);
        $sessionCart[$bookId] = $qty;
        session()->put(self::SESSION_KEY, $sessionCart);
    }

    public function removeItem(int $bookId): void
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())
                ->where('book_id', $bookId)
                ->delete();
            return;
        }

        $sessionCart = session()->get(self::SESSION_KEY, []);
        unset($sessionCart[$bookId]);
        session()->put(self::SESSION_KEY, $sessionCart);
    }

    public function clear(): void
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        }

        session()->forget(self::SESSION_KEY);
    }

    public function mergeSessionToDatabase(User $user): void
    {
        $sessionCart = session()->get(self::SESSION_KEY, []);
        if (empty($sessionCart)) {
            return;
        }

        DB::transaction(function () use ($user, $sessionCart) {
            $books = Book::whereIn('id', array_keys($sessionCart))
                ->where('status', 'published')
                ->get()
                ->keyBy('id');

            foreach ($sessionCart as $bookId => $qty) {
                $book = $books->get((int) $bookId);
                if (!$book || $book->stock < 1) {
                    continue;
                }

                $existing = CartItem::where('user_id', $user->id)
                    ->where('book_id', $book->id)
                    ->first();

                $combinedQty = ($existing?->qty ?? 0) + max(1, (int) $qty);
                $clampedQty = min($combinedQty, (int) $book->stock);

                if ($existing) {
                    $existing->update(['qty' => $clampedQty]);
                } else {
                    CartItem::create([
                        'user_id' => $user->id,
                        'book_id' => $book->id,
                        'qty' => $clampedQty,
                    ]);
                }
            }
        });

        session()->forget(self::SESSION_KEY);
    }
}
