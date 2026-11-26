<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    protected $fillable = [
        'url', 'path', 'user_id', 'ip_address', 'user_agent'
    ];

    public function user() { return $this->belongsTo(User::class); }
}
