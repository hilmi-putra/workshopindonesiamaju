<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'content',
        'sent_at',
        'total_sent',
        'status',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];
}
