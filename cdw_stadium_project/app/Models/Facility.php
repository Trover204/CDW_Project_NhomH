<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facility extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
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
}
