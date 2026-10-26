<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Court extends Model
{
    use HasFactory, LogsActivity, Searchable;

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

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
