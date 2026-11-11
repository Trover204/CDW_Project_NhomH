<?php

namespace Tests\Feature\Admin;

use App\Models\Comment;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_comments_index_with_news_relationship(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['name' => 'Khách Hàng Test', 'role' => 'customer']);
        $news = News::factory()->create([
            'title' => 'Bản tin Pickleball mùa thu',
            'slug' => 'ban-tin-pickleball-mua-thu',
            'user_id' => $admin->id,
            'status' => 'published',
        ]);

        Comment::factory()->create([
            'user_id' => $customer->id,
            'news_id' => $news->id,
            'content' => 'Nội dung bình luận kiểm tra admin',
            'rating' => 5,
            'status' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.comments.index'));

        $response->assertStatus(200);
        $response->assertSee('Khách Hàng Test');
        $response->assertSee('Bản tin Pickleball mùa thu');
        $response->assertSee('Nội dung bình luận kiểm tra admin');
    }

    public function test_admin_can_create_update_and_delete_comment(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);
        $news = News::factory()->create([
            'user_id' => $admin->id,
            'status' => 'published',
        ]);

        // Create
        $storeResponse = $this->actingAs($admin)->post(route('admin.comments.store'), [
            'user_id' => $customer->id,
            'news_id' => $news->id,
            'content' => 'Bình luận mới thêm từ trang quản trị',
            'rating' => 4,
            'status' => '1',
        ]);

        $storeResponse->assertRedirect(route('admin.comments.index'));
        $comment = Comment::first();
        $this->assertNotNull($comment);
        $this->assertEquals($news->id, $comment->news_id);
        $this->assertEquals(4, $comment->rating);
        $this->assertTrue($comment->status);

        // Update
        $updateResponse = $this->actingAs($admin)->put(route('admin.comments.update', $comment), [
            'user_id' => $customer->id,
            'news_id' => $news->id,
            'content' => 'Nội dung bình luận đã chỉnh sửa',
            'rating' => 5,
            'status' => '0',
            'version' => $comment->version,
        ]);

        $updateResponse->assertRedirect(route('admin.comments.index'));
        $comment->refresh();
        $this->assertEquals('Nội dung bình luận đã chỉnh sửa', $comment->content);
        $this->assertEquals(5, $comment->rating);
        $this->assertFalse($comment->status);
        $this->assertEquals(1, $comment->version);

        // Destroy
        $deleteResponse = $this->actingAs($admin)->delete(route('admin.comments.destroy', $comment));
        $deleteResponse->assertRedirect(route('admin.comments.index'));
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
