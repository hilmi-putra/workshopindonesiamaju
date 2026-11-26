<?php

namespace App\Services\Admin;

use App\Models\Review;
use Illuminate\Pagination\LengthAwarePaginator;

class ReviewService
{
    public function getPaginated(int $perPage, ?string $search, ?int $rating, ?bool $isHidden, ?string $status = null): LengthAwarePaginator
    {
        $query = Review::with(['user:id,name,email', 'book:id,title,cover_image', 'order:id,invoice_number']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('book', function($bq) use ($search) {
                      $bq->where('title', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($rating) {
            $query->where('rating', $rating);
        }

        if ($isHidden !== null) {
            $query->where('is_hidden', $isHidden);
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        return $query->latest()->paginate($perPage);
    }

    public function toggleHide(Review $review): Review
    {
        $review->update([
            'is_hidden' => !$review->is_hidden
        ]);
        return $review;
    }

    public function updateStatus(Review $review, string $status): Review
    {
        $review->update(['status' => $status]);
        return $review;
    }

    public function reply(Review $review, string $replyText): Review
    {
        $review->update([
            'reply' => $replyText
        ]);
        return $review;
    }

    public function delete(Review $review): void
    {
        $review->delete();
    }
}
