<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class PriceRule extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'court_id',
        'sport_type_id',
        'day_type',
        'start_time',
        'end_time',
        'price',
        'status',
    ];

    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class);
    }

    public function sportType(): BelongsTo
    {
        return $this->belongsTo(SportType::class);
    }

    protected function makeAllSearchableUsing(Builder $query): Builder
    {
        return $query->with(['court', 'sportType']);
    }

    public function toSearchableArray(): array
    {
        $array = [
            'id' => (int) $this->id,
            'day_type' => $this->day_type,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'price' => (float) $this->price,
        ];

        if (in_array(config('scout.driver'), ['elasticsearch', 'elastic'], true)) {
            $array['court_id'] = $this->court_id ? (int) $this->court_id : null;
            $array['sport_type_id'] = $this->sport_type_id ? (int) $this->sport_type_id : null;
            $array['court_name'] = $this->court?->name;
            $array['sport_type_name'] = $this->sportType?->name;
            $array['status'] = (bool) $this->status;
            $array['created_at'] = $this->created_at?->timestamp;
        }

        return $array;
    }
}
