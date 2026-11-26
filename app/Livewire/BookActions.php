<?php

namespace App\Livewire;

use App\Models\Book;
use App\Services\CartService;
use App\Services\WhatsAppCheckoutService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Component;

class BookActions extends Component
{
    public Book $book;
    public int $qty = 1;
    public bool $showBuyModal = false;
    public string $customer_name = '';
    public string $customer_phone = '';
    public string $customer_address = '';
    public string $notes = '';
    public string $idempotencyToken = '';
    public ?string $errorMessage = null;

    public function mount(Book $book): void
    {
        $this->book = $book;
        $this->idempotencyToken = Str::uuid()->toString();

        if (Auth::check()) {
            $user = Auth::user();
            $this->customer_name = (string) ($user->name ?? '');
            $this->customer_phone = (string) ($user->phone ?? '');
            $this->customer_address = (string) ($user->address ?? '');
        }
    }

    public function increment(): void
    {
        if ($this->qty < $this->book->stock) {
            $this->qty++;
        }
    }

    public function decrement(): void
    {
        if ($this->qty > 1) {
            $this->qty--;
        }
    }

    public function addToCart(CartService $cartService): void
    {
        $this->errorMessage = null;
        try {
            $cartService->addItem($this->book->id, $this->qty);
            $this->dispatch('cart-updated', count: $cartService->getCount());
            $this->dispatch('cart-item-added');
        } catch (\Throwable $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function buyViaWhatsApp(WhatsAppCheckoutService $checkoutService)
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
            'qty' => ['required', 'integer', 'min:1', 'max:' . max(1, (int) $this->book->stock)],
            'customer_name' => ['required', 'string', 'max:150'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'customer_address' => ['required', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'customer_name.required' => 'Nama pemesan wajib diisi.',
            'customer_phone.required' => 'Nomor WhatsApp wajib diisi.',
            'customer_address.required' => 'Alamat pengiriman singkat wajib diisi.',
        ]);

        $sessionKey = 'wa_direct_token_' . $this->idempotencyToken;
        if (session()->has($sessionKey)) {
            return redirect()->away(session()->get($sessionKey));
        }

        try {
            $order = $checkoutService->createOrderFromBook(
                $this->book,
                (int) $validated['qty'],
                $validated,
                Auth::user()
            );
            $waUrl = $checkoutService->buildWhatsAppUrl($order);
            session()->put($sessionKey, $waUrl);

            return redirect()->away($waUrl);
        } catch (\Throwable $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.book-actions');
    }
}
