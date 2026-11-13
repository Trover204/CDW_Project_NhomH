<?php

namespace App\Models;

use App\Traits\LogsActivity;
use App\Traits\OptimisticLocking;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Ad extends Model
{
    use HasFactory, LogsActivity, OptimisticLocking, Searchable;

    protected $fillable = [
        'name',
        'image',
        'link_url',
        'is_active',
        'description',
        'version',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'version' => 'integer',
    ];

    public function toSearchableArray(): array
    {
        $array = [
            'id' => (int) $this->id,
            'name' => $this->name,
            'link_url' => $this->link_url,
            'description' => $this->description,
        ];

        if (in_array(config('scout.driver'), ['elasticsearch', 'elastic'], true)) {
            $array['is_active'] = (bool) $this->is_active;
            $array['created_at'] = $this->created_at?->timestamp;
        }

        return $array;
    }
}
