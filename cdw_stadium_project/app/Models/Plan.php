<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Plan extends Model
{
    use HasFactory, LogsActivity, Searchable;

    protected $fillable = ['name', 'duration_days', 'price', 'discount_percent', 'status'];

    protected $casts = [
        'status' => 'boolean',
        'duration_days' => 'integer',
        'price' => 'decimal:2',
        'discount_percent' => 'integer',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function toSearchableArray(): array
    {
        $array = [
            'id' => (int) $this->id,
            'name' => $this->name,
            'duration_days' => (int) $this->duration_days,
            'price' => (float) $this->price,
            'discount_percent' => (int) $this->discount_percent,
        ];

        if (in_array(config('scout.driver'), ['elasticsearch', 'elastic'], true)) {
            $array['status'] = (bool) $this->status;
            $array['created_at'] = $this->created_at?->timestamp;
        }

        return $array;
    }
}
