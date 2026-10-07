<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SportType extends Model
{
    protected $fillable = ['name', 'image', 'description', 'status'];

    protected $casts = ['status' => 'boolean'];
}