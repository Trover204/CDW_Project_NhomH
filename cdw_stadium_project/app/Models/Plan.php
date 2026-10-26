<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

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
}
