<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoyaltyPayment extends Model
{
    protected $fillable = [
        'author_id', 'publisher_id', 'amount', 'reference_no', 
        'status', 'notes', 'paid_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function author() { return $this->belongsTo(Author::class); }
    public function publisher() { return $this->belongsTo(Publisher::class); }
}
