<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
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

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        if (str_contains($this->image, '..') || str_contains($this->image, '\\') || str_contains($this->image, "\0")) {
            return null;
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        $encodedPath = implode('/', array_map('rawurlencode', explode('/', $this->image)));

        if (str_starts_with($this->image, 'img_') || str_starts_with($this->image, 'images/')) {
            return asset($encodedPath);
        }

        if (file_exists(public_path($this->image))) {
            return asset($encodedPath);
        }

        return Storage::url($this->image);
    }

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

        if (in_array(config('scout.driver'), ['elasticsearch', 'elastic'], true)) {
            $array['cost_price'] = (float) $this->cost_price;
            $array['is_in_stock'] = (bool) $this->is_in_stock;
            $array['is_active'] = (bool) $this->is_active;
            $array['created_at'] = $this->created_at?->timestamp;
        }

        return $array;
    }
}
