<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\News;
use App\Models\User;
use Database\Seeders\CommentSeeder;
use Database\Seeders\NewsAdSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebNewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_news_index_displays_published_news(): void
    {
        News::factory()->create([
            'status' => 'published',
            'title' => 'Published News Title',
        ]);

        News::factory()->create([
            'status' => 'draft',
            'title' => 'Draft News Title',
        ]);

        $response = $this->get(route('news.index'));

        $response->assertStatus(200);
        $response->assertViewIs('web.news.index');
        $response->assertSee('Published News Title');
        $response->assertDontSee('Draft News Title');
    }

    public function test_news_show_displays_single_news_and_active_comments(): void
    {
        $user = User::factory()->create(['name' => 'Nguyễn Văn Bình Luận']);

        $news = News::factory()->create([
            'status' => 'published',
            'slug' => 'test-news-slug',
            'title' => 'Single News Title',
        ]);

        Comment::factory()->create([
            'user_id' => $user->id,
            'news_id' => $news->id,
            'rating' => 5,
            'content' => 'Bài viết rất bổ ích và hấp dẫn!',
            'status' => true,
        ]);

        Comment::factory()->create([
            'user_id' => $user->id,
            'news_id' => $news->id,
            'rating' => 3,
            'content' => 'Bình luận đang bị ẩn không hiển thị',
            'status' => false,
        ]);

        $response = $this->get(route('news.show', 'test-news-slug'));

        $response->assertStatus(200);
        $response->assertViewIs('web.news.detail');
        $response->assertSee('Single News Title');
        $response->assertSee('Nguyễn Văn Bình Luận');
        $response->assertSee('Bài viết rất bổ ích và hấp dẫn!');
        $response->assertDontSee('Bình luận đang bị ẩn không hiển thị');
    }

    public function test_authenticated_user_can_post_comment_on_news(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $news = News::factory()->create([
            'status' => 'published',
            'slug' => 'tin-tuc-the-thao-moi',
        ]);

        $response = $this->actingAs($user)->post(route('news.comments.store', $news->slug), [
            'content' => 'Cảm ơn bài viết rất hay từ hệ thống!',
            'rating' => 5,
        ]);

        $response->assertRedirect(route('news.show', $news->slug).'#news-comments');

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'news_id' => $news->id,
            'content' => 'Cảm ơn bài viết rất hay từ hệ thống!',
            'rating' => 5,
            'status' => 1,
        ]);
    }

    public function test_guest_cannot_post_comment_on_news(): void
    {
        $news = News::factory()->create([
            'status' => 'published',
            'slug' => 'tin-tuc-guest-test',
        ]);

        $response = $this->post(route('news.comments.store', $news->slug), [
            'content' => 'Bình luận từ khách chưa đăng nhập',
            'rating' => 5,
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('comments', [
            'content' => 'Bình luận từ khách chưa đăng nhập',
        ]);
    }

    public function test_comment_seeder_creates_25_sample_reviews_for_news(): void
    {
        $this->seed([
            UserSeeder::class,
            NewsAdSeeder::class,
            CommentSeeder::class,
        ]);

        $this->assertEquals(25, Comment::count());
        $this->assertNull(Comment::whereNull('news_id')->first());
        $this->assertNull(Comment::whereNull('user_id')->first());
    }

    public function test_news_show_returns_404_for_draft_news(): void
    {
        News::factory()->create([
            'status' => 'draft',
            'slug' => 'draft-news-slug',
        ]);

        $response = $this->get(route('news.show', 'draft-news-slug'));

        $response->assertStatus(404);
    }
}
