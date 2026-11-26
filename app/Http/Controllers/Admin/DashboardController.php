<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\ManuscriptSubmission;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $pendingOrdersCount = Order::where('status', Order::STATUS_PENDING)->count();
        $confirmedOrdersCount = Order::where('status', Order::STATUS_CONFIRMED)->count();
        $totalRevenue = (float) Order::whereIn('status', [Order::STATUS_PAID, Order::STATUS_COMPLETED])->sum('total');
        $lowStockBooks = Book::with('author')->where('stock', '<=', 5)->orderBy('stock', 'asc')->take(8)->get();
        $pendingSubmissionsCount = ManuscriptSubmission::where('status', ManuscriptSubmission::STATUS_PENDING)->count();

        $recentOrders = Order::with('items')
            ->latest()
            ->take(6)
            ->get();

        // Simple 7-day sales chart data
        $days = collect(range(6, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo)->toDateString();
            $dayOrders = Order::whereDate('created_at', $date)
                ->where('status', '!=', Order::STATUS_CANCELLED)
                ->get(['total']);

            return [
                'label' => now()->subDays($daysAgo)->format('d M'),
                'count' => $dayOrders->count(),
                'revenue' => (float) $dayOrders->sum('total'),
            ];
        });

        $maxChartVal = max(1, (int) $days->max('revenue'));

        return view('admin.dashboard', compact(
            'pendingOrdersCount',
            'confirmedOrdersCount',
            'totalRevenue',
            'lowStockBooks',
            'pendingSubmissionsCount',
            'recentOrders',
            'days',
            'maxChartVal'
        ));
    }
}
