<?php

namespace Tests\Feature;

use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebNewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_news_index_displays_published_news()
    {
        News::factory()->create([
            'status' => 'published',
            'title' => 'Published News Title'
        ]);

        News::factory()->create([
            'status' => 'draft',
            'title' => 'Draft News Title'
        ]);

        $response = $this->get(route('news.index'));

        $response->assertStatus(200);
        $response->assertViewIs('web.news.index');
        $response->assertSee('Published News Title');
        $response->assertDontSee('Draft News Title');
    }

    public function test_news_show_displays_single_news()
    {
        $news = News::factory()->create([
            'status' => 'published',
            'slug' => 'test-news-slug',
            'title' => 'Single News Title'
        ]);

        $response = $this->get(route('news.show', 'test-news-slug'));

        $response->assertStatus(200);
        $response->assertViewIs('web.news.detail');
        $response->assertSee('Single News Title');
    }

    public function test_news_show_returns_404_for_draft_news()
    {
        $news = News::factory()->create([
            'status' => 'draft',
            'slug' => 'draft-news-slug',
        ]);

        $response = $this->get(route('news.show', 'draft-news-slug'));

        $response->assertStatus(404);
    }
}
