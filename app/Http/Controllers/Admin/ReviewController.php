<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = Review::with(['user', 'book'])->latest();

        if ($request->filled('search')) {
            $s = trim((string) $request->search);
            $query->where(function ($q) use ($s) {
                $q->where('comment', 'like', "%{$s}%")
                  ->orWhereHas('book', fn ($qb) => $qb->where('title', 'like', "%{$s}%"));
            });
        }

        $reviews = $query->paginate(15)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function toggleHide(Review $review): RedirectResponse
    {
        $review->update(['is_hidden' => !$review->is_hidden]);

        return back()->with('success', $review->is_hidden ? 'Ulasan disembunyikan.' : 'Ulasan ditampilkan.');
    }

    public function reply(Request $request, Review $review): RedirectResponse
    {
        $validated = $request->validate([
            'reply' => ['required', 'string', 'max:1000'],
        ]);

        $review->update(['reply' => $validated['reply']]);

        return back()->with('success', 'Balasan ulasan disimpan.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();
        return back()->with('success', 'Ulasan dihapus.');
    }
}
