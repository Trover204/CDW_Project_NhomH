<?php

namespace App\Models;

use App\Traits\LogsActivity;
use App\Traits\OptimisticLocking;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class SportType extends Model
{
    use HasFactory, LogsActivity, OptimisticLocking, Searchable;

    protected $fillable = ['name', 'image', 'description', 'status', 'version'];

    protected $casts = ['status' => 'boolean'];

    public function courts(): HasMany
    {
        return $this->hasMany(Court::class);
    }

    public function priceRules(): HasMany
    {
        return $this->hasMany(PriceRule::class);
    }

    public function shouldBeSearchable(): bool
    {
        return (bool) $this->status;
    }

    public function toSearchableArray(): array
    {
        $array = [
            'id' => (int) $this->id,
            'name' => $this->name,
            'description' => $this->description,
        ];

        if (in_array(config('scout.driver'), ['elasticsearch', 'elastic'], true)) {
            $array['status'] = (bool) $this->status;
        }

        return $array;
    }
}
