<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\BlogPost;
use App\Models\Book;
use App\Models\SearchHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $queryText = trim((string) $request->query('q', ''));
        $books = collect();
        $authors = collect();
        $posts = collect();

        if ($queryText !== '') {
            $books = Book::with(['author', 'categories'])
                ->published()
                ->where(function ($q) use ($queryText) {
                    $q->where('title', 'like', "%{$queryText}%")
                      ->orWhere('isbn', 'like', "%{$queryText}%")
                      ->orWhere('short_description', 'like', "%{$queryText}%");
                })
                ->take(12)
                ->get();

            $authors = Author::where('name', 'like', "%{$queryText}%")
                ->take(6)
                ->get();

            $posts = BlogPost::with('category')
                ->where('status', 'published')
                ->where(function ($q) use ($queryText) {
                    $q->where('title', 'like', "%{$queryText}%")
                      ->orWhere('excerpt', 'like', "%{$queryText}%");
                })
                ->take(8)
                ->get();

            SearchHistory::create([
                'keyword' => $queryText,
                'results_count' => $books->count() + $authors->count() + $posts->count(),
                'user_id' => Auth::id(),
                'ip_address' => $request->ip(),
            ]);
        }

        $popularKeywords = SearchHistory::select('keyword')
            ->selectRaw('COUNT(*) as hits')
            ->groupBy('keyword')
            ->orderByDesc('hits')
            ->take(6)
            ->pluck('keyword');

        return view('public.search', compact('queryText', 'books', 'authors', 'posts', 'popularKeywords'));
    }
}
