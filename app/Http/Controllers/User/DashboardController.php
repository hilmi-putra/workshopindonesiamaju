<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Models\Order;
use App\Models\Wishlist;
use App\Services\WhatsAppCheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $totalOrders = Order::where('user_id', $user->id)->count();
        $pendingOrders = Order::where('user_id', $user->id)
            ->whereIn('status', [Order::STATUS_PENDING, Order::STATUS_CONFIRMED])
            ->count();
        $booksPurchased = (int) Order::where('user_id', $user->id)
            ->whereIn('status', [Order::STATUS_PAID, Order::STATUS_COMPLETED])
            ->withSum('items as total_qty', 'qty')
            ->get()
            ->sum('total_qty');
        $wishlistCount = Wishlist::where('user_id', $user->id)->count();

        $recentOrders = Order::with('items')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'booksPurchased',
            'wishlistCount',
            'recentOrders'
        ));
    }

    public function orders(): View
    {
        $orders = Order::with(['items.book.author'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.orders.index', compact('orders'));
    }

    public function orderShow(string $code, WhatsAppCheckoutService $waService): View
    {
        $order = Order::with(['items.book.author', 'timeline'])
            ->where(function ($q) use ($code) {
                $q->where('code', $code)->orWhere('id', is_numeric($code) ? (int) $code : 0);
            })
            ->firstOrFail();

        $this->authorize('view', $order);

        $waUrl = $waService->buildWhatsAppUrl($order);

        return view('user.orders.show', compact('order', 'waUrl'));
    }

    public function wishlist(): View
    {
        $wishlists = Wishlist::with(['book.author'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.wishlist', compact('wishlists'));
    }

    public function removeWishlist(int $bookId): RedirectResponse
    {
        Wishlist::where('user_id', Auth::id())
            ->where('book_id', $bookId)
            ->delete();

        return back()->with('success', 'Buku dihapus dari wishlist.');
    }

    public function profile(): View
    {
        return view('user.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(UpdateProfileRequest $request): RedirectResponse
    {
        $request->user()->update($request->validated());

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return back()->with('success', 'Kata sandi berhasil diperbarui.');
    }
}
