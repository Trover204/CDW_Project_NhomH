<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

class News extends Model
{
    use HasFactory, Searchable;

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
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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

        if (config('scout.driver') === 'meilisearch') {
            $array['slug'] = $this->slug;
            $array['status'] = $this->status;
            $array['views'] = (int) $this->views;
            $array['author_name'] = $this->user?->name;
            $array['created_at'] = $this->created_at?->timestamp;
        }

        return $array;
    }
}
