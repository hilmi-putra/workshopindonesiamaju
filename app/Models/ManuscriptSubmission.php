<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManuscriptSubmission extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_REVIEWING = 'reviewing';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'user_id',
        'title',
        'author_name',
        'email',
        'phone',
        'genre',
        'synopsis',
        'file_path',
        'original_filename',
        'status',
        'admin_notes',
    ];

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING => 'Menunggu Review',
            self::STATUS_REVIEWING => 'Sedang Dikurasi',
            self::STATUS_ACCEPTED => 'Diterima',
            self::STATUS_REJECTED => 'Ditolak',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
