<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'court_id',
        'sport_type_id',
        'day_type',
        'start_time',
        'end_time',
        'price',
        'status',
    ];
}
