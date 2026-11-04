<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class Review extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'user_id',
        'court_id',
        'booking_id',
        'rating',
        'content',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    protected function makeAllSearchableUsing(Builder $query): Builder
    {
        return $query->with(['user', 'court']);
    }

    public function toSearchableArray(): array
    {
        $array = [
            'id' => (int) $this->id,
            'content' => $this->content,
            'rating' => (int) $this->rating,
        ];

        if (in_array(config('scout.driver'), ['elasticsearch', 'elastic'], true)) {
            $array['user_id'] = $this->user_id ? (int) $this->user_id : null;
            $array['court_id'] = $this->court_id ? (int) $this->court_id : null;
            $array['booking_id'] = $this->booking_id ? (int) $this->booking_id : null;
            $array['user_name'] = $this->user?->name;
            $array['court_name'] = $this->court?->name;
            $array['status'] = (bool) $this->status;
            $array['created_at'] = $this->created_at?->timestamp;
        }

        return $array;
    }
}
