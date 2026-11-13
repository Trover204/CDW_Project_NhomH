<?php

namespace App\Models;

use App\Traits\LogsActivity;
use App\Traits\OptimisticLocking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

class News extends Model
{
    use HasFactory, LogsActivity, OptimisticLocking, Searchable;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'summary',
        'image',
        'category',
        'user_id',
        'views',
        'status',
        'version',
    ];

    protected $casts = [
        'views' => 'integer',
        'version' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->hasMany(Comment::class)->where('status', 1)->latest();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        // Chặn đường dẫn chứa ký tự điều hướng thư mục (Path Traversal).
        if (str_contains($this->image, '..') || str_contains($this->image, '\\') || str_contains($this->image, "\0")) {
            return null;
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        if (str_starts_with($this->image, 'images/')) {
            return asset($this->image);
        }

        return Storage::url($this->image);
    }

    public function shouldBeSearchable(): bool
    {
        return $this->status === 'published';
    }

    protected function makeAllSearchableUsing(Builder $query): Builder
    {
        return $query->with('user');
    }

    public function toSearchableArray(): array
    {
        $array = [
            'id' => (int) $this->id,
            'title' => $this->title,
            'summary' => $this->summary,
            'content' => $this->content,
            'category' => $this->category,
        ];

        if (in_array(config('scout.driver'), ['elasticsearch', 'elastic'], true)) {
            $array['slug'] = $this->slug;
            $array['status'] = $this->status;
            $array['views'] = (int) $this->views;
            $array['author_name'] = $this->user?->name;
            $array['created_at'] = $this->created_at?->timestamp;
        }

        return $array;
    }
}
