<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Wishlist;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerService
{
    /**
     * Get paginated list of customers (users) with their stats.
     */
    public function getCustomers(int $perPage, ?string $search, ?string $role, ?string $status): LengthAwarePaginator
    {
        $query = User::withCount('orders')
                     ->withSum('orders', 'grand_total');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($role) {
            $query->where('role', $role);
        }

        if ($status !== null && $status !== '') {
            // is_active is boolean
            $isActive = $status === 'active' || $status === '1' || $status === 'true';
            $query->where('is_active', $isActive);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get specific customer with their stats, recent orders and addresses.
     */
    public function getCustomerDetails(string $id): User
    {
        return User::with(['addresses', 'orders' => function($q) {
                $q->orderBy('created_at', 'desc')->take(10);
            }])
            ->withCount('orders')
            ->withSum('orders', 'grand_total')
            ->findOrFail($id);
    }

    /**
     * Get paginated list of customers who have addresses, along with their addresses.
     */
    public function getAddresses(int $perPage, ?string $search): LengthAwarePaginator
    {
        $query = User::has('addresses')->with('addresses');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('addresses', function($aq) use ($search) {
                      $aq->where('label', 'like', "%{$search}%")
                         ->orWhere('recipient_name', 'like', "%{$search}%")
                         ->orWhere('address', 'like', "%{$search}%");
                  });
            });
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get paginated list of wishlists.
     */
    public function getWishlists(int $perPage, ?string $search): LengthAwarePaginator
    {
        $query = Wishlist::with(['user:id,name,email', 'book:id,title,slug,price,stock,cover_image']);

        if ($search) {
            $query->whereHas('book', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            })->orWhereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get paginated list of all order histories (transactions).
     */
    public function getOrderHistories(int $perPage, ?string $search, ?string $status): LengthAwarePaginator
    {
        $query = Order::with(['user:id,name,email', 'payment']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get detailed order history.
     */
    public function getOrderHistoryDetails(string $orderId): Order
    {
        return Order::with([
            'user', 
            'items.book', 
            'payment', 
            'shippingAddress', 
            'timeline', 
            'shipment'
        ])->findOrFail($orderId);
    }
}
