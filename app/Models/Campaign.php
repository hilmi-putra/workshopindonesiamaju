<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'banner_desktop',
        'banner_mobile',
        'start_at',
        'end_at',
        'priority',
        'status',
        'is_featured',
        'show_homepage',
        'show_bookstore',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_featured' => 'boolean',
        'show_homepage' => 'boolean',
        'show_bookstore' => 'boolean',
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class, 'campaign_books');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'campaign_categories');
    }
}
