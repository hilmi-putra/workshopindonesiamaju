<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    protected $fillable = [
        'keyword', 'results_count', 'user_id', 'ip_address'
    ];

    public function user() { return $this->belongsTo(User::class); }
}
