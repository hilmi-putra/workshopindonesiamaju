<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WishlistToggle extends Component
{
    public int $bookId;
    public bool $isWishlisted = false;

    public function mount(int $bookId): void
    {
        $this->bookId = $bookId;
        if (Auth::check()) {
            $this->isWishlisted = Wishlist::where('user_id', Auth::id())
                ->where('book_id', $bookId)
                ->exists();
        }
    }

    public function toggle()
    {
        if (!Auth::check()) {
            $book = Book::find($this->bookId);
            $intended = $book ? route('bookstore.show', $book->slug) : url()->previous();
            session()->put('url.intended', $intended);
            return redirect()->route('login');
        }

        $existing = Wishlist::where('user_id', Auth::id())
            ->where('book_id', $this->bookId)
            ->first();

        if ($existing) {
            $existing->delete();
            $this->isWishlisted = false;
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'book_id' => $this->bookId,
            ]);
            $this->isWishlisted = true;
        }
    }

    public function render()
    {
        return view('livewire.wishlist-toggle');
    }
}
