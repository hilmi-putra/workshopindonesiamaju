<?php

namespace App\Http\Controllers\Admin;

use App\Exports\BestSellersExport;
use App\Exports\SalesExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $status = $request->input('status', 'all');

        $ordersQuery = Order::with(['items.book', 'user'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->when($status !== 'all', fn ($q) => $q->where('status', $status));

        $allOrders = (clone $ordersQuery)->latest()->get();
        $validOrders = $allOrders->where('status', '!=', Order::STATUS_CANCELLED);

        $summary = [
            'total_orders' => $allOrders->count(),
            'paid_orders' => $allOrders->whereIn('status', [Order::STATUS_PAID, Order::STATUS_COMPLETED])->count(),
            'total_revenue' => (float) $allOrders->whereIn('status', [Order::STATUS_PAID, Order::STATUS_COMPLETED])->sum(fn ($o) => $o->display_total),
            'total_potential' => (float) $validOrders->sum(fn ($o) => $o->display_total),
            'total_items' => (int) $validOrders->sum(fn ($o) => $o->items->sum(fn ($i) => $i->display_qty)),
        ];

        $bestSellers = OrderItem::query()
            ->select(
                'book_id',
                DB::raw('MAX(COALESCE(title_snapshot, "")) as book_title'),
                DB::raw('SUM(COALESCE(qty, quantity, 1)) as total_sold'),
                DB::raw('SUM(COALESCE(price_snapshot, price, 0) * COALESCE(qty, quantity, 1)) as total_revenue')
            )
            ->whereHas('order', function ($q) use ($startDate, $endDate) {
                $q->whereDate('created_at', '>=', $startDate)
                  ->whereDate('created_at', '<=', $endDate)
                  ->where('status', '!=', Order::STATUS_CANCELLED);
            })
            ->groupBy('book_id')
            ->orderByDesc('total_sold')
            ->with('book.author')
            ->take(10)
            ->get();

        if ($request->input('export') === 'excel') {
            return Excel::download(new SalesExport($allOrders), "laporan_penjualan_{$startDate}_{$endDate}.xlsx");
        }

        if ($request->input('export') === 'pdf') {
            $pdf = Pdf::loadView('pdf.sales', [
                'orders' => $allOrders,
                'summary' => $summary,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ]);
            return $pdf->download("laporan_penjualan_{$startDate}_{$endDate}.pdf");
        }

        $orders = $ordersQuery->latest()->paginate(15)->withQueryString();

        return view('admin.reports.index', compact(
            'orders',
            'summary',
            'bestSellers',
            'startDate',
            'endDate',
            'status'
        ));
    }
}
