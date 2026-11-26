<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\StockMovement;
use App\Services\AdminOrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StockController extends Controller
{
    public function index(Request $request): View
    {
        $books = Book::orderBy('title')->get();
        $movementsQuery = StockMovement::with(['book', 'order', 'admin'])->latest();

        if ($request->filled('book_id')) {
            $movementsQuery->where('book_id', $request->book_id);
        }

        $movements = $movementsQuery->paginate(20)->withQueryString();

        return view('admin.stock.index', compact('books', 'movements'));
    }

    public function adjust(Request $request, AdminOrderService $orderService): RedirectResponse
    {
        $validated = $request->validate([
            'book_id' => ['required', 'exists:books,id'],
            'change' => ['required', 'integer', 'not_in:0'],
            'reason' => ['required', 'string', 'max:100'],
        ]);

        try {
            $book = Book::findOrFail($validated['book_id']);
            $orderService->adjustStock(
                $book,
                (int) $validated['change'],
                $validated['reason'],
                Auth::user()
            );

            return back()->with('success', "Stok buku \"{$book->title}\" berhasil disesuaikan.");
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
