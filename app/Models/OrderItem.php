<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'book_id',
        'title_snapshot',
        'price_snapshot',
        'qty',
        // Legacy fields
        'quantity',
        'price',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'price_snapshot' => 'decimal:2',
            'qty' => 'integer',
        ];
    }

    public function getDisplayTitleAttribute(): string
    {
        return $this->title_snapshot ?: ($this->book?->title ?? 'Buku Tidak Tersedia');
    }

    public function getDisplayPriceAttribute(): float
    {
        if (!is_null($this->price_snapshot)) {
            return (float) $this->price_snapshot;
        }

        return (float) ($this->price ?? 0);
    }

    public function getDisplayQtyAttribute(): int
    {
        return (int) ($this->qty ?: ($this->quantity ?? 1));
    }

    public function getLineTotalAttribute(): float
    {
        return $this->display_price * $this->display_qty;
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
