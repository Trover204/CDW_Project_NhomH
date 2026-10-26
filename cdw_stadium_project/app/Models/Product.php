<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory, Searchable;

    protected $fillable = [
        'name',
        'description',
        'team',
        'season',
        'cost_price',
        'stock_quantity',
        'is_in_stock',
        'image',
        'is_active',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'is_in_stock' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function shouldBeSearchable(): bool
    {
        return (bool) $this->is_active;
    }

    public function toSearchableArray(): array
    {
        $array = [
            'id' => (int) $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'team' => $this->team,
            'season' => $this->season,
        ];

        if (config('scout.driver') === 'meilisearch') {
            $array['cost_price'] = (float) $this->cost_price;
            $array['is_in_stock'] = (bool) $this->is_in_stock;
            $array['is_active'] = (bool) $this->is_active;
            $array['created_at'] = $this->created_at?->timestamp;
        }

        return $array;
    }
}
