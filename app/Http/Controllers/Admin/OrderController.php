<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use App\Services\AdminOrderService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with(['items', 'user']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $s = trim((string) $request->search);
            $query->where(function ($q) use ($s) {
                $q->where('code', 'like', "%{$s}%")
                  ->orWhere('invoice_number', 'like', "%{$s}%")
                  ->orWhere('customer_name', 'like', "%{$s}%")
                  ->orWhere('customer_phone', 'like', "%{$s}%");
            });
        }

        $orders = $query->latest()->paginate(15)->withQueryString();
        $statuses = Order::statuses();

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    public function create(): View
    {
        $books = Book::published()->orderBy('title')->get();
        return view('admin.orders.create', compact('books'));
    }

    public function store(Request $request, AdminOrderService $orderService): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:150'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'customer_address' => ['required', 'string', 'max:1000'],
            'status' => ['required', 'in:pending,confirmed,paid,completed'],
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.book_id' => ['required', 'exists:books,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $order = $orderService->createManualOrder(
                $validated,
                $validated['items'],
                Auth::user()
            );

            return redirect()->route('admin.orders.show', $order)
                ->with('success', "Pesanan manual {$order->display_code} berhasil dibuat.");
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Order $order): View
    {
        $order->load(['items.book', 'timeline', 'stockMovements.book', 'stockMovements.admin', 'user']);

        $waPhone = preg_replace('/[^0-9]/', '', (string) $order->customer_phone);
        if (str_starts_with($waPhone, '0')) {
            $waPhone = '62' . substr($waPhone, 1);
        }
        $waChatUrl = $waPhone
            ? "https://wa.me/{$waPhone}?text=" . rawurlencode("Halo {$order->customer_name}, kami dari Admin Workshop Indonesia Maju mengonfirmasi pesanan {$order->display_code} (Status: {$order->status_label}).")
            : null;

        return view('admin.orders.show', [
            'order' => $order,
            'statuses' => Order::statuses(),
            'waChatUrl' => $waChatUrl,
        ]);
    }

    public function updateStatus(Request $request, Order $order, AdminOrderService $orderService): RedirectResponse
    {
        $this->authorize('updateStatus', $order);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,paid,completed,cancelled'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $orderService->transitionStatus(
                $order,
                $validated['status'],
                Auth::user(),
                $validated['note'] ?? null
            );

            return back()->with('success', 'Status pesanan berhasil diperbarui.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function invoicePdf(Order $order)
    {
        $order->load(['items.book', 'user']);
        $pdf = Pdf::loadView('pdf.invoice', compact('order'));
        $filename = 'invoice-' . str_replace(['/', '\\'], '-', $order->display_code) . '.pdf';

        return $pdf->stream($filename);
    }

    public function packingSlipPdf(Order $order)
    {
        $order->load(['items.book', 'user']);
        $pdf = Pdf::loadView('pdf.packing-slip', compact('order'));
        $filename = 'packing-slip-' . str_replace(['/', '\\'], '-', $order->display_code) . '.pdf';

        return $pdf->stream($filename);
    }
}
