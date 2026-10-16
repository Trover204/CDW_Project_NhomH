<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'summary',
        'image',
        'category',
        'user_id',
        'views',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
