<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Court extends Model
{
<<<<<<< HEAD
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'court_user_likes')->withTimestamps();
    }

    public function likes()
    {
        return $this->hasMany(CourtUserLike::class);
    }
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function sportType()
    {
        return $this->belongsTo(SportType::class);
=======
    protected $fillable = [
        'facility_id',
        'sport_type_id',
        'name',
        'capacity',
        'description',
        'status',
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
>>>>>>> origin/master
    }
}
