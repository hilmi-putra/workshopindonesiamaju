<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Publisher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $query = Book::with(['author', 'publisher', 'categories']);

        if ($request->filled('search')) {
            $s = trim((string) $request->search);
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('isbn', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $books = $query->latest()->paginate(15)->withQueryString();

        return view('admin.books.index', compact('books'));
    }

    public function create(): View
    {
        return view('admin.books.form', [
            'book' => new Book(['status' => 'published', 'book_type' => 'physical', 'cover_type' => 'softcover', 'stock' => 0]),
            'authors' => Author::orderBy('name')->get(),
            'publishers' => Publisher::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
            'genres' => Genre::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateBook($request);
        $validated['slug'] = $this->uniqueSlug($validated['title']);

        if ($request->hasFile('cover_image_file')) {
            $validated['cover_image'] = $request->file('cover_image_file')->store('books', 'public');
        }

        $book = Book::create($validated);
        $book->categories()->sync($request->input('categories', []));
        $book->genres()->sync($request->input('genres', []));

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Book $book): View
    {
        $book->load(['categories', 'genres']);

        return view('admin.books.form', [
            'book' => $book,
            'authors' => Author::orderBy('name')->get(),
            'publishers' => Publisher::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
            'genres' => Genre::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $validated = $this->validateBook($request, $book->id);

        if ($request->hasFile('cover_image_file')) {
            $validated['cover_image'] = $request->file('cover_image_file')->store('books', 'public');
        }

        $book->update($validated);
        $book->categories()->sync($request->input('categories', []));
        $book->genres()->sync($request->input('genres', []));

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil dihapus.');
    }

    protected function validateBook(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'isbn' => ['nullable', 'string', 'max:60', 'unique:books,isbn,' . ($ignoreId ?? 'NULL')],
            'author_id' => ['required', 'exists:authors,id'],
            'publisher_id' => ['required', 'exists:publishers,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'promo_price' => ['nullable', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'stock' => ['required', 'integer', 'min:0'],
            'page_count' => ['nullable', 'integer', 'min:1'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'dimensions' => ['nullable', 'string', 'max:100'],
            'cover_type' => ['required', 'in:softcover,hardcover,other'],
            'book_type' => ['required', 'in:physical,digital,both'],
            'status' => ['required', 'in:draft,published,archived'],
            'short_description' => ['nullable', 'string'],
            'long_description' => ['nullable', 'string'],
            'editor_note' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'string', 'max:500'],
            'cover_image_file' => ['nullable', 'image', 'max:4096'],
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_bestseller'] = $request->boolean('is_bestseller');
        $data['discount'] = $data['discount'] ?? 0;

        if ($data['status'] === 'published' && !$request->filled('published_at')) {
            $data['published_at'] = now();
        }

        return $data;
    }

    protected function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;
        while (Book::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
