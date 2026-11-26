<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\View\View;

class BookstoreController extends Controller
{
    public function index(): View
    {
        return view('store.bookstore.index');
    }

    public function show(string $slug): View
    {
        $book = Book::with([
            'author',
            'publisher',
            'categories',
            'genres',
            'reviews' => fn ($q) => $q->where('is_hidden', false)->with('user')->latest(),
        ])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $categoryIds = $book->categories->pluck('id');
        $relatedBooks = Book::with(['author', 'categories'])
            ->published()
            ->where('id', '!=', $book->id)
            ->when($categoryIds->isNotEmpty(), function ($q) use ($categoryIds) {
                $q->whereHas('categories', fn ($qc) => $qc->whereIn('categories.id', $categoryIds));
            })
            ->take(5)
            ->get();

        return view('store.bookstore.show', compact('book', 'relatedBooks'));
    }
}
