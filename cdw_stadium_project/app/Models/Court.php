<?php

namespace App\Models;

use App\Traits\LogsActivity;
use App\Traits\OptimisticLocking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Court extends Model
{
    use HasFactory, LogsActivity, Searchable, OptimisticLocking;

    protected $fillable = [
        'facility_id',
        'sport_type_id',
        'name',
        'capacity',
        'description',
        'status',
        'version',
    ];

    protected $casts = [
        'capacity' => 'integer',
    ];

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function sportType(): BelongsTo
    {
        return $this->belongsTo(SportType::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(CourtImage::class);
    }

    public function priceRules(): HasMany
    {
        return $this->hasMany(PriceRule::class);
    }

    public function getPriceAttribute(): ?int
    {
        $rule = PriceRule::where('status', 1)
            ->where(function ($q) {
                $q->where('court_id', $this->id)
                    ->orWhere(function ($sub) {
                        $sub->whereNull('court_id')->where('sport_type_id', $this->sport_type_id);
                    });
            })
            ->orderByRaw('court_id IS NULL')
            ->first();

        return $rule ? (int) $rule->price : null;
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function shouldBeSearchable(): bool
    {
        return $this->status !== 'inactive';
    }

    protected function makeAllSearchableUsing(Builder $query): Builder
    {
        return $query->with(['facility', 'sportType']);
    }

    public function toSearchableArray(): array
    {
        $array = [
            'id' => (int) $this->id,
            'name' => $this->name,
            'description' => $this->description,
        ];

        if (config('scout.driver') === 'meilisearch') {
            $array['facility_id'] = (int) $this->facility_id;
            $array['sport_type_id'] = (int) $this->sport_type_id;
            $array['capacity'] = (int) $this->capacity;
            $array['status'] = $this->status;
            $array['facility_name'] = $this->facility?->name;
            $array['district'] = $this->facility?->district;
            $array['address'] = $this->facility?->address;
            $array['sport_type_name'] = $this->sportType?->name;
            $array['created_at'] = $this->created_at?->timestamp;
        }

        return $array;
    }
}
