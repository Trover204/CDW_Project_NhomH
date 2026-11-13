<?php

namespace App\Models;

use App\Traits\LogsActivity;
use App\Traits\OptimisticLocking;
use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    /** @use HasFactory<CommentFactory> */
    use HasFactory, LogsActivity, OptimisticLocking;

    protected $fillable = [
        'user_id',
        'news_id',
        'content',
        'rating',
        'status',
        'version',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'status' => 'boolean',
            'version' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class);
    }
}


