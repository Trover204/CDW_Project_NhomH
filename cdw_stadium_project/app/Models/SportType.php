<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class SportType extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['name', 'image', 'description', 'status', 'version'];

    protected $casts = ['status' => 'boolean'];

    public function courts(): HasMany
    {
        return $this->hasMany(Court::class);
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

        if (config('scout.driver') === 'meilisearch') {
            $array['status'] = (bool) $this->status;
        }

        return $array;
    }
}
