<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Publisher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TaxonomyController extends Controller
{
    public function categories(): View
    {
        return view('admin.taxonomy.categories', [
            'categories' => Category::withCount('books')->orderBy('name')->get(),
            'genres' => Genre::withCount('books')->orderBy('name')->get(),
        ]);
    }

    public function storeCategory(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
        ]);
        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(4);
        Category::create($data);
        Cache::forget('catalog.filters');

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function destroyCategory(Category $category): RedirectResponse
    {
        $category->delete();
        Cache::forget('catalog.filters');
        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    public function storeGenre(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
        ]);
        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(4);
        Genre::create($data);
        Cache::forget('catalog.filters');

        return back()->with('success', 'Genre berhasil ditambahkan.');
    }

    public function destroyGenre(Genre $genre): RedirectResponse
    {
        $genre->delete();
        Cache::forget('catalog.filters');
        return back()->with('success', 'Genre berhasil dihapus.');
    }

    public function authors(): View
    {
        return view('admin.taxonomy.authors', [
            'authors' => Author::withCount('books')->orderBy('name')->paginate(15),
        ]);
    }

    public function storeAuthor(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:150'],
            'bio' => ['nullable', 'string'],
        ]);
        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(4);
        Author::create($data);

        return back()->with('success', 'Penulis berhasil ditambahkan.');
    }

    public function destroyAuthor(Author $author): RedirectResponse
    {
        $author->delete();
        return back()->with('success', 'Penulis berhasil dihapus.');
    }

    public function publishers(): View
    {
        return view('admin.taxonomy.publishers', [
            'publishers' => Publisher::withCount('books')->orderBy('name')->paginate(15),
        ]);
    }

    public function storePublisher(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
        ]);
        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(4);
        Publisher::create($data);

        return back()->with('success', 'Penerbit berhasil ditambahkan.');
    }

    public function destroyPublisher(Publisher $publisher): RedirectResponse
    {
        $publisher->delete();
        return back()->with('success', 'Penerbit berhasil dihapus.');
    }
}
