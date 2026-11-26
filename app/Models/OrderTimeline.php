<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTimeline extends Model
{
    protected $fillable = [
        'order_id', 
        'status', 
        'description',
        'user_id',
        'actor_name',
        'action',
        'old_value',
        'new_value',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
