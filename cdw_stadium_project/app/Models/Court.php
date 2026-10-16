<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Court extends Model
{
    use HasFactory, LogsActivity;

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

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
