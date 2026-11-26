<?php

namespace App\Livewire;

use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class CartDrawer extends Component
{
    public bool $open = false;

    #[On('open-cart-drawer')]
    public function openDrawer(): void
    {
        $this->open = true;
    }

    #[On('cart-item-added')]
    public function handleAdded(): void
    {
        $this->open = true;
    }

    public function increment(int $bookId, CartService $cartService): void
    {
        $item = $cartService->getItems()->firstWhere('book_id', $bookId);
        if (!$item) {
            return;
        }

        try {
            $cartService->updateQty($bookId, $item->qty + 1);
            $this->dispatch('cart-updated', count: $cartService->getCount());
        } catch (\Throwable $e) {
            session()->flash('cart_error', $e->getMessage());
        }
    }

    public function decrement(int $bookId, CartService $cartService): void
    {
        $item = $cartService->getItems()->firstWhere('book_id', $bookId);
        if (!$item) {
            return;
        }

        $cartService->updateQty($bookId, $item->qty - 1);
        $this->dispatch('cart-updated', count: $cartService->getCount());
    }

    public function remove(int $bookId, CartService $cartService): void
    {
        $cartService->removeItem($bookId);
        $this->dispatch('cart-updated', count: $cartService->getCount());
    }

    public function render(CartService $cartService)
    {
        return view('livewire.cart-drawer', [
            'items' => $cartService->getItems(),
            'subtotal' => $cartService->getSubtotal(),
            'count' => $cartService->getCount(),
        ]);
    }
}
