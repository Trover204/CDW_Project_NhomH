<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['name', 'duration_days', 'price', 'discount_percent', 'status'];
    protected $casts = [
        'status' => 'boolean',
        'duration_days' => 'integer',
        'price' => 'decimal:2',
        'discount_percent' => 'integer',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
