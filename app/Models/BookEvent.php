<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookEvent extends Model
{
    protected $fillable = [
        'book_id', 'event_type', 'user_id', 'ip_address'
    ];

    public function book() { return $this->belongsTo(Book::class); }
    public function user() { return $this->belongsTo(User::class); }
}
