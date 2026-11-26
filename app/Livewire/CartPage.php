<?php

namespace App\Livewire;

use App\Services\CartService;
use App\Services\WhatsAppCheckoutService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Component;

class CartPage extends Component
{
    public bool $showCheckoutModal = false;
    public string $customer_name = '';
    public string $customer_phone = '';
    public string $customer_address = '';
    public string $notes = '';
    public string $idempotencyToken = '';
    public ?string $errorMessage = null;

    public function mount(): void
    {
        $this->idempotencyToken = Str::uuid()->toString();
        if (Auth::check()) {
            $user = Auth::user();
            $this->customer_name = (string) ($user->name ?? '');
            $this->customer_phone = (string) ($user->phone ?? '');
            $this->customer_address = (string) ($user->address ?? '');
        }
    }

    public function increment(int $bookId, CartService $cartService): void
    {
        $this->errorMessage = null;
        $item = $cartService->getItems()->firstWhere('book_id', $bookId);
        if (!$item) {
            return;
        }

        try {
            $cartService->updateQty($bookId, $item->qty + 1);
            $this->dispatch('cart-updated', count: $cartService->getCount());
        } catch (\Throwable $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function decrement(int $bookId, CartService $cartService): void
    {
        $this->errorMessage = null;
        $item = $cartService->getItems()->firstWhere('book_id', $bookId);
        if (!$item) {
            return;
        }

        $cartService->updateQty($bookId, $item->qty - 1);
        $this->dispatch('cart-updated', count: $cartService->getCount());
    }

    public function remove(int $bookId, CartService $cartService): void
    {
        $this->errorMessage = null;
        $cartService->removeItem($bookId);
        $this->dispatch('cart-updated', count: $cartService->getCount());
    }

    public function openCheckout(): void
    {
        $this->errorMessage = null;
        $this->showCheckoutModal = true;
    }

    public function checkoutWhatsApp(WhatsAppCheckoutService $checkoutService, CartService $cartService)
    {
        $this->errorMessage = null;

        $rateKey = 'wa-checkout:' . (Auth::id() ?: request()->ip());
        if (RateLimiter::tooManyAttempts($rateKey, 10)) {
            $seconds = RateLimiter::availableIn($rateKey);
            $this->errorMessage = "Terlalu banyak percobaan checkout. Coba lagi dalam {$seconds} detik.";
            return null;
        }
        RateLimiter::hit($rateKey, 60);

        $validated = $this->validate([
            'customer_name' => ['required', 'string', 'max:150'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'customer_address' => ['required', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'customer_name.required' => 'Nama pemesan wajib diisi.',
            'customer_phone.required' => 'Nomor WhatsApp/HP wajib diisi.',
            'customer_address.required' => 'Alamat pengiriman singkat wajib diisi.',
        ]);

        // Guard double-click with session idempotency token
        $sessionKey = 'wa_checkout_token_' . $this->idempotencyToken;
        if (session()->has($sessionKey)) {
            $waUrl = session()->get($sessionKey);
            return redirect()->away($waUrl);
        }

        try {
            $order = $checkoutService->createOrderFromCart($validated, Auth::user());
            $waUrl = $checkoutService->buildWhatsAppUrl($order);
            session()->put($sessionKey, $waUrl);

            $this->dispatch('cart-updated', count: $cartService->getCount());
            return redirect()->away($waUrl);
        } catch (\Throwable $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function render(CartService $cartService)
    {
        return view('livewire.cart-page', [
            'items' => $cartService->getItems(),
            'subtotal' => $cartService->getSubtotal(),
            'count' => $cartService->getCount(),
        ]);
    }
}
