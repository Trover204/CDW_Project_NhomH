<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NewsFactory extends Factory
{
    protected $model = News::class;

    public function definition()
    {
        $title = $this->faker->sentence();
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $this->faker->paragraphs(3, true),
            'summary' => $this->faker->paragraph(),
            'category' => 'Thể thao',
            'status' => 'published',
            'views' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
