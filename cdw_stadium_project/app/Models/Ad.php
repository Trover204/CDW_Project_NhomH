<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Ad extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'name',
        'image',
        'link_url',
        'is_active',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function toSearchableArray(): array
    {
        $array = [
            'id' => (int) $this->id,
            'name' => $this->name,
            'link_url' => $this->link_url,
            'description' => $this->description,
        ];

        if (config('scout.driver') === 'elasticsearch') {
            $array['is_active'] = (bool) $this->is_active;
            $array['created_at'] = $this->created_at?->timestamp;
        }

        return $array;
    }
}
