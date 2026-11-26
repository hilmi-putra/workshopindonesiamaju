<?php

namespace App\Livewire;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Publisher;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class CatalogFilter extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $category = '';

    #[Url(except: '')]
    public string $genre = '';

    #[Url(except: '')]
    public string $author = '';

    #[Url(except: '')]
    public string $publisher = '';

    #[Url(except: 'latest')]
    public string $sort = 'latest';

    public function updated($property): void
    {
        if (in_array($property, ['search', 'category', 'genre', 'author', 'publisher', 'sort'], true)) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'category', 'genre', 'author', 'publisher', 'sort']);
        $this->sort = 'latest';
        $this->resetPage();
    }

    public function render()
    {
        $query = Book::with(['author', 'publisher', 'categories', 'genres'])
            ->published();

        if (trim($this->search) !== '') {
            $term = trim($this->search);
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                  ->orWhere('isbn', 'like', "%{$term}%")
                  ->orWhereHas('author', fn ($qa) => $qa->where('name', 'like', "%{$term}%"));
            });
        }

        if ($this->category !== '') {
            $cat = $this->category;
            $query->whereHas('categories', fn ($q) => $q->where('slug', $cat));
        }

        if ($this->genre !== '') {
            $gen = $this->genre;
            $query->whereHas('genres', fn ($q) => $q->where('slug', $gen));
        }

        if ($this->author !== '') {
            $auth = $this->author;
            $query->whereHas('author', fn ($q) => $q->where('slug', $auth));
        }

        if ($this->publisher !== '') {
            $pub = $this->publisher;
            $query->whereHas('publisher', fn ($q) => $q->where('slug', $pub));
        }

        match ($this->sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'title_asc' => $query->orderBy('title', 'asc'),
            'bestseller' => $query->orderByDesc('is_bestseller')->orderByDesc('id'),
            default => $query->orderByDesc('published_at')->orderByDesc('id'),
        };

        $filters = Cache::remember('catalog.filters', 3600, function () {
            return [
                'categories' => Category::orderBy('name')->get(),
                'genres' => Genre::orderBy('name')->get(),
                'authors' => Author::orderBy('name')->get(),
                'publishers' => Publisher::orderBy('name')->get(),
            ];
        });

        return view('livewire.catalog-filter', [
            'books' => $query->paginate(12),
            'categories' => $filters['categories'],
            'genres' => $filters['genres'],
            'authors' => $filters['authors'],
            'publishers' => $filters['publishers'],
        ]);
    }
}
