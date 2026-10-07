<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
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
    }
}
