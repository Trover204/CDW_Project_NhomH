<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Facility extends Model
{
    use HasFactory, LogsActivity, Searchable;

    protected $fillable = [
        'name',
        'district',
        'address',
        'phone',
        'open_time',
        'close_time',
        'status',
        'version',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

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
            'district' => $this->district,
            'address' => $this->address,
            'phone' => $this->phone,
        ];

        if (config('scout.driver') === 'meilisearch') {
            $array['status'] = (bool) $this->status;
            $array['created_at'] = $this->created_at?->timestamp;
        }

        return $array;
    }
}
