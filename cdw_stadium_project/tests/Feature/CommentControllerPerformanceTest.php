<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentControllerPerformanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_edit_comment_performance()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->count(10000)->create();

        $news = News::create([
            'title' => 'Test News',
            'slug' => 'test-news',
            'content' => 'Content',
            'user_id' => $admin->id,
            'status' => 'published',
        ]);

        $comment = new Comment([
            'user_id' => $admin->id,
            'content' => 'Test comment',
            'status' => true,
        ]);
        $comment->news_id = $news->id;
        $comment->save();

        $start = microtime(true);

        $response = $this->actingAs($admin)
            ->get(route('admin.comments.edit', $comment));

        $end = microtime(true);
        $time = $end - $start;

        $response->assertStatus(200);

        echo "Time taken for edit (optimized with 10k users): {$time} seconds\n";
        $this->assertTrue(true);
    }
}
