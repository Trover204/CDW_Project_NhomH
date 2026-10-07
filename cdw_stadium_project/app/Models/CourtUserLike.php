<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourtUserLike extends Model
{
    protected $table = 'court_user_likes';

    protected $fillable = ['user_id', 'court_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function court()
    {
        return $this->belongsTo(Court::class);
    }
}